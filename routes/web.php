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

// ログイン必須な処理
Route::group(['middleware'=>'auth'], function(){
    // エラー関係
    Route::get('/error/exclusion/{code?}', 'ErrorController@exclusionError')->name('exclusionError');
    Route::get('/error/forbidden/{code?}', 'ErrorController@forbiddenError')->name('forbiddenError');

    // TOPページ
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/myPage', 'HomeController@index')->name('myPage');

    // 店舗選択関係
    Route::get('shop/deselect', 'ShopSelectController@deselect')->name('shop.deselect');
    Route::get('shop/selected/{shop}', 'ShopSelectController@selected')->name('shop.selected');

    // ユーザー関係
    Route::get('user/belongSelect', 'UserController@belongSelect')->name('user.belongSelect');
    Route::get('user/belongSelected/{user}', 'UserController@belongSelected')->name('user.belongSelected');
    Route::get('user/deleteBelongShop/{user}', 'UserController@deleteBelongShop')->name('user.deleteBelongShop');
    Route::resource('user', 'UserController');

    // 顧客関係
    Route::resource('customer', 'CustomerController');
    Route::post('customer/destroyReport/{customer}', 'CustomerController@destroyReport')->name('customer.destroyReport');

    // 日報
    Route::get('report', 'ReportController@index')->name('report.index');
    Route::get('report/setStylist/{customer}', 'ReportController@setStylist')->name('report.setStylist');
    Route::post('report/settingStylist/{customer}', 'ReportController@settingStylist')->name('report.settingStylist');

    // 設定関連
    Route::get('setting/index', 'SettingController@index')->name('setting.index');
    Route::get('setting/changeEmail', 'SettingController@changeEmail')->name('setting.changeEmail');
    Route::post('setting/updateEmail', 'SettingController@updateEmail')->name('setting.updateEmail');
    Route::get('changePassword', 'SettingController@changePassword')->name('setting.changePassword');
    Route::post('updatePassword', 'SettingController@updatePassword')->name('setting.updatePassword');

    // 来店履歴関係
    Route::get('visitHistory/register/{customer}', 'VisitHistoryController@register')->name('visitHistory.register');
    Route::get('visitHistory/edit/{visitHistory}', 'VisitHistoryController@edit')->name('visitHistory.edit');
    Route::post('visitHistory/update/{visitHistory}', 'VisitHistoryController@update')->name('visitHistory.update');
    Route::post('destroy/update/{visitHistory}', 'VisitHistoryController@destroy')->name('visitHistory.destroy');
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


// 来店履歴関係
Route::group(['prefix'=>'visitHistory', 'middleware'=>'auth'], function(){
    Route::get('edit', 'VisitHistoryController@edit')->name('VisitHistory.edit');
    Route::get('updates/{id}', 'VisitHistoryController@updates')->name('VisitHistory.updates');
    Route::get('single_edit/{id}', 'VisitHistoryController@single_edit')->name('VisitHistory.single_edit');
    Route::get('destroy/{id}', 'VisitHistoryController@destroy')->name('VisitHistory.destroy');
    Route::get('delete/{id}/angle/{angle}', 'VisitHistoryController@delete')->name('VisitHistory.delete');
    Route::post('single_update/{id}', 'VisitHistoryController@single_update')->name('VisitHistory.single_update');
});


// PDF関連
Route::group(['prefix'=>'pdf', 'middleware'=>'auth'], function(){
    Route::get('index', 'PDFController@index')->name('pdf.index');
    Route::get('show_pdfFile/{file_name}', 'PDFController@show_pdfFile')->name('pdf.show_pdfFile');
});


// ログイン不要な処理
Route::get('/medical/{shop}', 'MedicalController@index')->name('medical.index');
Route::get('/medical/create/{shop}', 'MedicalController@create')->name('medical.create');
Route::post('/medical/store', 'MedicalController@store')->name('medical.store');
Route::get('/medical/complete/{customer}', 'MedicalController@complete')->name('medical.complete');

