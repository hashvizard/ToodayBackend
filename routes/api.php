<?php

use App\Http\Controllers\CityController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PassportAuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;

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

    //Profile Routes
    Route::post('profile/pic', [ProfileController::class, 'updatePhoto']);
    Route::post('profile/name', [ProfileController::class, 'updateName']);

    // Reviews
    Route::resource('reviews', ReviewController::class);
    Route::resource('posts', PostController::class);

});
