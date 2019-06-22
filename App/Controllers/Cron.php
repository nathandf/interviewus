<?php

namespace Controllers;

use \Core\Controller;

class Cron extends Controller
{
	public function indexAction()
	{
		$this->view->redirect( "" );
	}

	public function cullTwilioNumbers()
	{
		// Destroy twilio numbers that are about renew and have no conversations
	}

	public function dispatchSmsInterviewQuestions()
	{
		$input = $this->load( "input" );
		$inputValidator = $this->load( "input-validator" );
		$concatenatedSmsRepo = $this->load( "concatenated-sms-repository" );
		$conversationRepo = $this->load( "conversation-repository" );
		$interviewRepo = $this->load( "interview-repository" );
		$interviewDispatcher = $this->load( "interview-dispatcher" );
		$logger = $this->load( "logger" );

		if (
			$input->exists( "get" ) &&
			$inputValidator->validate(
				$input,
				[
					"cron-token" => [
						"required" => true,
						"equals" => "1234"
					]
				],
				"dispatch_sms_interview_questions"
			)
		) {
			// Get all concatenated smses
			$concatenatedSmses = $concatenatedSmsRepo->get( [ "*" ] );

			foreach ( $concatenatedSmses as $concatenatedSms ) {

				// Concatenated smses' updated_at property must be >= 2 seconds ago. This
				// will allow sufficient time for all unconcatenated inbound smses from carriers
				// like Sprint PCS to be processed and concatenated before dispatching
				// the body of the concatenated message as the the next interview question
				if ( ( time() - $concatenatedSms->updated_at ) >= 2 ) {

					// Get the conversation
					$conversation = $conversationRepo->get(
						[ "*" ],
						[ "id" => $concatenatedSms->conversation_id ],
						"single"
					);

					if ( !is_null( $conversation ) ) {
						$interview = $interviewRepo->get(
							[ "*" ],
							[
								"conversation_id" => $conversation->id,
								"status" => "active",
								"deployment_type_id" => 1
							],
							"single"
						);

						if ( !is_null( $interview ) ) {
							$interview = $interviewDispatcher->answerNextQuestion( $interview, $concatenatedSms->body );
							$concatenatedSmsRepo->deleteEntities( $concatenatedSms );

							// If the interview is complete, send the dispatching user a
				            // a completion email
							if ( $interview->status == "complete" ) {
				                $mailer = $this->load( "mailer" );
				                $emailBuilder = $this->load( "email-builder" );
				                $domainObjectFactory = $this->load( "domain-object-factory" );

								// Get the interviewee from the interview
				                $intervieweeRepo = $this->load( "interviewee-repository" );
				                $interviewee = $intervieweeRepo->get( [ "*" ], [ "id" => $interview->interviewee_id ], "single" );

				                // Get the user that dispatched the interview
				                $userRepo = $this->load( "user-repository" );
				                $user = $userRepo->get( [ "*" ], [ "id" => $interview->user_id ], "single" );

				                $emailContext = $domainObjectFactory->build( "EmailContext" );
				                $emailContext->addProps([
				                    "interviewee_name" => $interviewee->getFullName()
				                ]);

				                $resp = $mailer->setTo( $user->email, $user->getFullName() )
				                    ->setFrom( "noreply@interviewus.net", "InterviewUs" )
				                    ->setSubject( $interviewee->getFirstName() . " has completed their interview" )
				                    ->setContent( $emailBuilder->build( "interview-completion-notification.html", $emailContext ) )
				                    ->mail();
				            }
							return;
						}
						$logger->error( "Interview not found for conversation_id '{$conversation->id}'" );

						return;
					}
					$logger->error( "Conversation does not with id '{$concatenatedSms->conversation_id}'" );

					return;
				}
			}
		}
	}
}
