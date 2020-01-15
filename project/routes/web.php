<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'guest'], function(){
	Route::get('/', function () {
		return view('welcome');
	});
});

Auth::routes();

Route::group(['middleware' => 'auth'], function(){
	Route::get('home', function(){
		if(Auth::user()->status == 2){
			return view('dashboard');
		}else{
			return view('dashboard-pemilik');
		}
	})->name('home');
});

Route::get('/register', 'Auth\RegisterController@showRegisForm')->name('register');
Route::post('/register', 'Auth\RegisterController@store')->name('register.submit');

Route::get('HTML-404', ['as' => 'notfound', 'uses' => 'HomeController@pagenotfound']);

Route::group(['middleware' => 'auth'], function () {

	// ROUTE GENERAL
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);

	// ROUTE ADMIN
	Route::resource('user', 'UserController', ['except' => ['show']]);
	Route::resource('list-ukm', 'UKMController');
	Route::resource('pemilik', 'PemilikController');

	// ROUTE PEMILIK


});

