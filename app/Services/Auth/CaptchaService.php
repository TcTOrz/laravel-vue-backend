<?php

/**
 * Email: 591466539@qq.com
 * Date: 2020/06/29
 */
namespace App\Services\Auth;

use App\Services\BaseService;

class CaptchaService extends BaseService {
    public $charset = 'abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ23456789'; // 随机数
    public $code; // 验证码
    public $codelen = 4; // 验证码长度
    private $width = 130; // 宽度
    private $height = 50; // 高度
    private $img; // 图形资源句柄
    private $font; // 字体
    private $fontsize = 20; // 字体大小
    private $fontcolor; // 字体颜色

    public function __construct() {
        $this->font = public_path('/font/Elephant.ttf');
    }

    // 生成随机码
    private function createCode() {
        $_len = strlen($this->charset)-1;
        for ($i=0; $i < $this->codelen; $i++) {
            $this->code .= $this->charset[mt_rand(0,$_len)];
        }
    }

    // 生成背景
    public function createBg() {
        $this->img = imagecreatetruecolor($this->width, $this->height);
        $color = imagecolorallocate($this->img, mt_rand(157,255), mt_rand(157,255), mt_rand(157,255));
        imagefilledrectangle($this->img,0,$this->height,$this->width,0,$color);
    }

    // 生成线条、雪花
    public function createLine() {
        //线条
        for ($i=0;$i<6;$i++) {
            $color = imagecolorallocate($this->img,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
            imageline($this->img,mt_rand(0,$this->width),mt_rand(0,$this->height),mt_rand(0,$this->width),mt_rand(0,$this->height),$color);
        }
        //雪花
        for ($i=0;$i<100;$i++) {
            $color = imagecolorallocate($this->img,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
            imagestring($this->img,mt_rand(1,5),mt_rand(0,$this->width),mt_rand(0,$this->height),'*',$color);
        }
    }

    // 生成文字
    public function createFont() {
        $_x = $this->width / $this->codelen;
        for ($i=0;$i<$this->codelen;$i++) {
            $this->fontcolor = imagecolorallocate($this->img,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
            imagettftext($this->img,$this->fontsize,mt_rand(-30,30),$_x*$i+mt_rand(1,5),$this->height / 1.4,$this->fontcolor,$this->font,$this->code[$i]);
        }
    }

    // 输出
    private function outPut() {
        header('Content-type:image/png');
        imagepng($this->img);
        imagedestroy($this->img);
    }

    public function outImg() {
        $this->createBg();
        $this->createCode();
        $this->createLine();
        $this->createFont();

        $uuid = \Request::get('uuid');
        $this->log('service.request to '.__METHOD__,['uuid' => $uuid]);

        $captcha = 'captcha.'.$uuid;
        $oldCaptcha =  \Cache::pull($captcha);
        $code = strtolower($this->code);
        $this->log('service.request to '.__METHOD__,['captcha_code' => $code]);
        \Cache::put($captcha,$code,2);
        $this->outPut();

        return $this->code;
    }

    //获取验证码
    public function getCode() {
        return strtolower($this->code);
    }
}
