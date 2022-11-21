<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    $logged_cache_id = session('logged_cache_id', "");
    if(empty($logged_cache_id))
    {
        return redirect(env("APP_URL")."login");
    }

    $isSuperAdmin = session('isSuperAdmin', "0");
    return (!empty($isSuperAdmin)?view('portal/user_list'):view('portal/contact_list'));
});

Route::get('/contacts', function () {
    $logged_cache_id = session('logged_cache_id', "");
    if(empty($logged_cache_id))
    {
        return redirect(env("APP_URL")."login");
    }

    return view('portal/contact_list');
});

Route::get('/users', function () {
    $logged_cache_id = session('logged_cache_id', "");
    if(empty($logged_cache_id))
    {
        return redirect(env("APP_URL")."login");
    }

    return view('portal/user_list');
});

Route::get('/login', function () {
    $logged_cache_id = session('logged_cache_id', "");
    if(!empty($logged_cache_id))
    {
        return redirect(env("APP_URL"));
    }
    return view('portal/login');
});

Route::get('/profile', function () {
    $logged_cache_id = session('logged_cache_id', "");
    if(empty($logged_cache_id))
    {
        return redirect(env("APP_URL")."login");
    }
    return view('portal/profile');
});

Route::get('/change_password', function () {
    $logged_cache_id = session('logged_cache_id', "");
    if(empty($logged_cache_id))
    {
        return redirect(env("APP_URL")."login");
    }
    return view('portal/change_password');
});

Route::post('/login', 'App\Http\Controllers\WebAuthenticationController@login');
Route::get('/logout', 'App\Http\Controllers\WebAuthenticationController@logout');
