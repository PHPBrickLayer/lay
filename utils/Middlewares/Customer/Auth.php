<?php

namespace utils\Middlewares\Customer;

use BrickLayer\Lay\Core\Api\ApiEngine;
use bricks\Customer\Controller\CustomerSessions;

abstract class Auth
{
    public static function run(ApiEngine $instance) : void
    {
        $instance->group_middleware(fn() => CustomerSessions::new()->validate_session());
    }
}