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

    $( ".--c-cancel-confirm" ).on( "click", function( event ) {
        confirmation = confirm( "Cancelling your subscription is permanant. You're remaining interviews will be unaffected until the end of your billing cycle but access to advanced features will be limited immediately upon cancellation. Are you sure you want to continue?" );
        if ( confirmation === false ) {
            event.preventDefault();
        }
    } );

    $( ".--c-confirm" ).on( "click", function( event ) {
        confirmation = confirm( "Are you sure you want to continue? This action is permanant." );
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

    $( ".row-link" ).on( "click", function () {
        window.location = $( this ).data( "href" );
    } );

    $( ".lightbox-close" ).on( "click", function () {
        $( this ).parent().hide();
    } );

    $( ".con-message-success" ).delay( 5000 ).fadeOut( 1000 );

} );
