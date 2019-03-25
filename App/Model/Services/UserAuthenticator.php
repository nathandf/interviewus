<?php

namespace Model\Services;

class UserAuthenticator extends Service
{
    public $userRepo;

    public function __construct( UserRepository $userRepo )
    {
        $this->userRepo = $userRepo;
    }

    public function signIn( $email, $password )
    {
        $user = $userRepo->get( [ "*" ], [ "email" => $email ], "single" );

        if ( !is_null( $user ) ) {
            if ( password_verify( $password, $user->password ) ) {
                // TODO Log user in

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

        if ( isset( $_COOKIE[ "user_login_token" ] ) ) {
            unset( $_COOKIE[ "user_login_token" ] );
        }
    }
}
