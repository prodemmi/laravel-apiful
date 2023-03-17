<?php

namespace Prodemmi\Apiful\Decorators;

use Prodemmi\Apiful\ApifulDecorator;

class UserApiDecorator implements ApifulDecorator
{

    public function toArray(mixed $response) : array
    {

        return [
            'result' => $response['data'] ?? [],
            'errors' => $response['errors'] ?? []
        ];

    }

}