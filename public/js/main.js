$( function() {
    $( ".--c-hide" ).on( "click", function() {
        $( this ).hide( );
    } );

    $( ".--c-logout" ).on( "click", function( event ) {
        confirmation = confirm( "Are you sure you want to logout?" );
        if ( confirmation === false ) {
            event.preventDefault();
        }
    } );

    $( ".--c-trash" ).on( "click", function( event ) {
        confirmation = confirm( "Are you sure you want to delete this?" );
        if ( confirmation === false ) {
            event.preventDefault();
        }
    } );

    $( ".--c-mp-confirm" ).on( "click", function( event ) {
        confirmation = confirm( "Confirm prospect to member conversion." );
        if ( confirmation === false ) {
            event.preventDefault();
        }
    } );

    $( "input:file" ).change(
        function() {
            if ( $( this ).val() ) {
                $( ".file-upload-button" ).show();
                $( ".file-upload-container" ).show();
                $( ".file-upload-field-container" ).show();
            }
        }
    );

    $( "#nav-dropdown-button" ).on( "click", function() {
        $( "#nav-items-container" ).slideToggle( 250 );
        $( "#nav-items-container" ).scrollTop();
    } );

    $( ".--c-advanced-options" ).on( "click", function() {
        $( ".advanced-options" ).slideToggle();
    } );

    $( ".--update-button" ).on( "click", function () {
        $( this ).html( "Updating<i class=\"fa fa-spinner fa-spin push-l-sml\" aria-hidden=\"true\"></i>" );
    } );

    $( ".--save-button" ).on( "click", function () {
        $( this ).html( "Saving<i class=\"fa fa-spinner fa-spin push-l-sml\" aria-hidden=\"true\"></i>" );
    } );

    $( ".--delete-button" ).on( "click", function () {
        $( this ).html( "Deleting<i class=\"fa fa-spinner fa-spin push-l-sml\" aria-hidden=\"true\"></i>" );
    } );

    $( ".--load-button" ).on( "click", function () {
        $( this ).html( "Loading<i class=\"fa fa-spinner fa-spin push-l-sml\" aria-hidden=\"true\"></i>" );
    } );

    $( ".--upload-button" ).on( "click", function () {
        $( this ).html( "Uploading<i class=\"fa fa-spinner fa-spin push-l-sml\" aria-hidden=\"true\"></i>" );
    } );

    $( ".--create-button" ).on( "click", function () {
        $( this ).html( "Creating<i class=\"fa fa-spinner fa-spin push-l-sml\" aria-hidden=\"true\"></i>" );
    } );

    // Trigger modals with based on the buttons id
    $( ".--modal-trigger" ).on( "click", function () {
		$( "#" + this.id + "-modal" ).show( 0, function () {
            $( "#" + this.id + " > div.--modal-content" ).effect( "slide" );
        } );
	} );

    $( ".lightbox-close" ).on( "click", function () {
        $( this ).parent().hide();
    } );
} );
