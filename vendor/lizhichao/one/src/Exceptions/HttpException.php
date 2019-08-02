<?php

namespace One\Exceptions;

use One\Facades\Log;
use One\Http\Response;

class HttpException extends \ErrorException
{
    /**
     * @var Response
     */
    public $response = null;
    public function __construct($response, $message = "", $code = 0, $severity = 1, $filename = __FILE__, $lineno = __LINE__, Exception $previous = null)
    {
        $this->response = $response;
        parent::__construct($message, $code, $severity, $filename, $lineno, $previous);
    }
}