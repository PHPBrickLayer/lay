<?php

namespace tests\Unit;

use Oleonard\Lay\core\traits\IsSingleton;

class FakeControllerSingleton
{
    use IsSingleton;
    public function print_user(int $id): array
    {
        return [
            "name" => "User name",
            "id" => $id
        ];
    }
}