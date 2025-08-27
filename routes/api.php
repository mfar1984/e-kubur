<?php

use Illuminate\Support\Facades\Route as ApiRouteFacade;

ApiRouteFacade::get('/esolat/today', function() {
    try {
        $zone = \App\Models\Tetapan::where('kunci', 'prayer_zone')->first()->nilai ?? 'SWK16';
        $url = 'https://www.e-solat.gov.my/index.php?r=esolatApi/takwimsolat&period=today&zone=' . urlencode($zone);
        $response = @file_get_contents($url);
        $json = $response ? json_decode($response, true) : null;
        $obj = $json['prayerTime'][0] ?? [];
        $normalize = function($v) {
            $v = trim((string)($v ?? ''));
            if ($v === '' || $v === '-' || $v === '00:00:00') { return '--:--'; }
            // Convert 24h HH:MM:SS to HH:MM AM/PM
            if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $v)) {
                [$h, $m] = explode(':', $v); $ampm = ((int)$h < 12) ? 'AM' : 'PM';
                $h12 = (int)$h % 12; if ($h12 === 0) { $h12 = 12; }
                return sprintf('%02d:%02d %s', $h12, (int)$m, $ampm);
            }
            return $v;
        };
        // Handle possible key variants from API
        $get = function(array $a, array $keys) { foreach ($keys as $k) { if (isset($a[$k]) && $a[$k] !== null && $a[$k] !== '') return $a[$k]; } return null; };
        $times = [
            'imsak'   => $normalize($get($obj, ['imsak'])),
            'fajr'    => $normalize($get($obj, ['fajr','subuh'])),
            'syuruk'  => $normalize($get($obj, ['syuruk','syrok','syuruk_time'])),
            'dhuha'   => $normalize($get($obj, ['dhuha','duha'])),
            'zuhr'    => $normalize($get($obj, ['zohor','zuhr','zuhur','dzuhur','dhuhr'])),
            'asr'     => $normalize($get($obj, ['asar','asr'])),
            'maghrib' => $normalize($get($obj, ['maghrib'])),
            'isha'    => $normalize($get($obj, ['isyak','isha','isya'])),
        ];
        // Optional debug: append raw keys if requested
        $debug = request()->boolean('debug');
        if ($debug) {
            return response()->json([ 'success' => true, 'zone' => $zone, 'times' => $times, 'raw' => $obj ]);
        }
        return response()->json([ 'success' => true, 'zone' => $zone, 'times' => $times ]);
    } catch (\Throwable $e) {
        return response()->json([ 'success' => false, 'message' => 'Failed to fetch e-Solat' ]);
    }
});

use App\Http\Controllers\WeatherConfigurationController;
use App\Http\Controllers\SanctumTokenController;
use App\Http\Controllers\EmailConfigurationController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\KematianController;
use Illuminate\Http\Request;
// Route facade already imported at top

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Weather Configuration API Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::put('/weather-configurations/{weather_configuration}/update', [WeatherConfigurationController::class, 'updateAjax']);
});

// Sanctum Token management (protected by auth web; can adjust later to roles)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/sanctum-tokens', [SanctumTokenController::class, 'store']);
    Route::delete('/sanctum-tokens', [SanctumTokenController::class, 'destroyAll']);
});

// Public Healthcheck for integrations testing: /api/v1/health
Route::prefix('v1')->group(function () {
    Route::get('/health', function () {
        return response()->json([
            'ok' => true,
            'app' => config('app.name'),
            'time' => now()->toIso8601String(),
        ]);
    });
    
    // Send test email via API (requires Sanctum token with write:integrations or admin:all)
    Route::middleware('auth:sanctum')->post('/email/test', [EmailConfigurationController::class, 'testEmailApi']);
    
    
    // Public search API for kematian (no authentication required)
    Route::get('/kematian/search', [KematianController::class, 'search']);
    
    // Public reCAPTCHA configuration API (no authentication required)
    Route::get('/recaptcha/config', function () {
        try {
            $enabled = \App\Models\Tetapan::isRecaptchaEnabled();
            $siteKey = \App\Models\Tetapan::getRecaptchaSiteKey();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'enabled' => $enabled,
                    'site_key' => $enabled ? $siteKey : null,
                    'version' => 'v2',
                    'type' => 'invisible'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch reCAPTCHA configuration'
            ], 500);
        }
    });
    
    // Public feedback submission API (no authentication required, but requires reCAPTCHA)
    Route::post('/feedback', [FeedbackController::class, 'store']);
    Route::post('/feedback/verify', [FeedbackController::class, 'verify']);
});
