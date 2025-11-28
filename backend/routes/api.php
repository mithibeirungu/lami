<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoriteController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
|
*/

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/register-admin', [AuthController::class, 'registerAdmin']);
Route::post('/login', [AuthController::class, 'login']);

// Cars - public list and detail
Route::get('cars', [CarController::class, 'index']);
Route::get('cars/{car}', [CarController::class, 'show']);

// Protected routes (require Sanctum token)
Route::middleware('auth:sanctum')->group(function () {
	// Cars - create/update/delete
	Route::post('cars', [CarController::class, 'store']);
	Route::put('cars/{car}', [CarController::class, 'update']);
	Route::delete('cars/{car}', [CarController::class, 'destroy']);

	// Comments - store comment for a car
	Route::post('cars/{car}/comments', [CommentController::class, 'store']);

	// Favorites - toggle and list
	Route::post('cars/{car}/favorite', [FavoriteController::class, 'toggle']);
	Route::get('favorites', [FavoriteController::class, 'index']);
});

// Admin-only API routes
Route::prefix('admin')->middleware(['auth:sanctum', \App\Http\Middleware\EnsureAdmin::class])->group(function () {
	Route::get('dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard']);
});
