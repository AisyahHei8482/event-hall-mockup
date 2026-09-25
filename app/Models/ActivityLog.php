<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'action', 'subject_type', 'subject_id', 'description'])]
class ActivityLog extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function record(string $action, ?Model $subject, string $description): self
    {
        return static::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'subject_type' => $subject ? $subject::class : null,
            'subject_id' => $subject?->getKey(),
            'description' => $description,
        ]);
    }
}
