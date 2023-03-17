<?php

namespace Prodemmi\Apiful\Decorators;

use Prodemmi\Apiful\ApifulDecorator;

class ExceptionDecorator implements ApifulDecorator
{

    public function toArray(mixed $response) : array
    {
        $baseResponse = [
            'status'      => config('apiful.status.error_text'),
            'status_code' => $response['status_code'],
            'meta'        => $response['meta'] ?? []
        ];

        $exception = data_get($response, 'errors', []);

        data_set($baseResponse, 'message', $response['message'] ?? $exception->getMessage());

        $trace = tap(config('apiful.errors.trace'), fn ($trace) => $exception->getTrace());

        if ( $trace )
            data_set($baseResponse, 'errors.trace', $exception->getTrace());
        if ( config('apiful.errors.file') )
            data_set($baseResponse, 'errors.file', $exception->getFile());
        if ( config('apiful.errors.line') )
            data_set($baseResponse, 'errors.line', $exception->getLine());

        return $baseResponse;
    }
}