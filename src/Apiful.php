<?php

namespace Prodemmi\Apiful;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Traits\Macroable;
use Prodemmi\Apiful\Traits\ErrorResponses;
use Illuminate\Contracts\Support\Responsable;
use Prodemmi\Apiful\Traits\EntityResponses;
use Prodemmi\Apiful\Traits\SuccessResponses;
use Prodemmi\Apiful\Exceptions\DecoratorNotFound;
use Prodemmi\Apiful\Exceptions\InvalidHttpStatusCodeError;
use Symfony\Component\Routing\Exception\InvalidParameterException;

class Apiful implements Responsable
{

    use SuccessResponses, ErrorResponses, EntityResponses, Macroable;

    protected array $responseArray = [];
    protected array $headers       = [];

    protected ApifulDecorator|string $decorator;

    /**
     * @throws InvalidHttpStatusCodeError
     */
    public function __construct(mixed $data = null)
    {
        if ( $data instanceof \Throwable )
            $this->exception($data);
        else
            $this->withDecorator('success')->withData($data)->withStatusCode(200);

        return $this;
    }

    public function withData(mixed $data = null) : Apiful
    {
        data_set($this->responseArray, 'data', value($data));

        return $this;
    }

    public function withHeader(array|Closure $header) : Apiful
    {

        $this->headers = array_merge($this->headers, value($header));

        return $this;

    }

    public function withMessage(string|Closure $message) : Apiful
    {
        data_set($this->responseArray, 'message', value($message));

        return $this;
    }

    public function clearData() : Apiful
    {
        data_set($this->responseArray, 'data', null);

        return $this;
    }

    public function withMeta(array|Closure $meta) : Apiful
    {

        $meta = value($meta);

        if($meta instanceof Arrayable){

            data_set($this->responseArray, 'meta', value($meta));
            return $this;

        }

        throw new InvalidParameterException("$meta must be array type.");

    }

    public function withError(mixed $errors) : Apiful
    {

        $key = $errors instanceof \Throwable ? 'errors' : 'exceptions';

        data_set($this->responseArray, $key, value($errors));

        return $this;
    }

    public function withStatusCode(int|Closure $statusCode) : Apiful
    {
        data_set($this->responseArray, 'status_code', value($statusCode));

        return $this;
    }

    public function withDecorator(ApifulDecorator|string $decorator) : Apiful
    {
        $this->decorator = $decorator;

        return $this;
    }

    protected function send() : JsonResponse
    {
        $this->updateMessage();
        $decoratedResponse = $this->reformatWithDecorator($this->decorator);

        return response()->json($decoratedResponse, $this->getStatusCode(), $this->getHeaders());
    }

    protected function updateMessage() : Apiful
    {

        $message = $this->getMessage();

        if ( blank($message) )
            $message = data_get(Response::$statusTexts, $this->getStatusCode());

        return $this->withMessage($message);

    }

    public function getHeaders() : ?array
    {
        return $this->headers;
    }

    public function getMessage() : ?string
    {
        return data_get($this->responseArray, 'message');
    }

    public function getStatusCode() : ?int
    {
        return data_get($this->responseArray, 'status_code');
    }

    public function getData() : mixed
    {
        return data_get($this->responseArray, 'data', []);
    }

    public function getErrors() : mixed
    {
        return data_get($this->responseArray, 'errors', []);
    }

    public function getDecorator() : ApifulDecorator|string
    {
        return $this->decorator;
    }

    protected function responseSuccess() : JsonResponse
    {
        return $this->send();
    }

    protected function reformatWithDecorator(mixed $decorator) : ?array
    {
        try {

            $decorator = $decorator ?? $this->decorator;

            if ( $decorator instanceof ApifulDecorator ) {
                return $decorator->toArray($this->responseArray);
            }

            $configDecorated = config("apiful.decorators.$decorator");

            if ( $configDecorated ) {
                return resolve($configDecorated)->toArray($this->responseArray);
            } else {
                $decoratorClass = class_exists($decorator) ? ( new ( $decorator ?? $this->decorator ) ) : $decorator;
                return $decoratorClass->toArray($this->responseArray);
            }

        } catch ( \Exception $e ) {
            throw new DecoratorNotFound($decorator);
        }
    }

    public function toResponse($request) : JsonResponse
    {
        return $this->send();
    }
}
