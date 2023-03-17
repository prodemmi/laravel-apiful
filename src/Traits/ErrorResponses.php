<?php

namespace Prodemmi\Apiful\Traits;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Prodemmi\Apiful\Exceptions\InvalidHttpStatusCodeError;

trait ErrorResponses
{

    /**
     * @throws InvalidHttpStatusCodeError
     */
    public function error(mixed $errors = null) : JsonResponse
    {
        return $this->withError($errors)->sendErrorResponse('error');
    }

    /**
     * @throws InvalidHttpStatusCodeError
     */
    public function exception(Throwable $exception) : JsonResponse
    {

        if ( blank($this->getMessage()) )
            $this->withMessage($exception->getMessage());

        return $this->withError($exception)->sendErrorResponse('exception',
            Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @throws InvalidHttpStatusCodeError
     */
    public
    function badRequest() : JsonResponse
    {
        return $this->sendErrorResponse('error', Response::HTTP_BAD_REQUEST);
    }

    /**
     * @throws InvalidHttpStatusCodeError
     */
    public
    function unauthorized() : JsonResponse
    {
        return $this->sendErrorResponse('error', Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @throws InvalidHttpStatusCodeError
     */
    public
    function forbidden() : JsonResponse
    {
        return $this->sendErrorResponse('error', Response::HTTP_FORBIDDEN);
    }

    /**
     * @throws InvalidHttpStatusCodeError
     */
    public
    function internal() : JsonResponse
    {
        return $this->sendErrorResponse('error', Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @throws InvalidHttpStatusCodeError
     */
    public
    function notAcceptable() : JsonResponse
    {
        return $this->sendErrorResponse('error', Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * @throws InvalidHttpStatusCodeError
     */
    protected
    function sendErrorResponse(string $decorator, ?int $code = Response::HTTP_INTERNAL_SERVER_ERROR) : JsonResponse
    {

        $code = $code ? : $this->getStatusCode();

        if ( $code < Response::HTTP_BAD_REQUEST )
            throw new InvalidHttpStatusCodeError($code);

        $this->clearData()
            ->withDecorator($decorator);

        if ( filled($code) )
            $this->withStatusCode($code);

        return $this->send();
    }

}