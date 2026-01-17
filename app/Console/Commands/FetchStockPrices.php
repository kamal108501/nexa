<?php

namespace App\Console\Commands;

use App\Models\DailyTradeResult;
use App\Models\StockTip;
use App\Models\TradingSymbol;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchStockPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nexa:fetch-stock-prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch daily stock prices and update stock tips status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting stock price fetch...');

        // 1. Get all active stock tips
        $activeTips = StockTip::where('status', 'active')->get();

        if ($activeTips->isEmpty()) {
            $this->info('No active stock tips found.');
            return;
        }

        // 2. Group by symbol to batch process (though we do one API call per symbol)
        $tipsBySymbol = $activeTips->groupBy('symbol_id');

        foreach ($tipsBySymbol as $symbolId => $tips) {
            $symbol = TradingSymbol::find($symbolId);
            if (! $symbol) {
                $this->error("Symbol ID {$symbolId} not found.");
                continue;
            }

            // Assume symbol_code is compatible with Yahoo (e.g., 'AAPL', 'RELIANCE.NS')
            // You might need a mapping function if your codes differ
            $ticker = $symbol->symbol_code;

            $this->info("Fetching data for {$ticker}...");

            try {
                // 3. Fetch data from Yahoo Finance
                // Using unofficial chart API which is commonly used for this
                $response = Http::get("https://query1.finance.yahoo.com/v8/finance/chart/{$ticker}", [
                    'range' => '1d',
                    'interval' => '1d',
                ]);

                if ($response->failed()) {
                    $this->error("Failed to fetch data for {$ticker}: " . $response->body());
                    continue;
                }

                $data = $response->json();
                $result = $data['chart']['result'][0] ?? null;

                if (! $result) {
                    $this->error("Invalid data format for {$ticker}");
                    continue;
                }

                $quote = $result['indicators']['quote'][0];
                $meta = $result['meta'];
                
                // Ensure we have data
                if (empty($quote['high'])) {
                     $this->error("No quote data for {$ticker}");
                     continue;
                }

                $high = max($quote['high']); // Day's high
                $low = min($quote['low']);   // Day's low
                $open = $quote['open'][0];   // Open
                $close = end($quote['close']); // Current/Close price
                $date = Carbon::createFromTimestamp($meta['regularMarketTime'] ?? time())->format('Y-m-d');

                // 4. Record Daily Result (Optional, assuming schema matches)
                // We don't have a direct link to 'daily_trade_plans' here easily without more context,
                // so we will skip 'daily_trade_results' insert unless we know the 'daily_trade_plan_id'.
                // The prompt asked to "fetch daily stock ... and update values based on stock_tips table".
                // I will focus on updating the StockTip status.

                foreach ($tips as $tip) {
                     // Check High vs Target
                     if ($high >= $tip->target_price) {
                         $tip->update([
                             'status' => 'completed',
                             'notes' => $tip->notes . "\n[System] Target hit on {$date}. High: {$high}.",
                         ]);
                         $this->info("Tip ID {$tip->id} COMPLETED (Target Hit).");
                     }
                     // Check Low vs Stop Loss
                     elseif ($low <= $tip->stop_loss) {
                         // User asked to update values based on table. 
                         // Usually hitting SL means expired or loss. 
                         // Without 'loss' status in enum, I'll use 'expired' or 'active' with note?
                         // The migration has ['active', 'completed', 'expired'].
                         $tip->update([
                             'status' => 'expired', 
                             'notes' => $tip->notes . "\n[System] Stop Loss hit on {$date}. Low: {$low}.",
                         ]);
                         $this->info("Tip ID {$tip->id} EXPIRED (Stop Loss Hit).");
                     }
                }

            } catch (\Exception $e) {
                $this->error("Exception for {$ticker}: " . $e->getMessage());
                Log::error("StockFetch Error {$ticker}: " . $e->getMessage());
            }

            // Sleep to avoid rate limiting
            sleep(1); 
        }

        $this->info('Stock price fetch completed.');
    }
}
