<?php
declare(strict_types=1);

namespace bricks\Business\Api;

use BrickLayer\Lay\Core\Api\ApiHooks;
use bricks\Business\Controller\Newsletters;
use bricks\Business\Controller\Prospects;
use bricks\EndUser\Controller\EndUsers;
use utils\SharedBricks\ApiHelper\Crud;

class Hook extends ApiHooks
{
    public function hooks(): void
    {
        $this->request
            ->post("contact")->bind(fn() => EndUsers::new()->contact_us())
            ->post("subscribe-newsletter")->bind(fn() => Newsletters::new()->add())
            ->get("list-subscribers")->bind(fn() => Newsletters::new()->list())
            ->post("get-started")->bind(fn() => Prospects::new()->add());
    }

    public function admin_side_hooks() : void
    {
        Crud::set($this->request, Newsletters::new(), "business/newsletter");
    }
}