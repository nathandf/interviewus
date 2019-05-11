$( function () {
	function getFullName( first_name, last_name ) {
		var fullName = "";
		if ( first_name != null ) {
			fullName = first_name;
			if ( last_name != null ) {
				fullName = fullName + " " + last_name;
			}
		}

		return fullName;
	}

	// Every time page is reloaded, check the radio button
	$( "#yearly" ).prop( "checked", true );

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

	$( ".--c-billing-frequency-label" ).on( "click", function () {
		$( "#" + this.dataset.radio ).prop( "checked", true );
		PricingWidget.multiple = this.dataset.multiple;
		PricingWidget.frequency = this.dataset.frequency_text;
		$( ".plan-price" ).each( function () {
			if ( PricingWidget.frequency == "monthly" ) {
				$( this ).text( "$" + Math.ceil( PricingWidget.multiple * $( this ).data( "base_price" ) ) );
				$( ".frequency-text" ).text( PricingWidget.frequency );
				$( "#billing-frequency" ).val( "monthly" );
				$( "#billing-frequency-text" ).text( "Monthly" );
			} else {
				$( this ).text( "$" + Math.round( PricingWidget.multiple * $( this ).data( "base_price" ) ) );
				$( ".frequency-text" ).text( PricingWidget.frequency );
				$( "#billing-frequency" ).val( "annually" );
				$( "#billing-frequency-text" ).text( "Annually" );
			}
		} );
	} );

	$( ".--create-account" ).on( "click", function () {
		$( "#account-options" ).hide();
		$( "#sign-in-container" ).hide();
		$( "#create-account-container" ).show();
	} );

	$( ".--sign-in" ).on( "click", function () {
		$( "#account-options" ).hide();
		$( "#create-account-container" ).hide();
		$( "#sign-in-container" ).show();
	} );

	// Login form AJAX
	$( "#sign-in-form" ).submit( function( e ) {
        e.preventDefault();
        $.ajax( {
            type : "post",
            url : "sign-in",
            data : $( "#sign-in-form" ).serialize(),
            success : function( response ) {
				var user = JSON.parse( response );
				if ( !user.errors ) {
					$( "#create-account-container" ).hide();
					$( "#account-options" ).hide();
					$( "#sign-in-container" ).hide();
					$( "#checkout-button-container" ).show();
					$( "#user-name" ).text( getFullName( user.first_name, user.last_name ) );
					$( "#user-name-container" ).show();
					return;
				}

				alert( user.errors );
                return;
            },
            error : function() {
                alert( "Something went wrong." );
            }
        } );
        e.preventDefault();
    } );

	// Create account form AJAX
	$( "#create-account-form" ).submit( function( e ) {
        e.preventDefault();
        $.ajax( {
            type : "post",
            url : "create-account",
            data : $( "#create-account-form" ).serialize(),
            success : function( response ) {
				var user = JSON.parse( response );
				if ( !user.errors ) {
					$( "#create-account-container" ).hide();
					$( "#account-options" ).hide();
					$( "#sign-in-container" ).hide();
					$( "#checkout-button-container" ).show();
					$( "#user-name" ).text( getFullName( user.first_name, user.last_name ) );
					$( "#user-name-container" ).show();
					return;
				}

				alert( user.errors );
                return;
            },
            error : function() {
                alert( "Something went wrong." );
            }
        } );
        e.preventDefault();
    } );
} );
