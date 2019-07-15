<?php

namespace Model\Models;

use Core\Model;

class Feedback extends Model
{
	public function send()
	{
		$mailer = $this->load( "mailer" );
		$emailBuilder = $this->load( "email-builder" );
		$domainObjectFactory = $this->load( "domain-object-factory" );

		$emailContext = $domainObjectFactory->build( "EmailContext" );
		$emailContext->addProps([
			"user" => $this->request->post( "user" ),
			"account" => $this->request->post( "account" ),
			"opinion" => $this->request->post( "opinion" ),
			"subject" => $this->request->post( "subject" ),
			"message" => $this->request->post( "message" ),
			"recommendation" => $this->request->post( "recommendation" )
		]);

		// Notify admin of user feedback
		$resp = $mailer->setTo( "interview.us.app@gmail.com", "InterviewUs" )
			->setFrom( "noreply@interviewus.net" )
			->setSubject( "User Feedback Notification | {$this->request->post( "subject" )}" )
			->setContent( $emailBuilder->build( "user-feedback.html", $emailContext ) )
			->mail();
	}
}
