$( function () {
	$( ".--c-plan-id" ).on( "click", function () {
		$( "input[ name=plan_id ]" ).val( this.dataset.plan_id );
	} );

	$( ".--c-billing-interval-label" ).on( "click", function () {
		$( "#" + this.dataset.radio ).prop( "checked", true );
		var multiple = this.dataset.multiple;
		var frequency = this.dataset.frequency_text;
		$( ".plan-price" ).each( function () {
			if ( frequency == "monthly" ) {
				$( this ).text( "$" + Math.ceil( multiple * $( this ).data( "base_price" ) ) );
				$( ".frequency-text" ).text( frequency );
			} else {
				$( this ).text( "$" + Math.round( multiple * $( this ).data( "base_price" ) ) );
				$( ".frequency-text" ).text( frequency );
			}
		} );
	} );
} );
