<?php

namespace Bricks\Business\Model;

use BrickLayer\Lay\Libs\Primitives\Abstracts\BaseModelHelper;
use BrickLayer\Lay\Libs\Primitives\Abstracts\RequestHelper;

/**
 * @property string id
 * @property string email
 * @property string name
 * @property array options
 *
 * @property bool deleted
 * @property string created_by
 * @property int created_at
 * @property string|null updated_by
 * @property int|null updated_at
 */
class NewsletterSub extends BaseModelHelper {

    public static string $table = "newsletter_subs";

    protected function props_schema(array &$props): void
    {
        $this->parse_prop("options", "array");
    }

    public function is_duplicate(array|RequestHelper $columns) : bool
    {
        $columns = $this->req_2_array($columns);

        return $this->count("email", $columns['email']) > 0;
    }

    public function created_by() : ?string
    {
        return "END-USER";
    }
}