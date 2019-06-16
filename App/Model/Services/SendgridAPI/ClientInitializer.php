<?php

namespace Model\Services\SendgridAPI;

class ClientInitializer
{
    private $configs;

    public function __construct( \Conf\Config $Config )
    {
        if ( !isset( $Config->configs[ "sendgrid" ] ) ) {
            throw new \Exception( "Index 'sendgrid' does not exist in configs" );
        }
        $this->setConfigs( $Config->configs[ "sendgrid" ] );
    }

    private function setConfigs( $configs )
    {
        $this->configs = $configs;
    }

    public function init()
    {
        $sendgrid = new \SendGrid( $this->configs[ "api-key" ] );

        return $sendgrid->client;
    }
}
