<?php
/*
 * @Author: Li Jian
 * @Date: 2020-07-07 14:23:26
 * @LastEditTime: 2020-07-10 11:10:36
 * @LastEditors: Li Jian
 * @Description:
 * @FilePath: /water-environment-end/app/Http/Controllers/Index/TestController.php
 * @Motto: MMMMMMMM
 */

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
// use App\Mail\RegisterVerify;
// use Illuminate\Support\Facades\Mail;
use App\Services\Auth\UserService;

class TestController extends Controller
{
    public $userService;
    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function getVal() {
        $data = new \stdClass();
        $data->data = 'return back data';
        $this->setData($data);
        $this->setCode(200);
        return $this->response();
    }

    public function getVal1() {
        // dd('1234');
        // var_dump( \DB::table('users')->where('email', 'lijian@qq.com')->first()->id);
        \DB::table('users')->where('id', 1)->update(['hid'=> '12345']);
        // phpinfo();
        // return \App\Models\User::find(1)->phone;
        return \App\Models\User::find(1)->cs()->where('id', 2)->first();
        // return \App\Models\User::find(1);
        return \App\Models\User::where('id',1)->get()[0]->cs->where('id', 2)->first();
//
    }

    public function getVal2() {
        // $this->userService->verifyEmail('591466539@qq.com', '撒逼婆', 1);
        // Mail::to('591466539@qq.com')->send(new RegisterVerify());
    }
}
