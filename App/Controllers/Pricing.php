<?php

namespace Controllers;

use \Core\Controller;

class Pricing extends Controller
{
    public function before()
    {
        $userAuth = $this->load( "user-authenticator" );
        $countryRepo = $this->load( "country-repository" );
        $accountRepo = $this->load( "account-repository" );
        $accountUserRepo = $this->load( "account-user-repository" );
        $organizationRepo = $this->load( "organization-repository" );
        $cartRepo = $this->load( "cart-repository" );
        $productRepo = $this->load( "product-repository" );
        $planRepo = $this->load( "plan-repository" );

        $this->user = $userAuth->getAuthenticatedUser();
        $this->account = null;
        $this->organization = null;
        $this->cart = null;

        if ( !is_null( $this->user ) ) {
            $this->account = $accountRepo->get( [ "*" ], [ "id" => $this->user->current_account_id ], "single" );
            $this->organization = $organizationRepo->get( [ "*" ], [ "id" => $this->user->current_organization_id ], "single" );
        }

        $this->view->assign( "countries", $countryRepo->get( [ "*" ] ) );
        $this->view->assign( "account", $this->account );
        $this->view->assign( "organization", $this->organization );
        $this->view->assign( "user", $this->user );
    }

    public function indexAction()
    {
        $requestValidator = $this->load( "request-validator" );
        $planRepo = $this->load( "plan-repository" );
        $planDetailsRepo = $this->load( "plan-details-repository" );

        $plans = $planRepo->get( [ "*" ] );

        foreach ( $plans as $plan ) {
            $plan->details = $planDetailsRepo->get( [ "*" ], [ "plan_id" => $plan->id ], "single" );
        }

        if (
            $this->request->is( "post" ) &&
            $this->request->post( "add_to_cart" ) != "" &&
            $requestValidator->validate(
                $this->request,
                [
                    "token" => [
                        "required" => true,
                        "equals-hidden" => $this->request->session( "csrf-token" )
                    ],
                    "plan_id" => [
                        "required" => true,
                        "in_array" => $planRepo->get( [ "id" ], [], "raw" )
                    ],
                    "billing_frequency" => [
                        "required" => true,
                        "in_array" => [ "annually", "monthly" ]
                    ]
                ],
                "add_to_cart"
            )
        ) {
            if ( !is_null( $this->user ) ) {
                $cartRepo = $this->load( "cart-repository" );
                $productRepo = $this->load( "product-repository" );

                // Get existing cart
                $cart = $cartRepo->get( [ "*" ], [ "account_id" => $this->account->id ], "single" );

                // If no cart exists, create a new one
                if ( is_null( $cart ) ) {
                    $cart = $cartRepo->insert([
                        "account_id" => $this->account->id
                    ]);
                }

                // Get all products for this cart
                $cart->products = $productRepo->get( [ "*" ], [ "cart_id" => $cart->id ] );

                // Update all products in cart
                foreach ( $cart->products as $product ) {
                    $productRepo->delete( [ "id" ], [ $product->id ] );
                }

                // Add new product to the cart
                $product = $productRepo->insert([
                    "cart_id" => $cart->id,
                    "plan_id" => $this->request->post( "plan_id" ),
                    "billing_frequency" => $this->request->post( "billing_frequency" )
                ]);

                $this->view->redirect( "cart/" );
            }

            $requestValidator->addError( "add_to_cart", "Invalid Action. You must be logged in." );
        }

        $this->view->assign( "plans", $plans );
        $this->view->assign( "error_messages", $requestValidator->getErrors() );

        $this->view->setTemplate( "pricing/index.tpl" );
        $this->view->render( "App/Views/Index.php" );
    }

    public function signIn()
    {

        $requestValidator = $this->load( "request-validator" );

        if (
            $this->request->is( "post" ) &&
            $this->request->post( "sign_in" ) != "" &&
            $requestValidator->validate(
                $this->request,
                [
                    "email" => [
                        "required" => true,
                        "email" => true
                    ],
                    "password" => [
                        "required" => true,
                        "min" => 3
                    ]
                ],
                "ajax_sign_in"
            )
        ) {
            $userAuth = $this->load( "user-authenticator" );
            $userAuth->authenticate( $this->request->post( "email" ), $this->request->post( "password" ) );
            $user = $userAuth->getAuthenticatedUser();

            !is_null( $user ) ? echod( json_encode( $user ) ) : echod( json_encode( [ "errors" => "Invalid Credentials" ]) );
            return;
        }
        echo( json_encode( [ "errors" => $requestValidator->errors[ "ajax_sign_in" ] ] ) );

        return;
    }

