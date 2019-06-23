<?php

namespace Controllers;

use \Core\Controller;

class SignUp extends Controller
{
    public function before()
    {

    }

    public function indexAction()
    {
        $input = $this->load( "input" );
        $inputValidator = $this->load( "input-validator" );

        // Form validation
        if (
            $input->exists() &&
            $input->issetField( "create_account" ) &&
            $inputValidator->validate(
                $input,
                [
                    "token" => [
                        "required" => true,
                        "equals-hidden" => $this->session->getSession( "csrf-token" )
                    ],
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
            if ( !in_array( $input->get( "email" ), $userRepo->get( [ "email" ], [], "raw" ) ) ) {

                //Create new User
                $user = $userRepo->insert([
                    "role" => "owner",
                    "first_name" => trim( $input->get( "name" ) ),
                    "email" => strtolower( trim( $input->get( "email" ) ) ),
                    "password" => password_hash( trim( $input->get( "password" ) ), PASSWORD_BCRYPT )
                ]);

                // Update the first and last name
                if ( count( explode( " ", $input->get( "name" ) ) ) > 1 ) {
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

                // Create new Account with an upgraded plan to give user access
                // to free interviews
                $account = $accountRepo->insert([
                    "account_type_id" => 1,
                    "plan_id" => 1
                ]);

                // Provision Account
                $accountProvisioner = $this->load( "account-provisioner" );
                $accountProvisioner->provision( $account->id );

                // Update the account back to free to restrict access to premium
                // features. This will not remove the extra interviews they were
                // just provided.
                $accountRepo->update(
                    [ "plan_id" => 11 ],
                    [ "id" => $account->id ]
                );

                // Create braintree customer from a person
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
                    ->setSubject( "Here's 9 Free interviews on Us. Welcome to InterviewUs!" )
                    ->setContent( $emailBuilder->build( "welcome-email.html", $emailContext ) )
                    ->mail();

                // Authenticate and log in User
                $userAuth = $this->load( "user-authenticator" );
                $userAuth->authenticate( $user->email, $input->get( "password" ) );

                // Redirect to profile
                $this->view->redirect( "profile/" );
            }

            $inputValidator->addError( "create_account", "This email seems to be unavailable. Please try another!" );
        }

        // Form field data that was submitted
        $fields = [];

        if ( $input->issetField( "create_account" ) ) {
            $fields[ "create_account" ][ "name" ] = $input->get( "name" );
            $fields[ "create_account" ][ "email" ] = $input->get( "email" );
            $fields[ "create_account" ][ "password" ] = $input->get( "password" );
        }

        $this->view->assign( "fields", $fields );
        $this->view->assign( "csrf_token", $this->session->generateCSRFToken() );
        $this->view->setErrorMessages( $inputValidator->getErrors() );

        $this->view->setTemplate( "sign-up/index.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }
}
