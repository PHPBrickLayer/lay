<?php
namespace web\domains\Api;

use BrickLayer\Lay\Core\Api\ApiHooks;
use BrickLayer\Lay\Core\Api\Enums\ApiStatus;
use BrickLayer\Lay\Core\LayConfig;

class Plaster extends ApiHooks
{
    public function hooks(): void
    {
        if(LayConfig::is_bot())
            $this->request::set_response_header(ApiStatus::NOT_ACCEPTABLE);

        $this->request->group_limit(60, "1 minute");
        $this->load_brick_hooks();
    }
}