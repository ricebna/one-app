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

function ddd(...$arg){
    var_dump(...$arg);
}

function throw_ee($e, $title = ''){
    throw new \ErrorException($title .', '. $e->getMessage(), $e->getCode(), E_ERROR, $e->getFile(), $e->getLine(), $e->getPrevious());
}