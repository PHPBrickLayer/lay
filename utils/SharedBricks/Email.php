<?php

namespace utils\SharedBricks;

use BrickLayer\Lay\Core\LayConfig;
use BrickLayer\Lay\Core\Traits\IsSingleton;
use BrickLayer\Lay\Core\View\Tags\Anchor;
use BrickLayer\Lay\Libs\LayDate;
use BrickLayer\Lay\Libs\LayMail;

class Email extends LayMail
{
    use IsSingleton;
    
    private static function email_btn(string $link, string $text) : string {
        $color = LayConfig::new()->get_site_data('color','pry');
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

    public static function send_registration_link(array $data) : ?bool {
        $expire = LayDate::date($data['expires'],"d M, Y h:i:sa");
        $link = Anchor::new()->href("complete-registration/" . $data['token'])->get_href();
        $btn = self::email_btn($link,'Complete Registration');

        return self::new()
            ->subject("Complete Your Registration " . $data['name'])
            ->client($data['email'], $data['name'])
            ->body(<<<MSG
                <p>Hello {$data['name']},</p>
                <p>Your account has been created on our platform by one of our admins, but you need to complete your registration to login successfully.</p>
                <p>
                    Use this link to create a new password for your account to continue the registration. 
                    {$btn}
                    <b>Note:</b> This link expires <b>$expire</b>
                </p>
                <p>
                    Thanks and Regards<br><br>
                    {$data['admin']}<br>
                    {$data['role']}
                </p>
            MSG)
            ->to_client();
    }

    public static function welcome_admin(array $data) : ?bool {
        $link = Anchor::new()->href('sign-in')->get_href();
        $btn = self::email_btn($link,'Checkout The Dashboard');
        $company = LayConfig::site_data()->name->short;

        return self::new()
            ->subject("Welcome Onboard " . $data['name'])
            ->client($data['email'], $data['name'])
            ->body(<<<MSG
                <h2>Hurray!!! You have Successfully Created Your Account</h2>
                <p>
                    This email is a formal welcome into our platform. As of today, you are a part of something great, 
                    we want you to familiarize yourself with the dashboard, because, you will be doing a lot there. 
                </p>
                <p>
                    Welcome once again from all of us at $company. <br>
                    Use this link to create access the dashboard. 
                    {$btn}
                </p>
                <p>
                    Thanks and Best Regards<br><br>
                    Admin<br>
                    $company
                </p>
            MSG)
            ->to_client();
    }

    public static function send_password_reset_link(array $data) : ?bool {
        $link = Anchor::new()->href("new-password/" . $data['token'])->get_href();
        $btn = self::email_btn($link,'Continue');

        return self::new()
            ->subject("Reset Your Password " . $data['name'])
            ->client($data['email'], $data['name'])
            ->body(<<<MSG
                <p>Hello {$data['name']},</p>
                <p>You have placed a request to reset your password, please use the button below to complete the process.</p>
                <div>{$btn}</div>
                <p><b>Note:</b> This link is only valid for 30 minutes.</p>
                <p>Thanks and Regards</p>
            MSG)
            ->to_client();
    }

    public static function welcome_newsletter(array $data) : ?bool {
        $site =  LayConfig::site_data();
        $company = $site->name->short;
        $contact = $site->mail->{0};
        $admin_name = $site->others->default_personnel->name;
        $admin_post = $site->others->default_personnel->post;

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
            MSG)
            ->to_client();
    }



}