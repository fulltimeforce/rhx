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

Route::get('/expert/confirmation', 'ExpertController@confirmation')->name('experts.confirmation');

Route::resource('positions','PositionController',['except'=>['destroy']]);
Route::get('/','PositionController@index')->name('home');
Route::get('/logs', 'LogController@index')->name('logs.index');
Route::get('positions/{positionId}/experts','PositionController@relations')->name('positions.experts');



Route::get('/expert/{id}/resume','ExpertController@portfolioForm')->name('expert.portfolio.form');
Route::post('/expert/portfolio/save','ExpertController@portfolioSave')->name('expert.portfolio.save');
Route::post('/expert/portfolio/delete','ExpertController@deleteResume')->name('expert.portfolio.delete');

Route::get('/resume','ExpertController@portfolioResume')->name('expert.portfolio.resume');

Route::get('users','UserController@index')->name('user.menu');
Route::get('users/bootstrap','UserController@usersBootstrap')->name('user.list');
Route::get('user/configuration','UserController@configuration')->name('user.configuration');
Route::post('user/save','UserController@save')->name("user.save");
Route::post('user/update','UserController@update')->name("user.update");
Route::post('user/editForm','UserController@editForm')->name("user.editForm");
Route::post('user/delete','UserController@switchStatus')->name("user.switch");

Route::get('/recruiter/log','RecruiterlogController@index')->name('recruiter.log');

Route::get('login/google', 'Auth\LoginController@redirectToProvider')->name('login.google');
Route::get('login/google/callback', 'Auth\LoginController@handleProviderCallback');

Route::post('/upload/drive','ExpertController@uploadDrive')->name('experts.upload.drive');

Route::get('expert/tech','ExpertController@listTech')->name('experts.tech.menu');
Route::get('expert/tech/bootstrap','ExpertController@listTechBootstrap')->name('experts.tech.list');

/*
============================== ROUTES ACTION ============================================
*/ 

Route::get('/apply/{positionId}','ExpertController@apply')->name('experts.apply');
Route::post('/experts/filter', 'ExpertController@filter')->name('experts.filter');
Route::post('/experts/store', 'ExpertController@store')->name('experts.store');
Route::post('/experts/deleteExpert', 'ExpertController@deleteExpert')->name('experts.deleteExpert');
Route::get('expert/technologies','ExpertController@technologies')->name('expert.technologies');
Route::post('expert/fce','ExpertController@getFce')->name('experts.fce');
Route::get('expert/fce/bootstrat','ExpertController@listfcebootstratp')->name('experts.fce.list');
Route::get('expert/fce','ExpertController@listFce')->name('experts.fce.menu');
Route::post('expert/fce/save','ExpertController@saveFce')->name('experts.fce.save');
Route::post('positions/experts', 'PositionController@experts')->name('positions.experts.attach');
Route::get('position/listpositions', 'PositionController@listpositions')->name('positions.listpositions');

Route::get('positions/experts/list', 'PositionController@relationsExperts')->name('positions.experts.list');

Route::post('expert/validate','ExpertController@validateEmail')->name('experts.validate');

Route::get('expert/search','ExpertController@searchbyname')->name('experts.search');

Route::post('expert/selection','ExpertController@selectionExpert')->name('experts.selection');

Route::post('expert/log','ExpertController@log')->name('experts.log');

Route::post('positions/enabled','PositionController@enabled')->name('positions.enabled');
Route::post('positions/expert/status','PositionController@changeStatus')->name('positions.expert.status');

Route::get('/get_techs', 'ExpertController@techs');

// LOGS

Route::post('/logs/store', 'LogController@store')->name('logs.store');
Route::get('/logs/position/{positionId}', 'LogController@position')->name('logs.position');
Route::post('/logs/updateform', 'LogController@updateForm')->name('logs.updateForm');

