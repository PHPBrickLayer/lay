<?php
namespace web\domains\Api;

use BrickLayer\Lay\Core\Api\ApiHooks;
use bricks\EndUser\controller\EndUsers;
use bricks\Newsletter\controller\Newsletters;

class Plaster extends ApiHooks
{
    public function hooks(): void
    {
        $this->request->prefix("client")
            ->post("contact")->bind(fn() => EndUsers::new()->contact_us())
            ->post("subscribe-newsletter")->bind(fn() => Newsletters::new()->add())
            ->get("list-subscribers")->bind(fn() => Newsletters::new()->list())
            ->print_as_json();
    }
}