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
        $paymentMethodRepo = $this->load( "payment-method-repository" );

        if (
            $input->exists() &&
            $input->issetField( "add_payment_method" ) &&
            $inputValidator->validate(
                $input,
                [
                    "token" => [
                        "equals-hidden" => $this->session->getSession( "csrf-token" )
                    ],
                    "cc" => [
                        "required" => true
                    ]
                ],
                "add_payment_method"
            )
        ) {

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
                    "payment_method_id" => [
                        "required" => true,
                        "in_array" => $paymentMethodRepo->get( [ "id" ], [ "account_id" => $this->account->id ], "raw" )
                    ]
                ],
                "remove_payment_method"
            )
        ) {
            $paymentMethods = $paymentMethodRepo->get( [ "*" ], [ "account_id" => $this->account->id ] );

            if ( count( $pamentMethods ) > 1 ) {
                $paymentMethodRepo->delete( [ "id" ], [ $input->get( "payment_method_id" ) ] );
                $this->view->redirect( "profile/billing/" );
            }

            $inputValidator->addError( "remove_payment_method", "You cannot remove the only payment method on the account. Add a new payment method to remove the current payemnt method" );
        }

        $this->view->setTemplate( "profile/billing/index.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }
}
