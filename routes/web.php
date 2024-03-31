<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/login', '\App\Http\Controllers\AuthController@showLoginForm')->name('login');
Route::post('/login', '\App\Http\Controllers\AuthController@login');
Route::post('/logout', '\App\Http\Controllers\AuthController@logout')->middleware('auth');

Route::get('/', '\App\Http\Controllers\ListController@showList')->name('list')->middleware('auth');
Route::post('/', '\App\Http\Controllers\ListController@changeMode')->middleware('auth');

Route::get('/new/{mode}', '\App\Http\Controllers\DataController@showAdd')->middleware('auth');
Route::get('/import/{mode}', '\App\Http\Controllers\DataController@showImport')->middleware('auth');

Route::post('/new', '\App\Http\Controllers\DataController@add')->middleware('auth');
Route::post('/import', '\App\Http\Controllers\DataController@import')->middleware('auth');

Route::post('/delete', '\App\Http\Controllers\DataController@delete')->middleware(['auth', 'permit']);

Route::post('/edit', '\App\Http\Controllers\DataController@edit')->middleware(['auth', 'permit']);
Route::post('/edit_bsa_ms', '\App\Http\Controllers\DataController@edit_bsa_ms')->middleware(['auth', 'permit']);

Route::get('/units', '\App\Http\Controllers\UserController@showUnits')->middleware('admin');
Route::put('/units/account', '\App\Http\Controllers\UserController@editAccount')->middleware('admin');
Route::put('/units/password', '\App\Http\Controllers\UserController@changeAccountPassword')->middleware('admin');
Route::post('/units/account', '\App\Http\Controllers\UserController@addAccount')->middleware('admin');
Route::delete('/units/account', '\App\Http\Controllers\UserController@deleteAccount')->middleware('admin');

