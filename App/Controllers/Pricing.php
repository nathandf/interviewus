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

        $this->user = $userAuth->getAuthenticatedUser();
        $this->account = null;
        $this->organization = null;

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
        $planRepo = $this->load( "plan-repository" );
        $planDetailsRepo = $this->load( "plan-details-repository" );


        $plans = $planRepo->get( [ "*" ] );

        foreach ( $plans as $plan ) {
            $plan->details = $planDetailsRepo->get( [ "*" ], [ "plan_id" => $plan->id ], "single" );
        }

        $this->view->assign( "plans", $plans );
        $this->view->assign( "csrf_token", $this->session->generateCSRFToken() );

        $this->view->setTemplate( "pricing/index.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }

    public function signIn()
    {
        $input = $this->load( "input" );
        $inputValidator = $this->load( "input-validator" );

        if (
            $input->exists() &&
            $input->issetField( "sign_in" ) &&
            $inputValidator->validate(
                $input,
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
            $userAuth->authenticate( $input->get( "email" ), $input->get( "password" ) );
            $user = $userAuth->getAuthenticatedUser();

            !is_null( $user ) ? echod( json_encode( $user ) ) : echod( "Invalid Credentials" );
            return;
        }
        echo( implode( ", ", $inputValidator->errors[ "ajax_sign_in" ] ) );

        return;
    }

    public function createAccount()
    {
        $input = $this->load( "input" );
        $inputValidator = $this->load( "input-validator" );

        if (
            $input->exists() &&
            $input->issetField( "create_account" ) &&
            $inputValidator->validate(
                $input,
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
                // TODO Send welcome and confirmation email

                // Authenticate and log in User
                $userAuth = $this->load( "user-authenticator" );
                $userAuth->authenticate( $user->email, $input->get( "password" ) );

                $user = $userAuth->getAuthenticatedUser();

                echod( json_encode( $user ) );

                return;
            }

            echod( "Email unavailable" );

            return;
        }

        echod( implode( ", ", $inputValidator->errors[ "create_account" ] ) );
        return;
    }
}
