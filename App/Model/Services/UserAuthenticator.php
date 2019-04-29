<?php

namespace Model\Services;

class UserAuthenticator
{
    public $userRepo;
    public $session;
    public $authenticatedUser;

    public function __construct( UserRepository $userRepo, \Core\Session $session )
    {
        $this->userRepo = $userRepo;
        $this->session = $session;

        // Check for a logged in user
        $this->isLoggedIn();
    }

    public function authenticate( $email, $password )
    {
        $user = $this->userRepo->get( [ "*" ], [ "email" => strtolower( trim( $email ) ) ], "single" );

        if ( !is_null( $user ) ) {
            if ( password_verify( $password, $user->password ) ) {
                $this->setAuthenticatedUser( $user );
                $this->logIn( $user );

                return true;
            }

            return false;
        }

        return false;
    }

    public function logOut()
    {
        if ( session_status() != PHP_SESSION_NONE ) {
            session_unset();
            session_destroy();
        }

        $this->session->deleteCookie( "user-token" );
    }

    public function logIn( \Model\Entities\User $user )
    {
        $token = $this->session->generateToken();
        $this->session->setSession( "user-id", $user->id );
        $this->session->setCookie( "user-token", $token );
        $this->userRepo->update(
            [ "token" => $token ],
            [ "id" => $user->id ]
        );
    }

    public function isLoggedIn()
    {
        if ( $this->session->getSession( "user-id" ) ) {
            $user = $this->userRepo->get( [ "*" ], [ "id" => $this->session->getSession( "user-id" ) ], "single" );
            if ( is_null( $user ) ) {
                return false;
            }
            $this->setAuthenticatedUser( $user );

            return true;

        } elseif ( $this->session->getCookie( "user-token" ) ) {
            $user = $this->userRepo->get( [ "*" ], [ "token" => $this->session->getCookie( "user-token" ) ], "single" );
            if ( is_null( $user ) ) {
                return false;
            }
            $this->setAuthenticatedUser( $user );

            return true;
        }

        return false;
    }

    private function setAuthenticatedUser( \Model\Entities\User $user )
    {
        $this->authenticatedUser = $user;
        return $this;
    }

    public function getAuthenticatedUser()
    {
        return $this->authenticatedUser;
    }
}
