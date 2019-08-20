<?php

namespace Model\Models;

use Core\Model;

class ProfileModel extends Model
{
	public $account_validated = false;

	public function validateAccount()
	{
        $this->logger = $this->load( "logger" );

		$userAuth = $this->load( "user-authenticator" );
        $this->user = $userAuth->getAuthenticatedUser();

        if ( is_null( $this->user ) ) {
            return $this->account_validated;
        }

		$this->accountRepo = $this->load( "account-repository" );
        $this->account = $this->accountRepo->get( [ "*" ], [ "id" => $this->user->current_account_id ], "single" );

		$organizationRepo = $this->load( "organization-repository" );
        $this->organization = $organizationRepo->get( [ "*" ], [ "id" => $this->user->current_organization_id ], "single" );
		$this->organizations = $organizationRepo->get( [ "*" ], [ "account_id" => $this->account->id ] );

		$interviewTemplateRepo = $this->load( "interview-template-repository" );
		$this->interviewTemplates = $interviewTemplateRepo->get( [ "*" ], [ "organization_id" => $this->organization->id ] );

		$positionRepo = $this->load( "position-repository" );
		$this->positions = $positionRepo->get( [ "*" ], [ "organization_id" => $this->organization->id ] );

		$intervieweeRepo = $this->load( "interviewee-repository" );
		$this->interviewees = $intervieweeRepo->get( [ "*" ], [ "organization_id" => $this->organization->id ] );

		$countryRepo = $this->load( "country-repository" );
		$this->countries = $countryRepo->get( [ "*" ] );

		return $this->account_validated = true;
	}
}
