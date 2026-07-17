<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    protected $fillable = [
        'customer_name', 'customer_phone', 'item_description', 'quantity',
        'unit_price', 'expected_date', 'notes', 'status',
    ];
}
