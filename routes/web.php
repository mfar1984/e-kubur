<?php

use App\Http\Controllers\KematianController;
use App\Http\Controllers\PpjubController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserAccessController;
use App\Http\Controllers\IntegrationController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\TetapanController;
use App\Http\Controllers\WeatherConfigurationController;
use App\Http\Controllers\EmailConfigurationController;
use App\Http\Controllers\ApiConfigurationController;
use App\Http\Controllers\SanctumTokenController;
use App\Http\Controllers\SystemStatusController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\UserGuideController;
use App\Http\Controllers\ReleaseNotesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserSettingsController;
use App\Http\Controllers\KematianAttachmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('overview');
});

Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

Route::get('/overview', [App\Http\Controllers\OverviewController::class, 'index'])->middleware(['auth', 'verified'])->name('overview');

Route::get('/table-list', function () {
    return view('table-list');
})->middleware(['auth', 'verified'])->name('table-list');

Route::get('/textarea', function () {
    return view('textarea');
})->middleware(['auth', 'verified'])->name('textarea');

Route::get('/settings/global-config', function () {
    return redirect()->route('tetapan.index');
})->middleware(['auth', 'verified'])->name('settings.global-config');

Route::get('/settings/role-management', function () {
    return view('settings.role-management');
})->middleware(['auth', 'verified'])->name('settings.role-management');

Route::get('/settings/user-management', function () {
    return view('settings.user-management');
})->middleware(['auth', 'verified'])->name('settings.user-management');

Route::get('/settings/activity-logs', function () {
    return view('settings.activity-logs');
})->middleware(['auth', 'verified'])->name('settings.activity-logs');

// Weather API
Route::get('/weather', [App\Http\Controllers\WeatherController::class, 'getWeather'])
    ->middleware('api')
    ->name('weather');

// Kematian Resource Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('kematian', KematianController::class);
    Route::get('/kematian-export', [KematianController::class, 'export'])->name('kematian.export');

    // Kematian attachments (photo capture/upload/delete)
    Route::get('/kematian/{kematian}/attachments', [KematianAttachmentController::class, 'index'])->name('kematian.attachments.index');
    Route::post('/kematian/{kematian}/attachments', [KematianAttachmentController::class, 'store'])->name('kematian.attachments.store');
    Route::delete('/kematian/{kematian}/attachments/{attachment}', [KematianAttachmentController::class, 'destroy'])->name('kematian.attachments.destroy');
});

// PPJUB Resource Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('ppjub', PpjubController::class);
    Route::get('/ppjub-export', [PpjubController::class, 'export'])->name('ppjub.export');
});

// Role Management Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('roles', RoleController::class);
    Route::get('/roles-export', [RoleController::class, 'export'])->name('roles.export');
});

// User Access Management Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('user-access', UserAccessController::class);
    Route::get('/user-access-export', [UserAccessController::class, 'export'])->name('user-access.export');
});

// Integration Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('integrations', IntegrationController::class);
    Route::get('/integrations-export', [IntegrationController::class, 'export'])->name('integrations.export');
    
    // Weather Configuration Routes
    Route::resource('weather-configurations', WeatherConfigurationController::class)->except(['index', 'create', 'show', 'store']);
    Route::post('/weather-configurations/test-api', [WeatherConfigurationController::class, 'testApi'])->name('weather-configurations.test-api');
    
    // Email Configuration Routes
    Route::resource('email-configurations', EmailConfigurationController::class)->except(['index', 'create', 'show', 'store']);
    Route::post('/email-configurations/test-email', [EmailConfigurationController::class, 'testEmail'])->name('email-configurations.test-email');
    Route::get('/email-configurations/smtp-health', [EmailConfigurationController::class, 'smtpHealth'])->name('email-configurations.smtp-health');
    
    // API Configuration Routes
    Route::put('/api-configurations/{id}', [ApiConfigurationController::class, 'update'])->name('api-configurations.update');
    
    // Weather Configuration AJAX Routes (without CSRF)
    Route::put('/weather-configurations/{weather_configuration}/update-ajax', [WeatherConfigurationController::class, 'updateAjax'])
        ->name('weather-configurations.update-ajax')
        ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
    
    // Sanctum token management (web guard, uses session & CSRF)
    Route::get('/sanctum-tokens', [SanctumTokenController::class, 'index'])->name('sanctum-tokens.index');
    Route::post('/sanctum-tokens', [SanctumTokenController::class, 'store'])->name('sanctum-tokens.store');
    Route::delete('/sanctum-tokens', [SanctumTokenController::class, 'destroyAll'])->name('sanctum-tokens.destroy-all');
});

// Public healthcheck for Test API button (no auth)
Route::get('/health', function () {
    return response()->json([
        'ok' => true,
        'app' => config('app.name'),
        'time' => now()->toIso8601String(),
    ]);
})->name('health');

// Audit Logs Routes
Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index')->middleware(['auth', 'verified']);
Route::get('/audit-logs/{activity}', [AuditLogController::class, 'show'])->name('audit-logs.show')->middleware(['auth', 'verified']);

// System Status Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/system-status', [SystemStatusController::class, 'index'])->name('system-status.index');
    Route::get('/system-status/api', [SystemStatusController::class, 'api'])->name('system-status.api');
});

// FAQ Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/faq', [FAQController::class, 'index'])->name('faq.index');
});

// User Guide Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/user-guide', [UserGuideController::class, 'index'])->name('user-guide.index');
});

// Release Notes Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/release-notes', [ReleaseNotesController::class, 'index'])->name('release-notes.index');
});

// Profile Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});


Route::delete('/audit-logs/{activity}', [AuditLogController::class, 'destroy'])->name('audit-logs.destroy')->middleware(['auth', 'verified']);
Route::get('/audit-logs-export', [AuditLogController::class, 'export'])->name('audit-logs.export')->middleware(['auth', 'verified']);
Route::post('/audit-logs/clear', [AuditLogController::class, 'clearOldLogs'])->name('audit-logs.clear')->middleware(['auth', 'verified']);

// Tetapan Routes
Route::resource('tetapan', TetapanController::class)->middleware(['auth', 'verified']);
Route::post('/tetapan/bulk-update', [TetapanController::class, 'bulkUpdate'])->name('tetapan.bulk-update')->middleware(['auth', 'verified']);
Route::get('/tetapan-export', [TetapanController::class, 'export'])->name('tetapan.export')->middleware(['auth', 'verified']);

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// require __DIR__.'/auth.php'; // This line was commented out

// Feedback Routes (Public API)
Route::post('/api/feedback', [App\Http\Controllers\FeedbackController::class, 'store'])->name('feedback.store');
Route::post('/api/feedback/verify', [App\Http\Controllers\FeedbackController::class, 'verify'])->name('feedback.verify');
