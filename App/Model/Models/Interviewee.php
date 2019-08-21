<?php

namespace Model\Models;

class Interviewee extends ProfileModel
{
	public function index()
	{
		if ( $this->validateAccount() ) {
			$intervieweeRepo = $this->load( "interviewee-repository" );
	        $this->interviewee = $intervieweeRepo->get( [ "*" ], [ "id" => $this->request->params( "id" ) ], "single" );

			$phoneRepo = $this->load( "phone-repository" );
	        $this->interviewee->phone = $phoneRepo->get( [ "*" ], [ "id" => $this->interviewee->phone_id ], "single" );

			// Get the interviewees image
			$imageRepo = $this->load( "image-repository" );
			$this->interviewee->image = $imageRepo->get( [ "*" ], [ "id" => $this->interviewee->image_id ], "single" );

	        // Retrieve all interviews for this interviewee
			$interviewRepo = $this->load( "interview-repository" );
	        $this->interviewee->interviews = $interviewRepo->get( [ "*" ], [ "interviewee_id" => $this->interviewee->id ] );

	        // Get all questons for each interview
			$deploymentTypeRepo = $this->load( "deployment-type-repository" );
			$positionRepo = $this->load( "position-repository" );
	        $interviewQuestionRepo = $this->load( "interview-question-repository" );

	        foreach ( $this->interviewee->interviews as $interview ) {
	            $interview->deploymentType = $deploymentTypeRepo->get( [ "*" ], [ "id" => $interview->deployment_type_id ], "single" );
	            $interview->position = $positionRepo->get( [ "*" ], [ "id" => $interview->position_id ], "single" );
	            $interview->questions = $interviewQuestionRepo->get( [ "*" ], [ "interview_id" => $interview->id ] );

				// Get all interview questions
				$intervieweeAnswerRepo = $this->load( "interviewee-answer-repository" );
	            foreach ( $interview->questions as $question ) {
	                $question->answer = $intervieweeAnswerRepo->get( [ "*" ], [ "interview_question_id" => $question->id ], "single" );
	            }
	        }

	        // Get all interview templates
			$interviewTemplateRepo = $this->load( "interview-template-repository" );
			$this->interviewTemplates = $interviewTemplateRepo->get( [ "*" ], [ "organization_id" => $this->organization->id ] );

	        $this->positions = $positionRepo->get( [ "*" ], [ "organization_id" => $this->organization->id ] );
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

	public function update()
	{
		if ( $this->validateAccount() ) {
			$intervieweeRepo = $this->load( "interviewee-repository" );

			$interviewee = $intervieweeRepo->get( [ "*" ], [ "id" => $this->request->params( "id" ) ], "single" );
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

	public function uploadImage()
	{
		if ( $this->validateAccount() ) {
			// Save the image file
			$imageManager = $this->load( "image-manager" );
			$image_filename = $imageManager->saveTo( "image" );

			// Save image data to database
			$imageRepo = $this->load( "image-repository" );
			$image = $imageRepo->insert([
				"filename" => $image_filename,
				"file_type" => $imageManager->getFileType()
			]);

			// Save image id to interviewee
			$intervieweeRepo = $this->load( "interviewee-repository" );
			$intervieweeRepo->update(
				[ "image_id" => $image->id ],
				[ "id" => $this->request->params( "id" ) ]
			);
		}
	}
}
