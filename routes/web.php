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
    return view('welcome');
});


// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
// Route::middleware('cors')->group(function (){
Route::post('login', 'Auth\MyLoginController@login');
// });

Route::group(['prefix'=> 'captcha', 'middleware'=> 'captcha'], function() {
    Route::get('/','Auth\MyLoginController@getCaptcha')->name('index.get.captcha');
});

Route::group(['middleware' => 'tctorz'],function(){
    Route::get('api/val', 'Index\TestController@getVal');
});

Route::get('api/val1', 'Index\TestController@getVal1');
