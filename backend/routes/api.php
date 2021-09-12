<?php

use Illuminate\Http\Request;
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
Route::get('/user', function() {
    return Auth::user();
})->name('user');

Route::get('timers', 'TimerController@index');
Route::post('timers', 'TimerController@store');
Route::post('timers/save', 'TimerController@save');
Route::put('timers/{id}', 'TimerController@update');
Route::delete('timers/{id}', 'TimerController@stopRunning');
Route::get('timers/active', 'TimersController@running');

Route::post('categories', 'CategoryController@store');
Route::get('categories', 'CagtegoryController@index');

Route::get('records', 'TimerControler@indexMonth');
Route::get('records/total', 'TimerController@indexTotal');