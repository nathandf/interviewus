<?php

namespace Controllers;

use \Core\Controller;

class Profile extends Controller
{
    public function before()
    {
        $userAuth = $this->load( "user-authenticator" );
        $this->accountRepo = $this->load( "account-repository" );
        $accountUserRepo = $this->load( "account-user-repository" );
        $organizationRepo = $this->load( "organization-repository" );

        $this->user = $userAuth->getAuthenticatedUser();

        if ( is_null( $this->user ) ) {
            return [ null, "DefaultView:redirect", null, "sign-in" ];
        }

        $this->account = $this->accountRepo->get( [ "*" ], [ "id" => $this->user->current_account_id ], "single" );
        $this->organization = $organizationRepo->get( [ "*" ], [ "id" => $this->user->current_organization_id ], "single" );
    }

    public function indexAction()
    {
        $requestValidator = $this->load( "request-validator" );
        $interviewTemplateRepo = $this->load( "interview-template-repository" );
        $intervieweeRepo = $this->load( "interviewee-repository" );
        $positionRepo = $this->load( "position-repository" );

        if (
            $this->request->is( "post" ) &&
            $this->request->post( "new_interviewee" ) != "" &&
            $requestValidator->validate(
                $this->request,
                new \Model\Validations\Interviewee( $this->request->session( "csrf-token" ) ),
                "new_interviewee"
                )
        ) {

            return [ "Interviewee:create", "Interviewee:create", null, null ];
        }

        if (
            $this->request->is( "post" ) &&
            $this->request->post( "new_interview_template" ) != "" &&
            $requestValidator->validate(
                $this->request,
                new \Model\Validations\InterviewTemplate( $this->request->session( "csrf-token" ) ),
                "new_interview_template"
            )
        ) {

            return [ "InterviewTemplate:create", "InterviewTemplate:create", null, null ];
        }

        if (
            $this->request->is( "post" ) &&
            $this->request->post( "deploy-interview" ) != "" &&
            $requestValidator->validate(
                $this->request,
                new \Model\Validations\InterviewDeployment(
                    $this->request->session( "csrf-token" ),
                    $intervieweeRepo->get( [ "id" ], [ "organization_id" => $this->organization->id ], "raw" ),
                    $positionRepo->get( [ "id" ], [ "organization_id" => $this->organization->id ], "raw" ),
                    $interviewTemplateRepo->get( [ "id" ], [ "organization_id" => $this->organization->id ], "raw" )
                ),
                "deploy_interview"
            )
        ) {
            return [ "Interview:deploy", "Profile:deployInterview", null, null ];
        }

        return [ "Profile:index", "Profile:showAll", null, $requestValidator->getErrors() ];
    }

    public function archiveAction()
    {
        $requestValidator = $this->load( "request-validator" );
        $interviewRepo = $this->load( "interview-repository" );

        if (
            $this->request->is( "post" ) &&
            $requestValidator->validate(
                $this->request,
                new \Model\Validations\ArchiveInterview(
                    $this->request->session( "csrf-token" ),
                    $interviewRepo->get(
                        [ "id" ],
                        [
                            "id" => $this->request->post( "interview_id" ),
                            "organization_id" => $this->organization->id
                        ],
                        "raw"
                    )
                ),
                "archive"
            )
        ) {
            return [ "Interview:archive", "Profile:respondWithJson", null, "success" ];
        }

        return [ null, "Profile:respondWithJson", null, $requestValidator->getErrors()[ "archive" ][ 0 ] ];
    }

    public function shareInterviewAction()
    {
        $requestValidator = $this->load( "request-validator" );
        $interviewRepo = $this->load( "interview-repository" );

        if (
            $this->request->is( "post" ) &&
            $requestValidator->validate(
                $this->request,
                new \Model\Validations\ShareInterview(
                    $this->request->session( "csrf-token" ),
                    $interviewRepo->get(
                        [ "id" ],
                        [
                            "id" => $this->request->post( "interview_id" ),
                            "organization_id" => $this->organization->id
                        ],
                        "raw"
                    )
                ),
                "share"
            )
        ) {

            return [ "Profile:shareInterview", "Profile:respondWithJson", null, "success"  ];
        }

        if ( isset( $requestValidator->getErrors()[ "share" ] ) == true ) {

            return [ null, "Profile:respondWithJson", null, $requestValidator->getErrors()[ "share" ][ 0 ]  ];
        }

        return [ null, "Profile:respondWithJson", null, "An unknown error has occured"  ];
    }

    public function logout()
    {
        return [ "Profile:logout", "Home:redirect", null, "" ];
    }
}
