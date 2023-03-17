<?php

namespace Prodemmi\Apiful\Exceptions;

class InvalidHttpStatusCodeError extends \Exception
{

    public function __construct(mixed $statusCode)
    {
        parent::__construct("Status code $statusCode is not in range 4xx-5xx for error response.", 0, null);
    }

}