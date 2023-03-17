<?php

namespace Prodemmi\Apiful\Decorators;

use Prodemmi\Apiful\ApifulDecorator;

class SuccessDecorator implements ApifulDecorator {

    public function toArray($response): array
    {
        return [
            'status'      => config('apiful.status.success_text'),
            'status_code' => $response['status_code'],
            'message'     => $response['message'] ?? null,
            'data'        => $response['data'],
            'meta'        => $response['meta'] ?? [],
        ];
    }
}