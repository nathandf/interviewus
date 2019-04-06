$( function () {
	$( ".--existing-question" ).on( "keyup", function () {
		$( ".--update-questions-button" ).each( function () {
			$( this ).prop( "disabled", false )
		} );
		$( "#update-existing-questions-input" ).val( true );
		$( "#existing-question-" + this.dataset.id ).val( $( this ).text() );
	} );
} );
