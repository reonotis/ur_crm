<?php

namespace App\Exceptions;

use App\Consts\Common;
use Exception;

/**
 * ルーティングに対して、権限のない禁止された操作をしようとしています
 */
class ForbiddenException extends Exception
{
    public $authName;

    public function __construct(string $authName = '')
    {
        $this->authName = $authName;
    }
}
