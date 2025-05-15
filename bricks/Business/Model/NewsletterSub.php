<?php

namespace Bricks\Business\Model;

use BrickLayer\Lay\Libs\Primitives\Abstracts\BaseModelHelper;
use BrickLayer\Lay\Libs\Primitives\Abstracts\RequestHelper;

/**
 * @property string $id
 * @property string $email
 * @property string|null $name
 * @property array $options
 *
 * @property bool $deleted
 * @property string|null $created_by
 * @property int $created_at
 * @property string|null $updated_by
 * @property int $updated_at
 */
class NewsletterSub extends BaseModelHelper {

    public static string $table = "newsletter_subs";

    protected function props_schema(array $props): array
    {
        $props['options'] = is_string(@$props['options']) ?
            json_decode($props['options'], true) : ($props['options'] ?? null);

        return $props;
    }

    public function is_duplicate(array|RequestHelper $columns) : bool
    {
        if($columns instanceof RequestHelper)
            $columns = $columns->props();

        return self::db()
                ->where("email", $columns['email'])
                ->and_where("deleted", '0')
                ->count() > 0;
    }
}