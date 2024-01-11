<?php
namespace web\domains\Api;

use BrickLayer\Lay\Core\Api\ApiHooks;

class Plaster extends ApiHooks
{
    public function hooks(): void
    {
        $this->load_brick_hooks();
    }
}