    public function createAccount()
    {
        $requestValidator = $this->load( "request-validator" );

        if (
            $this->request->is( "post" ) &&
            $this->request->post( "create_account" ) != "" &&
            $requestValidator->validate(
                $this->request,
                [
                    "name" => [
                        "required" => true
                    ],
                    "email" => [
                        "required" => true,
                        "email" => true
                    ],
                    "password" => [
                        "required" => true,
                        "min" => 6
                    ]
                ],
                "create_account"
            )
        ) {
            $userRepo = $this->load( "user-repository" );
            $accountRepo = $this->load( "account-repository" );
            $accountUserRepo = $this->load( "account-user-repository" );
            $organizationRepo = $this->load( "organization-repository" );
            $organizationUserRepo = $this->load( "organization-user-repository" );

            // Ensure email is unique and create the new account, and user.
            if ( !in_array( $this->request->post( "email" ), $userRepo->get( [ "email" ], [], "raw" ) ) ) {

                //Create new User
                $user = $userRepo->insert([
                    "role" => "owner",
                    "first_name" => trim( $this->request->post( "name" ) ),
                    "email" => strtolower( trim( $this->request->post( "email" ) ) ),
                    "password" => password_hash( trim( $this->request->post( "password" ) ), PASSWORD_BCRYPT )
                ]);

                // Update the first and last name
                if ( count( explode( " ", $this->request->post( "name" ) ) ) > 1 ) {
                    $user->setNames( $user->first_name );
                    $userRepo->update(
                        [
                            "first_name" => $user->getFirstName(),
                            "last_name" => $user->getLastName()
                        ],
                        [
                            "id" => $user->id
                        ]
                    );
                }

                // Create new Account
                $account = $accountRepo->insert([
                    "account_type_id" => 1,
                    "user_id" => $user->id,
                    "plan_id" => 1
                ]);

                // Provision Account
                $accountProvisioner = $this->load( "account-provisioner" );
                $accountProvisioner->provision( $account );

                // Update the account back to free to restrict access to premium
                // features. This will not remove the extra interviews they were
                // just provided.
                $accountRepo->update(
                    [ "plan_id" => 11 ],
                    [ "id" => $account->id ]
                );

                // Create braintree customer
                $braintreeCustomerRepo = $this->load( "braintree-customer-repository" );
                $braintreeCustomer = $braintreeCustomerRepo->create( $user )->customer;

                // Update Account's braintree_customer_id
                $accountRepo->update(
                    [ "braintree_customer_id" => $braintreeCustomer->id ],
                    [ "id" => $account->id ]
                );

                // Update current_account_id to new account_id
                $userRepo->update(
                    [ "current_account_id" => $account->id ],
                    [ "id" => $user->id ]
                );

                // Create new AccountUser
                $accountUser = $accountUserRepo->insert([
                    "account_id" => $account->id,
                    "user_id" => $user->id
                ]);

                // Create new Organization
                $organization = $organizationRepo->insert([
                    "account_id" => $account->id,
                    "name" => "My Organization",
                    "user_id" => $user->id
                ]);

                // Update current_organization_id to new organization_id
                $userRepo->update(
                    [ "current_organization_id" => $organization->id ],
                    [ "id" => $user->id ]
                );

                // Create new OrganizationUser
                $organizationUser = $organizationUserRepo->insert([
                    "organization_id" => $organization->id,
                    "user_id" => $user->id
                ]);

                // Send welcome and confirmation email
                $mailer = $this->load( "mailer" );
                $emailBuilder = $this->load( "email-builder" );
                $domainObjectFactory = $this->load( "domain-object-factory" );

                $emailContext = $domainObjectFactory->build( "EmailContext" );
                $emailContext->addProps([
                    "first_name" => $user->getFirstName()
                ]);

                $resp = $mailer->setTo( $user->email, $user->getFullName() )
                    ->setFrom( "getstarted@interviewus.net", "InterviewUs" )
                    ->setSubject( "Welcome to InterviewUs!" )
                    ->setContent( $emailBuilder->build( "welcome-email.html", $emailContext ) )
                    ->mail();

                // Authenticate and log in User
                $userAuth = $this->load( "user-authenticator" );
                $userAuth->authenticate( $user->email, $this->request->post( "password" ) );

                $user = $userAuth->getAuthenticatedUser();

                echod( json_encode( $user ) );

                return;
            }

            echod( json_encode( [ "errors" => "Email unavailable" ] ) );

            return;
        }

        echod( json_encode( [ "errors" => $requestValidator->errors[ "create_account" ] ] ) );

        return;
    }
}
