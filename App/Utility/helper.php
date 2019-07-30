<?php

function error_report(\Throwable $e)
{
    if(in_array($e->getCode(), [404])){
        return false;
    }
    \One\Facades\Log::error([
        'file'  => $e->getFile() . ':' . $e->getLine(),
        'msg'   => $e->getMessage(),
        'code'  => $e->getCode(),
        'trace' => $e->getTrace()
    ]);
}