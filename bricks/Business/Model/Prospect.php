<?php
declare(strict_types=1);

namespace bricks\Business\Model;

use BrickLayer\Lay\Core\Traits\IsSingleton;
use utils\SharedBricks\Interfaces\Module;
use utils\SharedBricks\Traits\ModuleDefault;

class Prospect implements Module
{
    use IsSingleton;
    use ModuleDefault;

    public static string $table = "prospects";

    public function is_exist(string $name, string $email) : ?array
    {
        $data = self::get_by("`name`='$name' AND email='$email'")['data'];
        return empty($data) ? null : $data;
    }
}