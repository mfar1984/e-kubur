<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class KematianAttachment extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'kematian_attachments';

    protected $fillable = [
        'kematian_id',
        'filename',
        'path',
        'mime_type',
        'size_bytes',
        'uploaded_by',
    ];

    public function kematian()
    {
        return $this->belongsTo(Kematian::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([])  // Don't log any fields automatically
            ->dontSubmitEmptyLogs()
            ->useLogName('kematian_attachment');
    }
}


