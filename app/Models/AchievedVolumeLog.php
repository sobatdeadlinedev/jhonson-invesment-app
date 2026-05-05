<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AchievedVolumeLog extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'achieved_before',
        'achieved_after',
    ];

    protected $casts = [
        'amount'           => 'decimal:2',
        'achieved_before'  => 'decimal:2',
        'achieved_after'   => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}