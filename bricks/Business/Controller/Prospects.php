<?php

namespace Bricks\Business\Controller;

use BrickLayer\Lay\Core\LayConfig;
use BrickLayer\Lay\Libs\Primitives\Traits\ControllerHelper;
use BrickLayer\Lay\Libs\Primitives\Traits\IsSingleton;
use BrickLayer\Lay\Libs\LayDate;
use Bricks\Business\Model\Prospect;
use Bricks\Business\Request\SaveProspectRequest;
use Bricks\Business\Resource\ProspectResource;
use Utils\Email\Email;

class Prospects
{
    use IsSingleton, ControllerHelper;

    public function contact_us() : array
    {
        $post = self::request();

        //TODO: Include some sort of captcha to avoid spam messages from bots

        if(
            (new Email())
                ->client($post->email, $post->name)
                ->subject("Enquiry: " . $post->subject)
                ->body($post->message)
                ->to_server()
        )
            self::res_success(
                "Your enquiry has been sent and a response will be given accordingly, please ensure to check your email for a response"
            );

        return self::res_error();
    }

    public function add(): array
    {
        $request = new SaveProspectRequest();

        if($request->error)
            return self::res_warning($request->error);

        $body = [
            "subject" => $request->subject,
            "body" => $request->message,
            "tel" => $request->tel,
            "date" => LayDate::now(),
        ];

        $prospect = new Prospect();

        if ($prospect->is_duplicate($request)) {
            $data = $prospect->body ?? [];
            $data[] = $body;

            $edited = $prospect->edit_self([ "body" => json_encode($data) ]);

            if($edited)
                return self::res_success("Your request has been placed successfully. This is not your first rodeo. We will surely get back to you within 2 business working days. Thank you and best regards.");

            return self::res_warning("Could not complete process at the moment, please try again later");
        }

        $request->new_key('body', json_encode($body));
        $message = $request->message;
        $subject = $request->subject;
        $tel = $request->tel;

        $request->unset("subject", "message", "tel");

        $prospect->add($request);

        if ($prospect->is_empty())
            return self::res_warning("Could not complete process at the moment, please try again later");

        $message =  <<<BODY
        <h3>$subject</h3>
        <p><b>Phone Number:</b> $tel</p>
        <p>$message</p>
        BODY;

        (new Email())
            ->subject("Get Started: " . $subject)
            ->body($message)
            ->client($request->email, $request->name)
            ->server(
                LayConfig::site_data()->mail->{0},
                "LayMailer"
            )
            ->to_server();

        return self::res_success("Your request has been placed successfully. We will surely get back to you within 2 business working days. Thank you and best regards.");
    }

    public function list(int $page = 1): array
    {
        return ProspectResource::collect(
            (new Prospect())->all($page, 100)
        );
    }
}