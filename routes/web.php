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

		// Users
		Route::resource('users', 'UsersController');
		Route::post('/users/{user}/verify', 'UsersController@verify')->name('users.verify');

		// Regions
		Route::resource('regions', 'RegionsController');

		// Adverts > Categories
		Route::group(
			['prefix' => 'adverts', 'as' => 'adverts.', 'namespace' => 'Adverts'],
			function () {
				Route::resource('categories', 'CategoryController');
				Route::post('/categories/{category}/first', 'CategoryController@first')->name('categories.first');
				Route::post('/categories/{category}/up', 'CategoryController@up')->name('categories.up');
				Route::post('/categories/{category}/down', 'CategoryController@down')->name('categories.down');
				Route::post('/categories/{category}/last', 'CategoryController@last')->name('categories.last');
			}
		);
	}
);
