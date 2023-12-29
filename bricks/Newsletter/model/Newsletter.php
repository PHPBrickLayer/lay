<?php

namespace bricks\Newsletter\model;

use BrickLayer\Lay\Core\Traits\IsSingleton;
use utils\SharedBricks\interfaces\Module;
use utils\SharedBricks\traits\ModuleDefault;

class Newsletter implements Module {
    use IsSingleton;
    use ModuleDefault;

    public static string $table = "newsletters";

    public function is_exist(string $email): bool
    {
        return self::exists("`email`='$email'") > 0;
    }
}