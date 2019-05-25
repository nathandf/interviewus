$( function () {
	$( "#view-plan-details" ).on( "click", function () {
		$( "#plan-container" ).slideToggle();
	} );

	$( "#add-payment-method" ).on( "click", function () {
		$( "#payment-methods" ).hide();
		$( "#new-payment-method" ).show();
		$( "#hide" ).show();
		$( "#add-payment-method" ).hide();
	} );

	$( "#hide" ).on( "click", function () {
		$( "#new-payment-method" ).hide();
		$( "#hide" ).hide();
		$( "#payment-methods" ).show();
		$( "#add-payment-method" ).show();
	} );
} );
