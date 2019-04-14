<?php

namespace Controllers\Webhooks\Twilio;

use \Core\Controller;

class Incoming extends Controller
{
    public $organization_phone = null;

    public function before()
    {
        $this->requireParam( "sid" );
        $organizationRepo = $this->load( "organization-repository" );
        $phoneRepo = $this->load( "phone-repository" );
        $this->twilioServiceDispatcher = $this->load( "twilio-service-dispatcher" );
        $twilioPhoneNumberRepo = $this->load( "twilio-phone-number-repository" );

        $this->twilioPhoneNumber = $twilioPhoneNumberRepo->get( [ "*" ], [ "sid" => $this->params[ "sid" ] ], "single" );

        // If no twilio phone number exists
        if ( is_null( $this->twilioPhoneNumber ) ) {
            return;
        }

        $organization = $organizationRepo->get( [ "*" ], [ "id" => $this->twilioPhoneNumber->organization_id ], "single" );
        if ( !is_null( $organization ) ) {
            $this->organization_phone = $phoneRepo->get( [ "*" ], [ "id" => $organization->phone_id ], "single" );
        }
    }

    public function smsAction()
    {
        // TODO get sender data
    }

    public function voiceAction()
    {
        // Forward the call the organization's phone number
        if ( !is_null( $this->organization_phone ) ) {
            $this->twilioServiceDispatcher->forwardCall(
                $this->organization_phone->getE164FormattedPhoneNumber()
            );
        }
    }
}
