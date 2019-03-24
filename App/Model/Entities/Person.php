<?php

namespace Model\Entities;

class Person
{
	public $first_name;
    public $last_name;
    public $email;
    public $phone_id;
    public $address_id;

	public function setNames( $name )
	{
		$name_parts = explode( " ", $name, 2 );
        if ( count( $name_parts ) > 1 ) {
            $this->first_name = $name_parts[ 0 ];
            $this->setLastName( $name_parts[ 1 ] );
        } else {
            $this->first_name = $name;
        }
	}

    public function setLastName( $last_name )
    {
        $this->last_name = $last_name;
    }

    public function setEmail( $email )
    {
        $this->email = $email;
    }

	public function getFullName()
	{
		if ( isset( $this->first_name, $this->last_name ) ) {
			return $this->first_name . " " . $this->last_name;
		}

		return $this->first_name;
	}

	public function getFirstName()
	{
		if ( isset( $this->first_name ) ) {
			return $this->first_name;
		}

		return null;
	}

	public function getLastName()
	{
		if ( isset( $this->last_name ) ) {
			return $this->last_name;
		}

		return null;
	}
}
