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
    // return view('welcome');
    return view('auth/login');
});

Auth::routes([
    'verify'   => true, // メール確認機能（※5.7系以上のみ）
    'register' => false, // デフォルトの登録機能OFF
    'reset'    => true,  // メールリマインダー機能ON
]);




Route::group(['middleware'=>'auth'], function(){
    Route::get('/home', 'HomeController@index')->name('home');
});



// 管理画面関係
Route::group(['prefix'=>'admin', 'middleware'=>'auth'], function(){
    Route::get('index', 'AdminController@index')->name('admin.index');
    Route::get('customer_complet_course', 'AdminController@customer_complet_course')->name('admin.customer_complet_course');
    Route::get('unPayd', 'AdminController@unPayd')->name('admin.unPayd');
    Route::get('instructorRegistrRequest/{id}', 'SendMail\RegistrRequestController@instructorRegistrRequest')->name('admin.instructorRegistrRequest');
    Route::post('sendmailRegistrRequest/{id}', 'SendMail\RegistrRequestController@sendmailRegistrRequest')->name('admin.sendmailRegistrRequest');
    Route::get('requestPaymentCourseFee/{id}', 'SendMail\RequestPaymentCourseFeeController@index')->name('admin.requestPaymentCourseFee');
    Route::post('sendmailPaymentCourseFee/{id}', 'SendMail\RequestPaymentCourseFeeController@sendmailPaymentCourseFee')->name('admin.sendmailPaymentCourseFee');
    Route::get('RequestAnnualMembershipFee/{id}', 'SendMail\RequestAnnualMembershipFeeController@index')->name('admin.RequestAnnualMembershipFee');
    Route::post('sendRequestAnnualMembershipFee/{id}', 'SendMail\RequestAnnualMembershipFeeController@sendRequestAnnualMembershipFee')->name('admin.sendRequestAnnualMembershipFee');

    Route::get('confirmedPaymentCourseFee/{id}', 'AdminController@confirmedPaymentCourseFee')->name('admin.confirmedPaymentCourseFee');
    Route::get('completeContract/{id}', 'AdminController@completeContract')->name('admin.completeContract');
});

//
Route::group(['prefix'=>'report', 'middleware'=>'auth'], function(){
    Route::get('index', 'ReportController@index')->name('report.index');
});



// 顧客関係
Route::group(['prefix'=>'customer', 'middleware'=>'auth'], function(){
    Route::get('index', 'CustomerController@index')->name('customer.index');
    Route::get('create', 'CustomerController@create')->name('customer.create');
    Route::get('edit/{id}', 'CustomerController@edit')->name('customer.edit');
    Route::post('update/{id}', 'CustomerController@update')->name('customer.update');
    Route::get('search', 'CustomerController@search')->name('customer.search');
    Route::get('searching', 'CustomerController@searching')->name('customer.searching');
    Route::get('display/{id}', 'CustomerController@display')->name('customer.display');
});



// コース申し込み関係
Route::group(['prefix'=>'courseDetails', 'middleware'=>'auth'], function(){
    Route::get('apply/{id}', 'CoursePurchaseDetailsController@apply')->name('courseDetails.apply');
    Route::post('applySecond', 'CoursePurchaseDetailsController@applySecond')->name('courseDetails.applySecond');
    Route::post('courseApply', 'CoursePurchaseDetailsController@courseApply')->name('courseDetails.courseApply');
    Route::get('scheduleEdit/{id}', 'CoursePurchaseDetailsController@scheduleEdit')->name('courseDetails.scheduleEdit');
    Route::post('update/{id}', 'CoursePurchaseDetailsController@scheduleUpdate')->name('courseDetails.update');
});



// スケジュール関係
Route::group(['prefix'=>'schedule', 'middleware'=>'auth'], function(){
    Route::get('index', 'ScheduleController@index')->name('schedule.index');
    Route::get('list', 'ScheduleController@list')->name('schedule.list');
});



// 申請関係
Route::group(['prefix'=>'approval', 'middleware'=>'auth'], function(){
    Route::get('index', 'ApprovalController@index')->name('approval.index');
    Route::get('confilm/{id}', 'ApprovalController@confilm')->name('approval.confilm');
    Route::get('confilmParaCourse/{id}', 'ApprovalController@confilmParaCourse')->name('approval.confilmParaCourse');
    Route::get('confilmIntrCourse/{id}', 'ApprovalController@confilmIntrCourse')->name('approval.confilmIntrCourse');
    Route::post('update/{id}', 'ApprovalController@update')->name('approval.update');
});