Route::post('/logs/requirementbylog', 'LogController@requirementByLog')->name('logs.requirementByLog');
Route::post('/logs/savereqapplict', 'LogController@saveReqApplict')->name('logs.saveReqApplict');
Route::post('/logs/take', 'LogController@takeUser')->name('logs.takeUser');
Route::post('/logs/approvefilter', 'LogController@approveFilter')->name('logs.approveFilter');

Route::post('login', [ 'as' => 'login', 'uses' => 'LoginController@do']);

Auth::routes(['register' => false]);

Route::post('/requirement/position', 'RequirementController@positionById')->name('requirement.position');


Route::post('interviews/recruiterlog', 'RecruiterlogController@listnote')->name('interviews.recruiterlog');

Route::post('/recruiterlog/expert/remove', 'RecruiterlogController@removeExpert')->name('recruiterlog.expert.delete');
Route::get('/schedules','LogController@schedules')->name('log.schedules');
Route::post('/schedules/save','LogController@scheduleSave')->name('log.schedule.save');

//jqgrid
Route::get('/expert/list','ExpertController@listjqgrid')->name('expert.list');

// table bootstrap
Route::get('/expert/listtbootstrap','ExpertController@listtbootstrap')->name('expert.listtbootstrap');

//recruiter log

Route::get('/recruiter/listlogs','RecruiterlogController@listlogs')->name('recruiter.listlogs');
Route::post('/recruiter/save','RecruiterlogController@saveForm')->name('recruiter.save');
Route::post('/recruiter/update','RecruiterlogController@updateForm')->name('recruiter.update');
Route::post('/recruiter/delete','RecruiterlogController@deleteForm')->name('recruiter.delete');

Route::post('/recruiter/upload/audio','RecruiterlogController@uploadAudio')->name('recruiter.upload.audio');
Route::post('/recruiter/delete/audio','RecruiterlogController@deleteAudio')->name('recruiter.delete.audio');

// expert log

Route::post('/expert/logs/union','ExpertlogController@union')->name('expert.log.union');
Route::post('/log/note','RecruiterlogController@note')->name('log.note');
Route::post('/log/note/save','RecruiterlogController@noteSave')->name('log.note.save');

//
Route::post('/experts/portfolio/save','ExpertController@saveportfolio')->name('expert.saveportfolio');
Route::post('/experts/portfolio/uploadproject','ExpertController@imageproject')->name('expert.uploadproject');
Route::get('/resume/list','ExpertController@portfolioResumeList')->name('expert.portfolio.resume.list');
/*
============================== ROUTE SIGNED ===========================================
*/
Route::get('/developer/edit/{expertId}','ExpertController@developerEdit')->name('developer.edit')->middleware('signed');
Route::get('/developer/edit/signed/{expertId}','ExpertController@developerEditSigned')->name('developer.edit.signed');

// signed
Route::get('/applicant/register/signed' , 'ExpertController@applicantRegisterSigned')->name('applicant.register.signed');
Route::get('/applicant/register' , 'ExpertController@applicantRegister')->name('applicant.register')->middleware('signed');

// log
Route::get('/expert/register/signed' , 'LogController@synchronizationSigned')->name('log.synchronization.signed');
Route::get('/expert/register' , 'LogController@synchronization')->name('log.synchronization')->middleware('signed');

Route::post('/user/changepage','UserController@changePage')->name('user.changepage');
Route::post('/user/changepassword','UserController@changePassword')->name('user.changepassword');

//config
Route::post('/config/changefcelevel','ConfigController@changeFceLevel')->name("config.changefcelevel");

Auth::routes(['register' => false]);


/*
============================== ROUTE POSTS ===========================================
*/
Route::get('/position/{slug}','ExpertController@isSlug')->name('position.slug');
Route::get('/expert/{slug}','ExpertController@portfolioPreview')->name('expert.portfolio.preview');

Route::post('/expert/audioslog','ExpertController@listaudios')->name('expert.audioslog');

Route::get('experts.home', function () {
    return redirect('experts');
});
