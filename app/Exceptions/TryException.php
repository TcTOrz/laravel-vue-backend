<?php
/*
 * @Author: Li Jian
 * @Date: 2020-07-10 11:19:03
 * @LastEditTime: 2020-07-10 11:19:23
 * @LastEditors: Li Jian
 * @Description:
 * @FilePath: /water-environment-end/app/Exceptions/TryException.php
 * @Motto: MMMMMMMM
 */

namespace App\Exceptions;


use Mockery\Exception;
use Throwable;

class TryException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
