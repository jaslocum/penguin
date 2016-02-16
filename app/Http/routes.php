<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {

    Route::get('/session',function()
    {
        return view('pages.session');
    });

    Route::resource('image', 'ImageController');

    Route::resource('category', 'CategoryController');

    Route::post('{category}/{key}', 'BucketController@post');

    Route::get('{category}/{key}', 'BucketController@get');

    Route::get('{category}/{key}/index', 'BucketController@index');

    Route::get('{category}/{key}/update', 'BucketController@update');

    Route::get('{category}/{key}/destroy', 'BucketController@destroy');

    Route::get('{category}/{key}/{filename}', 'BucketController@get');

    Route::get('{category}/{key}/{filename}/destroy', 'BucketController@destroy');

    Route::get('/', function () {
        return view('pages.home');
    });


});
