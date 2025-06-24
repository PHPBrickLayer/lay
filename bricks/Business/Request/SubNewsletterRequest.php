<?php

namespace Bricks\Business\Request;

use BrickLayer\Lay\Libs\Primitives\Abstracts\RequestHelper;

/**
 * @property string name
 * @property string email
 */
class SubNewsletterRequest extends RequestHelper
{

    protected function rules(): void
    {
        $this->vcm(['field' => 'name', 'required' => false ]);
        $this->vcm(['field' => 'email', 'is_email' => true ]);
    }

    protected function post_validate(array $data): array
    {
        $data['name'] ??= "Subscriber";
        return $data;
    }
}