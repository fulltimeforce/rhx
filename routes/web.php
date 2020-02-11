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


Route::get('/','ExpertController@index');
Route::post('/', 'ExpertController@filter');
Route::resource('experts','ExpertController');

Route::get('/developer/edit/{expertId}','ExpertController@developerEdit')->name('developer.edit')->middleware('signed');
Route::get('/developer/edit/signed/{expertId}','ExpertController@developerEditSigned')->name('developer.edit.signed');

Route::get('get_techs', 'ExpertController@techs');
Auth::routes(['register' => false]);

//Route::get('/home', 'HomeController@index')->name('home');
