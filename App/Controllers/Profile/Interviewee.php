<?php

namespace Controllers\Profile;

use \Core\Controller;

class Interviewee extends Controller
{
    public function before()
    {
        $userAuth = $this->load( "user-authenticator" );
        $organizationRepo = $this->load( "organization-repository" );

        $this->user = $userAuth->getAuthenticatedUser();

        if ( is_null( $this->user ) ) {
            return [ null, "DefaultView:redirect", null, "sign-in" ];
        }

        $this->organization = $organizationRepo->get( [ "*" ], [ "id" => $this->user->current_organization_id ], "single" );

        // Ensure the current interviewee is owned by this organization
        $intervieweeRepo = $this->load( "interviewee-repository" );
        $interviewee = $intervieweeRepo->get( [ "*" ], [ "id" => $this->params[ "id" ], "organization_id" => $this->organization->id ], "single" );
        if ( is_null( $interviewee ) ) {
            return [ null, "Error:e404", null, null ];
        }
    }

    public function indexAction()
    {
        if ( !isset( $this->params[ "id" ] ) ) {
            return [ null, "DefaultView:redirect", null, "profile/" ];
        }

        $requestValidator = $this->load( "request-validator" );
        $intervieweeRepo = $this->load( "interviewee-repository" );
        $positionRepo = $this->load( "position-repository" );
        $interviewTemplateRepo = $this->load( "interview-template-repository" );

        if (
            $this->request->is( "post" ) &&
            $this->request->post( "update_interviewee" ) != "" &&
            $requestValidator->validate(
                $this->request,
                new \Model\Validations\PersonPhoneEmail( $this->request->session( "csrf-token" ) ),
                "update_interviewee"
            )
        ) {
            return [ "Interviewee:update", "Interviewee:redirect", null, "profile/interviewee/{$this->params[ "id" ]}/" ];
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
            return [ "Interview:deploy", "Interviewee:deployInterview", null, null ];
        }

        return [ "Interviewee:index", "Interviewee:index", null, $requestValidator->getErrors() ];
    }
}
