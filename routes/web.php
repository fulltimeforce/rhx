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

Route::get('/experts', 'ExpertController@index')->name('experts.home');

Route::post('/experts/filter', 'ExpertController@filter')->name('experts.filter');

Route::post('/experts/store', 'ExpertController@store')->name('experts.store');
Route::get('/developer/edit/{expertId}','ExpertController@developerEdit')->name('developer.edit')->middleware('signed');
Route::get('/developer/edit/signed/{expertId}','ExpertController@developerEditSigned')->name('developer.edit.signed');

// signed
Route::get('/applicant/register/signed' , 'ExpertController@applicantRegisterSigned')->name('applicant.register.signed');
Route::get('/applicant/register' , 'ExpertController@applicantRegister')->name('applicant.register')->middleware('signed');

Route::resource('positions','PositionController',['except'=>['destroy']]);

Route::get('positions/{positionId}/experts','PositionController@relations')->name('positions.experts');

Route::post('expert/validate','ExpertController@validateEmail')->name('experts.validate');

Route::post('positions/enabled','PositionController@enabled')->name('positions.enabled');

Route::get('expert/technologies','ExpertController@technologies')->name('expert.technologies');

Route::get('/','PositionController@index')->name('home');

Route::post('login', [ 'as' => 'login', 'uses' => 'LoginController@do']);

Route::get('/position/{slug}','ExpertController@isSlug')->name('position.slug');

Route::get('/position/{slug}','ExpertController@isSlug')->name('position.slug');

Route::post('positions/experts', 'PositionController@experts')->name('positions.experts.attach');

Auth::routes(['register' => false]);
