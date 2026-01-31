<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\TradingSymbol;
use App\Models\StockDailyPrice;
use App\Models\StockTip;
use App\Models\StockTipResult;
use App\Models\StockTradeExecution;
use App\Models\User;
use App\Notifications\StockTipNotification;
use Carbon\Carbon;

class FetchStockDailyPrices extends Command
{
    protected $signature = 'stocks:fetch-daily-prices {--date=}';
    protected $description = 'Fetch daily OHLC prices for stocks and store in stock_daily_prices';

    public function handle(): int
    {
        $date = $this->option('date')
            ? Carbon::parse($this->option('date'))->toDateString()
            : now('Asia/Kolkata')->toDateString();

        Log::info("FetchStockDailyPrices: Started for date: {$date}");
        $this->info("Fetching stock prices for date: {$date}");

        $symbols = TradingSymbol::where('segment', 'STOCK')
            ->where('is_active', true)
            ->get();

        foreach ($symbols as $symbol) {
            try {
                // NSE → .NS | BSE → .BO
                $suffix = strtoupper($symbol->exchange) === 'BSE' ? '.BO' : '.NS';
                $yahooSymbol = $symbol->symbol_code . $suffix;

                $response = Http::timeout(15)->get(
                    "https://query1.finance.yahoo.com/v8/finance/chart/{$yahooSymbol}",
                    [
                        'interval' => '1d',
                        'range' => '1d',
                    ]
                );

                if (!$response->successful()) {
                    $this->warn("Failed for {$yahooSymbol}");
                    continue;
                }

                $quote = data_get(
                    $response->json(),
                    'chart.result.0.indicators.quote.0'
                );

                if (!$quote) {
                    $this->warn("No data for {$yahooSymbol}");
                    continue;
                }

                $closePrice = $quote['close'][0] ?? null;
                $highPrice = $quote['high'][0] ?? null;

                StockDailyPrice::updateOrCreate(
                    [
                        'trading_symbol_id' => $symbol->id,
                        'price_date' => $date,
                    ],
                    [
                        'open_price' => $quote['open'][0] ?? null,
                        'high_price' => $highPrice,
                        'low_price' => $quote['low'][0] ?? null,
                        'close_price' => $closePrice,
                        'volume' => $quote['volume'][0] ?? null,
                        'source' => 'YAHOO',
                        'is_active' => true,
                    ]
                );

                $this->info("Saved: {$yahooSymbol}");

                // Check stock tips for this symbol and see if SL or Target is hit
                $this->checkStockTipsForHits($symbol, $closePrice, $highPrice, $date);

                // Check for buy opportunities and send notifications
                $this->checkBuyOpportunities($symbol, $closePrice, $date);
            } catch (\Throwable $e) {
                Log::error("FetchStockDailyPrices: Error for {$symbol->symbol_code}: {$e->getMessage()}");
                $this->error("Error for {$symbol->symbol_code}: {$e->getMessage()}");
            }
        }

        Log::info("FetchStockDailyPrices: Completed for date: {$date}");

        return Command::SUCCESS;
    }

