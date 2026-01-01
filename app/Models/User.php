<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;


class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public function canAccessPanel(Panel $panel): bool
    {
        return true; // allow the only user
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function monthlyRiskPlans(): HasMany
    {
        return $this->hasMany(TradingMonthlyRiskPlan::class);
    }

    public function monthlyRiskStats(): HasMany
    {
        return $this->hasMany(TradingMonthlyRiskStat::class);
    }

    /**
     * Current month active risk plan
     */
    public function currentRiskPlan(): HasOne
    {
        return $this->hasOne(TradingMonthlyRiskPlan::class)
            ->where('risk_year', now()->year)
            ->where('risk_month', now()->month)
            ->where('is_active', true);
    }

    /**
     * Current month risk stats
     */
    public function currentRiskStats(): HasOne
    {
        return $this->hasOne(TradingMonthlyRiskStat::class)
            ->where('risk_year', now()->year)
            ->where('risk_month', now()->month)
            ->where('is_active', true);
    }
}
