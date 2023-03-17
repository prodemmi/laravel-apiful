<?php

namespace Prodemmi\Apiful\Decorators;


use Prodemmi\Apiful\ApifulDecorator;

class ErrorDecorator implements ApifulDecorator
{

    public function toArray(mixed $response) : array
    {

        return [
            'status'      => config('apiful.status.error_text'),
            'status_code' => $response['status_code'],
            'message'     => $response['message'],
            'errors'      => $response['errors'] ?? [],
            'meta'        => $response['meta'] ?? []
        ];

    }

}