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

    Route::resource('category_definitions', 'CategoryDefinitionsController');

    Route::post('{category}/{category_rec_id}', 'CategoriesController@post');

    Route::get('{category}/{category_rec_id}', 'CategoriesController@get');

    Route::get('{category}/{category_rec_id}/index', 'CategoriesController@index');

    Route::get('{category}/{category_rec_id}/update', 'CategoriesController@update');

    Route::get('{category}/{category_rec_id}/destroy', 'CategoriesController@destroy');

    Route::get('{category}/{category_rec_id}/{filename}', 'CategoriesController@get');

    Route::get('{category}/{category_rec_id}/{filename}/destroy', 'CategoriesController@destroy');

    Route::get('/', function () {
        return view('pages.home');
    });


});
