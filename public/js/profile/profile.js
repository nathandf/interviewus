$( function () {
	$( ".--modal-trigger" ).on( "click", function () {
		$( "#" + this.id + "-modal" ).slideDown( 250 );
	} );
} );
