<?php

/*
 * @Author: Li Jian
 * @Date: 2020-07-07 11:29:36
 * @LastEditTime: 2020-07-21 10:27:50
 * @LastEditors: Li Jian
 * @Description: login
 * @FilePath: /water-environment-end/app/Http/Controllers/Auth/MyLoginController.php
 * @Motto: MMMMMMMM
 */

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\ConsoleLoginRequest;
use Illuminate\Http\Request;
use App\Services\Auth\CaptchaService;
use App\Services\Auth\UserService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Vinkla\Hashids\Facades\Hashids;
use Laravel\Socialite\Facades\Socialite;

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

    public function login(/* Request $request */ ConsoleLoginRequest $request ) {
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

        $authorization = $request->get('auth');
        $this->log('Controller.request to '.__METHOD__, ['authorization'=> $authorization]);
        if(!empty($authorization)) {
            $oauth = Hashids::connection('user')->decode($authorization);
            $this->log('controller.request to '.__METHOD__,['oauth' => $oauth]);
            if (empty($oauth)) {
                $this->setCode(config('validation.login')['oauth.failed']);
                return $this->response();
            }

            $verifyTime = config('tctorz.verify.valid_time');
            $now = time();
            if ( $now - $oauth[1] > $verifyTime ) {
                $this->setCode(config('validation.login')['oauth.failed']);
                return $this->response();
            }

            $login = config('tctorz.oauth.login.'.$oauth[2]);
            // var_dump($oauth); 9 1595295192 1
            // $result = $this->userService->checkExistsOauth($oauth[0],$login);
            \DB::table('users')->where('email', $request->get('email'))->update(['github_id'=> $oauth[0]]);
        }

        $email = $request->get('email');
        // $user = $this->userService->checkUserByEmail($email);

        // TODO: 用户名/邮箱/密码格式判断

        $now = time();
        $auth = [$user->id , $now];
        // TODO
        $hid = $user->hid;
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

        // dd($user->email.'通过验证');
        $result = $this->userService->updateVerify($message[0]);

        return redirect('/');
        // $this->response();
    }

    public function returnError() {
        $this->setCode(config('validation.login')['verify.failed']);
        return $this->response();
    }

    /**
     * 调用授权github界面
     * @param Request $request
     * @param $service
     * @return mixed
     */
    public function redirectToProvider(Request $request, $service) {
        if( !in_array($service, config('tctorz.token.login_way')) ) {
            $this->setCode(config('validation.default')['some.error']);
            return $this->response();
        }
        return Socialite::driver($service)->redirect();
    }

    /**
     * 授权回调界面
     * @param Request $request
     * @param $service
     * @return mixed
     */
    public function handleProviderCallback(Request $request, $service) {
        $user = Socialite::driver($service)->stateless()->user();

        switch ( $service ) {
            case 'github':
                return $this->loginByGithub($user);
                break;
            default:
                return $this->loginByEmail();
                break;
        }
    }

    public function loginByGithub($user) {
        $isGithub = $this->userService->checkIsGithub($user->id);
        if(empty($isGithub)) {
            return $this->userService->storeGithub($user);
        } else {
            $userId = \DB::table('github_user')->where('github_id', $user->id)->first()->id;
            $result = $this->userService->findUserByGithubId($userId);

            if( empty($result) ) {
                $now = time();
                $oauth = config('tctorz.oauth.auth.github');
                $param = [$userId, $now, $oauth];
                $auth = Hashids::connection('user')->encode($param);
                return redirect()->route('new.auth', ['auth'=> $auth]);
            }
            $now = time();
            $auth = [$userId, $now];
            $token = Hashids::connection('main')->encode($auth);
            return redirect()->route('new.login', ['token'=> $token, 'hid'=> $result->hid]);
        }
    }

    public function loginByEmail() {

    }
}
