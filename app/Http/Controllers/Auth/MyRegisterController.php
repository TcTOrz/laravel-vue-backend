<?php
/*
 * @Author: Li Jian
 * @Date: 2020-07-09 15:42:54
 * @LastEditTime: 2020-07-10 11:23:40
 * @LastEditors: Li Jian
 * @Description:
 * @FilePath: /water-environment-end/app/Http/Controllers/Auth/MyRegisterController.php
 * @Motto: MMMMMMMM
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Auth\UserService;
use App\Services\Auth\RegisterService;

class MyRegisterController extends Controller
{
    //
    protected $userService;
    protected $registerService;

    public function __construct(UserService $userService, RegisterService $registerService) {
        $this->userService = $userService;
        $this->registerService = $registerService;
    }

    public function register(Request $request) {
        $result = $this->registerService->store($request);
        $this->setData($result);
        $this->setCode(200);
        return $this->response();
    }
}
