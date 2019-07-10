<?php

namespace Controllers;

use \Core\Controller;

class Home extends Controller
{
    public function before()
    {

    }

    public function indexAction()
    {
        // $this->view->setTemplate( "index.tpl" );
        // $this->view->render( "App/Views/Home.php" );
        return [ "Home", "index", [] ];
    }

    public function signInAction()
    {
        $userAuth = $this->load( "user-authenticator" );

        $requestValidator = $this->load( "request-validator" );

        if ( !is_null( $userAuth->getAuthenticatedUser() ) ) {
            $this->view->redirect( "profile/" );
        }

        if (
            $this->request->is( "post" ) &&
            $this->request->post( "sign_in" ) != "" &&
            $requestValidator->validate(
                $this->request,
                [
                    "token" => [
                        "required" => true,
                        "equals-hidden" => $this->session->getSession( "csrf-token" )
                    ],
                    "email" => [
                        "required" => true,
                        "email" => true
                    ],
                    "password" => [
                        "required" => true
                    ],
                ],
                "sign_in"
            )
        ) {
            if ( $userAuth->authenticate(
                    $this->request->post( "email" ),
                    $this->request->post( "password" )
                )
            ) {
                $this->view->redirect( "profile/" );
            }

            $requestValidator->addError( "sign_in", "The credentials you have provided are invalid. Please try again." );
        }

        $this->view->assign( "error_messages", $requestValidator->getErrors() );

        $this->view->setTemplate( "sign-in.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }

    public function privacyPolicyAction()
    {

    }

    public function termsAndConditionsAction()
    {

    }
}
