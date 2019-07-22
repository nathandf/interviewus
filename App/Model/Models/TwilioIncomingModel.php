<?php

namespace Model\Models;

use Core\Model;

class TwilioIncomingModel extends Model
{
	public function validateTwilioNumber()
	{
		$twilioPhoneNumberRepo = $this->load( "twilio-phone-number-repository" );
        $this->logger = $this->load( "logger" );

        $this->twilioPhoneNumber = $twilioPhoneNumberRepo->get( [ "*" ], [ "sid" => $this->params[ "sid" ] ], "single" );

        // If no twilio phone number exists
        if ( is_null( $this->twilioPhoneNumber ) ) {
            $this->logger->error( "Twilio number with sid '{$this->params[ "sid" ]}' does not exist" );

			return false;
		}

		return true;
	}
}
