<?php

namespace Prodemmi\Apiful\Tests\Unit;

use GuzzleHttp\Exception\InvalidArgumentException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

trait DataFactory
{

    protected static function dataFactory() : array
    {

        return [
            [],
            fn () => [],
            "string",
            5,
            null
        ];

    }

    protected static function messageFactory() : array
    {

        return [
            "message",
            fn () => "message",
            5,
            null
        ];

    }

    protected static function errorFactory() : array
    {

        return [
            [],
            "error",
            null
        ];

    }

    protected static function exceptionFactory() : array
    {

        return [
            new \Exception("Exception message")
        ];

    }

    protected static function getHeaders() : array
    {

        return [
            [
                'header-key-1' => "header-value-1",
                'header-key-2' => "header-value-2",
                'header-key-3' => "header-value-3"
            ],
            fn () => [
                'header-key-1' => "header-value-1",
                'header-key-2' => "header-value-2",
                'header-key-3' => "header-value-3"
            ],
            []
        ];

    }

    public static function successDataProvider() : array
    {

        $dataFactory = [];

        foreach ( [ true, false ] as $useConstructor ) {

            foreach ( self::dataFactory() as $data ) {

                foreach ( self::messageFactory() as $message ) {

                    foreach ( self::getHeaders() as $header ) {

                        $dataFactory[] = [
                            'Use constructor' => $useConstructor,
                            'data'            => $data,
                            'message'         => $message,
                            'header'          => $header
                        ];

                    }

                }

            }

        }

        return $dataFactory;

    }

    public static function errorDataProvider() : array
    {

        $dataFactory = [];

        foreach ( [ true, false ] as $useConstructor ) {

            foreach ( self::errorFactory() as $error ) {

                foreach ( self::messageFactory() as $message ) {

                    foreach ( self::getHeaders() as $header ) {

                        $dataFactory[] = [
                            'Use constructor' => $useConstructor,
                            'error'           => $error,
                            'message'         => $message,
                            'header'          => $header
                        ];

                    }

                }

            }

        }

        return $dataFactory;

    }

    public static function exceptionDataProvider() : array
    {

        $dataFactory = [];

        foreach ( [ true, false ] as $useConstructor ) {

            foreach ( self::exceptionFactory() as $exception ) {

                foreach ( self::messageFactory() as $message ) {

                    foreach ( self::getHeaders() as $header ) {

                        $dataFactory[] = [
                            'Use constructor' => $useConstructor,
                            'exception'       => $exception,
                            'message'         => $message,
                            'header'          => $header
                        ];

                    }

                }

            }

        }

        return $dataFactory;

    }

    public static function entityDataProvider() : array
    {

        $dataFactory = [];

        foreach ( [ true, false ] as $useConstructor ) {

            foreach ( self::dataFactory() as $data ) {

                foreach ( self::messageFactory() as $message ) {

                    foreach ( self::getHeaders() as $header ) {

                        $dataFactory[] = [
                            'Use constructor' => $useConstructor,
                            'data'            => $data,
                            'message'         => $message,
                            'header'          => $header
                        ];

                    }

                }

            }

        }

        return $dataFactory;

    }

}