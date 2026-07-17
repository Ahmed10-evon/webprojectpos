<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
    protected $table = 'purchase_returns';

    protected $fillable = [
        'product_id', 'item_name', 'barcode', 'quantity', 'unit_cost',
        'reason', 'supplier_name', 'returned_at',
    ];

    protected function casts(): array
    {
        return ['returned_at' => 'datetime'];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
