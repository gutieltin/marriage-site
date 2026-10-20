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
    return response()->json([
        'config_file_exists' => file_exists(config_path('wedding.php')),
        'config_file_path' => config_path('wedding.php'),
        'config_has_admin_token' => array_key_exists(
            'admin_token',
            config('wedding', [])
        ),
        'token_configured' => (bool) config('wedding.admin_token'),
    ]);
});
Route::get('/admin-env-check', function () {
    return response()->json([
        'env_variable_exists' => (bool) env('WEDDING_ADMIN_TOKEN'),
        'config_token_exists' => (bool) config('wedding.admin_token'),
        'app_environment' => app()->environment(),
    ]);
});