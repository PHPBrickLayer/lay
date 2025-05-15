<?php
declare(strict_types=1);

namespace Bricks\Business\Api;

use BrickLayer\Lay\Core\Api\ApiHooks;
use Bricks\Business\Controller\NewsletterSubController;
use Bricks\Business\Controller\Prospects;

class Hook extends ApiHooks
{
    public function hooks(): void
    {
        $this->engine
            ->post("subscribe-newsletter")->bind(fn() => NewsletterSubController::new()->add())
            ->get("list-subscribers/{page}")->bind(fn($page) => NewsletterSubController::new()->list((int) $page))
            ->post("contact")->bind(fn() => Prospects::new()->contact_us())
            ->post("get-started")->bind(fn() => Prospects::new()->add());
    }
}