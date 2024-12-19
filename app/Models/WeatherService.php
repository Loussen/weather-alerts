<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeatherService extends Model
{
    protected $fillable = [
        'name',
        'api_key',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];
}
