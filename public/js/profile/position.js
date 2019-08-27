$( function () {
	$( ".property" ).on( "keyup change", function () {
		$( ".--update-position-button" ).each( function () {
			$( this ).prop( "disabled", false )
		} );
	} );
} );
