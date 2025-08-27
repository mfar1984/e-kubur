<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;

class Ppjub extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'ppjub';
    
    protected $fillable = [
        'nama',
        'no_ic',
        'telefon',
        'email',
        'alamat',
        'status',
        'tarikh_keahlian',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'tarikh_keahlian' => 'date',
    ];

    // Activity Log Configuration
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama', 'no_ic', 'telefon', 'email', 'alamat', 'status', 'tarikh_keahlian'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('ppjub');
    }

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
            // ignore if request() unavailable
        }
    }

    // Accessors for formatted dates
    public function getTarikhKeahlianFormattedAttribute()
    {
        return $this->tarikh_keahlian ? $this->tarikh_keahlian->format('d/m/Y') : '-';
    }

    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at ? $this->created_at->format('d/m/Y H:i') : '-';
    }

    public function getUpdatedAtFormattedAttribute()
    {
        return $this->updated_at ? $this->updated_at->format('d/m/Y H:i') : '-';
    }

    // Calculate age from IC number
    public function getUmurAttribute()
    {
        if (!$this->no_ic) return '-';
        
        $ic = str_replace('-', '', $this->no_ic);
        if (strlen($ic) !== 12) return '-';
        
        $year = substr($ic, 0, 2);
        $month = substr($ic, 2, 2);
        $day = substr($ic, 4, 2);
        
        // Determine century
        $currentYear = date('Y');
        $century = substr($currentYear, 0, 2);
        
        if ($year > substr($currentYear, -2)) {
            $century--;
        }
        
        $fullYear = $century . $year;
        $birthDate = Carbon::createFromDate($fullYear, $month, $day);
        
        return $birthDate->age;
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

    // Scopes for search
    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('no_ic', 'like', "%{$search}%")
                  ->orWhere('telefon', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    public function scopeFilterByEmail($query, $email)
    {
        if ($email) {
            $query->where('email', 'like', "%{$email}%");
        }
        return $query;
    }

    public function scopeFilterByStatus($query, $status)
    {
        if ($status) {
            $query->where('status', $status);
        }
        return $query;
    }
}