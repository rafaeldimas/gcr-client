<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@index')->name('home');

Route::resource('user', 'UserController');
Route::get('user/current', 'UserController@current')->name('user.current');

Route::resource('process', 'ProcessController');
