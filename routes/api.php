<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GatewayReadingController;

// Route to get the authenticated user with Sanctum authentication
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Define the route for storing gateway readings
Route::post('/gateway', [GatewayReadingController::class, 'store']);
