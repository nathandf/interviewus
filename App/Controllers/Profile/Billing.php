<?php

namespace Controllers\Profile;

use \Core\Controller;

class Billing extends Controller
{
    public function before()
    {
        $userAuth = $this->load( "user-authenticator" );
        $countryRepo = $this->load( "country-repository" );
        $accountRepo = $this->load( "account-repository" );
        $accountUserRepo = $this->load( "account-user-repository" );
        $organizationRepo = $this->load( "organization-repository" );

        $this->user = $userAuth->getAuthenticatedUser();

        if ( is_null( $this->user ) ) {
            $this->view->redirect( "sign-in" );
        }

        $this->account = $accountRepo->get( [ "*" ], [ "id" => $this->user->current_account_id ], "single" );

        $this->organization = $organizationRepo->get( [ "*" ], [ "id" => $this->user->current_organization_id ], "single" );

        $this->view->assign( "countries", $countryRepo->get( [ "*" ] ) );
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
        $braintreePaymentMethodRepo = $this->load( "braintree-payment-method-repository" );
        $braintreeSubscriptionRepo = $this->load( "braintree-subscription-repository" );
        $planRepo = $this->load( "plan-repository" );
        $planDetailsRepo = $this->load( "plan-details-repository" );

        $plan = $planRepo->get( [ "*" ], [ "id" => $this->account->plan_id ], "single" );
        $plan->details = $planDetailsRepo->get( [ "*" ], [ "plan_id" => $plan->id ], "single" );

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
                $this->view->redirect( "profile/billing/" );
            }

            $inputValidator->addError( "add_payment_method", $result->message );
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

            $this->view->redirect( "profile/billing/" );
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

            $this->view->redirect( "profile/billing/" );
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
        $this->view->assign( "csrf_token", $this->session->generateCSRFToken() );
        $this->view->assign( "error_messages", $inputValidator->getErrors() );
        $this->view->assign( "flash_messages", $this->session->getFlashMessages() );

        $this->view->setTemplate( "profile/billing/index.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }
}
