<?php

// Home
Route::get('/', 'HomeController@index')->name('home');

// Auth
Auth::routes();
Route::get('/verify/{token}', 'Auth\RegisterController@verify')->name('register.verify');

// Cabinet
Route::get('/cabinet', 'Cabinet\HomeController@index')->name('cabinet');

// Admin
Route::group(
    [
        'prefix' => 'admin',
        'as' => 'admin.',
        'namespace' => 'Admin',
        'middleware' => ['auth', 'can:admin-panel'],
    ],
    function () {
        Route::get('/', 'HomeController@index')->name('home');
        Route::resource('users', 'UsersController');
        Route::post('/users/{user}/verify', 'UsersController@verify')->name('users.verify');
        Route::resource('regions', 'RegionsController');
    }
);
