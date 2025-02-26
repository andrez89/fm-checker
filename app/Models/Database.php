<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Database extends Model
{
    protected $casts = [
        'last_check_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function scopeToCheck($query, $minutes = 5)
    {
        return $query->where('last_check_at', '<=', now()->subMinutes($minutes))
            ->orWhereNull('last_check_at');
    }
}
