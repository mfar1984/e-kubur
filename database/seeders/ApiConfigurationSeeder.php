<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ApiConfiguration;

class ApiConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ApiConfiguration::query()->updateOrCreate(
            ['id' => 1],
            [
                'base_url' => 'https://api.ekubur.com/v1',
                'version' => 'v1',
                'auth_type' => 'Bearer Token',
                'access_token' => null,
                'rate_limit' => 1000,
                'timeout' => 30,
                'max_retries' => 3,
                'ssl_verification' => 'Enabled',
                'logging_level' => 'Info',
                'token_default_expiry' => '6h',
                'allowed_origins' => 'https://www.ppjub.my, https://ppjub.com.my',
                'default_abilities' => json_encode(['read:overview','read:kematian','read:ppjub','read:tetapan','read:integrations']),
                'token_name' => 'public_website',
            ]
        );
    }
}


