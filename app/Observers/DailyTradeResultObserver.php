<?php

namespace App\Observers;

use App\Models\DailyTradeResult;
use App\Models\User;
use App\Services\TradingRiskManager;
use Carbon\Carbon;

class DailyTradeResultObserver
{
    /**
     * After a trade is CREATED
     */
    public function created(DailyTradeResult $trade): void
    {
        $this->applyRisk($trade, $trade->pnl_amount);
    }

    /**
     * After a trade is UPDATED
     * (VERY IMPORTANT)
     */
    public function updated(DailyTradeResult $trade): void
    {
        if ($trade->wasChanged('pnl_amount')) {
            $oldPnl = $trade->getOriginal('pnl_amount');

            // Reverse old P/L
            $this->applyRisk($trade, -$oldPnl);

            // Apply new P/L
            $this->applyRisk($trade, $trade->pnl_amount);
        }
    }

    /**
     * After a trade is DELETED
     */
    public function deleted(DailyTradeResult $trade): void
    {
        // Reverse impact
        $this->applyRisk($trade, -$trade->pnl_amount);
    }

    /**
     * Core logic
     */
    private function applyRisk(DailyTradeResult $trade, float $pnl): void
    {
        if (! $trade->created_by) {
            return;
        }

        $user = User::find($trade->created_by);

        if (! $user) {
            return;
        }

        app(TradingRiskManager::class)
            ->applyTradeResult($user, $pnl);
    }
}
