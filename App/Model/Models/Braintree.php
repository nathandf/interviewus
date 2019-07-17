<?php

namespace Model\Models;

class Braintree extends ProfileModel
{
	public $errors = [];

	public function processSubscriptions()
	{
		$braintreeGatewayInitializer = $this->load( "braintree-gateway-initializer" );
		$accountRepo = $this->load( "account-repository" );
		$accountProvisioner = $this->load( "account-provisioner" );
		$emailBuilder = $this->load( "email-builder" );
		$mailer = $this->load( "mailer" );
		$domainObjectFactory = $this->load( "domain-object-factory" );
		$userRepo = $this->load( "user-repository" );

		try {
			// Connect to braintree API
			$gateway = $braintreeGatewayInitializer->init();

			// Parse the signature and payload
			$webhookNotification = $gateway->webhookNotification()->parse(
				$this->request->post( "bt_signature" ),
				$this->request->post( "bt_payload" )
			);

			// Get the account associated with this notification
			$account = $accountRepo->get(
				[ "*" ],
				[ "braintree_subscription_id" => $webhookNotification->subscription->id ],
				"single"
			);

			// Get the primary user of the account
			$user = $userRepo->get( [ "*" ], [ "id" => $account->user_id ], "single" );

			// Handle for the different notification types
			switch ( $webhookNotification->kind ) {

				case "subscription_cancelled":
					// Notify account owner of subscription charge
					$emailContext = $domainObjectFactory->build( "EmailContext" );
					$emailContext->addProps([
						"account_id" => $account->id,
						"user_name" => $user->getFullName(),
						"user_email" => $user->email,
						"datetime" => $webhookNotification->timestamp->format( "D M j G:i:s T Y" )
					]);

					$resp = $mailer->setTo( "interview.us.app@gmail.com", "InterviewUs" )
						->setFrom( "noreply@interviewus.net", "InterviewUs" )
						->setSubject( "Subscription Cancelled" )
						->setContent( $emailBuilder->build( "subscription-cancellation.html", $emailContext ) )
						->mail();
					break;

				case "subscription_charged_successfully":
					// Provision account
					$accountProvisioner->provision( $account );

					// Notify account owner of subscription charge
					$emailContext = $domainObjectFactory->build( "EmailContext" );
					$emailContext->addProps([
						"transaction_id" => $webhookNotification->subscription->transactions[ 0 ]->id,
						"amount" => $webhookNotification->subscription->transactions[ 0 ]->amount,
						"full_name" => $user->getFullName(),
						"datetime" => $webhookNotification->timestamp->format( "D M j G:i:s T Y" )
					]);

					$resp = $mailer->setTo( $user->email, $user->getFullName() )
						->setFrom( "noreply@interviewus.net", "InterviewUs" )
						->setSubject( "Account Renewed - Thanks for using InterviewUs!" )
						->setContent( $emailBuilder->build( "account-renewal.html", $emailContext ) )
						->mail();
					break;

				case "subscription_charged_unsuccessfully":
					// Notify account owner of subscription charge
					$emailContext = $domainObjectFactory->build( "EmailContext" );
					$emailContext->addProps([
						"first_name" => $user->getFirstName(),
						"issue" => $webhookNotification->subscription->transactions[ 0 ]->processorResponseText,
						"amount" => $webhookNotification->subscription->transactions[ 0 ]->amount,
						"datetime" => $webhookNotification->timestamp->format( "D M j G:i:s T Y" )
					]);

					$resp = $mailer->setTo( $user->email, $user->getFullName() )
						->setFrom( "customersupport@interviewus.net", "InterviewUs" )
						->setSubject( "Uh Oh, {$user->getFirstName()}! - We failed to renew your subscription - InterviewUs" )
						->setContent( $emailBuilder->build( "subscription-renewal-failure.html", $emailContext ) )
						->mail();
					break;

				case "subscription_expired":
					break;

				case "subscription_trial_ended":
					break;

				case "subscription_went_active":
					break;

				case "subscription_went_past_due":
					break;

				default:
					$logger->info( "Braintree Webhook Notification: {$webhookNotification->kind}" );
					break;
			}

		} catch ( \Exception $e ) {
			$logger->error( $e->getMessage() );
		}
	}

