<?php

return [
    'status'     => [
        'success_text' => 'success',
        'error_text'   => 'error'
    ],
    'decorators' => [
        'success'            => \Prodemmi\Apiful\Decorators\SuccessDecorator::class,
        'error'              => \Prodemmi\Apiful\Decorators\ErrorDecorator::class,
        'exception'          => \Prodemmi\Apiful\Decorators\ExceptionDecorator::class,
        'pagination'         => \Prodemmi\Apiful\Decorators\PaginationDecorator::class,
        'user_api_decorator' => \Prodemmi\Apiful\Decorators\UserApiDecorator::class
    ],
    'errors'     => [
        'trace' => true,
        'file'  => true,
        'line'  => true
    ]
];