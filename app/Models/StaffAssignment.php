<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'booking_id', 'user_id', 'role', 'date', 'start_time', 'end_time', 'status', 'notes',
])]
class StaffAssignment extends Model
{
    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
