<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    protected $fillable = [
        'product_id', 'user_id', 'direction', 'quantity',
        'quantity_before', 'quantity_after', 'reason', 'notes', 'adjusted_at',
    ];

    protected function casts(): array
    {
        return ['adjusted_at' => 'datetime'];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
