$( function () {
	$( ".--modal-trigger" ).on( "click", function () {
		$( "#" + this.id + "-modal" ).toggle();
	} );
} );
