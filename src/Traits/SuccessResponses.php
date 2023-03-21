<?php

namespace Prodemmi\Apiful\Traits;

use Illuminate\Http\JsonResponse;
use Prodemmi\Apiful\Exceptions\InvalidHttpStatusCodeSuccess;

trait SuccessResponses
{
    /**
     * @throws InvalidHttpStatusCodeSuccess
     */
    public function success() : JsonResponse
    {
        return $this->successResponse();
    }

    /**
     * @throws InvalidHttpStatusCodeSuccess
     */
    public function noContent() : JsonResponse
    {
        return $this->successResponse(204);
    }

    protected function successResponse(?int $code = 200) : JsonResponse
    {
        if ( $code >= 300 || $code < 200 )
            throw new InvalidHttpStatusCodeSuccess($code);

        return $this->clearData()->withStatusCode($code)->withDecorator('success')->send();
    }
}