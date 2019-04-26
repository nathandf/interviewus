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
        $this->view->setTemplate( "index.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }

    public function signInAction()
    {
        $userAuth = $this->load( "user-authenticator" );
        $input = $this->load( "input" );
        $inputValidator = $this->load( "input-validator" );

        if ( !is_null( $userAuth->getAuthenticatedUser() ) ) {
            $this->view->redirect( "profile/" );
        }

        if (
            $input->exists() &&
            $input->issetField( "sign_in" ) &&
            $inputValidator->validate(
                $input,
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
                    $input->get( "email" ),
                    $input->get( "password" )
                )
            ) {
                $this->view->redirect( "profile/" );
            }

            $inputValidator->addError( "sign_in", "The credentials you have provided are invalid. Please try again." );
        }

        $this->view->assign( "csrf_token", $this->session->generateCSRFToken() );
        $this->view->assign( "error_messages", $inputValidator->getErrors() );

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
