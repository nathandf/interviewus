<?php

namespace Model\Mappers;

class TimezoneMapper extends DataMapper
{
	public function getAllAscAlpha( $iso )
	{

		if ( !is_null( $iso ) ) {
			$query = "SELECT * FROM `{$this->getTable()}` WHERE country_code = :country_code ORDER BY timezone ASC";
			$sql = $this->DB->prepare( $query );
			$sql->bindParam( ":country_code", $iso );
		} else {
			$query = "SELECT * FROM `{$this->getTable()}` ORDER BY timezone ASC";
			$sql = $this->DB->prepare( $query );
		}

		$sql->execute();

		$timezones = [];

		while ( $response = $sql->fetch( \PDO::FETCH_ASSOC ) ) {
            $timezone = $this->build( $this->formatEntityNameFromTable() );
            $this->populate( $timezone, $response );
            $timezones[] = $timezone;
        }

		return $timezones;
	}
}
