<?php

namespace App\Http\Middleware;

use Closure;

class CaptchaMiddleware {
    public function handle($request, Closure $next) {
        $uuid = trim($request->get('uuid'));
        if(empty($uuid)) {
            // $code = config('validation.captcha')['uuid.required'];
            // $this->setCode($code);
            // return $this->response();
        }
        return $next($request);
    }
}
