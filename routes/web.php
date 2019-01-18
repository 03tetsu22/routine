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
    Route::get('/routine/data', 'DataController@index');
    Route::post('routine/space/create', 'DataController@createSpace');
    Route::delete('/routine/space/{space}', 'DataController@destroySpace');
    Route::post('routine/point/create', 'DataController@createPoint');
    Route::delete('/routine/point/{point}', 'DataController@destroyPoint');
    Route::post('routine/frequency/create', 'DataController@createFrequency');
    Route::delete('/routine/frequency/{frequency}', 'DataController@destroyFrequency');
    Route::get('routine/staff', 'StaffController@index');
    Route::get('routine/{id}/staffEdit', ['as' => 'routine.staff-edit', 'uses' => 'StaffController@edit']);
    Route::patch('/routine/{id}/staff', 'StaffController@update');
    Route::delete('/routine/staff/{staff}', 'StaffController@destroy');
    Route::post('routine/create', 'StaffController@create');
});
Route::get('/routine/home', 'RoutineController@home');
Route::get('/routine', 'RoutineController@index');
Route::post('/routine_store', 'RoutineController@store');
Route::post('/routine/register', 'RoutineController@register');
Route::get('/routine/history', 'RoutineController@history');
Route::get('routine/ranking', 'RankController@rank');
Route::get('routine/ranking-year', 'RankController@rankYear');
Route::get('routine/ranking-date', 'RankController@rankDate');

// Route::get('/logout', 'RoutineController@logout');
