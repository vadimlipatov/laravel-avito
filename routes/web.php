<?php

// Home
Route::get('/', 'HomeController@index')->name('home');

// Auth
Auth::routes();
Route::get('/verify/{token}', 'Auth\RegisterController@verify')->name('register.verify');

// Cabinet
Route::group(
	[
		'prefix' => 'cabinet',
		'as' => 'cabinet.',
		'namespace' => 'Cabinet',
		'middleware' => ['auth'],
	],
	function () {
		Route::get('/', 'HomeController@index')->name('home');

		// Profile
		Route::get('/profile', 'ProfileController@index')->name('profile.home');
        Route::get('/profile/edit', 'ProfileController@edit')->name('profile.edit');
        Route::put('/profile/update', 'ProfileController@update')->name('profile.update');
	}
);

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

				Route::group(
					['prefix' => 'categories/{category}', 'as' => 'categories.'],
					function () {
						Route::post('/first', 'CategoryController@first')->name('first');
						Route::post('/up', 'CategoryController@up')->name('up');
						Route::post('/down', 'CategoryController@down')->name('down');
						Route::post('/last', 'CategoryController@last')->name('last');

						// Attributes
						Route::resource('attributes', 'AttributeController')->except('index');
					}
				);
			}
		);
	}
);
