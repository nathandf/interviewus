<?php

namespace Model\Models;

class Interviewee extends ProfileModel
{
	public function index( array $data )
	{
		if ( $this->validateAccount() ) {
			$interviewee_id = $data[ 0 ];
			$organization_id = $data[ 1 ];

			$intervieweeRepo = $this->load( "interviewee-repository" );
	        $interviewTemplateRepo = $this->load( "interview-template-repository" );
	        $interviewQuestionRepo = $this->load( "interview-question-repository" );
	        $positionRepo = $this->load( "position-repository" );
	        $interviewRepo = $this->load( "interview-repository" );
	        $intervieweeAnswerRepo = $this->load( "interviewee-answer-repository" );
	        $deploymentTypeRepo = $this->load( "deployment-type-repository" );
	        $phoneRepo = $this->load( "phone-repository" );
	        $this->interviewee = $intervieweeRepo->get( [ "*" ], [ "id" => $interviewee_id ], "single" );
	        $this->interviewee->phone = $phoneRepo->get( [ "*" ], [ "id" => $this->interviewee->phone_id ], "single" );

	        // Retrieve all interviews for this interviewee
	        $this->interviewee->interviews = $interviewRepo->get( [ "*" ], [ "interviewee_id" => $this->interviewee->id ] );

	        // Get all questons for each interview
	        foreach ( $this->interviewee->interviews as $interview ) {
	            $interview->deploymentType = $deploymentTypeRepo->get( [ "*" ], [ "id" => $interview->deployment_type_id ], "single" );
	            $interview->position = $positionRepo->get( [ "*" ], [ "id" => $interview->position_id ], "single" );
	            $interview->questions = $interviewQuestionRepo->get( [ "*" ], [ "interview_id" => $interview->id ] );
	            // Get all interview questions
	            foreach ( $interview->questions as $question ) {
	                $question->answer = $intervieweeAnswerRepo->get( [ "*" ], [ "interview_question_id" => $question->id ], "single" );
	            }
	        }

	        // Get all interview templates
	        $this->interviewTemplates = $interviewTemplateRepo->get( [ "*" ], [ "organization_id" => $organization_id ] );

	        $this->positions = $positionRepo->get( [ "*" ], [ "organization_id" => $organization_id ] );
		}
	}

	public function create()
	{
		if ( $this->validateAccount() ) {
			$phoneRepo = $this->load( "phone-repository" );
			$intervieweeRepo = $this->load( "interviewee-repository" );

			$phone = $phoneRepo->insert([
				"country_code" => $this->request->post( "country_code" ),
				"national_number" => $this->request->post( "national_number" ),
				"e164_phone_number" => "+" . $this->request->post( "country_code" ) . $this->request->post( "national_number" )
			]);

			$this->interviewee = $intervieweeRepo->insert([
				"organization_id" => $this->organization->id,
				"first_name" => $this->request->post( "name" ),
				"email" => $this->request->post( "email" ),
				"phone_id" => $phone->id
			]);

			// Update the first and last name
			$this->interviewee->setNames( $this->interviewee->first_name );

			if (
				!is_null( $this->interviewee->getFirstName() ) &&
				!is_null( $this->interviewee->getLastName() )
			) {
				$intervieweeRepo->update(
					[
						"first_name" => $this->interviewee->getFirstName(),
						"last_name" => $this->interviewee->getLastName()
					],
					[
						"id" => $this->interviewee->id
					]
				);
			}
		}
	}

	public function update( $interviewee_id )
	{
		if ( $this->validateAccount() ) {
			$intervieweeRepo = $this->load( "interviewee-repository" );

			$interviewee = $intervieweeRepo->get( [ "*" ], [ "id" => $interviewee_id ], "single" );
			$intervieweeRepo->update(
				[
					"first_name" => $this->request->post( "first_name" ),
					"last_name" => $this->request->post( "last_name" ),
					"email" => $this->request->post( "email" )
				],
				[ "id" => $interviewee->id ]
			);

			$phoneRepo = $this->load( "phone-repository" );
			$phoneRepo->update(
				[
					"country_code" => $this->request->post( "country_code" ),
					"national_number" => $this->request->post( "national_number" )
				],
				[ "id" => $interviewee->phone_id ]
			);

			$this->request->addFlashMessage( "success", "Interviewee Updated" );
			$this->request->setFlashMessages();
		}
	}
}
