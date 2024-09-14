<?php
declare(strict_types=1);

namespace Bricks\Business\Model;

use BrickLayer\Lay\Core\Traits\IsSingleton;
use Utils\Interfaces\Model;
use Utils\Traits\ModelDefault;

class Prospect implements Model
{
    use IsSingleton;
    use ModelDefault;

    public static string $table = "prospects";

    public function is_exist(string $name, string $email) : ?array
    {
        $data = self::get_by("`name`='$name' AND email='$email'")['data'];
        return empty($data) ? null : $data;
    }
}