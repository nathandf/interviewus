<?php

namespace Model\Services;

class TwilioPhoneNumberRepository extends Repository
{
	public function getAvailable( array $unavailable_twilio_phone_number_ids )
	{
		return $this->mapper->getAllExceptByIDs( $unavailable_twilio_phone_number_ids );
	}
}
