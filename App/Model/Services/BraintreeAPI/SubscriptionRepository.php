<?php

namespace Model\Services\BraintreeAPI;

class SubscriptionRepository
{
    // Gateway object for interfacing with braintree payments API
    public $gateway;

    public function __construct( GatewayInitializer $gateway )
    {
        // Initialize connection with braintree API
        $this->gateway = $gateway->init();
    }

    public function create( $payment_method_nonce, $plan_id )
    {
        $result = $this->gateway->subscription()->create([
            "paymentMethodNonce" => $payment_method_nonce,
            "planId" => $plan_id
        ]);

        return $result;
    }
}
