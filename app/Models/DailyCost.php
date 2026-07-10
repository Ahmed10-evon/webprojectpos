<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyCost extends Model
{
    protected $fillable = ['cost_date', 'amount', 'note'];

    protected function casts(): array
    {
        return ['cost_date' => 'date'];
    }
}
