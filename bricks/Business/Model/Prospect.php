<?php
declare(strict_types=1);

namespace bricks\Business\Model;

use BrickLayer\Lay\Core\Traits\IsSingleton;
use utils\Interfaces\Model;
use utils\Traits\ModelDefault;

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