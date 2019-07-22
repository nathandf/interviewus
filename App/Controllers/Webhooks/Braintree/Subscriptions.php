<?php

namespace Controllers\Webhooks\Braintree;

use \Core\Controller;

class Subscriptions extends Controller
{
    public function indexAction()
    {
        $requestValidator = $this->load( "request-validator" );

        if (
            $this->request->is( "post" ) &&
            $requestValidator->validate(
                $this->request,
                new \Model\Validations\BraintreeWebhookNotification,
                "braintree_subscription"
            )
        ) {
            return [ "Braintree:processSubscriptions", "DefaultView:index", null, null ];
        }
    }
}
