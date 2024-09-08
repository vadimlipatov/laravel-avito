<?php

//Home
Route::get('/', 'HomeController@index')->name('home');

//Auth
Auth::routes();
Route::get('/verify/{token}', 'Auth\RegisterController@verify')->name('register.verify');

//Cabinet
Route::get('/cabinet', 'Cabinet\HomeController@index')->name('cabinet');

// Admin
Route::group(
    [
        'prefix' => 'admin',
        'as' => 'admin.',
        'namespace' => 'Admin',
        'middleware' => ['auth'],
    ],
    function () {
        Route::get('/', 'HomeController@index')->name('home');
        Route::resource('users', 'UsersController');
        Route::get('/verify', 'UsersController@verify')->name('users.verify');
    }
);
