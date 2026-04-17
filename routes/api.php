<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\SearchController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public API Routes
Route::prefix('v1')->group(function () {
    
    // Properties
    Route::get('/properties', [PropertyController::class, 'index']);
    Route::get('/properties/featured', [PropertyController::class, 'featured']);
    Route::get('/properties/{property}', [PropertyController::class, 'show']);
    Route::get('/properties/{property}/similar', [PropertyController::class, 'similar']);
    
    // Locations
    Route::get('/locations', [LocationController::class, 'index']);
    Route::get('/locations/popular', [LocationController::class, 'popular']);
    Route::get('/locations/{location}/properties', [LocationController::class, 'properties']);
    
    // Search
    Route::post('/search', [SearchController::class, 'search']);
    Route::get('/search/suggestions', [SearchController::class, 'suggestions']);
    
    // Statistics
    Route::get('/stats', [App\Http\Controllers\Api\StatsController::class, 'index']);
});