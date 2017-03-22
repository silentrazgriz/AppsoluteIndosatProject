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

Route::get('/', 'HomeController@index');
Route::post('/login', 'Auth\LoginController@login')->name('login');

Route::get('/survey/{eventId}', 'EventController@index');

Route::group(['middleware' => 'admin', 'prefix' => 'cms'], function() {
	Route::get('/', 'AdminController@index');
	Route::get('/new-sales', 'Auth\RegisterController@showRegistrationForm');
	Route::post('/new-sales', 'Auth\RegisterController@register')->name('register');
});