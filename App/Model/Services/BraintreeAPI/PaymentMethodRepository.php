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

    public function get( $payment_method_token )
    {
        $result = $this->gateway->paymentMethod()->find( $payment_method_token );

        return $result;
    }
}
