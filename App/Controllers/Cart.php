<?php

namespace Controllers;

use \Core\Controller;

class Cart extends Controller
{
    public function before()
    {
        $userAuth = $this->load( "user-authenticator" );
        $this->user = $userAuth->getAuthenticatedUser();

        if ( is_null( $this->user ) ) {
            return [ null, "DefaultView:redirect", null, "" ];
        }

        $accountRepo = $this->load( "account-repository" );
        $this->account = $accountRepo->get( [ "*" ], [ "id" => $this->user->current_account_id ], "single" );

        $cartRepo = $this->load( "cart-repository" );
        $this->cart = $cartRepo->get( [ "*" ], [ "account_id" => $this->account->id ], "single" );
        if ( is_null( $this->cart ) ) {
            return [ null, "DefaultView:redirect", null, "pricing/" ];
        }
    }

    public function indexAction()
    {
        $requestValidator = $this->load( "request-validator" );

        if (
            $this->request->is( "get" ) &&
            $this->request->get( "purchase" ) != "" &&
            $requestValidator->validate(
                $this->request,
                new \Model\Validations\PaymentMethodNonce( $this->request->session( "csrf-token" ) ),
                "purchase"
            )
        ) {
            return [ "Cart:purchase", "Cart:purchase", null, null ];
        }

        return [ "Cart:index", "Cart:index", null, $requestValidator->getErrors() ];
    }
}
