<?php

use Illuminate\Support\Facades\Route;

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

Route::post('/register', 'Auth\RegisterController@register')->name('register');
Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/user', function () {
    return Auth::user();
})->name('user');

Route::get('timers', 'TimerController@index');
Route::post('timers', 'TimerController@store');
Route::post('timers/save', 'TimerController@save');
Route::put('timers/{id}', 'TimerController@update');
Route::delete('timers/{id}', 'TimerController@destroy');
Route::post('timers/stop', 'TimerController@stopRunning');
Route::get('timers/active', 'TimerController@running');

Route::post('categories', 'CategoryController@store');
Route::get('categories', 'CategoryController@index');

Route::get('records', 'TimerController@indexMonth');
Route::get('records/total', 'TimerController@indexTotal');
