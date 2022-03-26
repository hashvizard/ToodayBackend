<?php

use App\Http\Controllers\CityController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PassportAuthController;
use App\Http\Controllers\PostController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('register', [PassportAuthController::class, 'register']);

Route::middleware('auth:api')->group(function () {
    Route::get('userinfo', [PassportAuthController::class, 'user']);

    Route::get('cities', [CityController::class, 'index']);
    Route::post('cities', [CityController::class, 'userCity']);
    Route::resource('posts', PostController::class);

});
