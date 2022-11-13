<?php

namespace App\Exceptions;

use Exception;

/**
 * 特定のレコードに対する処理やパラメータを変更した時等の禁止された処理をしようとしています
 * 警告画面を表示します
 */
class ExclusionException extends Exception
{
    public $errorCode;

    public function __construct(string $errorCode = '')
    {
        $this->errorCode = $errorCode;
    }
}
