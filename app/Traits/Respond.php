<?php

namespace App\Traits;

use App\Exceptions\CodeException;
use App\Exceptions\ValidatorException;

/**
 *
 */
trait Respond
{
    public $code = 0;

    public $data;

    public $status = 200;

    public $message = '成功';

    public function setCode($code) {
        $this->code = $code;
        $this->message = config('message.'.$code);
    }

    public function getCode() {
        return $this->code;
    }

    public function setData($data) {
        $this->data = $data;
    }

    public function getData() {
        return $this->data;
    }

    public function getErrorCode() {
        return $this->code;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getMessage() {
        return $this->message;
    }

    public function setMessage($message) {
        $this->message = $message;
    }

    // TODO
    public function response() {
        $status = $this->getStatus();
        $data = $this->getData() ? $this->getData() : new \stdClass();
        $response = new \stdClass();
        $response->code = $this->getCode();
        $response->message = $this->getMessage();
        $response->data = $data;

        // dd($response->code);
        if ($response->code != 200 && $response->code != 0) throw new CodeException($response->code);
        return \Response::json($response, $status);
    }

    public function requestValidate(array $data, array $rules, $key = 'default') {
        $validation = config('validation');
        $validation = isset($validation[$key]) ? array_merge($validation['default'], $validation[$key]) : $validation['default'];
        $validate = \Validator::make($data, $rules, $validation);
//        dd($data,$rules,$validation,$key,$validate->fails());
//        dd($validate->errors()->first());
        if ($validate->fails()) {
            $code = (int)$validate->errors()->first();
            throw new ValidatorException($code);
        }
        return $validate;
    }
}
