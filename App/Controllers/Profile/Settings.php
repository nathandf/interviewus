<?php

namespace Controllers\Profile;

use \Core\Controller;

class Settings extends Controller
{
    public function before()
    {
        $userAuth = $this->load( "user-authenticator" );
        $this->user = $userAuth->getAuthenticatedUser();

        if ( is_null( $this->user ) ) {
            return [ null, "DefaultView:redirect", null, "sign-in" ];
        }

        $accountRepo = $this->load( "account-repository" );
        $this->account = $accountRepo->get( [ "*" ], [ "id" => $this->user->current_account_id ], "single" );
    }

    public function indexAction()
    {
        $requestValidator = $this->load( "request-validator" );
        $paymentMethodRepo = $this->load( "payment-method-repository" );
        $industryRepo = $this->load( "industry-repository" );

        if (
            $this->request->is( "get" ) &&
            $this->request->get( "add_payment_method" ) != "" &&
            $requestValidator->validate(
                $this->request,
                [
                    "token" => [
                        "equals-hidden" => $this->request->session( "csrf-token" )
                    ],
                    "payment_method_nonce" => [
                        "required" => true
                    ]
                ],
                "add_payment_method"
            )
        ) {
            return [ "PaymentMethod:create", "DefaultView:redirect", null, "profile/settings/" ];
        }

        if (
            $this->request->is( "post" ) &&
            $this->request->post( "update_organization" ) != "" &&
            $requestValidator->validate(
                $this->request,
                [
                    "token" => [
                        "equals-hidden" => $this->request->session( "csrf-token" ),
                        "required" => true
                    ],
                    "organization" => [
                        "min" => 1
                    ],
                    "industry_id" => [
                        "in_array" => $industryRepo->get( [ "id" ], [], "raw" )
                    ]
                ],
                "udpate_organization"
            )
        ) {
            return [ "Organization:update", "Home:redirect", null, "profile/settings/" ];
        }

        if (
            $this->request->is( "post" ) &&
            $this->request->post( "update_default_payment_method" ) != "" &&
            $requestValidator->validate(
                $this->request,
                [
                    "token" => [
                        "equals-hidden" => $this->request->session( "csrf-token" )
                    ],
                    "braintree_payment_method_token" => [
                        "required" => true,
                        "in_array" => $paymentMethodRepo->get(
                            [ "braintree_payment_method_token" ],
                            [ "account_id" => $this->account->id ],
                            "raw"
                        )
                    ]
                ],
                "update_default_payment_method"
            )
        ) {
            return [ "PaymentMethod:updateDefault", "Settings:updateDefaultPaymentMethod", null, null ];
        }

        if (
            $this->request->is( "post" ) &&
            $this->request->post( "remove_payment_method" ) != "" &&
            $requestValidator->validate(
                $this->request,
                [
                    "token" => [
                        "equals-hidden" => $this->request->session( "csrf-token" )
                    ],
                    "braintree_payment_method_token" => [
                        "required" => true,
                        "in_array" => $paymentMethodRepo->get(
                            [ "braintree_payment_method_token" ],
                            [
                                "account_id" => $this->account->id,
                                "is_default" => 0
                            ],
                            "raw"
                        )
                    ]
                ],
                "remove_payment_method"
            )
        ) {
            return [ "PaymentMethod:delete", "DefaultView:redirect", null, "profile/settings/" ];
        }

        if (
            $this->request->is( "post" ) &&
            $this->request->post( "cancel_subscription" ) != "" &&
            $requestValidator->validate(
                $this->request,
                [
                    "token" => [
                        "equals-hidden" => $this->request->session( "csrf-token" )
                    ]
                ],
                "cancel_subscription"
            )
        ) {
            return [ "Subscription:cancel", "DefaultView:redirect", null, "profile/settings/" ];
        }

        return [ "Settings:index", "Settings:index", null, $requestValidator->getErrors() ];
    }
}
