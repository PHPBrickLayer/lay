<?php
declare(strict_types=1);
namespace BrickLayer\Lay\orm\traits;

use BrickLayer\Lay\core\traits\IsSingleton;
use BrickLayer\Lay\orm\SQL;

trait Controller{
    use IsSingleton;
    use Clean;
    use SelectorOOP;
}
