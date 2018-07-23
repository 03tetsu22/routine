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

Route::get('/', function () {
    return view('auth/login');
});

Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
Route::get('/routine', 'RoutineController@index');
Route::post('/routine_store', 'RoutineController@store');
Route::post('/routine/register', 'RoutineController@register');
Route::get('/routine/{id}/edit', ['as' => 'routine.edit', 'uses' => 'RoutineController@edit']);
Route::patch('/routine/{id}', 'RoutineController@update');
Route::delete('/routine/{routine}', 'RoutineController@destroy');
Route::get('routine/ranking', 'RoutineController@rank');
Route::get('routine/ranking-year', 'RoutineController@rankYear');
Route::get('routine/ranking-date', 'RoutineController@rankDate');
Route::get('routine/staff', 'RoutineController@staff');
Route::get('routine/{id}/staffEdit', ['as' => 'routine.staff-edit', 'uses' => 'RoutineController@staffEdit']);
Route::patch('/routine/{id}/staff', 'RoutineController@staffUpdate');
Route::post('routine/create', 'RoutineController@create');
// Route::get('/logout', 'RoutineController@logout');
