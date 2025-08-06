<?php

use App\Http\Controllers\SchoolManagementController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/health-check', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
    ]);
})->name('health-check');

Route::get('/', [SchoolManagementController::class, 'index'])->name('home');
Route::post('/assign-student', [SchoolManagementController::class, 'store'])
    ->middleware(['auth'])
    ->name('assign.student');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
