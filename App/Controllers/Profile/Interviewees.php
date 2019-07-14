<?php

namespace Controllers\Profile;

use \Core\Controller;

class Interviewees extends Controller
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
        $intervieweeRepo = $this->load( "interviewee-repository" );
        $phoneRepo = $this->load( "phone-repository" );

        $requestValidator = $this->load( "request-validator" );

        $interviewees = array_reverse( $intervieweeRepo->get( [ "*" ], [ "organization_id" => $this->organization->id ] ) );

        foreach ( $interviewees as $interviewee ) {
            $interviewee->phone = $phoneRepo->get( [ "*" ], [ "id" => $interviewee->phone_id ], "single" );
        }

        if (
            $this->request->is( "post" ) &&
            $this->request->post( "new_interviewee" ) != "" &&
            $requestValidator->validate(
                $this->request,
                [
                    "token" => [
                        "required" => true,
                        "equals-hidden" => $this->request->session( "csrf-token" )
                    ],
                    "name" => [
                        "required" => true
                    ],
                    "email" => [
                        "required" => true,
                        "email" => true
                    ],
                    "country_code" => [
                        "required" => true
                    ],
                    "national_number" => [
                        "required" => true,
                        "phone" => true
                    ]
                ],
                "new_interviewee"
                )
        ) {
            return [ "Interviewee:create", "Interviewee:create", null, null ];
        }

        $this->view->assign( "interviewees", $interviewees );
        $this->view->setErrorMessages( $requestValidator->getErrors() );

        $this->view->setTemplate( "profile/interviewees/index.tpl" );
        $this->view->render( "App/Views/Index.php" );
    }
}
