# Filament User Management with Spatie Permissions

## Overview
This project now includes a complete user management system with role-based access control using Spatie Laravel Permission package.

## Features
- Two main roles: **admin** and **user**
- Admin can access all modules
- User can access only permission-wise modules
- Direct login at root URL (127.0.0.1) - no /admin or /user redirect needed
- Dynamic permission-based navigation
- Easy to add new modules with permissions

## Default Credentials

### Admin User
- Email: `admin@nexa.com`
- Password: `password`
- Role: admin (full access)

### Regular User
- Email: `user@nexa.com`
- Password: `password`
- Role: user (limited access based on permissions)

## Accessing the System

Simply visit `http://127.0.0.1` (or your local domain) and you'll be directed to the login page.

## User Management Interface

The system includes three management resources in the "User Management" navigation group:

1. **Users** - Manage user accounts and assign roles
2. **Roles** - Create and manage roles with permissions
3. **Permissions** - Create and manage individual permissions

## Permissions Format

Permissions follow this naming convention:
- `view_module` - View single record
- `view_any_module` - View list of records
- `create_module` - Create new records
- `update_module` - Edit existing records
- `delete_module` - Delete single record
- `delete_any_module` - Bulk delete records

## Available Modules with Permissions

The following modules are already configured with permissions:
- user
- role
- permission
- trading_symbol
- daily_trade_plan
- daily_trade_result
- option_contract
- stock_tip
- stock_trade_execution
- trading_monthly_risk_plan

## Adding New Modules

To add a new module with permission-based access:

### 1. Create the permissions
Navigate to **Permissions** in the Filament panel and create:
- `view_your_module`
- `view_any_your_module`
- `create_your_module`
- `update_your_module`
- `delete_your_module`
- `delete_any_your_module`

### 2. Assign permissions to roles
Go to **Roles** and assign the new permissions to appropriate roles.

### 3. Add to your Resource
In your Filament Resource class, add the `canAccess()` method:

```php
public static function canAccess(): bool
{
    return auth()->user()->can('view_any_your_module') || auth()->user()->hasRole('admin');
}
```

### 4. Create a Policy (Optional but Recommended)
Create a policy file in `app/Policies/YourModelPolicy.php`:

```php
<?php

namespace App\Policies;

use App\Models\User;
use App\Models\YourModel;
use Illuminate\Auth\Access\HandlesAuthorization;

class YourModelPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_your_module') || $user->hasRole('admin');
    }

    public function view(User $user, YourModel $model): bool
    {
        return $user->can('view_your_module') || $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->can('create_your_module') || $user->hasRole('admin');
    }

    public function update(User $user, YourModel $model): bool
    {
        return $user->can('update_your_module') || $user->hasRole('admin');
    }

    public function delete(User $user, YourModel $model): bool
    {
        return $user->can('delete_your_module') || $user->hasRole('admin');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_your_module') || $user->hasRole('admin');
    }
}
```

### 5. Register the Policy
Add it to the `$policies` array in `app/Providers/AppServiceProvider.php`:

```php
protected $policies = [
    YourModel::class => YourModelPolicy::class,
];
```

## How It Works

1. **Login**: Users access the system at the root URL
2. **Authentication**: After login, users see only the modules they have permissions for
3. **Admin Override**: Users with the 'admin' role always have access to everything
4. **Dynamic Navigation**: Navigation menu items appear based on permissions

## Customizing Roles

To customize which permissions a role has:
1. Login as admin
2. Go to **Roles**
3. Edit the role you want to modify
4. Select/deselect permissions as needed
5. Save changes

## Re-seeding Roles and Permissions

If you need to reset roles and permissions:

```bash
php artisan db:seed --class=RolePermissionSeeder
```

**Note**: This will create the default admin and user roles if they don't exist.

## Technical Details

- **Package**: spatie/laravel-permission (v6.24.0)
- **Panel Path**: `/` (root)
- **Authentication**: Filament's built-in authentication
- **Permission Caching**: Automatically managed by Spatie Permission

## Troubleshooting

### Clear Cache
If permissions aren't working as expected, clear the cache:
```bash
php artisan cache:clear
php artisan config:clear
```

### Permission Not Showing
1. Ensure the permission exists in the database
2. Check if the role has the permission assigned
3. Verify the user has the correct role
4. Clear cache

### Navigation Not Updating
1. Clear browser cache
2. Hard refresh (Ctrl+F5)
3. Check `canAccess()` method in the Resource class
