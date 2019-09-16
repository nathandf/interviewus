<?php

namespace Model\Services\SendgridAPI;

class ClientInitializer
{
    private $configs;
    private $evnironment;

    public function __construct( \Conf\Config $Config )
    {
        $this->setEnv( $Config->getEnv() );

        if ( !isset( $Config->configs[ "packages" ][ "sendgrid" ] ) ) {
            throw new \Exception( "Index 'sendgrid' does not exist in configs" );
        }

        $this->setConfigs( $Config->configs[ "packages" ][ "sendgrid" ] );
    }

    public function init()
    {
        $sendgrid = new \SendGrid( $this->configs[ "api-key" ] );

        return $sendgrid->client;
    }

    private function setConfigs( $configs )
    {
        $this->configs = $configs;
    }

    private function setEnv( $environment )
    {
        $this->environment = $environment;
    }

    public function getEnv()
    {
        return $this->environment;
    }
}
