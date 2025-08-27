<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiConfiguration extends Model
{
    use HasFactory;

    protected $table = 'api_configurations';

    protected $fillable = [
        'base_url',
        'version',
        'auth_type',
        'access_token',
        'rate_limit',
        'timeout',
        'max_retries',
        'ssl_verification',
        'logging_level',
        'token_default_expiry',
        'allowed_origins',
        'default_abilities',
        'token_name',
    ];
}


