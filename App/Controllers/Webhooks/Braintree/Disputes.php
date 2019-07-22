<?php

namespace Controllers\Webhooks\Braintree;

use \Core\Controller;

class Disputes extends Controller
{
    public function indexAction()
    {
        $requestValidator = $this->load( "request-validator" );

        if (
            $this->request->is( "post" ) &&
            $requestValidator->validate(
                $this->request,
                new \Model\Validations\BraintreeWebhookNotification,
                "braintree_dispute"
            )
        ) {
            return [ "Braintree:processDisputes", "DefaultView:index", null, null ];
        }
    }
}
