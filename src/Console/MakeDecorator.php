<?php

namespace Prodemmi\Apiful\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeDecorator extends GeneratorCommand
{
    protected $name = 'make:decorator';

    protected $description = 'Create a new apiful decorator';

    protected $type = 'Decorator';

    protected function getStub() : string
    {
        return __DIR__ . '/stubs/decorator.php.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Http\Decorators';
    }

    protected function getNameInput() : string
    {
        return Str::of(parent::getNameInput())
            ->lower()
            ->replace('decorator', '')
            ->ucfirst()
            ->append('Decorator')
            ->toString();
    }

    protected function replaceClass($stub, $name)
    {
        $className = $this->getNameInput();
        return str_replace([ 'DummyClass', '{{ class }}', '{{class}}' ], $className, $stub);
    }

}