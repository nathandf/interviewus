<?php

namespace Views;

use Core\View;

abstract class Page extends View
{	
	protected function showFacebookPixel( array $events = [] )
	{
		$facebookPixelRepo = $this->load( "facebook-pixel-repository" );
		$facebookPixel = $facebookPixelRepo->get( [ "*" ], [ "name" => "primary" ], "single" );
		
		$facebookPixelBuilder = $this->load( "facebook-pixel-builder" );
		$facebookPixelBuilder->addEvent( "PageView" );
		foreach ( $events as $event ) {
			$facebookPixelBuilder->addEvent( $event );
		}
		$facebookPixelBuilder->addPixelID( $facebookPixel->pixel_id );
		
		$this->assign( "facebook_pixel_code", $facebookPixelBuilder->build() );
	}
	
	protected function pageTitle( $title )
	{
		$this->assign( "page_title", "<title>{$title}</title>" );
	}
}
