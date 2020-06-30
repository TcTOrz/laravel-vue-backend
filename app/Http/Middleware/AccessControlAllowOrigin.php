<?php

namespace App\Http\Middleware;

use Closure;

class AccessControlAllowOrigin {
    public function handle($request, Closure $next) {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: *");
        header("Access-Control-Allow-Headers: *");
        header("Access-Control-Expose-Headers: *");

        return $next($request);
    }
}
