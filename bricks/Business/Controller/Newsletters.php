<?php

namespace bricks\Business\Controller;

use BrickLayer\Lay\Core\Traits\IsSingleton;
use bricks\Business\Model\Newsletter;
use utils\Email\Email;
use utils\Traits\Helper;

class Newsletters
{
    use IsSingleton;
    use Helper;

    public function list(): array
    {
        return self::model()->get()['data'];
    }

    private static function model(): Newsletter
    {
        return Newsletter::new();
    }

    public function add(): array
    {
        if (!self::validate_captcha())
            return self::resolve(0, "Invalid code received, please try again");

        $post = self::get_json();

        if ($p = self::required_post_missing($post, ['email']))
            return self::resolve(2, $p);


        self::cleanse($post->email);

        if (self::model()->is_exist($post->email))
            return self::resolve(1, "You have already subscribed, don't worry you will begin to see great tips, offers and freebies from us. We only dish out the best content and we don't spam");

        self::cleanse($post->name);

        if (
            !self::model()->add([
                "id" => "UUID()",
                "email" => $post->email,
                "name" => $post->name,
                "created_by" => "END_USER",
                "created_at" => self::date(),
            ])['data']
        )
            return self::resolve();

        Email::welcome_newsletter([
            "name" => $post->name,
            "email" => $post->email,
        ]);

        return self::resolve(1, "Congratulations! You have successfully subscribed! We will get in touch with you  with the latest tips, tricks, offers and freebies. We only dish out quality content, we don't spam");
    }
}