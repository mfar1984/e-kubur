<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Konfigurasi Cuaca - E-Kubur</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans min-h-screen flex flex-col">
    <x-double-navbar :user="$user" />
    
    <main class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <!-- Header -->
                <div class="mb-6">
                    <h1 class="text-xl font-bold text-gray-900 mb-1">Edit Konfigurasi Cuaca</h1>
                    <p class="text-xs text-gray-600">Kemas kini konfigurasi API cuaca untuk sistem</p>
                </div>

                <!-- Form -->
                <form action="{{ route('weather-configurations.update', $weatherConfig->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Provider -->
                        <div>
                            <label for="provider" class="block text-xs font-medium text-gray-700 mb-2">Weather Provider</label>
                            <select name="provider" id="provider" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="OpenWeatherMap" {{ $weatherConfig->provider == 'OpenWeatherMap' ? 'selected' : '' }}>OpenWeatherMap</option>
                                <option value="WeatherAPI" {{ $weatherConfig->provider == 'WeatherAPI' ? 'selected' : '' }}>WeatherAPI</option>
                                <option value="AccuWeather" {{ $weatherConfig->provider == 'AccuWeather' ? 'selected' : '' }}>AccuWeather</option>
                            </select>
                            @error('provider')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- API Key -->
                        <div>
                            <label for="api_key" class="block text-xs font-medium text-gray-700 mb-2">API Key</label>
                            <input type="password" name="api_key" id="api_key" value="{{ old('api_key', $weatherConfig->api_key) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   placeholder="Masukkan API key anda">
                            @error('api_key')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Base URL -->
                        <div>
                            <label for="base_url" class="block text-xs font-medium text-gray-700 mb-2">Base URL</label>
                            <input type="url" name="base_url" id="base_url" value="{{ old('base_url', $weatherConfig->base_url) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   placeholder="https://api.openweathermap.org/data/2.5">
                            @error('base_url')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Default Location -->
                        <div>
                            <label for="default_location" class="block text-xs font-medium text-gray-700 mb-2">Default Location</label>
                            <input type="text" name="default_location" id="default_location" value="{{ old('default_location', $weatherConfig->default_location) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   placeholder="Kuala Lumpur, MY">
                            @error('default_location')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Latitude -->
                        <div>
                            <label for="latitude" class="block text-xs font-medium text-gray-700 mb-2">Latitude</label>
                            <input type="number" name="latitude" id="latitude" value="{{ old('latitude', $weatherConfig->latitude) }}" 
                                   step="0.0000001" min="-90" max="90"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   placeholder="3.1390">
                            @error('latitude')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Longitude -->
                        <div>
                            <label for="longitude" class="block text-xs font-medium text-gray-700 mb-2">Longitude</label>
                            <input type="number" name="longitude" id="longitude" value="{{ old('longitude', $weatherConfig->longitude) }}" 
                                   step="0.0000001" min="-180" max="180"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   placeholder="101.6869">
                            @error('longitude')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Units -->
                        <div>
                            <label for="units" class="block text-xs font-medium text-gray-700 mb-2">Units</label>
                            <select name="units" id="units" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="metric" {{ $weatherConfig->units == 'metric' ? 'selected' : '' }}>Metric (Celsius)</option>
                                <option value="imperial" {{ $weatherConfig->units == 'imperial' ? 'selected' : '' }}>Imperial (Fahrenheit)</option>
                            </select>
                            @error('units')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Language -->
                        <div>
                            <label for="language" class="block text-xs font-medium text-gray-700 mb-2">Language</label>
                            <select name="language" id="language" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="ms" {{ $weatherConfig->language == 'ms' ? 'selected' : '' }}>Bahasa Melayu</option>
                                <option value="en" {{ $weatherConfig->language == 'en' ? 'selected' : '' }}>English</option>
                                <option value="zh" {{ $weatherConfig->language == 'zh' ? 'selected' : '' }}>中文</option>
                                <option value="ta" {{ $weatherConfig->language == 'ta' ? 'selected' : '' }}>தமிழ்</option>
                            </select>
                            @error('language')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Update Frequency -->
                        <div>
                            <label for="update_frequency" class="block text-xs font-medium text-gray-700 mb-2">Update Frequency (minit)</label>
                            <input type="number" name="update_frequency" id="update_frequency" value="{{ old('update_frequency', $weatherConfig->update_frequency) }}" 
                                   min="1" max="1440"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   placeholder="30">
                            @error('update_frequency')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Cache Duration -->
                        <div>
                            <label for="cache_duration" class="block text-xs font-medium text-gray-700 mb-2">Cache Duration (minit)</label>
                            <input type="number" name="cache_duration" id="cache_duration" value="{{ old('cache_duration', $weatherConfig->cache_duration) }}" 
                                   min="1" max="1440"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   placeholder="15">
                            @error('cache_duration')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('integrations.index') }}" 
                           class="px-4 py-2 bg-gray-500 text-white text-xs rounded-xs hover:bg-gray-600 font-medium">
                            <span class="material-icons text-xs mr-2">arrow_back</span>
                            Kembali
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white text-xs rounded-xs hover:bg-blue-700 font-medium">
                            <span class="material-icons text-xs mr-2">save</span>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <style>
        .action-button {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        
        .action-button .material-icons {
            font-size: 16px !important;
            line-height: 1 !important;
            margin-right: 8px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
    </style>
</body>
</html>
