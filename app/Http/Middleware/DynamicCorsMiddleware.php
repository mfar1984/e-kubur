<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ApiConfiguration;
use Illuminate\Support\Facades\Cache;

class DynamicCorsMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Get CORS configuration from database (cached for performance)
        $corsConfig = Cache::remember('cors_config', 300, function () {
            $config = ApiConfiguration::first();
            if (!$config || !$config->allowed_origins) {
                // If no configuration found in database, block all origins
                return [
                    'allowed_origins' => [],
                    'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
                    'allowed_headers' => ['*'],
                    'exposed_headers' => [],
                    'max_age' => 0,
                    'supports_credentials' => false,
                ];
            }

            $allowedOrigins = array_map('trim', explode(',', $config->allowed_origins));
            
            return [
                'allowed_origins' => $allowedOrigins,
                'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
                'allowed_headers' => ['*'],
                'exposed_headers' => [],
                'max_age' => 0,
                'supports_credentials' => false,
            ];
        });

        $origin = $request->header('Origin');
        
        // Handle preflight OPTIONS request
        if ($request->isMethod('OPTIONS')) {
            $response = response('', 204);
        } else {
            $response = $next($request);
        }

        // Override CORS headers based on database configuration
        if ($origin && in_array($origin, $corsConfig['allowed_origins'])) {
            $response->header('Access-Control-Allow-Origin', $origin);
        } else if ($origin) {
            // If origin is not in allowed list, don't set the header
            // This will cause the browser to block the request
        }

        $response->header('Access-Control-Allow-Methods', implode(',', $corsConfig['allowed_methods']));
        $response->header('Access-Control-Allow-Headers', implode(',', $corsConfig['allowed_headers']));
        $response->header('Access-Control-Max-Age', $corsConfig['max_age']);
        
        if ($corsConfig['supports_credentials']) {
            $response->header('Access-Control-Allow-Credentials', 'true');
        }

        // Add Vary header for proper caching
        $response->header('Vary', 'Origin, Access-Control-Request-Method, Access-Control-Request-Headers');

        return $response;
    }
}
