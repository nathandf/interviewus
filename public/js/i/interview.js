$( function () {
	$( "#start-interview" ).on( "click", function () {
		$( "#interview-intro" ).hide( 250, function () {
			$( "#interview" ).delay( 100 ).effect( "slide" );
		} );
	} );
} );
