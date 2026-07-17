<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $fillable = ['phone', 'note', 'start_date', 'expiry_date', 'status'];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'expiry_date' => 'date',
        ];
    }

    public function daysLeft(): int
    {
        return (int) now()->startOfDay()->diffInDays($this->expiry_date, false);
    }
}
