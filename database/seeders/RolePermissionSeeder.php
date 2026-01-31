<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear cache
        \Artisan::call('cache:clear');

        // Create permissions for each module
        $modules = [
            'user',
            'role',
            'permission',
            'trading_symbol',
            'daily_trade_plan',
            'daily_trade_result',
            'option_contract',
            'stock_tip',
            'stock_trade_execution',
            'trading_monthly_risk_plan',
        ];

        $permissions = [];
        foreach ($modules as $module) {
            $permissions[] = Permission::create(['name' => "view_$module"]);
            $permissions[] = Permission::create(['name' => "create_$module"]);
            $permissions[] = Permission::create(['name' => "update_$module"]);
            $permissions[] = Permission::create(['name' => "delete_$module"]);
        }

        // Create admin role and assign all permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Create user role with limited permissions
        $userRole = Role::create(['name' => 'user']);
        // Give user role basic view permissions
        $userRole->givePermissionTo([
            'view_trading_symbol',
            'view_daily_trade_plan',
            'view_daily_trade_result',
            'view_option_contract',
            'view_stock_tip',
            'view_stock_trade_execution',
            'view_trading_monthly_risk_plan',
        ]);

        // Create default admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@nexa.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
            ]
        );
        $admin->assignRole('admin');

        // Create default regular user
        $user = User::firstOrCreate(
            ['email' => 'user@nexa.com'],
            [
                'name' => 'Regular User',
                'password' => bcrypt('password'),
            ]
        );
        $user->assignRole('user');
    }
}
