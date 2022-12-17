<?php

use App\Http\Controllers\DivisionController;
use Illuminate\Support\Facades\Route;

Route::get('api/division', [DivisionController::class, 'api_list']);
Route::post('api/division', [DivisionController::class, 'api_store']);
Route::delete('api/division/{item}', [DivisionController::class, 'api_remove']);

Route::get('api/division/{item}', [DivisionController::class, 'api_get']);
Route::get('api/division/{item}/sub', [DivisionController::class, 'api_get_sub']);
