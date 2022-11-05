<?php

namespace App\Exceptions;

use App\Consts\Common;
use Exception;

class ForbiddenException extends Exception
{
    public $authName;

    public function __construct(string $authName = '')
    {
        $this->authName = $authName;
    }
}
