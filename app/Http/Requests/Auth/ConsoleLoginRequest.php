<?php
/*
 * @Author: Li Jian
 * @Date: 2020-07-13 09:49:21
 * @LastEditTime: 2020-07-13 10:12:03
 * @LastEditors: Li Jian
 * @Description:
 * @FilePath: /water-environment-end/app/Http/Requests/Auth/ConsoleLoginRequest.php
 * @Motto: MMMMMMMM
 */
namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;

class ConsoleLoginRequest extends BaseRequest
{
    public $key = 'login';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rule = [
            'email' => 'required|email|exists:users,email,status,activated',
            'password' => 'required|string',
            'captcha' => 'required',
//            'x-auth-uuid' => 'required'
        ];
        return $rule;
    }
}

