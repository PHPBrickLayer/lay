<?php

use BrickLayer\Lay\Core\LayConfig;

include_once __DIR__ . DIRECTORY_SEPARATOR . "vendor" .  DIRECTORY_SEPARATOR . "autoload.php";

LayConfig::validate_lay();

$sess = [
    "http_only" => true,
//    "only_cookies" => true,
    "secure" => true,
    "samesite" => 'None',
];

if(LayConfig::$ENV_IS_PROD) {
    $sess['lifetime'] = 0;
    $sess['path'] = "/";
    $sess['domain'] = "localhost";
}

LayConfig::session_start($sess);

LayConfig::set_cors(
    // Specify where to allow requests from
    allowed_origins: [
        "http://localhost",
    ],

    // Allow requests from all origins
    allow_all: true,

    // Headers to add to the response
    fun: function () {
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Headers: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }
);

///// Project Configuration

$site_name = "Sample Lay Project";

LayConfig::new()

    // Remove this to allow the framework to search through the prod folder for static assets
    // This happens when you run the: [ php bob deploy -m "Message" ] command;
    // A prod folder is created where minified css and js are stored
    ->dont_use_prod_folder()

    ->init_name($site_name, "$site_name | Slogan Goes Here")
    ->init_color("#082a96", "#0e72e3")
    ->init_mail("EMAIL-1", "EMAIL-2")
    ->init_tel("TEL-1", "TEL-2")
    ->init_author("PHP BrickLayer - Lay")
    ->init_copyright("&copy; " . date('Y') . "; All rights reserved <a href='https://lay.osaitech.dev'>PHP Bricklayer - Lay</a>")

    // Store non-sensitive data ands access it anywhere in the project by calling the `LayConfig::site_data()->others` method.
    // If you have a value that persists both on local environment and production, use this
    ->init_others([
        "desc" => (
            "This is an awesome project that is about to unfold you just watch and see 😉."
        ),
        "bucket_domain" => "https://bucket.lay.osaitech.dev/"
    ])
    ->init_orm(false)
->init_end();
