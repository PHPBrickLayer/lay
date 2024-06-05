<?php
namespace web\domains\Api;

use BrickLayer\Lay\Core\Api\ApiHooks;

class Plaster extends ApiHooks
{
    public function hooks(): void
    {
        $this->request->group_limit(60, "1 minute");
        $this->load_brick_hooks();
    }
}