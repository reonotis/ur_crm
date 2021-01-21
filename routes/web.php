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
    return view('welcome');
});

Auth::routes([
    'verify'   => true, // メール確認機能（※5.7系以上のみ）
    'register' => false, // デフォルトの登録機能OFF
    'reset'    => true,  // メールリマインダー機能ON
]);
Route::group(['prefix'=>'client', 'middleware'=>'auth'], function(){
    Route::get('index', 'ClientController@index')->name('client.index');
    Route::get('list', 'ClientController@list')->name('client.list');
    Route::get('display/{id}', 'ClientController@display')->name('client.display');
    // Route::get('display', 'ClientController@display')->name('client.display');
    Route::get('search', 'ClientController@search')->name('client.search');
    Route::get('searching', 'ClientController@searching')->name('client.searching');
    Route::get('create', 'ClientController@create')->name('client.create');
    Route::post('store', 'ClientController@store')->name('client.store');
    Route::post('update/{id}', 'ClientController@update')->name('client.update');
    Route::get('show/{id}', 'ClientController@show')->name('client.show');
    Route::get('newCall/{id}', 'ClientController@newCall')->name('client.newCall');
    
    // ajax
    Route::get('aj_history_id/{id}', 'ClientController@aj_history_id')->name('client.aj_history_id');
    Route::get('aj_contactList/{id}', 'ClientController@aj_contactList')->name('client.aj_contactList');
    Route::get('aj_orderList/{id}', 'ClientController@aj_orderList')->name('client.aj_orderList');
    Route::get('aj_history_detail/{id}', 'ClientController@aj_history_detail')->name('client.aj_history_detail');
    Route::post('aj_contact_update/{id}', 'ClientController@aj_contact_update')->name('client.aj_contact_update');
});

Route::group(['prefix'=>'user', 'middleware'=>'auth'], function(){
    Route::get('index', 'UserController@index')->name('user.index');
    Route::get('create', 'UserController@create')->name('user.create');
    Route::post('store', 'UserController@store')->name('user.store');
    Route::get('display/{id}', 'UserController@display')->name('user.display');

});
Route::group(['prefix'=>'history', 'middleware'=>'auth'], function(){
    Route::get('index', 'HistoryController@index')->name('history.index');

});


Route::get('/home', 'HomeController@index')->name('home');
