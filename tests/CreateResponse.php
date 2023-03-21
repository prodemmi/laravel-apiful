<?php

namespace Prodemmi\Apiful\Tests;

use Prodemmi\Apiful\Apiful;

trait CreateResponse
{

    protected function successResponse($method, $useConstructor, $data, $message, $header) : Apiful
    {

        if ( $useConstructor )
            $apiful = new Apiful($data);
        else
            $apiful = ( new Apiful() )->withData($data);

        if ( filled($message) )
            $apiful->withMessage($message);

        if ( filled($header) )
            $apiful->withHeader($header);

        $apiful->{$method}();

        return $apiful;

    }

    protected function errorResponse($method, $useConstructor, $error, $message, $header) : Apiful
    {

        if ( $useConstructor )
            $apiful = new Apiful($error);
        else
            $apiful = ( new Apiful() )->withError($error);

        if ( filled($message) )
            $apiful->withMessage($message);

        if ( filled($header) )
            $apiful->withHeader($header);

        $apiful->{$method}();

        return $apiful;

    }

    protected function exceptionResponse($useConstructor, $exception, $message, $header) : Apiful
    {

        if ( $useConstructor )
            $apiful = new Apiful($exception);
        else
            $apiful = ( new Apiful() );

        if ( filled($message) )
            $apiful->withMessage($message);

        if ( filled($header) )
            $apiful->withHeader($header);

        $apiful->exception($exception);

        return $apiful;

    }

    protected function entityResponse($method, $useConstructor, $data, $message, $header, $statusCode = null) : Apiful
    {

        if ( $useConstructor )
            $apiful = new Apiful($data);
        else
            $apiful = ( new Apiful() )->withData($data);

        if ( filled($message) )
            $apiful->withMessage($message);

        if ( filled($header) )
            $apiful->withHeader($header);

        $apiful->{$method}($statusCode);

        return $apiful;

    }

    protected function paginationResponse($useConstructor, $data, $message, $header) :
    Apiful
    {

        if ( $useConstructor )
            $apiful = new Apiful($data);
        else
            $apiful = ( new Apiful() )->withData($data);

        if ( filled($message) )
            $apiful->withMessage($message);

        if ( filled($header) )
            $apiful->withHeader($header);

        return $apiful->withPagination();

    }

}
