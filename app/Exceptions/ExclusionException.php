<?php

namespace App\Exceptions;

use Exception;

/**
 * 禁止された処理をしようとしています
 */
class ExclusionException extends Exception
{
    public $errorCode;

    public function __construct(string $errorCode = '')
    {
        $this->errorCode = $errorCode;
    }
}
