<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeatherAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'city_id',
        'uv_threshold',
        'precipitation_threshold',
        'is_active',
        'paused_until'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'paused_until' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function isPaused(): bool
    {
        return $this->paused_until !== null && $this->paused_until->isFuture();
    }
}
