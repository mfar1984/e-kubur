<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'smtp_host',
        'smtp_port',
        'username',
        'password',
        'encryption',
        'authentication',
        'from_name',
        'reply_to',
        'connection_timeout',
        'max_retries',
        'is_active',
        'last_test',
        'last_test_status',
        'last_test_message'
    ];

    protected $casts = [
        'smtp_port' => 'integer',
        'connection_timeout' => 'integer',
        'max_retries' => 'integer',
        'is_active' => 'boolean',
        'last_test' => 'datetime'
    ];

    protected $hidden = [
        'password'
    ];

    // Accessor untuk formatted last test
    public function getFormattedLastTestAttribute()
    {
        if (!$this->last_test) {
            return 'Belum diuji';
        }
        
        $diff = now()->diffForHumans($this->last_test, ['parts' => 1]);
        return $diff . ' lalu';
    }

    // Accessor untuk status badge
    public function getStatusBadgeAttribute()
    {
        if (!$this->last_test_status) {
            return 'Belum diuji';
        }
        
        return $this->last_test_status === 'success' ? 'Berjaya' : 'Gagal';
    }
}
