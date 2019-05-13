<?php

namespace Model\Services\BraintreeAPI;

class PaymentMethodRepository
{
    // Gateway object for interfacing with braintree payments API
    public $gateway;

    public function __construct( GatewayInitializer $gateway )
    {
        // Initialize connection with braintree API
        $this->gateway = $gateway->init();
    }
}
