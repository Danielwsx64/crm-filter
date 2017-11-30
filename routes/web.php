<?php

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/Dashboard', 'DashboardController@index')->name('dashboard');

Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');

Route::post('/login', 'Auth\LoginController@login');

Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
