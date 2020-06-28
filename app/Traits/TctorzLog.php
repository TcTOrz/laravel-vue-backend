<?php

namespace App\Traits;

/**
 *
 */
trait TctorzLog
{
    public function log($message, $context = array()) {
        return \Log::info($message, $context);
    }
}
