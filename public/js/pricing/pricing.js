$( function () {

	var PricingWidget = {
		plan_id: 1,
		plan_name: "Basic",
		plan_base_price: 19,
		frequency: "annually",
		multiple: 1,
		calculateTotal: function () {
			if ( this.frequency == "annually" ) {
				return ( Math.round( this.plan_base_price * this.multiple ) ) * 12;
			}

			return Math.ceil( this.plan_base_price * this.multiple );
		},
	};

	$( ".--c-plan-id" ).on( "click", function () {
		PricingWidget.plan_id = this.dataset.plan_id
		PricingWidget.plan_name = this.dataset.plan_name;
		PricingWidget.plan_base_price = this.dataset.base_price;
		$( "input[ name=plan_id ]" ).val( this.dataset.plan_id );
		if ( PricingWidget.frequency == "annually" ) {
			$( "#price" ).text( Math.round( PricingWidget.multiple * this.dataset.base_price ) );
		} else {
			$( "#price" ).text( Math.ceil( PricingWidget.multiple * this.dataset.base_price ) );
		}
		$( "#plan-name" ).text( PricingWidget.plan_name );
		$( "#total" ).text( PricingWidget.calculateTotal() );
	} );

	$( ".--c-billing-interval-label" ).on( "click", function () {
		$( "#" + this.dataset.radio ).prop( "checked", true );
		PricingWidget.multiple = this.dataset.multiple;
		PricingWidget.frequency = this.dataset.frequency_text;
		$( ".plan-price" ).each( function () {
			if ( PricingWidget.frequency == "monthly" ) {
				$( this ).text( "$" + Math.ceil( PricingWidget.multiple * $( this ).data( "base_price" ) ) );
				$( ".frequency-text" ).text( PricingWidget.frequency );
				$( "#billing-interval" ).val( "monthly" );
				$( "#billing-interval-text" ).text( "Monthly" );
			} else {
				$( this ).text( "$" + Math.round( PricingWidget.multiple * $( this ).data( "base_price" ) ) );
				$( ".frequency-text" ).text( PricingWidget.frequency );
				$( "#billing-interval" ).val( "annually" );
				$( "#billing-interval-text" ).text( "Yearly" );
			}
		} );
	} );

	$( ".--create-account" ).on( "click", function () {
		$( "#account-options" ).hide();
		$( "#sign-in-container" ).hide();
		$( "#create-account-container" ).show( "slide", { direction: "left" }, 333 );
	} );

	$( ".--sign-in" ).on( "click", function () {
		$( "#account-options" ).hide();
		$( "#create-account-container" ).hide();
		$( "#sign-in-container" ).show( "slide", { direction: "left" }, 333 );
	} );
} );
