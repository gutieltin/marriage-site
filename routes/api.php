<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

// Guest-facing: leave a message for the couple (30 per minute per IP, so a whole
// venue on one Wi-Fi network is not locked out)
Route::post('/messages', [MessageController::class, 'store'])->middleware('throttle:30,1');

// Admin (requires the X-Admin-Token header)
Route::prefix('admin')->middleware('admin.token')->group(function () {
    Route::get('/messages', [AdminController::class, 'messages']);
    Route::get('/messages/export', [AdminController::class, 'exportMessages']);
    Route::delete('/messages/{message}', [AdminController::class, 'destroyMessage']);
});
Route::get('/admin-token-check', function () {
    $file = config_path('wedding.php');
    $settings = require $file;

    return response()->json([
        'config_file' => $file,
        'loaded_config_has_token' => (bool) config('wedding.admin_token'),
        'fresh_config_has_token' => (bool) ($settings['admin_token'] ?? null),
        'environment_variable_exists' => (bool) env('WEDDING_ADMIN_TOKEN'),
        'server_variable_exists' => (bool) ($_SERVER['WEDDING_ADMIN_TOKEN'] ?? null),
        'env_array_variable_exists' => (bool) ($_ENV['WEDDING_ADMIN_TOKEN'] ?? null),
    ]);
});
Route::get('/admin-env-check', function () {
    return response()->json([
        'env_variable_exists' => (bool) env('WEDDING_ADMIN_TOKEN'),
        'config_token_exists' => (bool) config('wedding.admin_token'),
        'app_environment' => app()->environment(),
    ]);
});