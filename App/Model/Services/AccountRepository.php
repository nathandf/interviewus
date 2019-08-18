<?php

namespace Model\Services;

use Model\Entities\Account;
use Model\Entities\DeploymentType;


class AccountRepository extends Repository
{
	public function debitInterviewCredits(
		Account $account,
		DeploymentType $deploymentType,
		$debits = 1
	) {
		$deployment_type_property = "{$deploymentType->name}_interviews";

		// If account has -1 (unlimited) interviews, do not debit account
		if ( $account->$deployment_type_property > -1 ) {
			if ( $account->validateInterviewCredit( $deploymentType, $debits ) ) {
				$this->update(
					[ "{$deploymentType->name}_interviews" => ( $account->{$deploymentType->name . "_interviews"} - $debits ) ],
					[ "id" => $account->id ]
				);
			}
		}

		return $this->get( [ "*" ], [ "id" => $account->id ], "single" );
	}
}
