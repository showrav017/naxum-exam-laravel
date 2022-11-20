<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', function (Request $request) {
    return response()->json([
        'name' => 'N@XUM React Native Test',
        'version' => ("Laravel v".(Illuminate\Foundation\Application::VERSION." (PHP v".PHP_VERSION.")")),
    ]);
});

Route::any('/login', 'App\Http\Controllers\AuthenticationController@login');
Route::any('/logout', 'App\Http\Controllers\AuthenticationController@logout');


Route::prefix('users')->middleware('validateApiJWT')->group(function () {
    Route::post('list', 'App\Http\Controllers\UsersController@index');
    Route::post('create', 'App\Http\Controllers\UsersController@create');
    Route::put('update_my_profile', 'App\Http\Controllers\UsersController@update');
    Route::get('my_profile', 'App\Http\Controllers\UsersController@my_profile');
    Route::get('info/{user_id}', 'App\Http\Controllers\UsersController@show');
    Route::post('change_my_password', 'App\Http\Controllers\UsersController@change_my_password');
    Route::post('change_password/{user_id}', 'App\Http\Controllers\UsersController@change_password');
    Route::post('update_profile_picture', 'App\Http\Controllers\UsersController@update_profile_picture');
    Route::delete('remove/{user_id}', 'App\Http\Controllers\UsersController@destroy');
});


Route::prefix('contact')->middleware('validateApiJWT')->group(function () {
    Route::post('view_all', 'App\Http\Controllers\ContactsController@index');
    Route::post('create', 'App\Http\Controllers\ContactsController@create');
    Route::put('update/{contact_id}', 'App\Http\Controllers\ContactsController@update');
    Route::delete('remove/{contact_id}', 'App\Http\Controllers\ContactsController@remove');
});
