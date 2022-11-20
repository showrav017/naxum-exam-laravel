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
        return redirect("/login");
    }

    return view('portal/contact_list');
});

Route::get('/contacts', function () {
    $logged_cache_id = session('logged_cache_id', "");
    if(empty($logged_cache_id))
    {
        return redirect("/login");
    }

    return view('portal/contact_list');
});

Route::get('/users', function () {
    $logged_cache_id = session('logged_cache_id', "");
    if(empty($logged_cache_id))
    {
        return redirect("/login");
    }

    return view('portal/user_list');
});

Route::get('/login', function () {
    $logged_cache_id = session('logged_cache_id', "");
    if(!empty($logged_cache_id))
    {
        return redirect("/");
    }
    return view('portal/login');
});

Route::post('/login', 'App\Http\Controllers\WebAuthenticationController@login');
Route::get('/logout', 'App\Http\Controllers\WebAuthenticationController@logout');
