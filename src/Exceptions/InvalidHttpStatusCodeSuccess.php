<?php

namespace Prodemmi\Apiful\Exceptions;

class InvalidHttpStatusCodeSuccess extends \Exception
{

    public function __construct(mixed $statusCode)
    {
        parent::__construct("Status code $statusCode is not in range 2xx for successfully response.", 0, null);
    }

}