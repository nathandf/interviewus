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
        $braintreeCustomerRepo = $this->load( "braintree-customer-repository" );

        $creditCards = $braintreeCustomerRepo->get( $this->account->braintree_customer_id )->creditCards;

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
                    "braintree_payment_method_id" => [
                        "required" => true
                    ]
                ],
                "remove_payment_method"
            )
        ) {

        }
        
        $this->view->assign( "creditCards", $creditCards );

        $this->view->setTemplate( "profile/billing/index.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }
}
