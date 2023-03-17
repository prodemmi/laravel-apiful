<?php

namespace Prodemmi\Apiful\Traits;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Prodemmi\Apiful\Apiful;
use Prodemmi\Apiful\Decorators\EntityDecorator;
use Prodemmi\Apiful\Decorators\PaginationDecorator;

trait EntityResponses
{

    public function created() : JsonResponse
    {
        return $this->entityResponse()->withEntityMessage('created')->withStatusCode(Response::HTTP_CREATED)->send();
    }

    public function updated() : JsonResponse
    {
        return $this->entityResponse()->withEntityMessage('updated')->withStatusCode(Response::HTTP_OK)->send();
    }

    public function deleted() : JsonResponse
    {
        return $this->entityResponse()->withEntityMessage('deleted')->withStatusCode(Response::HTTP_OK)->send();
    }

    public function notCreated(int $statusCode) : JsonResponse
    {
        return $this->entityResponse('error')->withStatusCode($statusCode)->withEntityMessage('not_created')->send();
    }

    public function notUpdated(int $statusCode) : JsonResponse
    {
        return $this->entityResponse('error')->withStatusCode($statusCode)->withEntityMessage('not_updated')->send();
    }

    public function notDeleted(int $statusCode) : JsonResponse
    {
        return $this->entityResponse('error')->withStatusCode($statusCode)->withEntityMessage('not_deleted')->send();
    }

    public function notFound() : JsonResponse
    {
        return $this->withDecorator('error')->withStatusCode(Response::HTTP_NOT_FOUND)->send();
    }

    public function invalidQuery() : JsonResponse
    {
        return $this->withDecorator('error')->withStatusCode(Response::HTTP_BAD_REQUEST)->send();
    }

    public function withPagination() : Apiful
    {
        return $this->withDecorator(PaginationDecorator::class)->withStatusCode(Response::HTTP_OK);
    }

    private function withEntityMessage(string $key) : Apiful
    {
        return $this->withMessage(__("apiful::entity.$key"));
    }

    private function entityResponse(string $status = 'success') : Apiful
    {
        return $this->withDecorator(new EntityDecorator($status));
    }

}