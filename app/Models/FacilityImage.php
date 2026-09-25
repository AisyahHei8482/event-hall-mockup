<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['facility_id', 'path', 'caption', 'sort_order'])]
class FacilityImage extends Model
{
    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}
