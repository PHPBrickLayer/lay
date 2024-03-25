<?php


namespace utils\Email;

use BrickLayer\Lay\Core\LayConfig;
use BrickLayer\Lay\Core\Traits\IsSingleton;
use BrickLayer\Lay\Libs\LayMail;

class Email extends LayMail
{
    use IsSingleton;

    private static function email_btn(string $link, string $text): string
    {
        $color = LayConfig::site_data()->color->pry;
        return <<<BTN
            <span style="display: block; width: 100%; margin: 10px 0">
                <a style="
                    font-size: 16px;
                    font-weight: 600;
                    line-height: 1;
                    display: inline-block;
                    text-align: center;
                    text-decoration: none;
                    background: $color;
                    color: #fff;
                    padding: 15px;
                    border-radius: 5px;
                " href="$link">$text</a>
            </span>
        BTN;

    }

    public static function welcome_newsletter(array $data): ?bool
    {
        $site = LayConfig::site_data();
        $company = $site->name->short;
        $contact = $site->mail->{0};
        $admin_name = $site->others->default_personnel['name'];
        $admin_post = $site->others->default_personnel['post'];

        return self::new()
            ->subject("Welcome to $company Newsletter!")
            ->client($data['email'], $data['name'])
            ->body(<<<MSG
                <p>Dear {$data['name']},</p>
                
                <p>
                    Welcome to $company! We're thrilled to have you on board our newsletter. 
                    Thank you for subscribing and joining our community of <i>problem solvers that make money as a side effect</i>.
                </p>
                
                <p>
                    At $company, we're passionate about providing solutions, knowing our clients on a personal level, 
                    intimating with our goals, and we hope to bring this mentality to you. In a nutshell, we are solution-driven, 
                    and we want to bring in as many people as we can into this way of thinking and doing things.
                </p> 
                
                <p>Through our newsletter, you'll receive:</p>
                
                <ul>
                    <li>Exclusive updates on our latest products/services/events</li>
                    <li>Insightful industry news and trends</li>
                    <li>Special offers, promotions and freebies</li>
                    <li>Update of the latest job openings in our company</li>
                </ul>
                
                <p>We value your trust and promise to deliver valuable content straight to your inbox. We're excited to share our journey with you and to have you as part of our growing community!</p>
                
                <p>Feel free to reach out to us anytime at <a href="mailto:$contact">$contact</a> if you have any questions, feedback, or just want to say hello.</p>
                
                <p>Thank you once again for joining us. Let's embark on this exciting journey together!</p>
                
                <p>Warm regards.</p>
                
                <p>
                    $admin_name<br>
                    $admin_post<br>
                    $company               
                </p>
            MSG
            )
            ->to_client();
    }


}