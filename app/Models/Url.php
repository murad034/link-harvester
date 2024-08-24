<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Accessor to get created_at in Dhaka timezone
    public function getCreatedAtAttribute($value) {
        return Carbon::parse($value)->timezone('Asia/Dhaka')->format('Y-m-d H:i:s');
    }
}
