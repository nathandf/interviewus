<?php

namespace Controllers\Profile;

use \Core\Controller;

class Interviewee extends Controller
{
    public function before()
    {
        $userAuth = $this->load( "user-authenticator" );
        $organizationRepo = $this->load( "organization-repository" );
        $intervieweeRepo = $this->load( "interviewee-repository" );

        $this->user = $userAuth->getAuthenticatedUser();

        if ( is_null( $this->user ) ) {
            return [ null, "DefaultView:redirect", null, "sign-in" ];
        }

        $this->organization = $organizationRepo->get( [ "*" ], [ "id" => $this->user->current_organization_id ], "single" );

        // Ensure the current interviewee is owned by this organization
        if (
            isset( $this->params[ "id" ] ) &&
            !in_array(
                $this->params[ "id" ],
                $intervieweeRepo->get( [ "id" ], [ "organization_id" => $this->organization->id ], "raw" )
            )
        ) {
            return [ null, "DefaultView:redirect", null, "profile/" ];
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
                [
                    "token" => [
                        "required" => true,
                        "equals-hidden" => $this->request->session( "csrf-token" )
                    ],
                    "first_name" => [
                        "required" => true,
                        "max" => 128
                    ],
                    "last_name" => [
                        "max" => 128
                    ],
                    "email" => [
                        "required" => true,
                        "email" => true
                    ],
                    "country_code" => [
                        "required" => true,
                        "number" => true
                    ],
                    "national_number" => [
                        "required" => true
                    ]
                ],
                "update_interviewee"
            )
        ) {
            return [ "Interviewee:update", "Interviewee:redirect", $this->params[ "id" ], "profile/interviewee/{$this->params[ "id" ]}/" ];
        }

        if (
            $this->request->is( "post" ) &&
            $this->request->post( "deploy-interview" ) != "" &&
            $requestValidator->validate(
                $this->request,
                [
                    "token" => [
                        "equals-hidden" => $this->request->session( "csrf-token" ),
                        "required" => true
                    ],
                    "deployment_type_id" => [
                        "required" => true,
                        "in_array" => [ 1, 2 ]
                    ],
                    "interviewee_id" => [
                        "required" => true,
                        "in_array" => $intervieweeRepo->get( [ "id" ], [ "organization_id" => $this->organization->id ], "raw" )
                    ],
                    "position_id" => [
                        "in_array" => $positionRepo->get( [ "id" ], [ "organization_id" => $this->organization->id ], "raw" )
                    ],
                    "interview_template_id" => [
                        "required" => true,
                        "in_array" => $interviewTemplateRepo->get( [ "id" ], [ "organization_id" => $this->organization->id ], "raw" )
                    ],
                    "schedule_type" => [
                        "required" => true,
                        "in_array" => [ 1, 2 ]
                    ],
                    "date" => [],
                    "Hour" => [],
                    "Minute" => [],
                    "Meridian" => []
                ],
                "deploy_interview"
            )
        ) {
            return [ "Interview:deploy", "Interviewee:deployInterview", null, null ];
        }

        return [ "Interviewee:index", "Interviewee:index", [ $this->params[ "id" ], $this->organization->id ], $requestValidator->getErrors() ];
    }
}
