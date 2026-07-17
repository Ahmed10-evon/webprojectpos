<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseRequisition extends Model
{
    protected $fillable = [
        'item_description', 'quantity_needed', 'preferred_supplier', 'notes', 'status',
    ];
}
