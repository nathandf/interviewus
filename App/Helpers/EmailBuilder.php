<?php

namespace Helpers;

use Conf\Config;
use Model\DomainObjects\EmailContext;

class EmailBuilder
{
	public $email_templates_dir;
	public $app_details;

	public function __construct( Config $config )
	{
		$this->email_templates_dir = $config->configs[ "dir" ][ "email-templates" ];
		$this->app_details = $config->configs[ "app-details" ];
	}

	public function build( $email_template, EmailContext $emailContext )
	{
		$template = $this->replaceAppDetails(
			file_get_contents( $this->email_templates_dir . $email_template )
		);

		// Replace tags with data from emailContext object
		preg_match_all( "/\{\{([a-zA-Z_0-9]+)\}\}/", $template, $matches );

		foreach ( $matches[ 1 ] as $match ) {
			$template = preg_replace( "/\{\{" . $match . "\}\}/", $emailContext->$match, $template  );
		}

		return $template;
	}

	private function replaceAppDetails( $template )
	{
		foreach ( $this->app_details as $key => $detail ) {
			$template = preg_replace( "/\{\{" . $key . "\}\}/", $detail, $template );
		}

		return $template;
	}
}
