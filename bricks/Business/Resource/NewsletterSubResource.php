<?php

namespace Bricks\Business\Resource;

use BrickLayer\Lay\Libs\Primitives\Abstracts\ResourceHelper;
use Bricks\Business\Model\NewsletterSub;

class NewsletterSubResource extends ResourceHelper
{

    protected function schema(object|array $data): array
    {
        $sub = new NewsletterSub($data);

        return [
            "name" => $sub->name,
            "email" => $sub->email,
            "options" => $sub->options,
        ];
    }
}