// 実施講座関係
Route::group(['prefix'=>'courseSchedule', 'middleware'=>'auth'], function(){
    Route::get('index', 'CourseScheduleController@index')->name('courseSchedule.index');
    Route::get('intrCreate', 'CourseScheduleController@intrCreate')->name('courseSchedule.intrCreate');
    Route::post('intrConfilm', 'CourseScheduleController@intrConfilm')->name('courseSchedule.intrConfilm');
    Route::post('requestIntrCourse', 'CourseScheduleController@requestIntrCourse')->name('courseSchedule.requestIntrCourse');
    Route::get('intrShow/{id}', 'CourseScheduleController@intrShow')->name('courseSchedule.intrShow');
    Route::get('paraCreate', 'CourseScheduleController@paraCreate')->name('courseSchedule.paraCreate');
    Route::post('paraConfilm', 'CourseScheduleController@paraConfilm')->name('courseSchedule.paraConfilm');
    Route::get('paraShow/{id}', 'CourseScheduleController@paraShow')->name('courseSchedule.paraShow');
    Route::post('requestParaCourse', 'CourseScheduleController@requestParaCourse')->name('courseSchedule.requestParaCourse');
    Route::get('intrEdit/{id}', 'CourseScheduleController@intrEdit')->name('courseSchedule.intrEdit');
    Route::get('paraEdit/{id}', 'CourseScheduleController@paraEdit')->name('courseSchedule.paraEdit');
    Route::post('intrUpdate/{id}', 'CourseScheduleController@intrUpdate')->name('courseSchedule.intrUpdate');
    Route::post('intrUpdateOpenDay/{id}', 'CourseScheduleController@intrUpdateOpenDay')->name('courseSchedule.intrUpdateOpenDay');
    Route::post('paraUpdate/{id}', 'CourseScheduleController@paraUpdate')->name('courseSchedule.paraUpdate');
    Route::post('paraUpdateOpenDay/{id}', 'CourseScheduleController@paraUpdateOpenDay')->name('courseSchedule.paraUpdateOpenDay');
    Route::get('paraDelete/{id}', 'CourseScheduleController@paraDelete')->name('courseSchedule.paraDelete');
    Route::get('intrDelete/{id}', 'CourseScheduleController@intrDelete')->name('courseSchedule.intrDelete');
    Route::post('create3', 'CourseScheduleController@create3')->name('courseSchedule.create3');
    Route::get('intrRegister', 'CourseScheduleController@intrRegister')->name('courseSchedule.intrRegister');
});



// ユーザー（インストラクター関係）
Route::group(['prefix'=>'user', 'middleware'=>'auth'], function(){
    Route::get('index', 'UserController@index')->name('user.index');
    Route::get('create', 'UserController@create')->name('user.create');
    Route::post('store', 'UserController@store')->name('user.store');
    Route::get('display/{id}', 'UserController@display')->name('user.display');
    Route::get('searching', 'UserController@searching')->name('user.searching');
    Route::get('newClaim/{id}', 'ClaimController@create')->name('user.newClaim');
    Route::post('sendMailNewClaim/{id}', 'SendMail\NewClaimController@sendMailNewClaim')->name('user.sendMailNewClaim');
    Route::get('sendEmail/{id}', 'SendMail\sendEmailController@index')->name('user.sendEmail');
    Route::post('sendMail/{id}', 'SendMail\sendEmailController@sendMail')->name('user.sendMail');
    Route::get('claimDisplay/{id}', 'UserController@claimDisplay')->name('user.claimDisplay');
    Route::post('claimComplete/{id}', 'UserController@claimComplete')->name('user.claimComplete');
});


// 
Route::group(['prefix'=>'claim', 'middleware'=>'auth'], function(){
    Route::get('updateOrInsert/{id}', 'ClaimController@updateOrInsert')->name('claim.updateOrInsert');
    Route::get('addClaimDetail/{id}', 'ClaimController@addClaimDetail')->name('claim.addClaimDetail');
    Route::get('deleteClaimDetail/{id}', 'ClaimController@deleteClaimDetail')->name('claim.deleteClaimDetail');
    Route::get('updateOrInsert_claimDetail/{id}', 'ClaimController@updateOrInsert_claimDetail')->name('claim.updateOrInsert_claimDetail');
    Route::get('deleteTran/{id}', 'ClaimController@deleteTran')->name('claim.deleteTran');
    Route::get('rankDuwn/{id}', 'ClaimController@rankDuwn')->name('claim.rankDuwn');
    Route::get('confilmAddClaim/{id}', 'ClaimController@confilmAddClaim')->name('claim.confilmAddClaim');
    Route::get('storeClaim/{id}', 'ClaimController@storeClaim')->name('claim.storeClaim');
    Route::get('show/{id}', 'ClaimController@show')->name('claim.show');
});



// courseの詳細
Route::group(['prefix'=>'course_detail', 'middleware'=>'auth'], function(){
    Route::get('display/{id}', 'CourseDetailController@display')->name('course_detail.display');
    Route::get('completCustomerSchedule/{id}', 'CourseDetailController@completCustomerSchedule')->name('course_detail.completCustomerSchedule');
    Route::get('edit/{id}', 'CourseDetailController@edit')->name('course_detail.edit');
});



// setting関係
Route::group(['prefix'=>'setting', 'middleware'=>'auth'], function(){
    Route::get('index', 'SettingController@index')->name('setting.index');
    Route::get('editPassword', 'SettingController@editPassword')->name('setting.editPassword');
    Route::get('editTell', 'SettingController@editTell')->name('setting.editTell');
    Route::get('editAddress', 'SettingController@editAddress')->name('setting.editAddress');
    Route::post('sendChangeEmailLink', 'SettingController@sendChangeEmailLink')->name('setting.sendChangeEmailLink');
    Route::get('editEmail', 'SettingController@editEmail')->name('setting.editEmail');
    Route::get("resetEmail/{token}", "SettingController@resetEmail");
    Route::get('editImage', 'SettingController@editImage')->name('setting.editImage');
    Route::post('updatePassword', 'SettingController@updatePassword')->name('setting.updatePassword');
    Route::post('updateTell', 'SettingController@updateTell')->name('setting.updateTell');
    Route::post('updateAddress', 'SettingController@updateAddress')->name('setting.updateAddress');
    Route::post('updateImage', 'SettingController@updateImage')->name('setting.updateImage');
});



Route::group(['prefix'=>'history', 'middleware'=>'auth'], function(){
    Route::get('index', 'HistoryController@index')->name('history.index');
});


