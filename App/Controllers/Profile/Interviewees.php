<?php

namespace Controllers\Profile;

use \Core\Controller;

class Interviewees extends Controller
{
    public function before()
    {
        $countryRepo = $this->load( "country-repository" );
        $organizationRepo = $this->load( "organization-repository" );

        $this->organization = $organizationRepo->get( [ "*" ], [ "id" => 0 ], "single" );

        $this->view->assign( "countries", $countryRepo->get( [ "*" ] ) );
    }

    public function indexAction()
    {
        $intervieweeRepo = $this->load( "interviewee-repository" );
        $phoneRepo = $this->load( "phone-repository" );
        $input = $this->load( "input" );
        $inputValidator = $this->load( "input-validator" );

        $interviewees = $intervieweeRepo->get( [ "*" ], [ "organization_id" => $this->organization->id ] );

        if (
            $input->exists() &&
            $input->issetField( "new_interviewee" ) &&
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
            $phone = $phoneRepo->insert([
                "country_code" => $input->get( "country_code" ),
                "national_number" => $input->get( "national_number" ),
                "e164_phone_number" => "+" . $input->get( "country_code" ) . $input->get( "national_number" )
            ]);

            $interviewee = $intervieweeRepo->insert([
                "organization_id" => $this->organization->id,
                "first_name" => $input->get( "name" ),
                "email" => $input->get( "email" ),
                "phone_id" => $phone->id
            ]);

            // Update the first and last name
            $interviewee->setNames( $interviewee->first_name );

            if (
                !is_null( $interviewee->getFirstName() ) &&
                !is_null( $interviewee->getLastName() )
            ) {
                $intervieweeRepo->update(
                    [
                        "first_name" => $interviewee->getFirstName(),
                        "last_name" => $interviewee->getLastName()
                    ],
                    [
                        "id" => $interviewee->id
                    ]
                );
            }

            $this->view->redirect( "profile/interviewee/" . $interviewee->id . "/" );
        }

        $this->view->assign( "interviewees", $interviewees );
        $this->view->setErrorMessages( $inputValidator->getErrors() );
        $this->view->assign( "csrf_token", $this->session->generateCSRFToken() );

        $this->view->setTemplate( "profile/interviewees/index.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }
}
