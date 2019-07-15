<?php

namespace Controllers\Profile;

use \Core\Controller;

class Settings extends Controller
{
    public function before()
    {
        $userAuth = $this->load( "user-authenticator" );
        $countryRepo = $this->load( "country-repository" );
        $accountRepo = $this->load( "account-repository" );
        $accountUserRepo = $this->load( "account-user-repository" );
        $organizationRepo = $this->load( "organization-repository" );
        $this->industryRepo = $this->load( "industry-repository" );

        $this->user = $userAuth->getAuthenticatedUser();

        if ( is_null( $this->user ) ) {
            $this->view->redirect( "sign-in" );
        }

        $this->account = $accountRepo->get( [ "*" ], [ "id" => $this->user->current_account_id ], "single" );

        $this->organization = $organizationRepo->get( [ "*" ], [ "id" => $this->user->current_organization_id ], "single" );

        $this->view->assign( "countries", $countryRepo->get( [ "*" ] ) );
        $this->view->assign( "industry", $this->industryRepo->get( [ "*" ], [ "id" => $this->organization->industry_id ], "single" ) );
        $this->view->assign( "industries", $this->industryRepo->get( [ "*" ] ) );
        $this->view->assign( "account", $this->account );
        $this->view->assign( "organization", $this->organization );
        $this->view->assign( "user", $this->user );
    }

    public function indexAction()
    {

        $requestValidator = $this->load( "request-validator" );
        $braintreeClientTokenGenerator = $this->load( "braintree-client-token-generator" );
        $braintreeCustomerRepo = $this->load( "braintree-customer-repository" );
        $paymentMethodRepo = $this->load( "payment-method-repository" );
        $braintreePaymentMethodRepo = $this->load( "braintree-payment-method-repository" );
        $braintreeSubscriptionRepo = $this->load( "braintree-subscription-repository" );
        $planRepo = $this->load( "plan-repository" );
        $planDetailsRepo = $this->load( "plan-details-repository" );

        $plan = $planRepo->get( [ "*" ], [ "id" => $this->account->plan_id ], "single" );
        $plan->details = $planDetailsRepo->get( [ "*" ], [ "plan_id" => $plan->id ], "single" );

        $paymentMethods = $paymentMethodRepo->get( [ "*" ], [ "account_id" => $this->account->id ] );

        foreach ( $paymentMethods as $paymentMethod ) {
            $paymentMethod->braintreePaymentMethod = $braintreePaymentMethodRepo->get(
                $paymentMethod->braintree_payment_method_token
            );
        }

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
            $result = $braintreePaymentMethodRepo->create(
                $this->account->braintree_customer_id,
                $this->request->post( "payment_method_nonce" )
            );

            if ( $result->success ) {
                // Save this payment method and make it default if a payment
                // method doesn't exist with this token
                if (
                    empty(
                        $paymentMethodRepo->get(
                            [ "*" ],
                            [
                                "braintree_payment_method_token" => $result->paymentMethod->token,
                                "account_id" => $this->account->id
                            ],
                            "raw"
                        )
                    )
                ) {
                    $paymentMethodRepo->insert([
                        "account_id" => $this->account->id,
                        "braintree_payment_method_token" =>  $result->paymentMethod->token
                    ]);

                    // Unset all payment methods from default
                    $paymentMethodRepo->update(
                        [ "is_default" => 0 ],
                        [ "account_id" => $this->account->id ]
                    );

                    // Set the new payment method as default
                    $paymentMethodRepo->update(
                        [ "is_default" => 1 ],
                        [ "braintree_payment_method_token" => $result->paymentMethod->token ]
                    );

                    // If a subscription exists, make this payment method the default
                    if ( !is_null( $this->account->braintree_subscription_id ) ) {
                        $braintreeSubscriptionRepo->updatePaymentMethod(
                            $this->account->braintree_subscription_id,
                            $result->paymentMethod->token
                        );
                    }

                    $this->request->addFlashMessage( "success", "Payment Method Added" );
                    $this->request->setFlashMessages();
                }

                $this->view->redirect( "profile/settings/" );
            }

            $requestValidator->addError( "add_payment_method", $result->message );
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
                        "in_array" => $this->industryRepo->get( [ "id" ], [], "raw" )
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
            // Update payment method for this customer in braintree API
            $result = $braintreeCustomerRepo->updateDefaultPaymentMethod(
                $this->account->braintree_customer_id,
                $this->request->post( "braintree_payment_method_token" )
            );

            if ( !is_null( $result ) ) {
                // Unset all payment methods from default
                $paymentMethodRepo->update(
                    [ "is_default" => 0 ],
                    [ "account_id" => $this->account->id ]
                );

                // Set the new payment method as default
                $paymentMethodRepo->update(
                    [ "is_default" => 1 ],
                    [ "braintree_payment_method_token" => $this->request->post( "braintree_payment_method_token" ) ]
                );

                // If a subscription exists, make this payment method the default
                if ( !is_null( $this->account->braintree_subscription_id ) ) {
                    $braintreeSubscriptionRepo->updatePaymentMethod(
                        $this->account->braintree_subscription_id,
                        $this->request->post( "braintree_payment_method_token" )
                    );
                }

                $this->request->addFlashMessage( "success", "Default payment method updated" );
                $this->request->setFlashMessages();

                $this->view->redirect( "profile/settings/" );
            }

            $requestValidator->addError( "update_default_payment_method", "Invalid Payment Method" );
            $requestValidator->setErrors();
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
            if ( $braintreePaymentMethodRepo->delete(
                    $this->request->post(
                        "braintree_payment_method_token"
                    )
                )
            ) {
                $paymentMethodRepo->delete(
                    [ "braintree_payment_method_token", "is_default" ],
                    [ $this->request->post( "braintree_payment_method_token" ), 0 ]
                );
            }

            $this->request->addFlashMessage( "success", "Payment Method Deleted" );
            $this->request->setFlashMessages();

            $this->view->redirect( "profile/settings/" );
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
            $braintreeSubscriptionRepo->delete( $this->account->braintree_subscription_id );

            // Update account to Free plan but do not provision
            $accountRepo = $this->load( "account-repository" );
            $accountRepo->update(
                [
                    "plan_id" => 11,
                    "braintree_subscription_id" => ""
                ],
                [ "id" => $this->account->id ]
            );

            $this->request->addFlashMessage( "success", "Subscription successfully canceled." );
            $this->request->setFlashMessages();

            $this->view->redirect( "profile/settings/" );
        }

        $this->view->assign(
            "client_token",
            $braintreeClientTokenGenerator->generate(
                $this->account->braintree_customer_id
            )
        );

        $this->view->assign(
            "subscription",
            $braintreeSubscriptionRepo->get(
                $this->account->braintree_subscription_id
            )
        );

        $this->view->assign( "plan", $plan );
        $this->view->assign( "paymentMethods", $paymentMethods );
        $this->view->assign( "error_messages", $requestValidator->getErrors() );
        $this->view->assign( "flash_messages", $this->request->getFlashMessages() );

        $this->view->setTemplate( "profile/settings/index.tpl" );
        $this->view->render( "App/Views/Index.php" );
    }
}
