<?php

namespace Model\DomainObjects;

use Contracts\DomainObjectInterface;

class Person implements DomainObjectInterface
{
	public $first_name;
    public $last_name;
    public $email;
    public $phone_id;
    public $address_id;
	public $phone_number;
	public $organization;
	public $address;

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

	public function setEmail( $email )
	{
		$this->email = $email;
		return $this;
	}

	public function getEmail()
	{
		if ( isset( $this->email ) ) {
			return $this->email;
		}

		return null;
	}

	public function setPhoneNumber( $phone_number )
	{
		$this->phone_number = $phone_number;
		return $this;
	}

	public function getPhoneNumber()
	{
		if ( isset( $this->phone_number ) ) {
			return $this->phone_number;
		}

		return null;
	}

	public function setOrganization( $organization )
	{
		$this->organization = $organization;
		return $this;
	}

	public function getOrganization()
	{
		if ( isset( $this->organization ) ) {
			return $this->organization;
		}

		return null;
	}
}
