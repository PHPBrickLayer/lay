<?php

namespace Bricks\Business\Controller;

use BrickLayer\Lay\Libs\Primitives\Traits\ControllerHelper;
use BrickLayer\Lay\Libs\Primitives\Traits\IsSingleton;
use Bricks\Business\Model\NewsletterSub;
use Bricks\Business\Request\SubNewsletterRequest;
use Bricks\Business\Resource\NewsletterSubResource;
use Utils\Email\Email;

class NewsletterSubController
{
    use IsSingleton, ControllerHelper;

    public function list(int $page = 1): array
    {
        return NewsletterSubResource::collect(
            (new NewsletterSub())->all($page, 100)
        );
    }

    public function add(): array
    {
        $request = new SubNewsletterRequest();

        if ($request->error)
            return self::res_warning($request->error);

        $sub = new NewsletterSub();

        if($sub->is_duplicate($request))
            return self::res_success("Congratulations, you are already subscribed!");

        $sub->add($request);

        if ($sub->is_empty())
            return self::res_warning("Could not subscribe at the moment, please try again later");

        (new Email())->welcome_newsletter([
            "name" => $request->name,
            "email" => $request->email,
        ]);

        return self::res_success("Congratulations! You have successfully subscribed! We will get in touch with you  with the latest tips, tricks, offers and freebies. We only dish out quality content, we don't spam");
    }
}