<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Services\Auth\CaptchaService;
use App\Http\Controllers\Controller;

class MyLoginController extends Controller
{
    protected $captchaService;

    public function __construct(CaptchaService $captchaService) {
        $this->captchaService = $captchaService;
    }

    /**
     * @return Image
     */
    public function getCaptcha() {
        return $this->captchaService->outImg();
    }
}
