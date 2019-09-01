<?php

namespace Model\Models;

class Interview extends ProfileModel
{
	public $errors = [];

	public function archive()
	{
		if ( $this->validateAccount() ) {
			$interviewRepo = $this->load( "interview-repository" );

			$interviewRepo->update(
				[ "mode" => "archived" ],
				[ "id" => $this->request->post( "interview_id" ) ]
			);
		}
	}

	public function deploy()
	{
		if ( $this->validateAccount() ) {
			$positionRepo = $this->load( "position-repository" );
			$position = $positionRepo->get( [ "*" ], [ "id" => $this->request->post( "position_id" ) ], "single" );

			// Create a new position if the one submitted does not exist
            if ( $this->request->post( "position" ) != "" ) {
                $position = $positionRepo->insert([
                    "organization_id" => $this->organization->id,
                    "name" => $this->request->post( "position" )
                ]);
            }

			$deploymentTypeRepo = $this->load( "deployment-type-repository" );
            $deploymentType = $deploymentTypeRepo->get( [ "*" ], [ "id" => $this->request->post( "deployment_type_id" ) ], "single" );

			$intervieweeRepo = $this->load( "interviewee-repository" );
			$this->interviewee = $intervieweeRepo->get(
				[ "*" ],
				[
					"id" => $this->request->post( "interviewee_id" ),
					"organization_id" => $this->organization->id
				],
				"single"
			);

			$interviewTemplateRepo = $this->load( "interview-template-repository" );
			$this->interviewTemplate = $interviewTemplateRepo->get(
				[ "*" ],
				[
					"id" => $this->request->post( "interview_template_id" ),
					"organization_id" => $this->organization->id
				],
				"single"
			);

			// Ensure account has enough of the correct interview credits to create
			// this interview
            if ( $this->account->validateInterviewCredit( $deploymentType ) ) {
                // Build and dispatch the interview. Will return null if insufficient
                // interview credits in the account
                $interviewBuilder = $this->load( "interview-builder" );

                // Set scheduled time and status if deploying later. Default status
                // is "active"
                if (
                    $this->request->post( "schedule_type" ) == 2 &&
                    $this->request->post( "date" ) != ""
                ) {
                    $interviewBuilder->setStatus( "scheduled" )
                        ->setScheduledTime(
                            $this->request->post( "date" ) . " " . $this->request->post( "Hour" ) . ":" . $this->request->post( "Minute" ) . $this->request->post( "Meridian" )
                        );
                }

                $this->interview = $interviewBuilder->setIntervieweeID( $this->interviewee->id )
                    ->setInterviewTemplateID( $this->interviewTemplate->id )
                    ->setDeploymentTypeID( $deploymentType->id )
                    ->setAccount( $this->account )
                    ->setPositionID( $position->id )
                    ->setUserID( $this->user->id )
                    ->setOrganizationID( $this->organization->id )
                    ->build();

                if ( !is_null( $this->interview ) ) {
                    // Interview deployment flag. Default true.
                    $this->interview_deployment_successful = true;

                    // Debit the account of the interview credits for the deployment
                    // type provided
                    $this->account = $this->accountRepo->debitInterviewCredits(
                        $this->account,
                        $deploymentType
                    );

					// Get the interviewee from the inteview
					$this->interviewee = $interviewBuilder->getInterviewee();

					// Provision a new converation for this interview if sms deployment
                    if ( $this->interview->deployment_type_id == 1 ) {

                        // Get the interviewee's phone
						$phoneRepo = $this->load( "phone-repository" );
                        $this->interviewee->phone = $phoneRepo->get( [ "*" ], [ "id" => $this->interviewee->phone_id ], "single" );

                        // Try to create a conversation for an sms interview deployement
						$interviewRepo = $this->load( "interview-repository" );
						$conversationProvisioner = $this->load( "conversation-provisioner" );

						try {
                            // Create a new conversation between a twilio number and
                            // the interviewee's phone number
                            $conversation = $conversationProvisioner->provision(
                                $this->interviewee->phone->e164_phone_number
                            );

                            // Update the interview with a conversation id so it can
                            // be dispatched to the right phone number

                            $interviewRepo->update(
                                [ "conversation_id" => $conversation->id ],
                                [ "id" => $this->interview->id ]
                            );
                        // An exception will be thrown if conversation limit between
                        // the inteviewee's phone number and the twilio phone number
                        // has been reached or if the this interviewee's phone number
                        // currently has a conversation with every twilio phone number.
                        // The later is unlikely but still possible.
                        } catch ( \Exception $e ) {
                            // Log the error and pass the error message to the view
                            $this->logger->error( $e );
                            $this->errors[] = $e->getMessage();

                            // Refund the account for the interview
                            $accountProvisioner = $this->load( "account-provisioner" );
                            $accountProvisioner->refundInterview( $this->account, $this->interview );

                            // Remove the interview from the records
                            $interviewRepo->delete(
                                [ "id" ],
                                [ $this->interview->id ]
                            );

                            $this->interview_deployment_successful = false;
                        }
                    }

                    if ( $this->interview_deployment_successful && $this->interview->status != "scheduled" ) {
                        // Send interviewee email prompting to start interview
                        $mailer = $this->load( "mailer" );
                        $emailBuilder = $this->load( "email-builder" );
                        $domainObjectFactory = $this->load( "domain-object-factory" );

                        $this->interviewee = $interviewBuilder->getInterviewee();

                        $emailContext = $domainObjectFactory->build( "EmailContext" );
                        $emailContext->addProps([
                            "full_name" => $this->interviewee->getFullName(),
                            "first_name" => $this->interviewee->getFirstName(),
                            "interview_token" => $this->interview->token,
                            "sent_by" => $this->user->getFullName()
                        ]);

                        $resp = $mailer->setTo( $this->interviewee->email, $this->interviewee->getFullName() )
                            ->setFrom( "noreply@interviewus.net", "InterviewUs" )
                            ->setSubject( "You have a pending interivew: {$this->interviewee->getFullName()}" )
                            ->setContent( $emailBuilder->build( "interview-dispatch-notification.html", $emailContext ) )
                            ->mail();

                        $this->request->addFlashMessage( "success", ucfirst( $deploymentType->name ) . " interview successfully deployed" );
                        $this->request->setFlashMessages();
                    }
                }
            } else {
                $this->errors[] = "You have reached your {$deploymentType->name} interview deployment limit. Upgrade your account for more interviews.";
            }
		}
	}

