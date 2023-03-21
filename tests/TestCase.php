<?php

namespace Prodemmi\Apiful\Tests;

use Prodemmi\Apiful\Decorators\ErrorDecorator;
use Prodemmi\Apiful\Decorators\SuccessDecorator;
use Prodemmi\Apiful\Decorators\ExceptionDecorator;
use Prodemmi\Apiful\Decorators\PaginationDecorator;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{

    protected function defineEnvironment($app)
    {

        $app['config']->set('apiful.status.success_text', 'success');
        $app['config']->set('apiful.status.error_text', 'error');

        $app['config']->set('apiful.decorators.success', SuccessDecorator::class);
        $app['config']->set('apiful.decorators.error', ErrorDecorator::class);
        $app['config']->set('apiful.decorators.exception', ExceptionDecorator::class);
        $app['config']->set('apiful.decorators.pagination', PaginationDecorator::class);

        $app['config']->set('apiful.errors.trace', true);
        $app['config']->set('apiful.errors.file', true);
        $app['config']->set('apiful.errors.line', true);

    }

}
