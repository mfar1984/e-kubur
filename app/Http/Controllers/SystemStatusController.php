<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Models\EmailConfiguration;
use App\Models\WeatherConfiguration;
use Carbon\Carbon;

class SystemStatusController extends Controller
{
    public function index()
    {
        $status = $this->getSystemStatus();
        $user = auth()->user();
        
        return view('system-status.index', compact('status', 'user'));
    }
    
    public function api()
    {
        $status = $this->getSystemStatus();
        
        return response()->json([
            'success' => true,
            'data' => $status,
            'timestamp' => now()->toISOString()
        ]);
    }
    
    private function getSystemStatus()
    {
        return [
            'overall_status' => $this->getOverallStatus(),
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
            'storage' => $this->checkStorage(),
            'memory' => $this->checkMemory(),
            'email_config' => $this->checkEmailConfiguration(),
            'weather_api' => $this->checkWeatherApi(),
            'system_info' => $this->getSystemInfo(),
            'last_updated' => now()->format('d/m/Y H:i:s')
        ];
    }
    
    private function getOverallStatus()
    {
        $checks = [
            $this->checkDatabase(),
            $this->checkCache(),
            $this->checkStorage(),
            $this->checkMemory(),
            $this->checkEmailConfiguration(),
            $this->checkWeatherApi()
        ];
        
        $failedChecks = collect($checks)->where('status', 'failed')->count();
        
        if ($failedChecks === 0) {
            return 'operational';
        } elseif ($failedChecks <= 2) {
            return 'degraded';
        } else {
            return 'down';
        }
    }
    
    private function checkDatabase()
    {
        try {
            DB::connection()->getPdo();
            $queryTime = microtime(true);
            DB::select('SELECT 1');
            $queryTime = (microtime(true) - $queryTime) * 1000;
            
            return [
                'status' => 'operational',
                'message' => 'Database connection successful',
                'response_time' => round($queryTime, 2) . 'ms',
                'icon' => 'check_circle',
                'color' => 'text-green-500'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'failed',
                'message' => 'Database connection failed: ' . $e->getMessage(),
                'response_time' => null,
                'icon' => 'error',
                'color' => 'text-red-500'
            ];
        }
    }
    
