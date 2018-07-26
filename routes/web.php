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
    if (Auth::check()) {
        return redirect('/routine/home');
    } else {
        return view('auth/login');
    }
});
Route::get('/routine/403', function () {
    return view('/routine/403');
});

Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
Route::group(['middleware' => ['auth', 'can:admin-higher']], function () {
    Route::get('/routine/{id}/edit', ['as' => 'routine.edit', 'uses' => 'RoutineController@edit']);
    Route::patch('/routine/{id}', 'RoutineController@update');
    Route::delete('/routine/{routine}', 'RoutineController@destroy');
    Route::get('/routine/data', 'RoutineController@data');
    Route::post('routine/space/create', 'RoutineController@createSpace');
    Route::delete('/routine/space/{space}', 'RoutineController@destroySpace');
    Route::post('routine/point/create', 'RoutineController@createPoint');
    Route::delete('/routine/point/{point}', 'RoutineController@destroyPoint');
    Route::post('routine/frequency/create', 'RoutineController@createFrequency');
    Route::delete('/routine/frequency/{frequency}', 'RoutineController@destroyFrequency');
    Route::get('routine/staff', 'RoutineController@staff');
    Route::get('routine/{id}/staffEdit', ['as' => 'routine.staff-edit', 'uses' => 'RoutineController@staffEdit']);
    Route::patch('/routine/{id}/staff', 'RoutineController@staffUpdate');
    Route::post('routine/create', 'RoutineController@create');
});
Route::get('/routine/home', 'RoutineController@home');
Route::get('/routine', 'RoutineController@index');
Route::post('/routine_store', 'RoutineController@store');
Route::post('/routine/register', 'RoutineController@register');
Route::get('/routine/history', 'RoutineController@history');
Route::get('routine/ranking', 'RoutineController@rank');
Route::get('routine/ranking-year', 'RoutineController@rankYear');
Route::get('routine/ranking-date', 'RoutineController@rankDate');

// Route::get('/logout', 'RoutineController@logout');
