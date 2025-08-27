<?php

namespace App\Http\Controllers;

use App\Models\WeatherConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Facades\Activity;

class WeatherConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $weatherConfig = WeatherConfiguration::first();
        
        if (!$weatherConfig) {
            // Create default configuration if none exists
            $weatherConfig = WeatherConfiguration::create([
                'provider' => 'OpenWeatherMap',
                'api_key' => '',
                'base_url' => 'https://api.openweathermap.org/data/2.5',
                'default_location' => 'Kuala Lumpur, MY',
                'latitude' => 3.1390,
                'longitude' => 101.6869,
                'units' => 'metric',
                'language' => 'ms',
                'update_frequency' => 30,
                'cache_duration' => 15,
                'is_active' => true
            ]);
        }
        
        return response()->json($weatherConfig);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WeatherConfiguration $weatherConfiguration)
    {
        $weatherConfig = WeatherConfiguration::first();
        
        if (!$weatherConfig) {
            return redirect()->route('integrations.index')->with('error', 'Konfigurasi cuaca tidak dijumpai');
        }
        
        return view('integrations.weather.edit', compact('weatherConfig'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WeatherConfiguration $weatherConfiguration)
    {
        $validator = Validator::make($request->all(), [
            'provider' => 'required|string|max:255',
            'api_key' => 'required|string|max:255',
            'base_url' => 'required|url|max:500',
            'default_location' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'units' => 'required|in:metric,imperial',
            'language' => 'required|in:ms,en,zh,ta',
            'update_frequency' => 'required|integer|min:1|max:1440',
            'cache_duration' => 'required|integer|min:1|max:1440',
        ], [
            'provider.required' => 'Provider cuaca diperlukan',
            'api_key.required' => 'API Key diperlukan',
            'base_url.required' => 'Base URL diperlukan',
            'base_url.url' => 'Base URL mesti dalam format yang betul',
            'default_location.required' => 'Lokasi default diperlukan',
            'latitude.required' => 'Latitude diperlukan',
            'latitude.between' => 'Latitude mesti antara -90 hingga 90',
            'longitude.required' => 'Longitude diperlukan',
            'longitude.between' => 'Longitude mesti antara -180 hingga 180',
            'units.required' => 'Unit diperlukan',
            'units.in' => 'Unit mesti metric atau imperial',
            'language.required' => 'Bahasa diperlukan',
            'language.in' => 'Bahasa tidak sah',
            'update_frequency.required' => 'Kekerapan kemas kini diperlukan',
            'update_frequency.min' => 'Kekerapan kemas kini mesti sekurang-kurangnya 1 minit',
            'update_frequency.max' => 'Kekerapan kemas kini tidak boleh melebihi 1440 minit',
            'cache_duration.required' => 'Tempoh cache diperlukan',
            'cache_duration.min' => 'Tempoh cache mesti sekurang-kurangnya 1 minit',
            'cache_duration.max' => 'Tempoh cache tidak boleh melebihi 1440 minit',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $weatherConfig = WeatherConfiguration::first();
        
        if (!$weatherConfig) {
            $weatherConfig = new WeatherConfiguration();
        }
        
        $weatherConfig->fill($request->all());
        $weatherConfig->last_update = now();
        $weatherConfig->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Konfigurasi cuaca berjaya dikemas kini'
            ]);
        }

        activity('integrations')
            ->event('weather_updated')
            ->causedBy(Auth::user())
            ->performedOn($weatherConfig)
            ->withProperties(array_merge(
                collect($request->all())->only(['provider','base_url','default_location','latitude','longitude','units','language','update_frequency','cache_duration'])->toArray(),
                [
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]
            ))
            ->log('Konfigurasi Cuaca dikemaskini');
        
        return redirect()->route('integrations.index')
            ->with('success', 'Konfigurasi cuaca berjaya dikemas kini');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WeatherConfiguration $weatherConfiguration)
    {
        $weatherConfiguration->delete();
        
        return redirect()->route('integrations.index')
            ->with('success', 'Konfigurasi cuaca berjaya dipadam');
    }

    /**
     * Update weather configuration via AJAX
     */
    public function updateAjax(Request $request, WeatherConfiguration $weatherConfiguration)
    {
        $validator = Validator::make($request->all(), [
            'provider' => 'required|string|max:255',
            'api_key' => 'required|string|max:255',
            'base_url' => 'required|url|max:500',
            'default_location' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'units' => 'required|in:metric,imperial',
            'language' => 'required|in:ms,en,zh,ta',
            'update_frequency' => 'required|integer|min:1|max:1440',
            'cache_duration' => 'required|integer|min:1|max:1440',
        ], [
            'provider.required' => 'Provider cuaca diperlukan',
            'api_key.required' => 'API Key diperlukan',
            'base_url.required' => 'Base URL diperlukan',
            'base_url.url' => 'Base URL mesti dalam format yang betul',
            'default_location.required' => 'Lokasi default diperlukan',
            'latitude.required' => 'Latitude diperlukan',
            'latitude.between' => 'Latitude mesti antara -90 hingga 90',
            'longitude.required' => 'Longitude diperlukan',
            'longitude.between' => 'Longitude mesti antara -180 hingga 180',
            'units.required' => 'Unit diperlukan',
            'units.in' => 'Unit mesti metric atau imperial',
            'language.required' => 'Bahasa diperlukan',
            'language.in' => 'Bahasa tidak sah',
            'update_frequency.required' => 'Kekerapan kemas kini diperlukan',
            'update_frequency.min' => 'Kekerapan kemas kini mesti sekurang-kurangnya 1 minit',
            'update_frequency.max' => 'Kekerapan kemas kini tidak boleh melebihi 1440 minit',
            'cache_duration.required' => 'Tempoh cache diperlukan',
            'cache_duration.min' => 'Tempoh cache mesti sekurang-kurangnya 1 minit',
            'cache_duration.max' => 'Tempoh cache tidak boleh melebihi 1440 minit',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $weatherConfiguration->fill($request->all());
            $weatherConfiguration->last_update = now();
            $weatherConfiguration->save();

            return response()->json([
                'success' => true,
                'message' => 'Konfigurasi cuaca berjaya dikemas kini'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ralat semasa menyimpan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test weather API connection
     */
    public function testApi()
    {
        $weatherConfig = WeatherConfiguration::first();
        
        if (!$weatherConfig || !$weatherConfig->api_key) {
            return response()->json([
                'success' => false,
                'message' => 'API Key tidak dikonfigurasi'
            ], 400);
        }

        try {
            // Simple test to check if API key is valid
            $url = $weatherConfig->base_url . '/weather';
            $params = [
                'q' => $weatherConfig->default_location,
                'appid' => $weatherConfig->api_key,
                'units' => $weatherConfig->units,
                'lang' => $weatherConfig->language
            ];
            
            $response = file_get_contents($url . '?' . http_build_query($params));
            $data = json_decode($response, true);
            
            if (isset($data['cod']) && $data['cod'] == 200) {
                // Update current weather
                $weatherConfig->current_weather = $data['weather'][0]['description'] . ', ' . round($data['main']['temp']) . '°C';
                $weatherConfig->last_update = now();
                $weatherConfig->save();
                
                $result = [
                    'success' => true,
                    'message' => 'API berfungsi dengan baik',
                    'weather' => $weatherConfig->current_weather
                ];

                activity('integrations')
                    ->event('weather_tested')
                    ->causedBy(Auth::user())
                    ->performedOn($weatherConfig)
                    ->withProperties([
                        'result' => 'success',
                        'ip_address' => request()->ip(),
                        'user_agent' => request()->userAgent(),
                    ])
                    ->log('Ujian API Cuaca');

                return response()->json($result);
            } else {
                activity('integrations')
                    ->event('weather_tested')
                    ->causedBy(Auth::user())
                    ->performedOn($weatherConfig)
                    ->withProperties([
                        'result' => 'failed',
                        'ip_address' => request()->ip(),
                        'user_agent' => request()->userAgent(),
                    ])
                    ->log('Ujian API Cuaca - gagal');

                return response()->json([
                    'success' => false,
                    'message' => 'API response tidak sah: ' . ($data['message'] ?? 'Unknown error')
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ralat sambungan: ' . $e->getMessage()
            ], 500);
        }
    }
}
