<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'name', 'code', 'company_name', 'address', 'contact_person',
    'phone', 'email', 'logo', 'operating_hours', 'status',
])]
class Franchise extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'operating_hours' => 'array',
        ];
    }

    public function eventHalls()
    {
        return $this->hasMany(EventHall::class);
    }

    public function activeHalls()
    {
        return $this->eventHalls()->where('is_active', true);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'franchise_users')
            ->withPivot('franchise_role')
            ->withTimestamps();
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }

    public function bookings()
    {
        return $this->hasManyThrough(Booking::class, EventHall::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
