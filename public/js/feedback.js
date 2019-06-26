$( function () {
	$( "#user-feedback-form" ).submit( function( e ) {
        e.preventDefault();
        $.ajax( {
            type : "post",
            url : $( this ).attr( "action" ),
            data : $( this ).serialize(),
            success : function( response ) {
				$( "#feedback-form-container" ).hide( "drop", function () {
					$( "#feedback-success" ).show( "slide", 300 );
				} );
                return;
            },
            error : function() {
                alert( "Something went wrong." );
            }
        } );
        e.preventDefault();
    } );

	$( ".--feedback-modal-close" ).on( "click", function () {
		$( "#user-feedback-modal" ).hide();
	} );
} );
