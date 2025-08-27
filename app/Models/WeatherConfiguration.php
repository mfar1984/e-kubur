<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider',
        'api_key',
        'base_url',
        'default_location',
        'latitude',
        'longitude',
        'units',
        'language',
        'update_frequency',
        'cache_duration',
        'last_update',
        'current_weather',
        'is_active'
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'update_frequency' => 'integer',
        'cache_duration' => 'integer',
        'last_update' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function getFormattedLastUpdateAttribute()
    {
        if ($this->last_update) {
            $diff = now()->diffInMinutes($this->last_update);
            if ($diff < 1) {
                return 'Baru sahaja';
            } elseif ($diff < 60) {
                return $diff . ' minit lalu';
            } elseif ($diff < 1440) {
                $hours = floor($diff / 60);
                return $hours . ' jam lalu';
            } else {
                $days = floor($diff / 1440);
                return $days . ' hari lalu';
            }
        }
        return 'Belum pernah';
    }

    public function getFormattedUnitsAttribute()
    {
        return $this->units === 'metric' ? 'Metric (Celsius)' : 'Imperial (Fahrenheit)';
    }

    public function getFormattedLanguageAttribute()
    {
        $languages = [
            'ms' => 'ms (Bahasa Melayu)',
            'en' => 'en (English)',
            'zh' => 'zh (中文)',
            'ta' => 'ta (தமிழ்)'
        ];
        return $languages[$this->language] ?? $this->language;
    }
}
