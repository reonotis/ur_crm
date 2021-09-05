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

Route::get('/medical_record/complete/{id}', 'MedicalRecordController@complete')->name('medical_record.complete');
Route::get('/medical_record/{id}', 'MedicalRecordController@index')->name('medical_record');
Route::post('/medical_record/confirm', 'MedicalRecordController@confirm')->name('medical_record.confirm');

Route::group(['middleware'=>'auth'], function(){
    Route::get('/home', 'HomeController@index')->name('home');
});

// 日報
Route::group(['prefix'=>'report', 'middleware'=>'auth'], function(){
    Route::get('index', 'ReportController@index')->name('report.index');
    Route::get('set_stylist/{id}', 'ReportController@set_stylist')->name('report.set_stylist');
    Route::post('setting_stylist/{id}', 'ReportController@setting_stylist')->name('report.setting_stylist');
});

// 過去日報
Route::group(['prefix'=>'oldReport', 'middleware'=>'auth'], function(){
    Route::get('index', 'OldReportController@index')->name('oldReport.index');
    Route::get('daily', 'OldReportController@daily')->name('oldReport.daily');
    Route::get('getDayRecord', 'OldReportController@getDayRecord')->name('oldReport.getDayRecord');
    Route::get('getMonthRecord', 'OldReportController@getMonthRecord')->name('oldReport.getMonthRecord');
    Route::get('weekly', 'OldReportController@weekly')->name('oldReport.weekly');
    Route::get('monthly', 'OldReportController@monthly')->name('oldReport.monthly');
});

// スタイリスト関係
Route::group(['prefix'=>'stylist', 'middleware'=>'auth'], function(){
    Route::get('index', 'StylistController@index')->name('stylist.index');
    Route::get('create', 'StylistController@create')->name('stylist.create');
    Route::post('store', 'StylistController@store')->name('stylist.store');
    Route::get('show/{id}', 'StylistController@show')->name('stylist.show');
    Route::get('edit/{id}', 'StylistController@edit')->name('stylist.edit');
    Route::post('update/{id}', 'StylistController@update')->name('stylist.update');
});

// 顧客関係
Route::group(['prefix'=>'customer', 'middleware'=>'auth'], function(){
    Route::get('search', 'CustomerController@search')->name('customer.search');
    Route::get('searching', 'CustomerController@searching')->name('customer.searching');
    Route::get('create', 'CustomerController@create')->name('customer.create');
    Route::get('show/{id}', 'CustomerController@show')->name('customer.show');
    Route::get('edit/{id}', 'CustomerController@edit')->name('customer.edit');
    Route::post('update/{id}', 'CustomerController@update')->name('customer.update');
    Route::post('store', 'CustomerController@store')->name('customer.store');
    Route::get('delete/{id}', 'CustomerController@delete')->name('customer.delete');
});

// 来店履歴関係
Route::group(['prefix'=>'VisitHistory', 'middleware'=>'auth'], function(){
    Route::get('register/{id}', 'VisitHistoryController@register')->name('VisitHistory.register');
    Route::get('edit', 'VisitHistoryController@edit')->name('VisitHistory.edit');
    Route::get('updates/{id}', 'VisitHistoryController@updates')->name('VisitHistory.updates');
    Route::get('single_edit/{id}', 'VisitHistoryController@single_edit')->name('VisitHistory.single_edit');
    Route::get('destroy/{id}', 'VisitHistoryController@destroy')->name('VisitHistory.destroy');
    Route::get('delete/{id}/angle/{angle}', 'VisitHistoryController@delete')->name('VisitHistory.delete');
    Route::post('single_update/{id}', 'VisitHistoryController@single_update')->name('VisitHistory.single_update');
});


// 設定関連
Route::group(['prefix'=>'setting', 'middleware'=>'auth'], function(){
    Route::get('index', 'SettingController@index')->name('setting.index');
    Route::get('Email', 'SettingController@Email')->name('setting.Email');
    Route::get('ChangeEmail', 'SettingController@ChangeEmail')->name('setting.ChangeEmail');
    Route::post('updateEmail', 'SettingController@updateEmail')->name('setting.updateEmail');
    Route::get('EditPassword', 'SettingController@EditPassword')->name('setting.EditPassword');
    Route::post('updatePassword', 'SettingController@updatePassword')->name('setting.updatePassword');
    Route::get('lecture', 'SettingController@lecture')->name('setting.lecture');
    Route::get('notice', 'SettingController@notice')->name('setting.notice');
});



// 設定関連
Route::group(['prefix'=>'pdf', 'middleware'=>'auth'], function(){
    Route::get('index', 'PDFController@index')->name('pdf.index');
    Route::get('show_pdfFile/{file_name}', 'PDFController@show_pdfFile')->name('pdf.show_pdfFile');
});





