<?php

namespace bricks\EndUser\controller;

use BrickLayer\Lay\Core\Traits\IsSingleton;
use BrickLayer\Lay\Libs\LayObject;
use utils\SharedBricks\Email;

class EndUsers
{
    use IsSingleton;

    public function contact_us() : array {
        $post = LayObject::new()->get_json();

        if(
            Email::new()
                ->client($post->email, $post->name)
                ->subject("Enquiry: " . $post->subject)
                ->body($post->message)
            ->to_server()
        )
            return [
                "code" => 1,
                "msg" => "Your enquiry has been sent and a response will be given accordingly, please ensure to check your email for a response"
            ];

        return [
            "code" => 0,
            "msg" => "Cannot contact us at the moment, please try again later"
        ];
    }

    public function get_started() : array {
        $post = LayObject::new()->get_json();

        if(
            Email::new()
                ->client($post->email, $post->name)
                ->subject("Get Started: " . $post->subject)
                ->body($post->message)
            ->to_server()
        )
            return [
                "code" => 1,
                "msg" => "Your enquiry has been sent and a response will be given accordingly, please ensure to check your email for a response"
            ];

        return [
            "code" => 0,
            "msg" => "Cannot contact us at the moment, please try again later"
        ];
    }


}
