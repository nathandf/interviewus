<?php

namespace Model\Models;

class Interviews extends ProfileModel
{
	public $errors = [];

	public function dispatchScheduledInterviews()
	{
		$interviewRepo = $this->load( "interview-repository" );
		$interviewDispatcher = $this->load( "interview-dispatcher" );
		$organizationRepo = $this->load( "organization-repository" );
		$timezoneHelper = $this->load( "time-zone-helper" );

		$interviews = $interviewRepo->get( [ "*" ], [ "status" => "scheduled" ] );

		// Server time
		$now = time();

		foreach ( $interviews as $interview ) {
			// Get the organiations timezone
			$organization_timezone = $organizationRepo->get( [ "timezone" ], [ "id" => $interview->organization_id ], "raw" )[ 0 ];

			// Resolve timezone differences between server time and the
			// organization timezone
			$timezone_offset = $timezoneHelper->getUTCTimeZoneOffset( $organization_timezone );
			$deployment_time = strtotime( $interview->scheduled_time ) - $timezone_offset;
			
			if ( $deployment_time <= $now ) {
				// Update the inteview status to pending
				$interviewRepo->update(
					[ "status" => "pending" ],
					[ "id" => $interview->id ]
				);

				// Dipatch the interview if it's web based
				if ( $interview->deployment_type_id == 2 ) {
					$interviewDispatcher->dispatch( $interview );
				}

				// Send interviewee email prompting to start interview
				$mailer = $this->load( "mailer" );
				$emailBuilder = $this->load( "email-builder" );
				$domainObjectFactory = $this->load( "domain-object-factory" );
				$intervieweeRepo = $this->load( "interviewee-repository" );
				$userRepo = $this->load( "user-repository" );

				// Get interviewee for this interview
				$interviewee = $intervieweeRepo->get( [ "*" ], [ "id" => $interview->interviewee_id ], "single" );

				// Get the user that dispatched this interview
				$user = $userRepo->get( [ "*" ], [ "id" => $interview->user_id ], "single" );

				$emailContext = $domainObjectFactory->build( "EmailContext" );
				$emailContext->addProps([
					"full_name" => $interviewee->getFullName(),
					"first_name" => $interviewee->getFirstName(),
					"interview_token" => $interview->token,
					"sent_by" => $user->getFullName()
				]);

				$resp = $mailer->setTo( $interviewee->email, $interviewee->getFullName() )
					->setFrom( "noreply@interviewus.net", "InterviewUs" )
					->setSubject( "You have a pending interivew: {$interviewee->getFullName()}" )
					->setContent( $emailBuilder->build( "interview-dispatch-notification.html", $emailContext ) )
					->mail();
			}
		}
	}

	public function dispatchSmsInterviewQuestions()
	{
		$concatenatedSmsRepo = $this->load( "concatenated-sms-repository" );
		$conversationRepo = $this->load( "conversation-repository" );
		$interviewRepo = $this->load( "interview-repository" );
		$interviewDispatcher = $this->load( "interview-dispatcher" );
		$logger = $this->load( "logger" );

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
							// Update the end time
							$interviewRepo->update(
								[ "end_time" => date( "M j, Y \@ g:i:s a" ) ],
								[ "id" => $interview->id ]
							);

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
