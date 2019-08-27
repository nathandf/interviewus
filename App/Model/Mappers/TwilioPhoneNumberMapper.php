<?php

namespace Model\Mappers;

class TwilioPhoneNumberMapper extends DataMapper
{
	public function getAllExceptByIDs( array $unavailable_twilio_phone_number_ids )
	{
		$ids = implode( ", ", $unavailable_twilio_phone_number_ids );
		$query = "SELECT * FROM `{$this->table}` WHERE id NOT IN ( {$ids} )";
		$sql = $this->DB->prepare( $query );
		$sql->execute();

		$entities = [];

		while ( $response = $sql->fetch( \PDO::FETCH_ASSOC ) ) {
			$entity = $this->build( $this->formatEntityNameFromTable() );
			$this->populate( $entity, $response );
			$entities[] = $entity;
		}

		return $entities;
	}
}
