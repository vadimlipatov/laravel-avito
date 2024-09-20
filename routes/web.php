<?php

// Home
Route::get('/', 'HomeController@index')->name('home');

// Auth
Auth::routes();
Route::get('/verify/{token}', 'Auth\RegisterController@verify')->name('register.verify');

// Ajax
Route::get('/ajax/regions', 'Ajax\RegionController@get')->name('ajax.regions');

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

		Route::group(
			['prefix' => 'profile', 'as' => 'profile.'],
			function () {

				// Profile
				Route::get('/', 'ProfileController@index')->name('home');
				Route::get('/edit', 'ProfileController@edit')->name('edit');
				Route::put('/update', 'ProfileController@update')->name('update');

				// Phone
				Route::post('/phone', 'PhoneController@request');
				Route::get('/phone', 'PhoneController@form')->name('phone');
				Route::put('/phone', 'PhoneController@verify')->name('phone.verify');
				Route::post('/phone/auth', 'PhoneController@auth')->name('phone.auth');
			}
		);

		Route::resource('adverts', 'Adverts\AdvertController');
		Route::get('adverts/create/region/{category}', 'Adverts\AdvertController')->name('adverts.create.region');
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
