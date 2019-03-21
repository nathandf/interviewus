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

        $this->configs[ "sitemap_base_url" ] = "https://www.interviewus.net/";
        $this->configs[ "facebook" ][ "pixel_id" ] = "";
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
                "url_prefix" => "https://interviewus.net/"
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

        // SendGrid API
        $this->configs[ "sendgrid" ] = [
            "api_key" => "" // TODO Create Sendgrid account
        ];

        // Twilio API
        $this->configs[ "twilio" ] = [
            "primary_number" => "", // TODO Create Sendgrid account
            "account_sid" => "", // TODO Create Sendgrid account
            "auth_token" => "" // TODO Create Sendgrid account
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
