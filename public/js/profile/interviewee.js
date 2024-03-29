$( function () {
	$( "#image-uploader" ).change( function () {
        // Check if a file has been selected or not
        if ( this.files && this.files[ 0 ] ) {
            // Check if the uploaded file is an Image file
            if ( this.files[ 0 ].type.startsWith( "image/" ) ) {
				var reader = new FileReader();
				reader.readAsDataURL( this.files[ 0 ] );
				reader.onloadend = function () {
					$( "#uploaded-image" ).show().attr( "src", reader.result );
				}
            } else {
                // If an image is not selected then show an other image.
                $( "#uploaded-image" ).attr
                ( "src", "http://placehold.it/550x270&text=No+PreView!" );
            }
        }
    } );
} );
