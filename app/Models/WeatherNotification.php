<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeatherNotification extends Model
{
    protected $fillable = [
        'user_id',
        'city_id',
        'type',
        'data',
        'sent_at'
    ];

    protected $casts = [
        'data' => 'array',
        'sent_at' => 'datetime'
    ];
}
