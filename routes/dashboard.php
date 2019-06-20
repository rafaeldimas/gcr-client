<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@index')->name('home');

Route::resource('user', 'UserController')->except(['show']);
Route::get('user/current', 'UserController@current')->name('user.current');

Route::get('password/change/show', 'UserController@firstTimeLoginShow')->name('user.first.time.login.show');
Route::post('password/change/{user}', 'UserController@firstTimeLogin')->name('user.first.time.login');

Route::resource('status', 'StatusController')->except(['show']);

Route::resource('process', 'ProcessController')->except(['destroy']);
