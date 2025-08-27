<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Integration extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'nama',
        'jenis',
        'status',
        'konfigurasi',
        'penerangan',
        'url_endpoint',
        'api_key',
        'terakhir_sync',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'konfigurasi' => 'array',
        'terakhir_sync' => 'datetime',
    ];

    // Activity Log Configuration
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama', 'jenis', 'status', 'penerangan', 'url_endpoint', 'terakhir_sync'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('integration');
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

    // Accessors
    public function getTerakhirSyncFormattedAttribute()
    {
        return $this->terakhir_sync ? $this->terakhir_sync->format('d/m/Y H:i') : '-';
    }

    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at->format('d/m/Y H:i');
    }

    public function getUpdatedAtFormattedAttribute()
    {
        return $this->updated_at->format('d/m/Y H:i');
    }

    // Scopes
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%")
              ->orWhere('jenis', 'like', "%{$search}%")
              ->orWhere('penerangan', 'like', "%{$search}%");
        });
    }

    public function scopeFilterByStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    public function scopeFilterByType($query, $jenis)
    {
        if ($jenis) {
            return $query->where('jenis', $jenis);
        }
        return $query;
    }
}
