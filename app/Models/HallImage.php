<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['event_hall_id', 'path', 'caption', 'sort_order'])]
class HallImage extends Model
{
    public function hall()
    {
        return $this->belongsTo(EventHall::class, 'event_hall_id');
    }
}
