<?php

namespace App\Src\Helpers;

use Illuminate\Support\Facades\Log;

class Logging
{

    public static function error($message, $data = [])
    {
        $trace = $data['trace'] ?? '';
        $trace = "[stacktrace]\n{$trace}";
        unset($data['trace']);
        $context = json_encode(array_merge(['payloads' => fn_request()->all()], $data));

        Log::channel('daily')->error("$message $context\n$trace");
    }

    public static function info($message, $data = [])
    {
        $context = json_encode($data);
        Log::channel('daily')->info("$message $context");
    }
}
