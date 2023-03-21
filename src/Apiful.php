<?php

namespace Prodemmi\Apiful;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Traits\Macroable;
use Prodemmi\Apiful\Traits\ErrorResponses;
use Prodemmi\Apiful\Traits\EntityResponses;
use Illuminate\Contracts\Support\Arrayable;
use Prodemmi\Apiful\Traits\SuccessResponses;
use Illuminate\Contracts\Support\Responsable;
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
        data_set($this->responseArray, 'data', []);

        return $this;
    }

    public function withMeta(array|Closure $meta) : Apiful
    {

        $meta = value($meta);

        if ( $meta instanceof Arrayable ) {

            data_set($this->responseArray, 'meta', value($meta));
            return $this;

        }

        throw new InvalidParameterException("$meta must be array type.");

    }

    public function withError(mixed $errors) : Apiful
    {

        data_set($this->responseArray, 'errors', value($errors));

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
        return data_get($this->responseArray, 'data', null);
    }

    public function getErrors() : mixed
    {
        return data_get($this->responseArray, 'errors', []);
    }

    public function getDecorator() : ApifulDecorator|string
    {
        return $this->decorator;
    }

    protected function send() : JsonResponse
    {
        $decoratedResponse = $this->reformatWithDecorator($this->decorator);
        return response()->json($decoratedResponse, $this->getStatusCode(), $this->getHeaders());
    }

    protected function reformatWithDecorator(mixed $decorator) : ?array
    {

        $decorator = $decorator ?? $this->decorator;

        if ( in_array($decorator, [ 'success', 'error', 'exception', 'pagination' ]) ) {

            return $this->getDecoratorFromString($decorator);

        } else {

            if ( $decorator instanceof ApifulDecorator )
                return $decorator->toArray($this->responseArray);

            if ( !class_exists($decorator) )
                throw new DecoratorNotFound($decorator);

            return resolve($decorator)->toArray($this->responseArray);

        }

    }

    protected function getDecoratorFromString(string $decorator)
    {

        $configDecorated = config("apiful.decorators.$decorator");

        if ( $configDecorated ) {
            return resolve($configDecorated)->toArray($this->responseArray);
        }

        throw new DecoratorNotFound($decorator);

    }

    public function toResponse($request) : JsonResponse
    {
        return $this->send();
    }
}
