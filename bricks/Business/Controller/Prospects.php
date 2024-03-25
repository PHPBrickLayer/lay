<?php

namespace bricks\Business\Controller;

use BrickLayer\Lay\Core\LayConfig;
use BrickLayer\Lay\Core\Traits\IsSingleton;
use bricks\Business\Model\Prospect;
use utils\SharedBricks\Email;
use utils\Traits\Helper;

class Prospects
{
    use IsSingleton;
    use Helper;

    private static function model(): Prospect
    {
        return Prospect::new();
    }

    public function contact_us() : array {
        $post = self::get_json();

        if(
            \utils\Email\Email::new()
                ->client($post->email, $post->name)
                ->subject("Enquiry: " . $post->subject)
                ->body($post->message)
                ->to_server()
        )
            return self::resolve( 1,
                "Your enquiry has been sent and a response will be given accordingly, please ensure to check your email for a response"
            );

        return self::resolve();
    }

    public function add(): array
    {
        $post = self::get_json();

        if (!self::validate_captcha())
            return self::resolve(2, "Invalid captcha code received");

        if ($p = self::required_post_missing($post, ['name', 'email', 'subject', 'message']))
            return self::resolve(2, $p);

        $date = self::date();
        self::cleanse($post->name);
        self::cleanse($post->email);
        self::cleanse($post->subject);
        self::cleanse($post->tel, strict: false);

        $message = nl2br($post->message);
        self::cleanse($message, strict: false);

        $body = [
            "subject" => $post->subject,
            "body" => $message,
            "date" => $date,
        ];

        if ($data = self::model()->is_exist($post->name, $post->email)) {

            $data['body'] = @$data['body'] ? json_decode($data['body'], true) : [];
            $data['body'][] = $body;

            return $this->edit($data['id'], $data['body']);
        }

        if (
            !self::model()->add([
                "id" => "UUID()",
                "name" => $post->name,
                "email" => $post->email,
                "tel" => $post->tel ?? null,
                "body" => json_encode($body),
                "created_by" => "END_USER",
                "created_at" => $date,
            ])['data']
        )
            return self::resolve();

        Email::new()
            ->subject("Get Started: " . $post->subject)
            ->body($post->message)
            ->client($post->email, $post->name)
            ->server(
                LayConfig::site_data()->mail->{0},
                "Hello @ Osai Tech"
            )
            ->to_server();

        return self::resolve(1, "Your request has been placed successfully. We will surely get back to you within 2 business working days. Thank you and best regards.");
    }

    private function edit(string $id, array $body): array
    {

        self::cleanse($id);
        $body = json_encode($body);

        self::cleanse($body);

        if (
            self::model()->edit(
                $id,
                [
                    "body" => $body,
                ]
            )['data']
        )
            return self::resolve(1, "Your request has been placed successfully. This is not your first rodeo. We will surely get back to you within 2 business working days. Thank you and best regards.");

        return self::resolve();
    }

    public function delete(?string $id = null): array
    {
        $id = $id ?? self::get_json()->id;
        self::cleanse($id);

        return self::resolve();
    }

    public function list(): array
    {
        return self::model()->get()['data'];
    }
}