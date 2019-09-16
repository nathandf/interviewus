<?php

namespace Model\Services\BraintreeAPI;

class GatewayInitializer
{
    private $configs;

    public function __construct( \Conf\Config $Config )
    {
        if ( !isset( $Config->configs[ "packages" ][ "braintree" ] ) ) {
            throw new \Exception( "Index 'braintree' does not exist in configs" );
        }
        $this->setConfigs( $Config->configs[ "packages" ][ "braintree" ] );
    }

    public function init()
    {
        $gateway = new \Braintree_Gateway([
            "environment" => $this->configs[ "environment" ],
            "merchantId" => $this->configs[ "credentials" ][ "merchant_id" ],
            "publicKey" => $this->configs[ "credentials" ][ "public_key" ],
            "privateKey" => $this->configs[ "credentials" ][ "private_key" ]
        ]);

        return $gateway;
    }

    private function setConfigs( $configs )
    {
        $this->configs = $configs;
    }

}
