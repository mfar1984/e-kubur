<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Sistem - E-Kubur</title>
    
    <!-- Favicon -->
    <x-favicon />
    
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans min-h-screen flex flex-col" data-theme="corporate" x-data="systemStatus()">
    <x-double-navbar :user="$user" />
    
    <div class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <!-- Main Container -->
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <!-- Header Section -->
                <div class="mb-6">
                    <h1 class="text-xl font-bold text-gray-900 mb-2">Status Sistem</h1>
                    <p class="text-xs text-gray-600">Monitor kesihatan dan prestasi sistem E-Kubur</p>
                </div>

                <!-- Overall Status Card -->
                <div class="mb-6">
                    <div class="bg-gray-50 rounded-xs border border-gray-200 p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                            <div class="flex items-center justify-center sm:justify-start">
                                <div class="w-12 h-12 rounded-full 
                                    @if($status['overall_status'] === 'operational') bg-green-100 text-green-600
                                    @elseif($status['overall_status'] === 'degraded') bg-yellow-100 text-yellow-600
                                    @else bg-red-100 text-red-600 @endif
                                    flex items-center justify-center">
                                    <span class="material-icons text-xl">
                                        @if($status['overall_status'] === 'operational') check_circle
                                        @elseif($status['overall_status'] === 'degraded') warning
                                        @else error @endif
                                    </span>
                                </div>
                                <div class="ml-4 text-center sm:text-left">
                                    <h2 class="text-lg font-semibold text-gray-900">
                                        Status Sistem: 
                                        <span class="
                                            @if($status['overall_status'] === 'operational') text-green-600
                                            @elseif($status['overall_status'] === 'degraded') text-yellow-600
                                            @else text-red-600 @endif">
                                            @if($status['overall_status'] === 'operational') Operasi Normal
                                            @elseif($status['overall_status'] === 'degraded') Terjejas
                                            @else Tidak Berfungsi @endif
                                        </span>
                                    </h2>
                                    <p class="text-sm text-gray-600">Dikemas kini: {{ $status['last_updated'] }}</p>
                                </div>
                            </div>
                            <button @click="refreshStatus()" class="flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition-colors">
                                <span class="material-icons text-xs mr-2">refresh</span>
                                Kemas Kini
                            </button>
                        </div>
                    </div>
                </div>

                <!-- System Components Grid -->
                <div class="grid grid-cols-1 gap-4 md:gap-6 md:grid-cols-2 lg:grid-cols-3 mb-8">
                    <!-- Database Status -->
                    <div class="bg-gray-50 rounded-xs border border-gray-200 p-6">
                        <div class="text-center mb-4">
                            <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mx-auto mb-3">
                                <span class="material-icons text-xl">storage</span>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-900">Database</h3>
                            <p class="text-xs text-gray-600">MySQL Connection</p>
                        </div>
                        <div class="text-center space-y-2">
                            <div class="flex items-center justify-center">
                                <span class="material-icons text-sm {{ $status['database']['color'] }} mr-2">{{ $status['database']['icon'] }}</span>
                                <span class="text-xs text-gray-700">{{ $status['database']['message'] }}</span>
                            </div>
                            @if(isset($status['database']['response_time']))
                            <div class="text-xs text-gray-500">
                                Response Time: {{ $status['database']['response_time'] }}
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Cache Status -->
                    <div class="bg-gray-50 rounded-xs border border-gray-200 p-6">
                        <div class="text-center mb-4">
                            <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center mx-auto mb-3">
                                <span class="material-icons text-xl">cached</span>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-900">Cache</h3>
                            <p class="text-xs text-gray-600">System Cache</p>
                        </div>
                        <div class="text-center space-y-2">
                            <div class="flex items-center justify-center">
                                <span class="material-icons text-sm {{ $status['cache']['color'] }} mr-2">{{ $status['cache']['icon'] }}</span>
                                <span class="text-xs text-gray-700">{{ $status['cache']['message'] }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Storage Status -->
                    <div class="bg-gray-50 rounded-xs border border-gray-200 p-6">
                        <div class="text-center mb-4">
                            <div class="w-12 h-12 rounded-full bg-green-100 text-green-600 flex items-center justify-center mx-auto mb-3">
                                <span class="material-icons text-xl">hard_drive</span>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-900">Storage</h3>
                            <p class="text-xs text-gray-600">Disk Space</p>
                        </div>
                        <div class="text-center space-y-2">
                            <div class="flex items-center justify-center">
                                <span class="material-icons text-sm {{ $status['storage']['color'] }} mr-2">{{ $status['storage']['icon'] }}</span>
                                <span class="text-xs text-gray-700">{{ $status['storage']['message'] }}</span>
                            </div>
                            <div class="text-xs text-gray-500">
                                Usage: {{ $status['storage']['usage_percentage'] }}%
                            </div>
                            <div class="text-xs text-gray-500">
                                Free: {{ $status['storage']['free_space'] }} / {{ $status['storage']['total_space'] }}
                            </div>
                        </div>
                    </div>

                    <!-- Memory Status -->
                    <div class="bg-gray-50 rounded-xs border border-gray-200 p-6">
                        <div class="text-center mb-4">
                            <div class="w-12 h-12 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center mx-auto mb-3">
                                <span class="material-icons text-xl">memory</span>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-900">Memory</h3>
                            <p class="text-xs text-gray-600">PHP Memory Usage</p>
                        </div>
                        <div class="text-center space-y-2">
                            <div class="flex items-center justify-center">
                                <span class="material-icons text-sm {{ $status['memory']['color'] }} mr-2">{{ $status['memory']['icon'] }}</span>
                                <span class="text-xs text-gray-700">{{ $status['memory']['message'] }}</span>
                            </div>
                            <div class="text-xs text-gray-500">
                                Usage: {{ $status['memory']['usage_percentage'] }}%
                            </div>
                            <div class="text-xs text-gray-500">
                                Current: {{ $status['memory']['current_usage'] }} / {{ $status['memory']['limit'] }}
                            </div>
                        </div>
                    </div>

                    <!-- Email Configuration -->
                    <div class="bg-gray-50 rounded-xs border border-gray-200 p-6">
                        <div class="text-center mb-4">
                            <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mx-auto mb-3">
                                <span class="material-icons text-xl">email</span>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-900">Email</h3>
                            <p class="text-xs text-gray-600">SMTP Configuration</p>
                        </div>
                        <div class="text-center space-y-2">
                            <div class="flex items-center justify-center">
                                <span class="material-icons text-sm {{ $status['email_config']['color'] }} mr-2">{{ $status['email_config']['icon'] }}</span>
                                <span class="text-xs text-gray-700">{{ $status['email_config']['message'] }}</span>
                            </div>
                            @if(isset($status['email_config']['provider']))
                            <div class="text-xs text-gray-500">
                                Provider: {{ $status['email_config']['provider'] }}
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Weather API -->
                    <div class="bg-gray-50 rounded-xs border border-gray-200 p-6">
                        <div class="text-center mb-4">
                            <div class="w-12 h-12 rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center mx-auto mb-3">
                                <span class="material-icons text-xl">wb_sunny</span>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-900">Weather API</h3>
                            <p class="text-xs text-gray-600">Weather Service</p>
                        </div>
                        <div class="text-center space-y-2">
                            <div class="flex items-center justify-center">
                                <span class="material-icons text-sm {{ $status['weather_api']['color'] }} mr-2">{{ $status['weather_api']['icon'] }}</span>
                                <span class="text-xs text-gray-700">{{ $status['weather_api']['message'] }}</span>
                            </div>
                            @if(isset($status['weather_api']['provider']))
                            <div class="text-xs text-gray-500">
                                Provider: {{ $status['weather_api']['provider'] }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- System Information -->
                <div class="bg-gray-50 rounded-xs border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 text-center sm:text-left">Maklumat Sistem</h3>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3 md:gap-4">
                        <div class="space-y-2">
                            <div class="flex flex-col sm:flex-row sm:justify-between text-xs">
                                <span class="text-gray-600 text-center sm:text-left">PHP Version:</span>
                                <span class="text-gray-900 font-medium text-center sm:text-right">{{ $status['system_info']['php_version'] }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:justify-between text-xs">
                                <span class="text-gray-600 text-center sm:text-left">Laravel Version:</span>
                                <span class="text-gray-900 font-medium text-center sm:text-right">{{ $status['system_info']['laravel_version'] }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:justify-between text-xs">
                                <span class="text-gray-600 text-center sm:text-left">Environment:</span>
                                <span class="text-gray-900 font-medium text-center sm:text-right">{{ $status['system_info']['environment'] }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:justify-between text-xs">
                                <span class="text-gray-600 text-center sm:text-left">App Name:</span>
                                <span class="text-gray-900 font-medium text-center sm:text-right">{{ $status['system_info']['app_name'] }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:justify-between text-xs">
                                <span class="text-gray-600 text-center sm:text-left">App URL:</span>
                                <span class="text-gray-900 font-medium text-center sm:text-right">{{ $status['system_info']['app_url'] }}</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex flex-col sm:flex-row sm:justify-between text-xs">
                                <span class="text-gray-600 text-center sm:text-left">Server:</span>
                                <span class="text-gray-900 font-medium text-center sm:text-right">{{ $status['system_info']['server'] }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:justify-between text-xs">
                                <span class="text-gray-600 text-center sm:text-left">Timezone:</span>
                                <span class="text-gray-900 font-medium text-center sm:text-right">{{ $status['system_info']['timezone'] }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:justify-between text-xs">
                                <span class="text-gray-600 text-center sm:text-left">Uptime:</span>
                                <span class="text-gray-900 font-medium text-center sm:text-right">{{ $status['system_info']['uptime'] }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:justify-between text-xs">
                                <span class="text-gray-600 text-center sm:text-left">Database:</span>
                                <span class="text-gray-900 font-medium text-center sm:text-right">{{ $status['system_info']['database_connection'] }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:justify-between text-xs">
                                <span class="text-gray-600 text-center sm:text-left">Cache Driver:</span>
                                <span class="text-gray-900 font-medium text-center sm:text-right">{{ $status['system_info']['cache_driver'] }}</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex flex-col sm:flex-row sm:justify-between text-xs">
                                <span class="text-gray-600 text-center sm:text-left">Session Driver:</span>
                                <span class="text-gray-900 font-medium text-center sm:text-right">{{ $status['system_info']['session_driver'] }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:justify-between text-xs">
                                <span class="text-gray-600 text-center sm:text-left">Queue Driver:</span>
                                <span class="text-gray-900 font-medium text-center sm:text-right">{{ $status['system_info']['queue_driver'] }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:justify-between text-xs">
                                <span class="text-gray-600 text-center sm:text-left">Log Channel:</span>
                                <span class="text-gray-900 font-medium text-center sm:text-right">{{ $status['system_info']['log_channel'] }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:justify-between text-xs">
                                <span class="text-gray-600 text-center sm:text-left">Debug Mode:</span>
                                <span class="text-gray-900 font-medium text-center sm:text-right">{{ $status['system_info']['debug_mode'] }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:justify-between text-xs">
                                <span class="text-gray-600 text-center sm:text-left">Maintenance:</span>
                                <span class="text-gray-900 font-medium text-center sm:text-right">{{ $status['system_info']['maintenance_mode'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-footer />

    <script>
        function systemStatus() {
            return {
                refreshStatus() {
                    // Show loading state
                    const button = event.target.closest('button');
                    const originalText = button.innerHTML;
                    button.innerHTML = '<span class="material-icons text-xs mr-2 animate-spin">refresh</span>Kemas Kini...';
                    button.disabled = true;
                    
                    // Reload the page
                    location.reload();
                }
            }
        }
    </script>
</body>
</html>
