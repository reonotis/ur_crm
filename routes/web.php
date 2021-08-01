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

// スタイリスト関係
Route::group(['prefix'=>'stylist', 'middleware'=>'auth'], function(){
    Route::get('index', 'StylistController@index')->name('stylist.index');
});

// 顧客関係
Route::group(['prefix'=>'customer', 'middleware'=>'auth'], function(){
    Route::get('search', 'CustomerController@search')->name('customer.search');
    Route::get('searching', 'CustomerController@searching')->name('customer.searching');
    Route::get('create', 'CustomerController@create')->name('customer.create');
    Route::get('show/{id}', 'CustomerController@show')->name('customer.show');
    Route::get('visit_history/{id}', 'CustomerController@visit_history')->name('customer.visit_history');
    Route::get('edit/{id}', 'CustomerController@edit')->name('customer.edit');
    Route::post('update/{id}', 'CustomerController@update')->name('customer.update');
});

// 設定関連
Route::group(['prefix'=>'setting', 'middleware'=>'auth'], function(){
    Route::get('index', 'SettingController@index')->name('setting.index');
    Route::get('changeEmail', 'SettingController@changeEmail')->name('setting.changeEmail');
    Route::get('changePassword', 'SettingController@changePassword')->name('setting.changePassword');
});

