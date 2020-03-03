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



//Route::get('/experts','ExpertController@index');

Route::resource('experts','ExpertController');
Route::get('/apply/{positionId}','ExpertController@apply')->name('experts.apply');
Route::post('/experts', 'ExpertController@filter')->name('experts.filter');
Route::post('/experts/store', 'ExpertController@store')->name('experts.store');
Route::get('/developer/edit/{expertId}','ExpertController@developerEdit')->name('developer.edit')->middleware('signed');
Route::get('/developer/edit/signed/{expertId}','ExpertController@developerEditSigned')->name('developer.edit.signed');

// signed
Route::get('/applicant/register/signed' , 'ExpertController@applicantRegisterSigned')->name('applicant.register.signed');
Route::get('/applicant/register' , 'ExpertController@applicantRegister')->name('applicant.register')->middleware('signed');

Route::resource('positions','PositionController',['except'=>['destroy']]);

Route::get('positions/{positionId}/experts','PositionController@relations')->name('positions.experts');

Route::post('expert/validate','ExpertController@validateEmail')->name('experts.validate');

Route::get('position/enabled/{expertId}','PositionController@enabled')->name('position.enabled');


Route::get('/','PositionController@index')->name('home');

Route::post('login', [ 'as' => 'login', 'uses' => 'LoginController@do']);

Route::get('/position/{slug}','ExpertController@isSlug')->name('position.slug');

Route::get('/get_techs', 'ExpertController@techs');

Auth::routes(['register' => false]);
