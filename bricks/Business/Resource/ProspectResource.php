<?php

namespace Bricks\Business\Resource;

use BrickLayer\Lay\Libs\Primitives\Abstracts\ResourceHelper;
use Bricks\Business\Model\Prospect;

class ProspectResource extends ResourceHelper
{

    protected function schema(object|array $data): array
    {
        $prospect = new Prospect($data);

        return [
            "propId" => $prospect->id,
            "name" => $prospect->name,
            "email" => $prospect->email,
            "msgData" => $prospect->body,
        ];
    }
}