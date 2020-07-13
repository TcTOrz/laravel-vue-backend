<?php
/*
 * @Author: Li Jian
 * @Date: 2020-07-13 09:43:14
 * @LastEditTime: 2020-07-13 09:45:34
 * @LastEditors: Li Jian
 * @Description:
 * @FilePath: /water-environment-end/app/Exceptions/ValidatorException.php
 * @Motto: MMMMMMMM
 */

namespace App\Exceptions;
use Throwable;

class ValidatorException extends \Exception {
    public function __construct($code=0, $message='', Throwable $previous=null)
    {
        parent::__construct($message, $code, $previous);
    }
}
