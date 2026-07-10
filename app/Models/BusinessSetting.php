<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessSetting extends Model
{
    protected $fillable = [
        'business_name', 'address', 'phone',
        'receipt_footer_line1', 'receipt_footer_line2', 'barcode_prefix',
    ];
}
