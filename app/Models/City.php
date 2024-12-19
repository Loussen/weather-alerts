<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    protected $fillable = [
        'name',
        'country',
        'latitude',
        'longitude'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'weather_alerts');
    }

    public function weatherAlerts(): HasMany
    {
        return $this->hasMany(WeatherAlert::class);
    }
}
