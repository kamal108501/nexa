<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\DailyTradeResult;
use App\Models\User;
use App\Observers\DailyTradeResultObserver;
use App\Policies\UserPolicy;
use App\Policies\RolePolicy;
use App\Policies\PermissionPolicy;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Role::class => RolePolicy::class,
        Permission::class => PermissionPolicy::class,
    ];

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

        // Register policies
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }

        // Explicit Livewire registration to avoid "Unable to find component" errors
        // for Filament widgets outside the default Livewire namespace.
        Livewire::component(
            'app.filament.widgets.stock-tips-stats-widget',
            \App\Filament\Widgets\StockTipsStatsWidget::class,
        );
    }
}
