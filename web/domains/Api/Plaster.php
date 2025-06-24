<?php
namespace Web\Api;

use BrickLayer\Lay\Core\Api\ApiHooks;

class Plaster extends ApiHooks
{
    protected function hooks(): void
    {
        $this->set_version("v1");

        $this->group_limit(60, "1 minute");

        $this->load_brick_hooks();
    }
}