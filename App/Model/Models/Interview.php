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
}
