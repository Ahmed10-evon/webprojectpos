<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'barcode', 'name', 'category', 'brand', 'unit',
        'price', 'quantity', 'reorder_level', 'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'quantity' => 'integer',
            'reorder_level' => 'integer',
        ];
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function stockAdjustments()
    {
        return $this->hasMany(StockAdjustment::class);
    }

    /**
     * Low Stock Alert check — uses this product's own reorder_level rather
     * than one fixed number for the whole shop, so a low-turnover item (say,
     * reorder at 3) and a fast-moving one (reorder at 20) can each have a
     * sensible threshold.
     */
    public function isLowStock(): bool
    {
        return $this->status !== 'archived' && $this->quantity > 0 && $this->quantity <= $this->reorder_level;
    }

    public function isOutOfStock(): bool
    {
        return $this->status !== 'archived' && $this->quantity === 0;
    }
}
