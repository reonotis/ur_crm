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
    Route::get('/', 'HomeController@index')->name('home');
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

    // 日報
    Route::get('report', 'ReportController@index')->name('report.index');
    Route::get('report/setStylist/{customer}', 'ReportController@setStylist')->name('report.setStylist');
    Route::post('report/settingStylist/{customer}', 'ReportController@settingStylist')->name('report.settingStylist');

    // データ分析
    Route::post('data/getAnalyzed', 'DataController@getAnalyzed')->name('data.getAnalyzed');
    Route::get('data/{date?}', 'DataController@data')->name('data');

    // 顧客関係
    Route::resource('customer', 'CustomerController');
    Route::post('customer/destroyReport/{customer}', 'CustomerController@destroyReport')->name('customer.destroyReport');

    // 設定関連
    Route::get('setting/index', 'SettingController@index')->name('setting.index');
    Route::get('setting/changeEmail', 'SettingController@changeEmail')->name('setting.changeEmail');
    Route::post('setting/updateEmail', 'SettingController@updateEmail')->name('setting.updateEmail');
    Route::get('changePassword', 'SettingController@changePassword')->name('setting.changePassword');
    Route::post('updatePassword', 'SettingController@updatePassword')->name('setting.updatePassword');
    Route::get('setting/system_information', 'SettingController@systemInformation')->name('setting.system_information');

    // 店舗設定関連
    Route::get('shop_setting/index', 'ShopSettingController@index')->name('shop_setting.index');
    Route::get('shop_setting/business_hour_edit', 'ShopSettingController@businessHourEdit')->name('shop_setting.business_hour_edit');
    Route::post('shop_setting/business_hour_register', 'ShopSettingController@businessHourRegister')->name('shop_setting.business_hour_register');
    Route::get('shop_setting/business_hour_delete/{shopBusinessHour}', 'ShopSettingController@businessHourDelete')->name('shop_setting.business_hour_delete');
    Route::get('shop_setting/temporary_business_hour_edit/', 'ShopSettingController@temporaryBusinessHourEdit')->name('shop_setting.temporary_business_hour_edit');
    Route::post('shop_setting/temporary_business_hour_register/', 'ShopSettingController@temporaryBusinessHourRegister')->name('shop_setting.temporary_business_hour_register');
    Route::get('shop_setting/temporary_business_hour_delete/{shopBusinessHourTemporary}', 'ShopSettingController@temporaryBusinessHourDelete')->name('shop_setting.temporary_business_hour_delete');
    Route::get('shop_setting/close_day_edit/', 'ShopSettingController@closeDayEdit')->name('shop_setting.close_day_edit');
    Route::post('shop_setting/update_close_day/', 'ShopSettingController@closeDayUpdate')->name('shop_setting.update_close_day');
    Route::get('shop_setting/start_week_edit/', 'ShopSettingController@startWeekEdit')->name('shop_setting.start_week_edit');
    Route::post('shop_setting/update_start_week/', 'ShopSettingController@startWeekUpdate')->name('shop_setting.update_start_week');

    // 来店履歴関係
    Route::group(['prefix'=>'visitHistory'], function(){
        Route::get('register/{customer}', 'VisitHistoryController@register')->name('visitHistory.register');
        Route::get('edit/{reserve_info}', 'VisitHistoryController@edit')->name('visitHistory.edit');
        Route::post('/update/{reserve_info}', 'VisitHistoryController@update')->name('visitHistory.update');
        Route::post('/destroy/{reserve_info}', 'VisitHistoryController@destroy')->name('visitHistory.destroy');
    });

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

