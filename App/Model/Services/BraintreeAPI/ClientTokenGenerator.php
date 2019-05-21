<?php

namespace Model\Services\BraintreeAPI;

class ClientTokenGenerator
{
    // Gateway object for interfacing with braintree payments API
    public $gateway;

    public function __construct( GatewayInitializer $gateway )
    {
        // Initialize connection with braintree API
        $this->gateway = $gateway->init();
    }

    public function generate( $customer_id )
    {
        try {
            $clientToken = $this->gateway->clientToken()->generate([
                "customerId" => $customer_id
            ]);
        } catch (\Exception $e) {
            return null;
        }

        return $clientToken;
    }
}
