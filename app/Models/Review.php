<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['facility_id', 'user_id', 'guest_name', 'rating', 'comment', 'is_approved'])]
class Review extends Model
{
    protected function casts(): array
    {
        return [
            'is_approved' => 'boolean',
        ];
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
