<?php

namespace Model\Services\IPDataAPI;

use Conf\Config;

class Gateway
{
	private $api_key;
	public $endpoint = "https://api.ipdata.co";

	public function __construct( Config $config )
	{
		$this->api_key = $config->getConfigs()[ "packages" ][ "ipdata" ][ "api-key" ];
	}

	// Determines the geographic data related to the ip address making the query
	public function query()
	{
		$curl = curl_init();

		curl_setopt_array(
			$curl,
			[
				CURLOPT_URL => $this->endpoint . "?api-key=" . $this->api_key,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
			]
		);

		$response = curl_exec( $curl );

		curl_close( $curl );

		return json_decode( $response );
	}
}
