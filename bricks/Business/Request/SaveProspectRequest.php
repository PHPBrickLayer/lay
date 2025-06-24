<?php

namespace Bricks\Business\Request;

use BrickLayer\Lay\Libs\Primitives\Abstracts\RequestHelper;

/**
 * @property string name
 * @property string email
 * @property string|null tel
 * @property string subject
 * @property string message
 */
class SaveProspectRequest extends RequestHelper
{
    protected function rules(): void
    {
        $this->vcm([ 'is_captcha' => true, 'field' => 'captcha' ]);
        $this->vcm([ 'field' => 'name' ]);
        $this->vcm([ 'field' => 'email', 'is_email' => true ]);
        $this->vcm([ 'field' => 'tel', 'is_num' => true, 'required' => false ]);
        $this->vcm([ 'field' => 'subject' ]);
        $this->vcm([ 'field' => 'message', 'after_clean' => fn($v) => nl2br($v) ]);
    }
}