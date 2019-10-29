<?php

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

Route::get('/', 'IndexController@index')->name('index');
Route::get('/place/{slug}', 'PlaceController@show')->name('place.show');
Route::get('/place/{slug}/like/{value}', 'PlaceController@like')
    ->name('place.like')
    ->middleware('auth')
;

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();
