<?php

namespace bricks\Business\Model;

use BrickLayer\Lay\Core\Traits\IsSingleton;
use utils\Interfaces\Model;
use utils\Traits\ModelDefault;

class Newsletter implements Model {
    use IsSingleton;
    use ModelDefault;

    public static string $table = "newsletters";

    public function is_exist(string $email): bool
    {
        return self::exists("`email`='$email'") > 0;
    }
}