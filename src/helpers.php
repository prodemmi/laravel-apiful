<?php

use Prodemmi\Apiful\Apiful;

if (!function_exists('apiful')) {
    function apiful(mixed $data = null): Apiful
    {
        return new Apiful($data);
    }
}