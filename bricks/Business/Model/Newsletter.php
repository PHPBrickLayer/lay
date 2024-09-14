<?php

namespace Bricks\Business\Model;

use BrickLayer\Lay\Core\Traits\IsSingleton;
use Utils\Interfaces\Model;
use Utils\Traits\ModelDefault;

class Newsletter implements Model {
    use IsSingleton;
    use ModelDefault;

    public static string $table = "newsletters";

    public function is_exist(string $email): bool
    {
        return self::exists("`email`='$email'") > 0;
    }
}