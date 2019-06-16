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
				// the body of the concatenated message as the the next inteview question
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
							$interviewDispatcher->answerNextQuestion( $interview, $concatenatedSms->body );
							$concatenatedSmsRepo->deleteEntities( $concatenatedSms );
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
