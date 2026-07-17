<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'supplier_name', 'supplier_phone', 'expected_date', 'notes', 'status',
    ];

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
}
