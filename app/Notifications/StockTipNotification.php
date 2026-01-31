<?php

namespace App\Notifications;

use App\Models\StockTip;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StockTipNotification extends Notification
{
    use Queueable;

    protected $stockTip;
    protected $type;
    protected $currentPrice;

    public function __construct(StockTip $stockTip, string $type, float $currentPrice)
    {
        $this->stockTip = $stockTip;
        $this->type = $type;
        $this->currentPrice = $currentPrice;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $config = [
            'support_buy' => [
                'title' => '🟢 Buy Opportunity',
                'body' => "Stock {$this->stockTip->symbol->symbol_code} is near support level at ₹{$this->currentPrice}. Consider buying!",
                'status' => 'info',
            ],
            'strong_buy' => [
                'title' => '🔥 Strong Buy Signal',
                'body' => "Stock {$this->stockTip->symbol->symbol_code} has dropped significantly to ₹{$this->currentPrice}. Strong buy opportunity!",
                'status' => 'warning',
            ],
            'stop_loss' => [
                'title' => '🔴 Stop Loss Hit',
                'body' => "Stock {$this->stockTip->symbol->symbol_code} has hit stop loss at ₹{$this->currentPrice}. Consider exiting position.",
                'status' => 'danger',
            ],
            'target_hit' => [
                'title' => '✅ Target Achieved',
                'body' => "Stock {$this->stockTip->symbol->symbol_code} has reached target at ₹{$this->currentPrice}. Book profits!",
                'status' => 'success',
            ],
        ];

        $data = $config[$this->type] ?? $config['support_buy'];

        return [
            'title' => $data['title'],
            'body' => $data['body'],
            'status' => $data['status'],
            'duration' => 'persistent',
            'format' => 'filament',
            'stockTipId' => $this->stockTip->id,
            'symbolCode' => $this->stockTip->symbol->symbol_code,
            'currentPrice' => $this->currentPrice,
        ];
    }

    public function toArray($notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
