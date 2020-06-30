<?php

namespace App\Exceptions;

use Mockery\Exception;
use Throwable;


class CodeException extends Exception {
    public function __construct($code = 0, $message = "", Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
