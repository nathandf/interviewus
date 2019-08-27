<?php

namespace Conf;

class Config
{
    private $environments = [ "development", "staging", "production" ];
    public $environment;
    public $configs;

    public function __construct()
    {
        $this->initConfigs();

        if ( !in_array( $this->configs[ "environment" ], $this->environments ) ) {
            throw new \Exception( "\"{$this->environment}\" is not valid environment - Environments list [ " . implode( ",", $this->environments ) ." ]" );
        }

        $this->environment = $this->configs[ "environment" ];

        // Make sure people aint stealing my shit
        if ( $this->environment == "production" && $_SERVER[ "REMOTE_ADDR" ] != "::1" ) {
            if ( !in_array( $_SERVER[ "SERVER_NAME" ], $this->configs[ "approved_server_names" ] ) ) {
                header( "location: " . $this->configs[ "routing" ][ "production" ][ "root" ] );
            }
        }

        // Prohibit search engines from index develop or staging sites
        if ( !in_array( $_SERVER[ "SERVER_NAME" ], $this->configs[ "indexable_domains" ] ) ) {
            header( "X-Robots-Tag: noindex, nofollow", true );
        }

    }

    public function initConfigs()
    {
        // Only use _ for keys in app-details configs. prefix all keys with 'app_'.
        $this->configs[ "app-details" ] = [
            "app_url" => "https://www.interviewus.net/",
            "app_name" => "InterviewUs",
            "app_business_name" => "InterviewUs LLC",
            "app_founder" => "Nathan Freeman",
            "app_business_contact" => "+1 (812) 276-3172",
            "app_customer_support_number" => "+1 (812) 276-3172",
            "app_customer_support_email" => "interview.us.app@gmail.com"
        ];

        $this->configs[ "dir" ] = [
            "email-templates" => "App/templates/email-templates/"
        ];

        $this->configs[ "environment" ] = "development";

        $this->configs[ "approved_server_names" ] = [
            "www.interviewus.net"
        ];

        $this->config[ "allowable_origins" ] = [
            "https://www.interviewus.net"
        ];

        $this->configs[ "indexable_domains" ] = [
            "www.interviewus.net"
        ];

        $this->configs[ "max_upload_filesize" ] = "2GB";

        // Logging
        $this->configs[ "logs_directory" ] = "App/logs/";

        // Routing
        $this->configs[ "routing" ] = [
            "development" => [
                "root" => "/interviewus.net/"
            ],
            "production" => [
                "root" => "https://www.interviewus.net/"
            ]
        ];

        // Email Settings
        $this->configs[ "email_settings" ] = [
            "development" => [
                "url_prefix" => "http://localhost/interviewus.net/"
            ],
            "production" => [
                "url_prefix" => "https://www.interviewus.net/"
            ],
        ];

        // Database
        $this->configs[ "db" ] = [
            "development" => [
                "host" => "localhost",
                "dbname" => "interviewus",
                "user" => "interviewus",
                "password" => "0b5wStZMAapOQJuG"
            ],
            "production" => [
                "host" => "localhost",
                "dbname" => "interviewus",
                "user" => "interviewus",
                "password" => "0b5wStZMAapOQJuG"
            ]
        ];

        // IPData API
        $this->configs[ "ipdata" ] = [
            "api-key" => "939c6e36861de58f2e9c59354074e7c9866ccd9335dabfbd29a20385"
        ];

        // SendGrid API
        $this->configs[ "sendgrid" ] = [
            "api-key" => "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"
        ];

        // Twilio API
        $this->configs[ "twilio" ] = [
            "account_sid" => "AC594867e02250c1d3bb129379cf0021c9",
            "auth_token" => "d9421fc6876d85e18b0dd1d95315c92d",
            "development" => [
                "status_callback" => "http://1428890c.ngrok.io/interviewus.net/webhooks/twilio/status/"
            ],
            "production" => [
                "status_callback" => "http://www.interviewus.net/webhooks/twilio/status/"
            ]
        ];

        // Braintree API
        $this->configs[ "braintree" ] = [
            "environment" => "sandbox",
            "credentials" => [
                "merchant_id" => "m5wfnmpqf5gwz3fx",
                "public_key" => "ck6j2s6fvwng3rnt",
                "private_key" => "a35ec12071b162cf9e2b85d13b5689c9",
                "tokenization_key" => ""
            ]
        ];
    }

    public function getEnv()
    {
        return $this->environment;
    }

    public function getConfigs()
    {
        return $this->configs;
    }
}
