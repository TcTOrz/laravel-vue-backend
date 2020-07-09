<?php

/*
 * @Author: Li Jian
 * @Date: 2020-07-07 11:29:36
 * @LastEditTime: 2020-07-09 11:18:33
 * @LastEditors: Li Jian
 * @Description: login
 * @FilePath: /water-environment-end/app/Http/Controllers/Auth/MyLoginController.php
 * @Motto: MMMMMMMM
 */

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Services\Auth\CaptchaService;
use App\Services\Auth\UserService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Vinkla\Hashids\Facades\Hashids;

use Illuminate\Support\Facades\Hash;

class MyLoginController extends Controller
{
    protected $captchaService;
    protected $userService;

    public function __construct(CaptchaService $captchaService, UserService $userService) {
        $this->captchaService = $captchaService;
        $this->userService = $userService;
    }

    /**
     * @return Image
     */
    public function getCaptcha() {
        return $this->captchaService->outImg();
    }

    public function makeToken($auth, $hid) {
        $token = Hashids::connection('main')->encode($auth);

        $data = new \stdClass();
        $data->token = $token;
        // TODO
        $data->hid = $hid;
        $this->setData($data);
        $this->setCode(200);
        return $this->response();
        // $dtoken = Hashids::connection('main')->decode('qLoxjPYa5gO7KWX83my43QJBFr03no7MNbwk21rv6nAJeDBd90');
        // dd($token, $dtoken);
    }

    public function login(Request $request) {
        $uuid = $request->header('x-auth-uuid');
        $this->log('controller.request to'.__METHOD__, ['x-auth-uuid', $uuid]);
        if(empty($uuid)) {
            $code = config('validation.captcha')['uuid.required'];
            $this->setCode($code);
            // TODO
            return $this->response();
        }

        $cacheCaptcha = Cache::pull('captcha.'.$uuid);
        $captcha = strtolower($request->get('captcha'));
        $this->log('controller.request to'.__METHOD__, ['cache_captcha'=> $cacheCaptcha, 'captcha'=> $captcha]);

        if( $cacheCaptcha != $captcha ) {
            $code = config('validation.captcha')['captcha.error'];
            $this->setCode($code);
            // TODO
            return $this->response();
        }

        $email = $request->get('email');
        $user = $this->userService->checkUserByEmail($email);
        if (empty($user)) {
            $this->setCode(config('validation.login')['login.error']);
            return $this->response();
        }

        // if (Hash::check('lijian', '$2y$10$WSS7Y1WEzocf/zLsvVvQruzFFlHZFZ4a9xGqs257eK2cfUc02r9Kys')) {
        //     // dd('saas');
        // }

        $requestPwd = $request->get('password');

        // dd(password_verify($requestPwd,$user->password));
        // dd($requestPwd, $user->password);
        $this->userService->checkPwd($requestPwd, $user->password);


        $email = $request->get('email');
        // $user = $this->userService->checkUserByEmail($email);

        // TODO: 用户名/邮箱/密码格式判断

        $now = time();
        $auth = [$user->id , $now];
        // TODO
        $hid = 32123;
        return $this->makeToken($auth,$hid);
        // dd( $cacheCaptcha.'11111'.$captcha );
        // return $uuid.'12345';
    }

    /**
     * 邮箱校验
     * @param Request $request
     * @return:
     */
    public function verify(Request $request) {
        $token = $request->get('token');
        $message = Hashids::connection('code')->decode($token);

        if( empty($message) ) return $this->returnError();

        if( $message[2] != 3 ) return $this->returnError();

        // 超时校验
        $now = time();
        $validTime = config('tctorz.verify.valid_time');
        if( $now - $message[1] > $validTime ) return $this->returnError();

        // 获取不到id，校验失败
        $user = $this->userService->getUserById($message[0]);
        if( empty($user) ) return $this->returnError();

        dd($user->email.'通过验证');
    }

    public function returnError() {
        $this->setCode(config('validation.login')['verify.failed']);
        return $this->response();
    }
}
