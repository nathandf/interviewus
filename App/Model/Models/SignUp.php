<?php

namespace Model\Models;

use Core\Model;

class SignUp extends Model
{
	public $errors = [];
	public $user;

	public function createAccount()
	{
		$userRepo = $this->load( "user-repository" );
		$accountRepo = $this->load( "account-repository" );
		$accountUserRepo = $this->load( "account-user-repository" );
		$organizationRepo = $this->load( "organization-repository" );
		$organizationUserRepo = $this->load( "organization-user-repository" );

		// Ensure email is unique and create the new account, and user.
		if ( !in_array( $this->request->post( "email" ), $userRepo->get( [ "email" ], [], "raw" ) ) ) {

			//Create new User
			$user = $userRepo->insert([
				"role" => "owner",
				"first_name" => trim( $this->request->post( "name" ) ),
				"email" => strtolower( trim( $this->request->post( "email" ) ) ),
				"password" => password_hash( trim( $this->request->post( "password" ) ), PASSWORD_BCRYPT )
			]);

			// Update the first and last name
			if ( count( explode( " ", $this->request->post( "name" ) ) ) > 1 ) {
				$user->setNames( $user->first_name );
				$userRepo->update(
					[
						"first_name" => $user->getFirstName(),
						"last_name" => $user->getLastName()
					],
					[
						"id" => $user->id
					]
				);
			}

			// Get the timezone from ip data and save in cookieif it hasn't been
			// set
			$timezone = $this->request->cookie( "timezone" );
			if ( is_null( $timezone ) ) {
				// Load the ip data gateway
				$ipdata = $this->load( "ipdata-gateway" );

				// Request geo data from ipdata api
				try {
					$response = $ipdata->query();
					$timezone = $response->time_zone->name;
				} catch ( \Exception $e ) {
					// Log the error and set the default timezone as America/Chicago
					$logger = $this->load( "logger" );
					$logger->error( $e->getMessage() );
					$timezone = "America/Chicago";
				}

				// Save timezone in cookie so the no additional api calls need to
				// be made
				$this->request->setCookie( "timezone", $timezone );
			}

			// Create new Account with an upgraded plan to give user access
			// to free interviews
			$account = $accountRepo->insert([
				"account_type_id" => 1,
				"user_id" => $user->id,
				"plan_id" => 1,
				"timezone" => $timezone
			]);

			// Provision Account
			$accountProvisioner = $this->load( "account-provisioner" );
			$accountProvisioner->provision( $account );

			// Update the account back to free to restrict access to premium
			// features. This will not remove the extra interviews they were
			// just provided.
			$accountRepo->update(
				[ "plan_id" => 11 ],
				[ "id" => $account->id ]
			);

			// Create braintree customer from a person
			$braintreeCustomerRepo = $this->load( "braintree-customer-repository" );
			$braintreeCustomer = $braintreeCustomerRepo->create( $user )->customer;

			// Update Account's braintree_customer_id
			$accountRepo->update(
				[ "braintree_customer_id" => $braintreeCustomer->id ],
				[ "id" => $account->id ]
			);

			// Update current_account_id to new account_id
			$userRepo->update(
				[ "current_account_id" => $account->id ],
				[ "id" => $user->id ]
			);

			// Create new AccountUser
			$accountUser = $accountUserRepo->insert([
				"account_id" => $account->id,
				"user_id" => $user->id
			]);

			// Create new Organization
			$organization = $organizationRepo->insert([
				"account_id" => $account->id,
				"name" => "My Organization",
				"user_id" => $user->id
			]);

			// Update current_organization_id to new organization_id
			$userRepo->update(
				[ "current_organization_id" => $organization->id ],
				[ "id" => $user->id ]
			);

			// Create new OrganizationUser
			$organizationUser = $organizationUserRepo->insert([
				"organization_id" => $organization->id,
				"user_id" => $user->id
			]);

			// Send welcome and confirmation email
			$mailer = $this->load( "mailer" );
			$emailBuilder = $this->load( "email-builder" );
			$domainObjectFactory = $this->load( "domain-object-factory" );

			$emailContext = $domainObjectFactory->build( "EmailContext" );
			$emailContext->addProps([
				"first_name" => $user->getFirstName()
			]);

			$resp = $mailer->setTo( $user->email, $user->getFullName() )
				->setFrom( "getstarted@interviewus.net", "InterviewUs" )
				->setSubject( "Here's 9 Free interviews on Us 🤖 Welcome to InterviewUs!" )
				->setContent( $emailBuilder->build( "welcome-email.html", $emailContext ) )
				->mail();

			// Authenticate and log in User
			$userAuth = $this->load( "user-authenticator" );
			$userAuth->authenticate( $user->email, $this->request->post( "password" ) );

			$this->user = $userAuth->getAuthenticatedUser();

			return;
		}

		$this->errors[] = "Email unavailable";

		return;
	}
}
