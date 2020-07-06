<?php

namespace App\Http\Middleware;

use App\Services\Auth\UserService;
use App\Traits\Respond;
use App\Traits\TctorzLog;
use Closure;
use Vinkla\Hashids\Facades\Hashids;

class Tctorz
{
    use Respond, TctorzLog;

    public $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $tctorz = $request->header('x-auth-token');
        $this->log('header.request to '.__METHOD__,['x-auth-token' => $tctorz]);

        if( empty($tctorz) ) {
            $code = config('validation.token')['token.isNull'];
            return $this->handleRes($code);
        }

        $token = Hashids::connection('main')->decode($tctorz);

        if (empty($token) || !is_array($token) || count($token) < 2) {
            $this->log('header.error to '.__METHOD__,['error_message' => '[tctorz中间件]token错误']);
            $code = config('validation.token')['token.invalid'];
            return $this->handleRes($code);
        }

        $id = $token[0];
        $user = $this->userService->findUserByToken($id);
        $this->log('header.request to '.__METHOD__,['user' => $user]);
        if (empty($user)) {
            $this->log('header.error to '.__METHOD__,['error_message' => '[g9zz中间件]用户不存在']);
            $code = config('validation.token')['token.invalid'];
            return $this->handleRes($code);
        }

        $now = time();
        $beginTime = $token[1];
        $limitTime = config('tctorz.token.valid_time');
        if ($now - $beginTime > $limitTime) {
            $this->log('header.error to '.__METHOD__,['error_message' => '[g9zz中间件]token失效']);
            $code = config('validation.token')['token.invalid'];
            return $this->handleRes($code);
        }

        // $request->offsetSet('tctorz_user_id',$user->id);
        // $request->offsetSet('tctorz_user_hid',$user->hid);

        return $next($request);
    }

    public function handleRes($code) {
        $this->log('error.request to '.__METHOD__,['code' => $code]);
        $this->setCode($code);
        return $this->response();
    }
}
