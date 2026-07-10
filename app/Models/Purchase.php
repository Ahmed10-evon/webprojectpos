<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'product_id', 'item_name', 'barcode', 'quantity', 'unit_cost',
        'total_cost', 'supplier_name', 'supplier_phone', 'payment_status', 'purchased_at',
    ];

    protected function casts(): array
    {
        return ['purchased_at' => 'datetime'];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
