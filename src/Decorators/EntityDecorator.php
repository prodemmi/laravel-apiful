<?php

namespace Prodemmi\Apiful\Decorators;

use Prodemmi\Apiful\ApifulDecorator;

class EntityDecorator implements ApifulDecorator
{

    protected string $status;

    public function __construct(string $status)
    {
        $this->status = $status;
    }

    public function toArray($response) : array
    {

        return [
            'status'      => $this->status,
            'status_code' => $response['status_code'],
            'message'     => $response['message'] ?? null,
            'data'        => $response['data'] ?? [],
            'meta'        => $response['meta'] ?? []
        ];
    }
}