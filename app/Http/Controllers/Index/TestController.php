<?php

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
}
