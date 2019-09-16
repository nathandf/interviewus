<?php

namespace Conf;

class Config
{
    private $environments = [ "development", "staging", "production" ];
    public $environment;
    public $configs;

    public function __construct()
    {
        $this->init( "development" );
		
        if ( !in_array( $this->environment, $this->environments ) ) {
            throw new \Exception( "{$this->environment} is not valid environment - Environments list [ " . implode( ", ", $this->environments ) ." ]" );
        }

        // Redirect to approved server name if invalid server name is detected
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

    public function init( $environment )
    {
        $this->environment = $environment;
        $this->configs[ "environment" ] = $environment;

        $config_files = [
            "App/Conf/configs/app.json",
            "App/Conf/configs/database.{$environment}.json"
        ];

        foreach ( $config_files as $configs ) {
            $this->configs = array_merge(
                $this->configs,
                json_decode( file_get_contents( $configs ), true )
            ); 
        }
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
