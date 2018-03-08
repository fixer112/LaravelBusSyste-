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


Route::get('/', function(){
    return view('index');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/users', 'UserController@index')->middleware('admin');
Route::get('/users/{$id}', 'UserController@show')->middleware('admin');
Route::get('/createstop', 'StopsController@create')->middleware('admin');
Route::get('/createticket', 'TicketsController@create')->middleware('auth');
Route::get('/createroute', 'RoutesController@create')->middleware('admin');
Route::get('/routes', 'RoutesController@index');
Route::get('/routes/{$route_id}', 'RoutesController@show');
Route::get('/stops', 'StopsController@index');
Route::get('/stops/{$stop_id}', 'StopsController@show');
Route::get('/schedules/{$schedule_id}', 'SchedulesController@show');
Route::get('/tickets', 'TicketsController@index')->middleware('auth');

Route::post('create_schedule', array('uses' => 'SchedulesController@create'))->middleware('admin');

Route::group(['prefix' => 'admin',  'middleware' => 'admin'], function () {
    
    Route::resource('stops','StopsController', ['except' => [
        'index', 'show'
    ]]);
    Route::resource('admins','AdminsController');
    Route::resource('schedules','SchedulesController', ['except' => [
         'show'
    ]]);
    Route::resource('users','UserController');
    Route::resource('routes','RoutesController', ['except' => [
        'index','show'
   ]]);
   Route::resource('routestops','RouteStopsController');

});


Route::group(['prefix' => 'user',  'middleware' => 'auth'], function () {
    Route::resource('tickets','TicketsController');
});

Route::resource('stops','StopsController');
Route::resource('routes','RoutesController');
Route::resource('routestops','RouteStopsController');
Route::resource('schedules','SchedulesController');
Route::resource('admins','AdminsController');
Route::resource('users','UserController');
Route::resource('tickets','TicketsController');
