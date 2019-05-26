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

    public function create( $customer_id, $payment_method_nonce, $default = true )
    {
        $result = $this->gateway->paymentMethod()->create([
            "customerId" => $customer_id,
            "paymentMethodNonce" => $payment_method_nonce,
            "options" => [
                "makeDefault" => $default,
                "failOnDuplicatePaymentMethod" => true
            ]
        ]);

        return $result;
    }

    public function delete( $payment_method_token )
    {
        try {
            $this->gateway->paymentMethod()->delete( $payment_method_token );
        } catch ( \Exception $e ) {
            return false;
        }

        return true;
    }
}
