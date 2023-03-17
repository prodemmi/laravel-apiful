<?php

namespace Prodemmi\Apiful\Exceptions;

class DecoratorNotFound extends \RuntimeException
{

    public function __construct(string $decorator)
    {
        parent::__construct("Decorator [$decorator] not found.");
    }

}