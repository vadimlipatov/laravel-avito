<?php

use App\Http\Middleware\FilledProfile;

// Home
Route::get('/', 'HomeController@index')->name('home');

// Auth
Auth::routes();
Route::get('/verify/{token}', 'Auth\RegisterController@verify')->name('register.verify');

// AdvertsS
Route::group([
	'prefix' => 'adverts',
	'as' => 'adverts.',
	'namespace' => 'Adverts'
], function () {
	Route::get('/show/{advert}', 'AdvertController@show')->name('show');
	Route::get('/show/{advert}/phone', 'AdvertController@phone')->name('phone');

	Route::get('/all/{category?}', 'AdvertController@all')->name('index.all');
	Route::get('/{region?}/{category?}', 'AdvertController@index')->name('index');
});

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
	}
);

Route::group([
	'prefix' => 'adverts',
	'as' => 'adverts.',
	'namespace' => 'Adverts',
	'middleware' => FilledProfile::class
], function () {
	Route::get('/', 'AdvertController@index')->name('index');
	Route::get('/create', 'CreateController@create')->name('create');
	Route::get('/create/region/{category}/{region?}', 'CreateController@region')->name('create.region');
	Route::get('/create/advert/{category}/{region?}', 'CreateController@advert')->name('create.advert');
	Route::post('/create/advert/{category}/{region?}', 'CreateController@store')->name('create.advert.store');

	Route::get('{advert}/edit', 'ManageController@edit')->name('edit');
	Route::put('{advert}/edit', 'ManageController@update')->name('update');
	Route::get('{advert}/photos', 'ManageController@photos')->name('photos');
	Route::post('{advert}/photos', 'ManageController@photos');
	Route::post('{advert}/send', 'ManageController@send')->name('send');
	Route::delete('{advert}/destroy', 'ManageController@destroy')->name('destroy');
});

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
