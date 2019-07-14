<?php

namespace Model\Services;

class UserAuthenticator
{
    public $userRepo;
    public $request;
    public $authenticatedUser;

    public function __construct( UserRepository $userRepo, \Core\Request $request )
    {
        $this->userRepo = $userRepo;
        $this->request = $request;

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

        $this->request->deleteCookie( "user-token" );
    }

    public function logIn( \Model\Entities\User $user )
    {
        $token = $this->request->generateToken();
        $this->request->setSession( "user-id", $user->id );
        $this->request->setCookie( "user-token", $token );
        $this->userRepo->update(
            [ "token" => $token ],
            [ "id" => $user->id ]
        );
    }

    public function isLoggedIn()
    {
        if ( $this->request->session( "user-id" ) ) {
            $user = $this->userRepo->get( [ "*" ], [ "id" => $this->request->session( "user-id" ) ], "single" );
            if ( is_null( $user ) ) {
                return false;
            }
            $this->setAuthenticatedUser( $user );

            return true;

        } elseif ( $this->request->getCookie( "user-token" ) ) {
            $user = $this->userRepo->get( [ "*" ], [ "token" => $this->request->getCookie( "user-token" ) ], "single" );
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
