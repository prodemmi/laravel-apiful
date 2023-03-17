<?php

namespace Prodemmi\Apiful\Decorators;

use Prodemmi\Apiful\ApifulDecorator;

class PaginationDecorator implements ApifulDecorator
{

    public function toArray($response) : array
    {

        $paginator = data_get($response, 'data')->toArray();
        $data      = data_get($paginator, 'data', []);
        unset($paginator['data']);

        return [
            'status'      => config('apiful.status.success_text'),
            'status_code' => $response['status_code'],
            'message'     => $response['message'] ?? null,
            'data'        => $data,
            'pagination'  => $paginator,
        ];
    }
}