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
        'token_configured' => (bool) config('wedding.admin_token'),
        'token_length' => strlen(
            (string) config('wedding.admin_token')
        ),
    ]);
});