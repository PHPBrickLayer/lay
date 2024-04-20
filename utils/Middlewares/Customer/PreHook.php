<?php

namespace utils\Middlewares\Customer;

use BrickLayer\Lay\Core\Api\ApiEngine;

abstract class PreHook
{
    public static function run(ApiEngine $instance) : ApiEngine
    {
        return $instance->prefix("client");
    }
}