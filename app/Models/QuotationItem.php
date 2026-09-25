<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'quotation_id', 'item_type', 'description', 'quantity', 'unit',
    'unit_price', 'discount_amount', 'total', 'addon_id', 'sort_order',
])]
class QuotationItem extends Model
{
    protected function casts(): array
    {
        return [
            'unit_price'      => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'total'           => 'decimal:2',
        ];
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function addon()
    {
        return $this->belongsTo(Addon::class);
    }
}
