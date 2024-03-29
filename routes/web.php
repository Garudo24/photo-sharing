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

use Illuminate\Support\Facades\Auth;

Route::group(['middleware' => 'guest'], function () {
    Route::post('/register', 'Auth\RegisterController@register')->name('register');
    Route::post('/login', 'Auth\LoginController@login')->name('login');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/user', fn () => Auth::user())->name('user');
    Route::post('/photos', 'PhotoController@create')->name('photo.create');
    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
    Route::post('/photos/{photo_id}/comments', 'PhotoController@addComment')->name('photo.comment');
    Route::get('/photos/{photo_id}', 'PhotoController@show')->name('photo.show');
    Route::get('/photos/{photo_id}/download', 'PhotoController@download');
});

Route::get('/photos', 'PhotoController@index')->name('photo.index');
Route::put('/photos/{photo_id}/like', 'PhotoController@like')->name('photo.like');
Route::delete('/photos/{photo_id}/like', 'PhotoController@unlike')->name('photo.unlike');

Route::get('/{any?}', fn () => view('index'))->where('any', '.+');