	public function processDisputes()
	{
		$braintreeGatewayInitializer = $this->load( "braintree-gateway-initializer" );
		$accountRepo = $this->load( "account-repository" );
		$accountProvisioner = $this->load( "account-provisioner" );
		$emailBuilder = $this->load( "email-builder" );
		$mailer = $this->load( "mailer" );
		$domainObjectFactory = $this->load( "domain-object-factory" );
		$userRepo = $this->load( "user-repository" );

		try {
			// Connect to braintree API
			$gateway = $braintreeGatewayInitializer->init();

			// Parse the signature and payload
			$webhookNotification = $gateway->webhookNotification()->parse(
				$this->request->post( "bt_signature" ),
				$this->request->post( "bt_payload" )
			);

			// Get the account associated with this notification
			$account = $accountRepo->get(
				[ "*" ],
				[ "braintree_customer_id" => $webhookNotification->dispute->transaction->customerDetails->id ],
				"single"
			);

			// Get the primary user of the account
			$user = $userRepo->get( [ "*" ], [ "id" => $account->user_id ], "single" );

			// Handle for the different notification types
			switch ( $webhookNotification->kind ) {

				case "dispute_opened":
					// Log the dispute details
					$logger->info( "Dispute Opened:\nInterviewUs Account ID - {$account->id}\nUser ID: {$user->id}\nUser Full Name: {$user->getFullName()}\nDispute ID - {$webhookNotification->dispute->id}\nAmount disputed: {$webhookNotification->dispute->amount}" );

					// Send dispute opened email
					$emailContext = $domainObjectFactory->build( "EmailContext" );

					$emailContext->addProps([
						"dispute_id" => $webhookNotification->dispute->id,
						"amount" => $webhookNotification->dispute->transaction->amount,
						"account_id" => $account->id,
						"customer_id" => $webhookNotification->dispute->transaction->customerDetails->id,
						"full_name" => $user->getFullName(),
						"email" => $user->email,
						"datetime" => $webhookNotification->timestamp->format( "D M j G:i:s T Y" )
					]);

					$resp = $mailer->setTo( "interview.us.app@gmail.com", "InterviewUs" )
						->setFrom( "disputes@interviewus.net", "InterviewUs" )
						->setSubject( "Dispute Opened" )
						->setContent( $emailBuilder->build( "dipute-opened.html", $emailContext ) )
						->mail();
					break;

				case "dispute_won":
					// Log dispute resolution result
					$logger->info( "Dispute Won: Dispute ID - {$webhookNotification->dispute->id}" );

					// Send dispute won email
					$emailContext = $domainObjectFactory->build( "EmailContext" );

					$emailContext->addProps([
						"dispute_id" => $webhookNotification->dispute->id,
						"amount" => $webhookNotification->dispute->transaction->amount,
						"datetime" => $webhookNotification->timestamp->format( "D M j G:i:s T Y" )
					]);

					$resp = $mailer->setTo( "interview.us.app@gmail.com", "InterviewUs" )
						->setFrom( "disputes@interviewus.net", "InterviewUs" )
						->setSubject( "Dispute Won" )
						->setContent( $emailBuilder->build( "dipute-won.html", $emailContext ) )
						->mail();
					break;

				case "dispute_lost":
					// Log dispute resolution result
					$logger->info( "Dispute Lost: Dispute ID - {$webhookNotification->dispute->id}" );

					// Send dispute won email
					$emailContext = $domainObjectFactory->build( "EmailContext" );

					$emailContext->addProps([
						"dispute_id" => $webhookNotification->dispute->id,
						"amount" => $webhookNotification->dispute->transaction->amount,
						"datetime" => $webhookNotification->timestamp->format( "D M j G:i:s T Y" )
					]);

					$resp = $mailer->setTo( "interview.us.app@gmail.com", "InterviewUs" )
						->setFrom( "disputes@interviewus.net", "InterviewUs" )
						->setSubject( "Dispute Lost" )
						->setContent( $emailBuilder->build( "dipute-lost.html", $emailContext ) )
						->mail();
					break;

				default:
					$logger->info( "Braintree Dispute: {$webhookNotification->kind}" );
					break;
			}

		} catch ( \Exception $e ) {
			$logger->error( $e->getMessage() );
		}
	}
}
