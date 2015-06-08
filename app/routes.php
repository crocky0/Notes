<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});
Route::group(array('prefix' => 'api/v1', 'before' => 'Check_Access_Token|Check_Is_JSON'), function()
{
    Route::resource('notes', 'NoteController');
});
Route::group(array('prefix' => 'api/v1', 'before' => 'Check_Is_JSON'), function()
{
    Route::resource('users', 'UserController');
});
Route::group(array('before' => 'Check_Is_JSON'), function()
{
    Route::post('login', 'AuthorizeController@Login');
});
