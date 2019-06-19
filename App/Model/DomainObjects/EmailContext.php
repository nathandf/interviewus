<?php

namespace Model\DomainObjects;

use Contracts\DomainObjectInterface;

class EmailContext implements DomainObjectInterface
{
	public function addProps( array $props )
	{
		foreach ( $props as $key => $prop ) {
			if ( preg_match( "/^[a-zA-Z]/", $key ) == false ) {
				throw new \Exception( "Property must start with a letter." );
			}

			$key = rtrim( preg_replace( "/_+/", "_", str_replace( "-", "_", $key ) ) );

			$this->$key = $prop;
		}
	}
}
