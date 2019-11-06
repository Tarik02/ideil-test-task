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

Route::group([
    'as' => 'admin.',
    'prefix' => '/admin',
    'middleware' => 'admin',
], function () {
    Route::group([
        'as' => 'api.',
        'prefix' => '/api',
        'namespace' => 'Admin',
    ], function () {
        Route::apiResource('places', 'PlaceController');

        Route::get('/places/{slug}/comments', 'PlaceCommentController@show');
        Route::patch('/places/{slug}/comments/{id}', 'PlaceCommentController@update');
        Route::delete('/places/{slug}/comments/{id}', 'PlaceCommentController@destroy');
    });

    Route::any('/{path?}', function (string $path = null) {
        if ($path !== null && 0 === stripos($path, 'api')) {
            abort(404);
        }

        return view('admin.app');
    })->where('path', '.*');
});
