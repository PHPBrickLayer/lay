<?php
declare(strict_types=1);

namespace Bricks\Business\Model;

use BrickLayer\Lay\Libs\Primitives\Abstracts\BaseModelHelper;
use BrickLayer\Lay\Libs\Primitives\Abstracts\RequestHelper;

/**
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string|null $tel
 * @property array $body
 * @property bool $deleted
 * @property string|null $created_by
 * @property int $created_at
 * @property string|null $updated_by
 * @property int $updated_at
 */
class Prospect extends BaseModelHelper
{
    public static string $table = "prospects";

    public function is_duplicate(array|RequestHelper $columns) : bool
    {
        if($columns instanceof RequestHelper)
            $columns = $columns->props();

        $this->fill(
            self::db()
                ->where("name", $columns['name'])
                ->and_where("email", $columns['email'])
                ->and_where("deleted", '0')
            ->then_select()
        );

        return $this->exists();
    }

    protected function props_schema(array &$props): void
    {
        $this->parse_prop("body", "array");
    }

    public function created_by() : ?string
    {
        return "END-USER";
    }
}