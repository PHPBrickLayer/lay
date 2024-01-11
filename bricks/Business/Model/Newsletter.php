<?php

namespace bricks\Business\Model;

use BrickLayer\Lay\Core\Traits\IsSingleton;
use utils\SharedBricks\Interfaces\Module;
use utils\SharedBricks\Traits\ModuleDefault;

class Newsletter implements Module {
    use IsSingleton;
    use ModuleDefault;

    public static string $table = "newsletters";

    public function is_exist(string $email): bool
    {
        return self::exists("`email`='$email'") > 0;
    }
}