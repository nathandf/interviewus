<?php

namespace Controllers;

use \Core\Controller;

class SignUp extends Controller
{
    public function before()
    {
        $this->view->assign( "csrf_token", $this->session->generateCSRFToken() );
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

                // Create new Account
                $account = $accountRepo->insert([
                    "account_type_id" => 1
                ]);

                // Create new AccountUser
                $accountUser = $accountUserRepo->insert([
                    "account_id" => $account->id,
                    "user_id" => $user->id
                ]);

                // Send welcome and confirmation email
                // TODO Send welcome and confirmation email

                // Authenticate and log in User
                $userAuth = $this->load( "user-authenticator" );
                $userAuth->logIn( $user->email, $input->get( "password" ) );

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
        $this->view->setErrorMessages( $inputValidator->getErrors() );

        $this->view->setTemplate( "sign-up/index.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }
}
