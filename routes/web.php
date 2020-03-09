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

/*
============================== ROUTES PAGE ============================================
*/ 
Route::resource('experts','ExpertController');
Route::get('/experts', 'ExpertController@index')->name('experts.home');
Route::resource('positions','PositionController',['except'=>['destroy']]);
Route::get('/','PositionController@index')->name('home');
Route::get('/logs', 'LogController@index')->name('logs.index');
Route::get('positions/{positionId}/experts','PositionController@relations')->name('positions.experts');
/*
============================== ROUTES ACTION ============================================
*/ 

Route::get('/apply/{positionId}','ExpertController@apply')->name('experts.apply');
Route::post('/experts/filter', 'ExpertController@filter')->name('experts.filter');
Route::post('/experts/store', 'ExpertController@store')->name('experts.store');
Route::get('expert/technologies','ExpertController@technologies')->name('expert.technologies');

Route::post('positions/experts', 'PositionController@experts')->name('positions.experts.attach');
Route::post('expert/validate','ExpertController@validateEmail')->name('experts.validate');
Route::post('positions/enabled','PositionController@enabled')->name('positions.enabled');

Route::get('/get_techs', 'ExpertController@techs');

// LOGS

Route::post('/logs/store', 'LogController@store')->name('logs.store');
Route::get('/logs/position/{positionId}', 'LogController@position')->name('logs.position');
Route::post('/logs/updateform', 'LogController@updateForm')->name('logs.updateForm');

Route::post('login', [ 'as' => 'login', 'uses' => 'LoginController@do']);

Route::get('/position/{slug}','ExpertController@isSlug')->name('position.slug');

Auth::routes(['register' => false]);

Route::post('/requirement/position', 'RequirementController@positionById')->name('requirement.position');


Route::post('interviews/experts', 'InterviewController@expert')->name('interviews.expert');
Route::post('interviews/save', 'InterviewController@save')->name('interviews.save');
Route::post('interviews/delete', 'InterviewController@delete')->name('interviews.delete');


/*
============================== ROUTE SIGNED ===========================================
*/
Route::get('/developer/edit/{expertId}','ExpertController@developerEdit')->name('developer.edit')->middleware('signed');
Route::get('/developer/edit/signed/{expertId}','ExpertController@developerEditSigned')->name('developer.edit.signed');

// signed
Route::get('/applicant/register/signed' , 'ExpertController@applicantRegisterSigned')->name('applicant.register.signed');
Route::get('/applicant/register' , 'ExpertController@applicantRegister')->name('applicant.register')->middleware('signed');




Auth::routes(['register' => false]);



