<?php

namespace Prodemmi\Apiful\Facades;

use Illuminate\Support\Facades\Facade;

class Apiful extends Facade
{
    protected static function getFacadeAccessor() : string
    {
        return 'apiful';
    }
}