    private function checkCache()
    {
        try {
            $testKey = 'system_status_test_' . time();
            Cache::put($testKey, 'test', 60);
            $value = Cache::get($testKey);
            Cache::forget($testKey);
            
            if ($value === 'test') {
                return [
                    'status' => 'operational',
                    'message' => 'Cache system working properly',
                    'icon' => 'check_circle',
                    'color' => 'text-green-500'
                ];
            } else {
                return [
                    'status' => 'failed',
                    'message' => 'Cache read/write test failed',
                    'icon' => 'error',
                    'color' => 'text-red-500'
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 'failed',
                'message' => 'Cache system error: ' . $e->getMessage(),
                'icon' => 'error',
                'color' => 'text-red-500'
            ];
        }
    }
    
    private function checkStorage()
    {
        try {
            $disk = Storage::disk('local');
            $freeSpace = disk_free_space(storage_path());
            $totalSpace = disk_total_space(storage_path());
            $usedSpace = $totalSpace - $freeSpace;
            $usagePercentage = ($usedSpace / $totalSpace) * 100;
            
            $status = 'operational';
            $message = 'Storage space available';
            
            if ($usagePercentage > 90) {
                $status = 'warning';
                $message = 'Storage space running low';
            } elseif ($usagePercentage > 95) {
                $status = 'failed';
                $message = 'Storage space critically low';
            }
            
            return [
                'status' => $status,
                'message' => $message,
                'usage_percentage' => round($usagePercentage, 1),
                'free_space' => $this->formatBytes($freeSpace),
                'total_space' => $this->formatBytes($totalSpace),
                'icon' => $status === 'operational' ? 'check_circle' : ($status === 'warning' ? 'warning' : 'error'),
                'color' => $status === 'operational' ? 'text-green-500' : ($status === 'warning' ? 'text-yellow-500' : 'text-red-500')
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'failed',
                'message' => 'Storage check failed: ' . $e->getMessage(),
                'icon' => 'error',
                'color' => 'text-red-500'
            ];
        }
    }
    
    private function checkMemory()
    {
        try {
            $memoryLimit = ini_get('memory_limit');
            $memoryUsage = memory_get_usage(true);
            $memoryPeak = memory_get_peak_usage(true);
            
            // Convert memory limit to bytes
            $limitBytes = $this->convertToBytes($memoryLimit);
            $usagePercentage = ($memoryUsage / $limitBytes) * 100;
            
            $status = 'operational';
            $message = 'Memory usage normal';
            
            if ($usagePercentage > 80) {
                $status = 'warning';
                $message = 'Memory usage high';
            } elseif ($usagePercentage > 95) {
                $status = 'failed';
                $message = 'Memory usage critical';
            }
            
            return [
                'status' => $status,
                'message' => $message,
                'usage_percentage' => round($usagePercentage, 1),
                'current_usage' => $this->formatBytes($memoryUsage),
                'peak_usage' => $this->formatBytes($memoryPeak),
                'limit' => $memoryLimit,
                'icon' => $status === 'operational' ? 'check_circle' : ($status === 'warning' ? 'warning' : 'error'),
                'color' => $status === 'operational' ? 'text-green-500' : ($status === 'warning' ? 'text-yellow-500' : 'text-red-500')
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'failed',
                'message' => 'Memory check failed: ' . $e->getMessage(),
                'icon' => 'error',
                'color' => 'text-red-500'
            ];
        }
    }
    
    private function checkEmailConfiguration()
    {
        try {
            $emailConfig = EmailConfiguration::first();
            
            if (!$emailConfig) {
                return [
                    'status' => 'warning',
                    'message' => 'Email configuration not set up',
                    'icon' => 'warning',
                    'color' => 'text-yellow-500'
                ];
            }
            
            if (!$emailConfig->is_active) {
                return [
                    'status' => 'failed',
                    'message' => 'Email configuration is disabled',
                    'icon' => 'error',
                    'color' => 'text-red-500'
                ];
            }
            
            return [
                'status' => 'operational',
                'message' => 'Email configuration active',
                'provider' => $emailConfig->provider,
                'icon' => 'check_circle',
                'color' => 'text-green-500'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'failed',
                'message' => 'Email configuration check failed: ' . $e->getMessage(),
                'icon' => 'error',
                'color' => 'text-red-500'
            ];
        }
    }
    
    private function checkWeatherApi()
    {
        try {
            $weatherConfig = WeatherConfiguration::first();
            
            if (!$weatherConfig) {
                return [
                    'status' => 'warning',
                    'message' => 'Weather API not configured',
                    'icon' => 'warning',
                    'color' => 'text-yellow-500'
                ];
            }
            
            if (!$weatherConfig->api_key) {
                return [
                    'status' => 'failed',
                    'message' => 'Weather API key not set',
                    'icon' => 'error',
                    'color' => 'text-red-500'
                ];
            }
            
            return [
                'status' => 'operational',
                'message' => 'Weather API configured',
                'provider' => $weatherConfig->provider,
                'icon' => 'check_circle',
                'color' => 'text-green-500'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'failed',
                'message' => 'Weather API check failed: ' . $e->getMessage(),
                'icon' => 'error',
                'color' => 'text-red-500'
            ];
        }
    }
    
    private function getSystemInfo()
    {
        return [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'uptime' => $this->getUptime(),
            'timezone' => config('app.timezone'),
            'environment' => config('app.env'),
            'app_name' => config('app.name'),
            'app_url' => config('app.url'),
            'database_connection' => config('database.default'),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'queue_driver' => config('queue.default'),
            'log_channel' => config('logging.default'),
            'debug_mode' => config('app.debug') ? 'Enabled' : 'Disabled',
            'maintenance_mode' => app()->isDownForMaintenance() ? 'Enabled' : 'Disabled'
        ];
    }
    
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
    
    private function convertToBytes($memoryLimit)
    {
        $unit = strtolower(substr($memoryLimit, -1));
        $value = (int) substr($memoryLimit, 0, -1);
        
        switch ($unit) {
            case 'k': return $value * 1024;
            case 'm': return $value * 1024 * 1024;
            case 'g': return $value * 1024 * 1024 * 1024;
            default: return $value;
        }
    }
    
    private function getUptime()
    {
        try {
            $uptime = shell_exec('uptime -p 2>/dev/null');
            if ($uptime) {
                return trim($uptime);
            }
            
            // Fallback for systems without uptime command
            $startTime = filemtime(storage_path('logs/laravel.log'));
            if ($startTime) {
                $uptime = time() - $startTime;
                $days = floor($uptime / 86400);
                $hours = floor(($uptime % 86400) / 3600);
                $minutes = floor(($uptime % 3600) / 60);
                
                return "up {$days} days, {$hours} hours, {$minutes} minutes";
            }
            
            return 'Unknown';
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }
}
