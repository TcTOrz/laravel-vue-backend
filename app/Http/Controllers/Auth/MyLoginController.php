<?php

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

        /* if( $cacheCaptcha != $captcha ) {
            $code = config('validation.captcha')['captcha.error'];
            $this->setCode($code);
            // TODO
            return $this->response();
        } */

        $email = $request->get('email');
        $user = $this->userService->checkUserByEmail($email);
        // dd($user);

        // if (Hash::check('lijian', '$2y$10$WSS7Y1WEzocf/zLsvVvQruzFFlHZFZ4a9xGqs257eK2cfUc02r9Kys')) {
        //     // dd('saas');
        // }

        $requestPwd = $request->get('password');

        // dd(password_verify($requestPwd,$user->password));

        $this->userService->checkPwd($requestPwd, $user->password);


        $email = $request->get('email');
        // $user = $this->userService->checkUserByEmail($email);

        // TODO: 用户名/邮箱/密码格式判断

        $now = time();
        $auth = [/* $user->id */12321, $now];
        $hid = 32123;
        return $this->makeToken($auth,$hid);
        // dd( $cacheCaptcha.'11111'.$captcha );
        // return $uuid.'12345';
    }
}