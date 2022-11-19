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
