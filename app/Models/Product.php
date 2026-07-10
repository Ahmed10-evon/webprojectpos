<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'barcode', 'name', 'category', 'brand', 'unit',
        'price', 'quantity', 'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'quantity' => 'integer',
        ];
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function isLowStock(int $threshold = 3): bool
    {
        return $this->status !== 'archived' && $this->quantity > 0 && $this->quantity <= $threshold;
    }
}
