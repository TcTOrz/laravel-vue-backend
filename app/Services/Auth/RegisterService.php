<?php
/*
 * @Author: Li Jian
 * @Date: 2020-07-10 09:12:23
 * @LastEditTime: 2020-07-10 10:22:24
 * @LastEditors: Li Jian
 * @Description:
 * @FilePath: /water-environment-end/app/Services/Auth/RegisterService.php
 * @Motto: MMMMMMMM
 */

namespace App\Services\Auth;

use App\Services\BaseService;
use App\Exceptions\CodeException;
// use Illuminate\Http\Request;

class RegisterService extends BaseService
{
    protected $userService;
    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function store($request) {
        $uuid = $request->header('x-auth-uuid');
        $this->log('controller.request to'.__METHOD__, ['x-auth-uuid'=> $uuid]);
        if( empty($uuid) ) {
            $code = config('validation.captcha')['uuid.required'];
            throw new CodeException($code);
        }

        $cacheCaptcha = \Cache::pull('captcha.'.$uuid);
        $captcha = strtolower($request->get('captcha'));
        $this->log('controller.request to'.__METHOD__, ['cache_captcha'=> $cacheCaptcha, 'captcha'=> $captcha]);
        if( $cacheCaptcha != $captcha ) {
            $code = config('validation.captcha')['captcha.error'];
            throw new CodeException($code);
        }

        $create = [
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
        ];
        return $this->userService->loginCreate($create);
    }
}
