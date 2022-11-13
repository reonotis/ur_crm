<?php

namespace App\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class Base extends Command
{
    public function writeConsoleAndLog(string $type, string $msg)
    {
        Log::{$type}($msg);
        $this->{$type}(Carbon::now()->format('Y-m-d H:i:s') . ' : ' . $msg);
    }

}

