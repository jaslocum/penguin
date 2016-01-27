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

    //Route::resource('images','ImagesController');

    Route::resource('categories','CategoriesController');

    Route::post('{category}/{category_rec_id}', 'CategoriesController@images');

    Route::get('{category}/{category_rec_id}', 'CategoriesController@show');

    Route::get('/', function () {
        return view('pages.home');
    });

});
