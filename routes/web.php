<?php
/*
 * @Author: Li Jian
 * @Date: 2020-07-10 10:05:54
 * @LastEditTime: 2020-07-20 13:39:32
 * @LastEditors: Li Jian
 * @Description:
 * @FilePath: /water-environment-end/routes/web.php
 * @Motto: MMMMMMMM
 */

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

Route::post('register', 'Auth\MyRegisterController@register');
// });

Route::group(['prefix'=> 'captcha', 'middleware'=> 'captcha'], function() {
    Route::get('/','Auth\MyLoginController@getCaptcha')->name('index.get.captcha');
});

Route::group(['middleware' => 'tctorz'],function(){
    Route::get('api/val', 'Index\TestController@getVal');
});

// 邮箱token验证
Route::get('/verify', 'Auth\MyLoginController@verify');

Route::get('api/val1', 'Index\TestController@getVal1');

Route::get('api/val2', 'Index\TestController@getVal2');


// Route::get('new/auth', function(){
//     $auth = \Request::get('auth');
//     // var_dump($auth);
//     return redirect(' http://10.10.10.61:8001/login?auth='.$auth,302);
// });

Route::get('auth/{service}', 'Auth\MyLoginController@redirectToProvider');
Route::get('auth/{service}/callback', 'Auth\MyLoginController@handleProviderCallback');

//授权登录 需要绑定账号
Route::get('new/auth', function(){
    $auth = \Request::get('auth');
    return redirect(env('TCTORZ_INDEX_DOMAIN').':'.env('TCTORZ_INDEX_PORT').env('TCTORZ_AUTH_REDIRECT').'?auth='.$auth, 302);
})->name('new.auth');

//直接登录 返回前端首页
Route::get('new/login', function(){
    $token = \Request::get('token');
    $hid = \Request::get('hid');
    return redirect(env('TCTORZ_INDEX_DOMAIN').':'.env('TCTORZ_INDEX_PORT').env('TCTORZ_LOGIN_REDIRECT').'?secret='.$token.'&secretId='.$hid,302);
})->name('new.login');
