<?php

use App\Http\Controllers\BlockedController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PassportAuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ViewController;

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
Route::group(['middleware' => ['cors']], function () {

Route::post('register', [PassportAuthController::class, 'register']);

Route::middleware('auth:api')->group(function () {
    Route::get('userinfo', [PassportAuthController::class, 'user']);

    Route::get('cities', [CityController::class, 'index']);
    Route::post('cities', [CityController::class, 'userCity']);

    //Profile Routes
    Route::post('profile/pic', [ProfileController::class, 'updatePhoto']);
    Route::post('profile/name', [ProfileController::class, 'updateName']);
    Route::post('profile/bio', [ProfileController::class, 'updateBio']);
    Route::post('profile/profession', [ProfileController::class, 'updateProfession']);
    // Reviews
    Route::resource('reviews', ReviewController::class);
    //Block
    Route::resource('blocked', BlockedController::class);
    Route::resource('posts', PostController::class);

    Route::get('posts/user/{id}', [PostController::class,'userPosts']);
    Route::resource('view', ViewController::class);
    //Report
    Route::resource('report', ReportController::class);
    // Comment's
    Route::resource('comment', CommentController::class);
});
});
