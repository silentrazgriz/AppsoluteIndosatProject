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

Route::get('/', 'HomeController@index')->name('home');
Route::post('/login', 'Auth\LoginController@login')->name('post-login');
Route::post('/logout', 'Auth\LoginController@logout')->name('post-logout');

Route::group(['middleware' => 'auth'], function() {
	Route::get('/survey/{id}', 'EventController@survey')->name('survey');
	Route::post('/survey/{id}', 'EventController@storeAnswer')->name('post-survey');

	Route::post('/balance', 'SalesController@updateSalesBalance')->name('post-balance');
});

Route::group(['middleware' => 'admin', 'prefix' => 'cms'], function() {
	Route::get('/', 'AdminController@index')->name('dashboard');

	Route::get('/sales', 'SalesController@index')->name('sales');
	Route::get('/sales/new', 'SalesController@create')->name('create-sales');
	Route::get('/sales/edit/balance', 'SalesController@editBalance')->name('sales-balance');
	Route::get('/sales/{id}/edit', 'SalesController@edit')->name('edit-sales');
	Route::post('/sales', 'SalesController@store')->name('post-sales');
	Route::put('/sales/{id}', 'SalesController@update')->name('put-sales');
	Route::patch('/sales/balance', 'SalesController@updateBalance')->name('post-sales-balance');
	Route::delete('/sales/{id}', 'SalesController@destroy')->name('delete-sales');

	Route::get('/event', 'EventController@index')->name('event');
	Route::get('/event/new', 'EventController@create')->name('create-event');
	Route::get('/event/{id}/edit', 'EventController@edit')->name('edit-event');
	Route::get('/event/{id}', 'EventController@show')->name('show-event');
	Route::post('/event', 'EventController@store')->name('post-event');
	Route::put('/event/{id}', 'EventController@update')->name('put-event');
	Route::delete('/event/{id}', 'EventController@destroy')->name('delete-event');
});