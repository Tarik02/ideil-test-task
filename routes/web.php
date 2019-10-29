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

Route::group([
    'as' => 'place.',
    'prefix' => '/place',
], function () {
    Route::get('/{slug}', 'PlaceController@show')->name('show');

    Route::get('/{slug}/like/{value}', 'PlaceController@like')
        ->name('like')->middleware('auth');

    Route::get('/{slug}/comments', 'PlaceController@comments')
        ->name('comments');

    Route::put('/{slug}/comments', 'PlaceController@addComment')
        ->name('comment')->middleware('auth');
});

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();
