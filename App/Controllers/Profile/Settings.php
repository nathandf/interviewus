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
        $input = $this->load( "input" );
        $inputValidator = $this->load( "input-validator" );
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
            $input->exists( "get" ) &&
            $input->issetField( "add_payment_method" ) &&
            $inputValidator->validate(
                $input,
                [
                    "token" => [
                        "equals-hidden" => $this->session->getSession( "csrf-token" )
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
                $input->get( "payment_method_nonce" )
            );

            if ( $result->success ) {
                $this->session->addFlashMessage( "Payment Method Added" );
                $this->session->setFlashMessages();
                $this->view->redirect( "profile/settings/" );
            }

            $inputValidator->addError( "add_payment_method", $result->message );
        }

        if (
            $input->exists() &&
            $input->issetField( "update_organization" ) &&
            $inputValidator->validate(
                $input,
                [
                    "token" => [
                        "equals-hidden" => $this->session->getSession( "csrf-token" ),
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
            $organizationRepo = $this->load( "organization-repository" );
            $organizationRepo->update(
                [
                    "industry_id" => $input->get( "industry_id" ),
                    "name" => $input->get( "organization" )
                ],
                [ "id" => $this->organization->id ]
            );

            $this->session->addFlashMessage( "Organization updated" );
            $this->session->setFlashMessages();

            $this->view->redirect( "profile/settings/" );
        }

        if (
            $input->exists() &&
            $input->issetField( "update_default_payment_method" ) &&
            $inputValidator->validate(
                $input,
                [
                    "token" => [
                        "equals-hidden" => $this->session->getSession( "csrf-token" )
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
                $input->get( "braintree_payment_method_token" )
            );

            if ( !is_null( $result ) ) {
                // Unset all payment methods from default
                $paymentMethodRepo->update(
                    [ "is_default" => 0 ],
                    [ "account_id" => $this->account->id ]
                );

                // Set the payment method as default based on the token submitted
                $paymentMethodRepo->update(
                    [ "is_default" => 1 ],
                    [ "braintree_payment_method_token" => $input->get( "braintree_payment_method_token" ) ]
                );

                $this->session->addFlashMessage( "Payment Method Updated" );
                $this->session->setFlashMessages();

                $this->view->redirect( "profile/settings/" );
            }

            $inputValidator->addError( "update_default_payment_method", "Invalid Payment Method" );
            $inputValidator->setErrors();
        }

        if (
            $input->exists() &&
            $input->issetField( "remove_payment_method" ) &&
            $inputValidator->validate(
                $input,
                [
                    "token" => [
                        "equals-hidden" => $this->session->getSession( "csrf-token" )
                    ],
                    "braintree_payment_method_id" => [
                        "required" => true
                    ]
                ],
                "remove_payment_method"
            )
        ) {
            $this->session->addFlashMessage( "Payment Method Deleted" );
            $this->session->setFlashMessages();

            $this->view->redirect( "profile/settings/" );
        }

        if (
            $input->exists() &&
            $input->issetField( "cancel_subscription" ) &&
            $inputValidator->validate(
                $input,
                [
                    "token" => [
                        "equals-hidden" => $this->session->getSession( "csrf-token" )
                    ]
                ],
                "cancel_subscription"
            )
        ) {
            $braintreeSubscriptionRepo->delete( $this->account->braintree_subscription_id );

            // Update account to Free plan but do not provision
            $accountRepo = $this->load( "account-repository" );
            $accountRepo->update(
                [ "plan_id" => 11 ],
                [ "id" => $this->account->id ]
            );

            $this->session->addFlashMessage( "Subscription successfully canceled." );
            $this->session->setFlashMessages();

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
        $this->view->assign( "csrf_token", $this->session->generateCSRFToken() );
        $this->view->assign( "error_messages", $inputValidator->getErrors() );
        $this->view->assign( "flash_messages", $this->session->getFlashMessages() );

        $this->view->setTemplate( "profile/settings/index.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }
}
