$( function () {
	$( ".--expand" ).on( "click", function () {
		$( ".interview-details-" + this.dataset.interview_id ).toggle( 333 );
		if ( $( this ).text() == "EXPAND" ) {
			$( this ).text( "COLLAPSE" );
		} else {
			$( this ).text( "EXPAND" );
		}
	} );
} );
