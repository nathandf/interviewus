<?php

namespace Helpers;

class TimeZoneHelper
{
    public $server_timezone;

    public function getTimeZoneOffset( $timezone_1, $timezone_2 )
    {
        // Get UTC DateTimeZone object and DateTimeZone object for businesses timezone
        $dateTimeZone1 = new \DateTimeZone( $timezone_1 );
        $dateTimeZone2 = new \DateTimeZone( $timezone_2 );

        // Get DateTime object from DateTimeZone Object
        $dateTime1 = new \DateTime( "now", $dateTimeZone1 );
        $dateTime2 = new \DateTime( "now", $dateTimeZone2 );

        // Get time offset in seconds
        return $dateTimeZone1->getOffset( $dateTime1 ) - $dateTimeZone2->getOffset( $dateTime2 );
    }

    public function getServerTimeZoneOffset( $timezone )
    {
        return $this->getTimeZoneOffset( $this->getServerTimezone(), $timezone );
    }

    private function getServerTimezone()
    {
        if ( isset( $this->server_timezone ) ) {
            return $this->server_timezone;
        }

        $this->server_timezone = date_default_timezone_get();

        return $this->server_timezone;
    }

    public function getUTCTimeZoneOffset( $timezone )
    {
        $dateTime = new \DateTime( "now", new \DateTimeZone( $timezone ) );
        
        return $dateTime->getOffset();
    }
}
