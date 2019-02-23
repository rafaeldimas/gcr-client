<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@index')->name('home');

Route::resource('user', 'UserController');
Route::get('user/current', 'UserController@current')->name('user.current');

Route::prefix('process')->name('process.')->namespace('Process')->group(function () {
    Route::resource('businessman', 'BusinessmanController');
    Route::resource('society', 'SocietyController');
    Route::resource('ireli', 'IreliController');
    Route::resource('other', 'OtherController');
});
