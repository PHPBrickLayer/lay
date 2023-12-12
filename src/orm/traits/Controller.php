<?php
declare(strict_types=1);
namespace Oleonard\Lay\orm\traits;

use Oleonard\Lay\core\traits\IsSingleton;
use Oleonard\Lay\orm\SQL;

trait Controller{
    use IsSingleton;
    use Clean;
    use SelectorOOP;
}
