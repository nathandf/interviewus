<?php

$config[ "approved_server_names" ] = [
    "www.interviewus.net",
    "develop.jiujitsuscout.stupidsimple.tech",
    "stupidsimple.tech"
];

$config[ "allowable_origins" ] = [
    "https://www.interviewus.net"
];

$config[ "indexable_domains" ] = [
    "www.interviewus.net"
];

$config[ "sitemap_base_url" ] = "https://www.interviewus.net/";
$config[ "facebook" ][ "pixel_id" ] = '1842001532752101';
$config[ "max_upload_filesize" ] = "2GB";

// Logging
$config[ "logs_directory" ] = "App/logs/";

// Routing
$config[ "routing" ] = [
    "development" => [
        "root" => "/interviewus.net/"
    ],
    "production" => [
        "root" => "https://www.interviewus.net/"
    ]
];

// Email Settings
$config[ "email_settings" ] = [
    "development" => [
        "url_prefix" => "http://localhost/interviewus.net/"
    ],
    "production" => [
        "url_prefix" => "https://interviewus.net/"
    ],
];

// Database
$config[ "db" ] = [
    "development" => [
        "host" => "localhost",
        "dbname" => "yurigloc_jjs_development",
        "user" => "yurigloc_develop",
        "password" => "Q7Np4WBUfCveynAy",
    ],
    "production" => [
        "host" => "localhost",
        "dbname" => "yurigloc_jjs_main",
        "user" => "yurigloc_main",
        "password" => "XHN8yxNzpN2l",
    ]
];

// SendGrid API
$config[ "sendgrid" ] = [
    "api_key" => "" // TODO Create Sendgrid account
];

// Twilio API
$config[ "twilio" ] = [
    "primary_number" => "", // TODO Create Sendgrid account
    "account_sid" => "", // TODO Create Sendgrid account
    "auth_token" => "" // TODO Create Sendgrid account
];