	public function downloadCsv()
	{
		$accountRepo = $this->load( "account-repository" );
		$userRepo = $this->load( "user-repository" );
		$organizationRepo = $this->load( "organization-repository" );
		$interviewRepo = $this->load( "interview-repository" );
		$interviewQuestionRepo = $this->load( "interview-question-repository" );
		$intervieweeAnswerRepo = $this->load( "interviewee-answer-repository" );
		$intervieweeRepo = $this->load( "interviewee-repository" );
		$positionRepo = $this->load( "position-repository" );
		$csvGenerator = $this->load( "csv-generator" );

		// Verify that the submitted information belongs to the user
		$user = $userRepo->get(
			[ "*" ],
			[
				"id" => $this->request->post( "user_id" ),
				"current_account_id" => $this->request->post( "account_id" ),
				"current_organization_id" => $this->request->post( "organization_id" )
			]
		);

		// Verfiy that the interview being retrieved belongs to the organization
		// with this organization_id
		//
		// If both the user and the interview belong to the same organiztion,
		// it is highly unlikely that they're trying to get their hands on
		// interview data that isn't already theirs
		if ( is_null( $user ) ) {
			throw new \Exception( "User either does not exist or the information provided to retrieve the user doesn't match any user's data" );
		}

		$interview = $interviewRepo->get(
			[ "*" ],
			[
				"id" => $this->request->post( "interview_id" ),
				"organization_id" => $this->request->post( "organization_id" )
			],
			"single"
		);

		// Retrieve this interview's interview questions and their answers
		if ( is_null( $interview ) ) {
			throw new \Exception( "Interview either does not exist or the information provided to retrieve the interview doesn't match any interview's data" );
		}

		$interview->questions = $interviewQuestionRepo->get(
			[ "*" ],
			[ "interview_id" => $interview->id ]
		);

		// Retrieve the answers for these questions
		foreach ( $interview->questions as $question ) {
			$question->answer = $intervieweeAnswerRepo->get(
				[ "*" ],
				[ "interview_question_id" => $question->id ],
				"single"
			);
		}

		// Get the interviewee for this interview
		$interviewee = $intervieweeRepo->get( [ "*" ], [ "id" => $interview->interviewee_id ], "single" );

		// Get the position for this interview
		$interview->position = $positionRepo->get( [ "*" ], [ "id" => $interview->position_id ], "single" );

		// Create entries in the csv
		$csvGenerator->addColumns( [ "question", "answer" ] );
		foreach ( $interview->questions as $question ) {
			if ( is_null(  $question->answer ) ) {
				$csvGenerator->addEntry( [ $question->body, "Not Answered" ] );
				continue;
			}

			$csvGenerator->addEntry( [ $question->body, $question->answer->body ] );
		}

		// Create file name from interviewee full name and position name.
		$filename = str_replace( " ", "", str_replace( "'", "", $interviewee->getFullName() . $interview->position->name ) );

		// Download the csv
		$csvGenerator->download( $filename );
	}
	
	public function remind()
	{
		// Send interviewee email prompting to start interview
		if ( $this->validateAccount() ) {
			
			$interviewRepo = $this->load( "interview-repository" );
			$interview = $interviewRepo->get(
				[ "*" ],
				[ "id" => $this->request->post( "interview_id" ), "organization_id" => $this->organization->id ],
				"single"
			);
			
			$intervieweeRepo = $this->load( "interviewee-repository" );
			$interviewee = $intervieweeRepo->get(
				[ "*" ],
				[ "id" => $interview->interviewee_id, "organization_id" => $this->organization->id ],
				"single"
			);
			
			if ( !is_null( $interviewee ) ) {
				$domainObjectFactory = $this->load( "domain-object-factory" );
				$emailContext = $domainObjectFactory->build( "EmailContext" );
				$emailContext->addProps([
					"full_name" => $interviewee->getFullName(),
					"first_name" => $interviewee->getFirstName(),
					"interview_token" => $interview->token,
					"sent_by" => $this->user->getFullName()
				]);

				$mailer = $this->load( "mailer" );
				$emailBuilder = $this->load( "email-builder" );
				$resp = $mailer->setTo( $interviewee->email, $interviewee->getFullName() )
					->setFrom( "noreply@interviewus.net", "InterviewUs" )
					->setSubject( "You have a pending interivew: {$interviewee->getFullName()}" )
					->setContent( $emailBuilder->build( "interview-dispatch-notification.html", $emailContext ) )
					->mail();
			}
		}	
	}
}