    private function checkStockTipsForHits(TradingSymbol $symbol, ?float $closePrice, ?float $highPrice, string $date): void
    {
        // Get active stock tips for this symbol
        $activeStockTips = StockTip::where('trading_symbol_id', $symbol->id)
            ->where('status', 'active')
            ->where('is_active', true)
            ->get();

        foreach ($activeStockTips as $tip) {
            // If expiry date has passed, mark as expired (no SL/Target checks).
            if ($tip->expiry_date !== null && Carbon::parse($date)->gt(Carbon::parse($tip->expiry_date))) {
                $exitPrice = $closePrice ?? $tip->buy_price;

                $tip->update([
                    'status' => 'expired',
                    'exit_price' => $exitPrice,
                    'exit_date' => $date,
                    'is_active' => false,
                ]);

                $pnlAmount = ($exitPrice - $tip->buy_price);
                $pnlPercent = $tip->buy_price != 0.0 ? (($pnlAmount / $tip->buy_price) * 100) : 0.0;

                StockTipResult::create([
                    'stock_tip_id' => $tip->id,
                    'exit_price' => $exitPrice,
                    'exit_date' => $date,
                    'pnl_amount' => $pnlAmount,
                    'pnl_percent' => round($pnlPercent, 2),
                    'exit_reason' => 'TIME_EXPIRED',
                    'is_correct' => false,
                    'is_active' => true,
                ]);

                // Create SELL execution for all BUY executions linked to this expired tip
                $buyExecutions = StockTradeExecution::where('stock_tip_id', $tip->id)
                    ->where('execution_type', 'BUY')
                    ->where('is_active', true)
                    ->get();

                foreach ($buyExecutions as $buyExecution) {
                    StockTradeExecution::create([
                        'trading_symbol_id' => $tip->trading_symbol_id,
                        'stock_tip_id' => $tip->id,
                        'execution_type' => 'SELL',
                        'quantity' => $buyExecution->quantity,
                        'price' => $exitPrice,
                        'execution_at' => Carbon::parse($date)->setTime(15, 30)->setTimezone('Asia/Kolkata'),
                        'execution_notes' => "Auto-generated SELL: expired - Exit Price: {$exitPrice}",
                        'is_active' => true,
                    ]);

                    $this->info("Created SELL execution for expired Tip ID: {$tip->id}, Quantity: {$buyExecution->quantity}");
                }

                $this->warn("EXPIRED for {$symbol->symbol_code} - Tip ID: {$tip->id}, Exit Price: {$exitPrice}");
                continue;
            }

            $hitType = null;
            $exitPrice = null;

            // Check if Stop Loss is hit (use SL value as exit price)
            if ($closePrice !== null && $closePrice <= $tip->stop_loss) {
                $hitType = 'sl_hit';
                $exitPrice = $tip->stop_loss;
                $this->warn("SL HIT for {$symbol->symbol_code} - Tip ID: {$tip->id}, Exit Price: {$exitPrice}");
            }
            // Check if Target is hit (use target value as exit price)
            elseif ($highPrice !== null && $highPrice >= $tip->target_price) {
                $hitType = 'completed';
                $exitPrice = $tip->target_price;
                $this->info("TARGET HIT for {$symbol->symbol_code} - Tip ID: {$tip->id}, Exit Price: {$exitPrice}");
            }

            // If either target or SL hit, update the stock tip
            if ($hitType && $exitPrice !== null) {
                // Send notification before updating the tip
                if ($hitType === 'sl_hit') {
                    $this->sendNotificationToUsers($tip, 'stop_loss', $exitPrice);
                } elseif ($hitType === 'completed') {
                    $this->sendNotificationToUsers($tip, 'target_hit', $exitPrice);
                }

                $tip->update([
                    'status' => $hitType,
                    'exit_price' => $exitPrice,
                    'exit_date' => $date,
                    'is_active' => false,
                ]);

                // Create stock tip result record
                $pnlAmount = ($exitPrice - $tip->buy_price);
                $pnlPercent = ($pnlAmount / $tip->buy_price) * 100;

                StockTipResult::create([
                    'stock_tip_id' => $tip->id,
                    'exit_price' => $exitPrice,
                    'exit_date' => $date,
                    'pnl_amount' => $pnlAmount,
                    'pnl_percent' => round($pnlPercent, 2),
                    'exit_reason' => $hitType === 'sl_hit' ? 'SL_HIT' : 'TARGET_HIT',
                    'is_correct' => $hitType === 'completed' ? true : false,
                    'is_active' => true,
                ]);

                // Create SELL execution for all BUY executions linked to this tip
                $buyExecutions = StockTradeExecution::where('stock_tip_id', $tip->id)
                    ->where('execution_type', 'BUY')
                    ->where('is_active', true)
                    ->get();

                foreach ($buyExecutions as $buyExecution) {
                    StockTradeExecution::create([
                        'trading_symbol_id' => $tip->trading_symbol_id,
                        'stock_tip_id' => $tip->id,
                        'execution_type' => 'SELL',
                        'quantity' => $buyExecution->quantity,
                        'price' => $exitPrice,
                        'execution_at' => Carbon::parse($date)->setTime(15, 30)->setTimezone('Asia/Kolkata'),
                        'execution_notes' => "Auto-generated SELL: {$hitType} - Exit Price: {$exitPrice}",
                        'is_active' => true,
                    ]);

                    $this->info("Created SELL execution for Tip ID: {$tip->id}, Quantity: {$buyExecution->quantity}");
                }

                $this->info("Updated Stock Tip ID: {$tip->id} - Status: {$hitType}");
            }
        }
    }

    private function checkBuyOpportunities(TradingSymbol $symbol, ?float $currentPrice, string $date): void
    {
        if ($currentPrice === null) {
            return;
        }

        // Get active stock tips for this symbol
        $activeStockTips = StockTip::where('trading_symbol_id', $symbol->id)
            ->where('status', 'active')
            ->where('is_active', true)
            ->get();

        foreach ($activeStockTips as $tip) {
            $buyPrice = $tip->buy_price;
            $stopLoss = $tip->stop_loss;

            // Calculate price change percentage from buy price
            $priceChangePercent = (($currentPrice - $buyPrice) / $buyPrice) * 100;

            try {
                // Check for stop loss hit (will be handled by checkStockTipsForHits, but notify here too)
                if ($currentPrice <= $stopLoss) {
                    $this->sendNotificationToUsers($tip, 'stop_loss', $currentPrice);
                    $this->warn("NOTIFICATION - STOP LOSS: {$symbol->symbol_code} at ₹{$currentPrice}");
                }
                // Check for strong buy signal (price dropped 15% or more from buy price)
                elseif ($priceChangePercent <= -15) {
                    $this->sendNotificationToUsers($tip, 'strong_buy', $currentPrice);
                    $this->info("NOTIFICATION - STRONG BUY: {$symbol->symbol_code} at ₹{$currentPrice} ({$priceChangePercent}%)");
                }
                // Check for support level buy signal (price dropped 10% or more from buy price)
                elseif ($priceChangePercent <= -10) {
                    $this->sendNotificationToUsers($tip, 'support_buy', $currentPrice);
                    $this->info("NOTIFICATION - SUPPORT BUY: {$symbol->symbol_code} at ₹{$currentPrice} ({$priceChangePercent}%)");
                }
            } catch (\Throwable $e) {
                Log::error("Error sending notification for stock tip {$tip->id}: {$e->getMessage()}");
            }
        }
    }

    private function sendNotificationToUsers(StockTip $stockTip, string $type, float $currentPrice): void
    {
        // Send to all users who have permission to view stock tips or are admins
        $users = User::all()
            ->filter(function ($user) {
                return $user->can('view_stock_tip') || $user->hasRole('admin');
            });

        foreach ($users as $user) {
            try {
                $user->notify(new StockTipNotification($stockTip, $type, $currentPrice));
            } catch (\Exception $e) {
                Log::error("Failed to send notification to user {$user->id}: {$e->getMessage()}");
                $this->error("Failed to send notification to user {$user->id}: {$e->getMessage()}");
            }
        }
    }
}
