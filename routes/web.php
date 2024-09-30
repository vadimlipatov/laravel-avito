<?php

use App\Http\Middleware\FilledProfile;

// Home
Route::get('/', 'HomeController@index')->name('home');

// Auth
Auth::routes();
Route::get('/verify/{token}', 'Auth\RegisterController@verify')->name('register.verify');

// Adverts
Route::group([
	'prefix' => 'adverts',
	'as' => 'adverts.',
	'namespace' => 'Adverts'
], function () {
	Route::get('/show/{advert}', 'AdvertController@show')->name('show');
	Route::post('/show/{advert}/phone', 'AdvertController@phone')->name('phone');

	Route::get('/all/{category?}', 'AdvertController@all')->name('index.all');
	Route::get('/{region?}/{category?}', 'AdvertController@index')->name('index');

	Route::get('/{advert_path?}', 'AdvertController@index')->name('index');
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

		Route::get('favorites', 'FavoriteController@index')->name('favorites.index');
		Route::delete('favorites/{advert}', 'FavoriteController@remove')->name('favorites.remove');

		Route::group([
			'prefix' => 'adverts',
			'as' => 'adverts.',
			'namespace' => 'Adverts',
			'middleware' => FilledProfile::class
		], function () {
			Route::get('/', 'AdvertController@index')->name('index');
			Route::get('/create', 'CreateController@category')->name('create');
			Route::get('/create/region/{category}/{region?}', 'CreateController@region')->name('create.region');
			Route::get('/create/advert/{category}/{region?}', 'CreateController@advert')->name('create.advert');
			Route::post('/create/advert/{category}/{region?}', 'CreateController@store')->name('create.advert.store');

			Route::get('/{advert}/edit', 'ManageController@editForm')->name('edit');
			Route::put('/{advert}/edit', 'ManageController@edit');
			Route::get('/{advert}/photos', 'ManageController@photosForm')->name('photos');
			Route::post('/{advert}/photos', 'ManageController@photos');
			Route::get('/{advert}/attributes', 'ManageController@attributesForm')->name('attributes');
			Route::post('/{advert}/attributes', 'ManageController@attributes');
			Route::post('/{advert}/send', 'ManageController@send')->name('send');
			Route::post('/{advert}/close', 'ManageController@close')->name('close');
			Route::delete('/{advert}/destroy', 'ManageController@destroy')->name('destroy');
		});
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

						// Adverts
						Route::group(['prefix' => 'adverts', 'as' => 'adverts.'], function () {
							Route::get('/', 'AdvertController@index')->name('index');
							Route::get('/{advert}/edit', 'AdvertController@editForm')->name('edit');
							Route::put('/{advert}/edit', 'AdvertController@edit');
							Route::get('/{advert}/photos', 'AdvertController@photosForm')->name('photos');
							Route::post('/{advert}/photos', 'AdvertController@photos');
							Route::get('/{advert}/attributes', 'AdvertController@attributesForm')->name('attributes');
							Route::post('/{advert}/attributes', 'AdvertController@attributes');
							Route::post('/{advert}/moderate', 'AdvertController@moderate')->name('moderate');
							Route::get('/{advert}/reject', 'AdvertController@rejectForm')->name('reject');
							Route::post('/{advert}/reject', 'AdvertController@reject');
							Route::delete('/{advert}/destroy', 'AdvertController@destroy')->name('destroy');
						});
					}
				);
			}
		);
	}
);
