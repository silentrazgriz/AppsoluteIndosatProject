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
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/reset-balance', 'HomeController@resetBalance')->name('resetBalance');

Route::group(['middleware' => 'auth'], function() {
	Route::get('/survey/{eventId}', 'SurveyController@show')->name('survey');
	Route::post('/survey/{eventId}', 'SurveyController@store')->name('post-survey');
    Route::get('/performance', 'HomeController@leaderboard')->name('leaderboard');

	Route::post('/balance', 'SalesController@updateSalesBalance')->name('post-balance');
});

Route::group(['middleware' => 'admin', 'prefix' => 'cms'], function() {
	Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
	Route::get('/dashboard/area', 'DashboardController@dashboardPerArea')->name('dashboard-area');
    Route::get('/dashboard/agent', 'DashboardController@dashboardPerAgent')->name('dashboard-agent');

	Route::get('/survey/{id}/edit', 'SurveyController@edit')->name('edit-survey');
	Route::put('/survey/{id}', 'SurveyController@update')->name('put-survey');

    Route::get('/report', 'DashboardController@report')->name('report');
	Route::get('/gallery', 'DashboardController@gallery')->name('gallery');
	Route::get('/hashtag', 'DashboardController@hashtag')->name('hashtag');

    Route::get('/export/kpi', 'ExcelController@kpiToExcel')->name('export-kpi');
	Route::get('/export/answer', 'ExcelController@answerToExcel')->name('export-answer');
	Route::get('/export/sales', 'ExcelController@salesToExcel')->name('export-sales');

	Route::get('/sales', 'SalesController@index')->name('sales');
	Route::get('/admin/new', 'SalesController@createAdmin')->name('create-admin');
	Route::get('/sales/new', 'SalesController@create')->name('create-sales');
	Route::get('/sales/edit/balance', 'SalesController@editBalance')->name('sales-balance');
	Route::get('/sales/{id}/edit', 'SalesController@edit')->name('edit-sales');
	Route::post('/sales', 'SalesController@store')->name('post-sales');
	Route::put('/sales/{id}', 'SalesController@update')->name('put-sales');
	Route::patch('/sales/balance', 'SalesController@updateBalance')->name('post-sales-balance');
	Route::delete('/sales/{id}', 'SalesController@destroy')->name('delete-sales');

	Route::get('/sales-area', 'SalesAreaController@index')->name('area');
	Route::get('/sales-area/new', 'SalesAreaController@create')->name('create-area');
	Route::get('/sales-area/{id}/edit', 'SalesAreaController@edit')->name('edit-area');
	Route::post('/sales-area', 'SalesAreaController@store')->name('post-area');
	Route::put('/sales-area/{id}', 'SalesAreaController@update')->name('put-area');
	Route::delete('/sales-area/{id}', 'SalesAreaController@destroy')->name('delete-area');

	Route::get('/event', 'EventController@index')->name('event');
	Route::get('/event/new', 'EventController@create')->name('create-event');
	Route::get('/event/{id}/edit', 'EventController@edit')->name('edit-event');
	Route::get('/event/{id}', 'EventController@show')->name('show-event');
	Route::post('/event', 'EventController@store')->name('post-event');
	Route::put('/event/{id}', 'EventController@update')->name('put-event');
	Route::delete('/event/{id}', 'EventController@destroy')->name('delete-event');

	Route::get('/number', 'NumberListController@index')->name('number');
	Route::get('/number/new', 'NumberListController@create')->name('create-number');
	Route::post('/number', 'NumberListController@store')->name('post-number');
	Route::delete('/number/{id}', 'NumberListController@destroy')->name('delete-number');
});

Route::get('/compress', 'DashboardController@compressImage');