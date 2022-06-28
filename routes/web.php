<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Privacy-policy
Route::get('/privacy-policy', function () {
    return view('privacyPolicy');
})->name('privacy-policy');

// Terms & Condition
Route::get('/tnc', function () {
    return view('tnc');
})->name('tnc');
