<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'phone', 'loyalty_points'])]
#[Hidden(['password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const TIERS = [
        'Bronze' => 0,
        'Silver' => 500,
        'Gold' => 1500,
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
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    public function hasEnabledTwoFactorAuthentication(): bool
    {
        return ! is_null($this->two_factor_secret) && ! is_null($this->two_factor_confirmed_at);
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'manager']);
    }

    public function isStaff(): bool
    {
        return in_array($this->role, ['admin', 'manager', 'staff']);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function wishlistedFacilities()
    {
        return $this->belongsToMany(Facility::class, 'wishlists');
    }

    public function loyaltyTier(): string
    {
        $tier = 'Bronze';

        foreach (self::TIERS as $name => $threshold) {
            if ($this->loyalty_points >= $threshold) {
                $tier = $name;
            }
        }

        return $tier;
    }
}
