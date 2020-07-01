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

Route::get('login', function(){
    dd('login/get');
});

Route::group(['prefix'=> 'captcha', 'middleware'=> 'captcha'], function() {
    Route::get('/','Auth\MyLoginController@getCaptcha')->name('index.get.captcha');
});

Route::get('test', function(){
    dd('123');
});
