<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Schema;
use App\Models\ApiConfiguration;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Facades\Activity;

class ApiConfigurationController extends Controller
{
    /**
     * Update the API configuration.
     * Note: Persistence to MySQL is intentionally not implemented per user rules.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'base_url' => ['required', 'string', 'max:255'],
            'version' => ['required', 'string', 'max:50'],
            'auth_type' => ['required', 'string', 'max:100'],
            // Removed API Key / Secret Key for Sanctum-only auth
            'access_token' => ['nullable', 'string', 'max:255'],
            'rate_limit' => ['nullable'],
            'timeout' => ['nullable'],
            'max_retries' => ['nullable'],
            'ssl_verification' => ['nullable', 'string', 'max:50'],
            'logging_level' => ['nullable', 'string', 'max:50'],
            // Sanctum fields
            'token_default_expiry' => ['nullable', 'string', 'max:20'],
            'allowed_origins' => ['nullable', 'string'],
            'default_abilities' => ['nullable', 'array'],
            'token_name' => ['nullable', 'string', 'max:100'],
        ]);

        // Normalize numeric fields: store numbers only
        $rateLimitInput = $request->input('rate_limit');
        $timeoutInput = $request->input('timeout');
        $maxRetriesInput = $request->input('max_retries');

        $normalizedRateLimit = 0;
        if (is_numeric($rateLimitInput)) {
            $normalizedRateLimit = (int) $rateLimitInput;
        } elseif (is_string($rateLimitInput)) {
            if (strtolower(trim($rateLimitInput)) === 'unlimited') {
                $normalizedRateLimit = 0;
            } elseif (preg_match('/(\d+)/', $rateLimitInput, $m)) {
                $normalizedRateLimit = (int) $m[1];
            }
        }

        $normalizedTimeout = 0;
        if (is_numeric($timeoutInput)) {
            $normalizedTimeout = (int) $timeoutInput;
        } elseif (is_string($timeoutInput) && preg_match('/(\d+)/', $timeoutInput, $m)) {
            $normalizedTimeout = (int) $m[1];
        }

        $normalizedMaxRetries = null;
        if ($maxRetriesInput !== null) {
            if (is_numeric($maxRetriesInput)) {
                $normalizedMaxRetries = (int) $maxRetriesInput;
            } elseif (is_string($maxRetriesInput) && preg_match('/(\d+)/', $maxRetriesInput, $m)) {
                $normalizedMaxRetries = (int) $m[1];
            }
        }

        $validated['rate_limit'] = $normalizedRateLimit;
        $validated['timeout'] = $normalizedTimeout;
        if ($normalizedMaxRetries !== null) {
            $validated['max_retries'] = $normalizedMaxRetries;
        }

        // Convert abilities array to JSON string for storage
        if (array_key_exists('default_abilities', $validated) && is_array($validated['default_abilities'])) {
            $validated['default_abilities'] = json_encode($validated['default_abilities']);
        }

        // If table exists, persist config; otherwise return success without DB
        if (Schema::hasTable('api_configurations')) {
            $config = ApiConfiguration::query()->find($id);
            if (!$config) {
                $config = new ApiConfiguration();
                $config->id = $id; // fixed singleton row by id
            }
            $config->fill($validated);
            $config->save();
            activity('integrations')
                ->event('api_config_updated')
                ->causedBy(Auth::user())
                ->performedOn($config)
                ->withProperties(array_merge($config->only(array_keys($validated)), [
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]))
                ->log('Konfigurasi API dikemas kini');

            return response()->json([
                'success' => true,
                'message' => 'Konfigurasi API berjaya disimpan.',
                'data' => $config->only(array_keys($validated)),
            ]);
        }

        activity('integrations')
            ->event('api_config_updated')
            ->causedBy(Auth::user())
            ->withProperties(array_merge($validated, [
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'note' => 'table_not_exist'
            ]))
            ->log('Konfigurasi API diterima tanpa simpanan DB');

        return response()->json([
            'success' => true,
            'message' => 'Konfigurasi API diterima (jadual belum wujud, tiada simpanan DB).',
            'data' => $validated,
        ]);
    }
}


