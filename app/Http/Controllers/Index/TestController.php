<?php
/*
 * @Author: Li Jian
 * @Date: 2020-07-07 14:23:26
 * @LastEditTime: 2020-07-07 16:52:13
 * @LastEditors: Li Jian
 * @Description:
 * @FilePath: /water-environment-end/app/Http/Controllers/Index/TestController.php
 * @Motto: MMMMMMMM
 */

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;

class TestController extends Controller
{

    public function __construct() {

    }

    public function getVal() {
        $data = new \stdClass();
        $data->data = 'return back data';
        $this->setData($data);
        $this->setCode(200);
        return $this->response();
    }

    public function getVal1() {
        // phpinfo();
        // return \App\Models\User::find(1)->phone;
        return \App\Models\User::find(1)->cs()->where('id', 2)->first();
        // return \App\Models\User::find(1);
        return \App\Models\User::where('id',1)->get()[0]->cs->where('id', 2)->first();
    }
}
