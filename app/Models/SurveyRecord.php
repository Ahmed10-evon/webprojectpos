<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyRecord extends Model
{
    protected $fillable = ['record_date', 'amount'];

    protected function casts(): array
    {
        return ['record_date' => 'date'];
    }
}
