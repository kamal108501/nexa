<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\DailyTradeResult;
use App\Observers\DailyTradeResultObserver;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        DailyTradeResult::observe(DailyTradeResultObserver::class);

        // Explicit Livewire registration to avoid "Unable to find component" errors
        // for Filament widgets outside the default Livewire namespace.
        Livewire::component(
            'app.filament.widgets.stock-tips-stats-widget',
            \App\Filament\Widgets\StockTipsStatsWidget::class,
        );
    }
}
