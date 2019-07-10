<?php

namespace Core;

class Request
{
	private $request_types = [ "get", "post", "put", "delete" ];
	private $origin_whitelist = [];

	public function is( $request_type )
	{
		if ( !in_array( $request_type, $this->request_types ) ) {
			throw new \Exception( "Invalid request types" );
		}

		if ( strtolower( $this->method() ) == $request_type ) {
			return true;
		}

		return false;
	}

	public function get( $key = null )
	{
		if ( isset( $_GET ) ) {
			if ( !is_null( $key ) ) {
				if ( isset( $_GET[ $key ] ) ) {
					return $_GET[ $key ];
				}

				return "";
			}

			return $_GET;
		}

		return null;
	}

	public function post( $key = null )
	{
		if ( isset( $_POST ) ) {
			if ( !is_null( $key ) ) {
				if ( isset( $_POST[ $key ] ) ) {
					return $_POST[ $key ];
				}

				return "";
			}

			return $_POST;
		}

		return null;
	}

	public function method()
	{
		return filter_input( INPUT_SERVER, "REQUEST_METHOD" );
	}

	public function queryString()
	{
		return filter_input( INPUT_SERVER, "QUERY_STRING" );
	}

	public function setParams( $params )
	{
		$this->params = $params;
		return $this;
	}

	public function params( $key = null )
	{
		if ( isset( $this->params ) ) {
			if ( !is_null( $key ) ) {
				if ( isset( $this->params[ $key ] ) ) {
					return $this->params[ $key ];
				}

				throw new \Exception( "Param {$key} not set" );
			}

			return $this->params;
		}
	}

	// TODO validate that origins are valid URLs
	public function populateWhitelist( $origins )
	{
		if ( is_array( $origins ) ) {
			foreach ( $origins as $origin ) {
				$this->origin_whitelist[] = $origin;
			}

			return;
		}

		$this->origin_whitelist[] = $origins;

		return;
	}

	public function allowOrigin( $origin )
	{
		if ( in_array( $origin, $this->origin_whitelist ) ) {
			header( "Access-Control-Allow-Origin: " . $origin );

			return true;
		}

		return false;
	}

	public function getOrigin()
	{
		if ( array_key_exists( "HTTP_ORIGIN", $_SERVER ) ) {
			$origin = $_SERVER[ "HTTP_ORIGIN" ];
		} elseif ( array_key_exists( "HTTP_REFERER", $_SERVER ) ) {
			$origin = $_SERVER[ "HTTP_REFERER" ];
		} else {
			$origin = $_SERVER[ "REMOTE_ADDR" ];
		}

		return $origin;
	}
}
