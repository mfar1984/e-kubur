<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;

class Tetapan extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'tetapan';
    
    protected $fillable = [
        'kunci',
        'nama',
        'nilai',
        'jenis',
        'penerangan',
        'boleh_edit',
        'kategori',
        'susunan',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'boleh_edit' => 'boolean',
        'susunan' => 'integer',
    ];

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['kunci', 'nama', 'nilai', 'jenis', 'kategori'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('tetapan');
    }

    // Append request metadata (IP & User Agent) to activity log entries
    public function tapActivity(Activity $activity, string $eventName)
    {
        try {
            $ip = request()?->ip();
            $ua = request()?->userAgent();
            $current = $activity->properties ?? collect();
            $activity->properties = $current->merge([
                'ip_address' => $ip,
                'user_agent' => $ua,
            ]);
        } catch (\Throwable $e) {
            // ignore if request() unavailable (e.g., CLI)
        }
    }

    // Relationships
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kunci', 'like', "%{$search}%")
                  ->orWhere('penerangan', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    public function scopeFilterByKategori($query, $kategori)
    {
        if ($kategori) {
            $query->where('kategori', $kategori);
        }
        return $query;
    }

    public function scopeFilterByJenis($query, $jenis)
    {
        if ($jenis) {
            $query->where('jenis', $jenis);
        }
        return $query;
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('kategori')->orderBy('susunan')->orderBy('nama');
    }

    // Accessors
    public function getFormattedNilaiAttribute()
    {
        switch ($this->jenis) {
            case 'boolean':
                return $this->nilai ? 'Ya' : 'Tidak';
            case 'date':
                return $this->nilai ? date('d/m/Y', strtotime($this->nilai)) : '-';
            default:
                return $this->nilai;
        }
    }

    // Static method to get setting value
    public static function get($kunci, $default = null)
    {
        $setting = static::where('kunci', $kunci)->first();
        return $setting ? $setting->nilai : $default;
    }

    // Static method to set setting value
    public static function set($kunci, $nilai)
    {
        return static::updateOrCreate(
            ['kunci' => $kunci],
            ['nilai' => $nilai]
        );
    }

    // Helper methods for common settings
    public static function getSystemName()
    {
        return static::get('nama_sistem', 'E-Kubur');
    }

    public static function getSystemVersion()
    {
        return static::get('versi_sistem', '1.0.0');
    }

    public static function getSystemAddress()
    {
        return static::get('alamat_sistem', 'Jalan Masjid, 93000 Kuching, Sarawak');
    }

    public static function getDefaultLatitude()
    {
        return static::get('default_latitude', 2.3000);
    }

    public static function getDefaultLongitude()
    {
        return static::get('default_longitude', 111.8167);
    }

    // reCAPTCHA helper methods
    public static function getRecaptchaSiteKey()
    {
        return static::get('recaptcha_site_key', '');
    }

    public static function getRecaptchaSecretKey()
    {
        return static::get('recaptcha_secret_key', '');
    }

    public static function isRecaptchaEnabled()
    {
        return static::get('recaptcha_enabled', false);
    }
}
