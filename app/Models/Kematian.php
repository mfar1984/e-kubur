<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;

class Kematian extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'kematian';
    
    protected $fillable = [
        'nama',
        'tarikh_lahir',
        'no_ic',
        'tarikh_meninggal',
        'longitude',
        'latitude',
        'waris',
        'telefon_waris',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'tarikh_lahir' => 'date',
        'tarikh_meninggal' => 'date',
        'longitude' => 'decimal:6',
        'latitude' => 'decimal:6',
    ];

    // Activity Log Configuration
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama', 'no_ic', 'tarikh_lahir', 'tarikh_meninggal', 'longitude', 'latitude', 'waris', 'telefon_waris', 'catatan'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('kematian');
    }

    // Append request metadata (IP & User Agent) to activity log entries
    public function tapActivity(Activity $activity, string $eventName)
    {
        try {
            $ip = request()?->ip();
            $ua = request()?->userAgent();
            $current = method_exists($activity->properties, 'toArray')
                ? collect($activity->properties->toArray())
                : collect((array) $activity->properties);
            $activity->properties = $current->merge([
                'ip_address' => $ip,
                'user_agent' => $ua,
            ]);
        } catch (\Throwable $e) {
            // ignore if request() unavailable (e.g., CLI)
        }
    }

    // Accessors for formatted dates
    public function getTarikhLahirFormattedAttribute()
    {
        return $this->tarikh_lahir ? $this->tarikh_lahir->format('d/m/Y') : '-';
    }

    public function getTarikhMeninggalFormattedAttribute()
    {
        return $this->tarikh_meninggal ? $this->tarikh_meninggal->format('d/m/Y') : '-';
    }

    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at ? $this->created_at->format('d/m/Y H:i') : '-';
    }

    public function getUpdatedAtFormattedAttribute()
    {
        return $this->updated_at ? $this->updated_at->format('d/m/Y H:i') : '-';
    }

    // Calculate age from birth date
    public function getUmurAttribute()
    {
        if (!$this->tarikh_lahir) return '-';
        
        $birthDate = Carbon::parse($this->tarikh_lahir);
        $deathDate = $this->tarikh_meninggal ? Carbon::parse($this->tarikh_meninggal) : Carbon::now();
        
        return (int) $birthDate->diffInYears($deathDate);
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

    public function attachments()
    {
        return $this->hasMany(KematianAttachment::class);
    }

    // Scopes for search
    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('no_ic', 'like', "%{$search}%")
                  ->orWhere('waris', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    public function scopeFilterByDateRange($query, $dariTarikh, $sehinggaTarikh)
    {
        if ($dariTarikh && $sehinggaTarikh) {
            $query->whereBetween('tarikh_meninggal', [$dariTarikh, $sehinggaTarikh]);
        } elseif ($dariTarikh) {
            $query->where('tarikh_meninggal', '>=', $dariTarikh);
        } elseif ($sehinggaTarikh) {
            $query->where('tarikh_meninggal', '<=', $sehinggaTarikh);
        }
        return $query;
    }
}