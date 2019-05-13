<?php

namespace Model\Services\BraintreeAPI;

use Model\DomainObjects\Person;

class CustomerRepository
{
    // Gateway object for interfacing with braintree payments API
    public $gateway;

    public function __construct( GatewayInitializer $gateway )
    {
        // Initialize connection with braintree API
        $this->gateway = $gateway->init();
    }

    private function customerExists( $customer_id )
    {
        $collection = $this->gateway->customer()->search([
            \Braintree_CustomerSearch::id()->is( $customer_id )
        ]);

        foreach ( $collection as $customer ) {
            if ( $customer->id == $customer_id ) {
                return true;
            }
        }

        return false;
    }

    public function create( Person $person )
    {
        $result = $this->gateway->customer()->create([
            "firstName" => $person->getFirstName(),
            "lastName" => $person->getLastName(),
            "email" => $person->getEmail(),
            "phone" => $person->getPhoneNumber()
        ]);

        return $result;
    }
}
