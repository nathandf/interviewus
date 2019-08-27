<?php

namespace Model\Models;

use Core\Model;

class Subscription extends ProfileModel
{
	public $errors = [];

	public function cancel()
	{
		if ( $this->validateAccount() ) {
			$braintreeSubscriptionRepo = $this->load( "braintree-subscription-repository" );
			$braintreeSubscriptionRepo->delete( $this->account->braintree_subscription_id );

            // Update account to Free plan but do not provision
            $accountRepo = $this->load( "account-repository" );
            $accountRepo->update(
                [
                    "plan_id" => 11,
                    "braintree_subscription_id" => ""
                ],
                [ "id" => $this->account->id ]
            );

            $this->request->addFlashMessage( "success", "Subscription successfully canceled." );
            $this->request->setFlashMessages();
		}
	}
}
