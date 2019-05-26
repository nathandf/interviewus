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

    public function get( $subscription_id )
    {
        try {
            $result = $this->gateway->subscription()->find( $subscription_id );

            return $result;
        } catch ( \Exception $e ) {

            return null;
        }
    }

    public function updatePlan( $subscription_id, $payment_method_token, $plan_id, $price )
    {
        try {
            $result = $this->gateway->subscription()->update( $subscription_id, [
                "paymentMethodToken" => $payment_method_token,
                "planId" => $plan_id,
                "price" => $price
            ]);
        } catch ( \Exception $e ) {

            return null;
        }

        return $result;
    }

    public function updatePaymentMethod( $subscription_id, $payment_method_token )
    {
        try {

            $result = $this->gateway->subscription()->update( $subscription_id, [
                "paymentMethodToken" => $payment_method_token
            ]);
        } catch ( \Exception $e ) {

            return false;
        }

        return true;
    }

    public function delete( $subscription_id )
    {
        if ( !is_null( $this->get( $subscription_id ) ) ) {
            $result = $this->gateway->subscription()->cancel( $subscription_id );

            return true;
        }

        return false;
    }
}
