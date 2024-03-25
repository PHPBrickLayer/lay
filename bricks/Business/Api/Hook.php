<?php
declare(strict_types=1);

namespace bricks\Business\Api;

use BrickLayer\Lay\Core\Api\ApiHooks;
use bricks\Business\Controller\Newsletters;
use bricks\Business\Controller\Prospects;
use bricks\EndUser\Controller\EndUsers;

class Hook extends ApiHooks
{
    public function hooks(): void
    {
        $this->request
            ->post("subscribe-newsletter")->bind(fn() => Newsletters::new()->add())
            ->get("list-subscribers")->bind(fn() => Newsletters::new()->list())
            ->post("contact")->bind(fn() => Prospects::new()->contact_us())
            ->post("get-started")->bind(fn() => Prospects::new()->add());
    }
}