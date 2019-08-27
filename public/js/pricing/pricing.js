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

	Pricing = {
		frequency: "annually"
	};

	// Every time page is reloaded, check the radio button
	$( "#yearly" ).prop( "checked", true );

	$( ".--c-plan-id" ).on( "click", function () {
		$( "input[ name=plan_id ]" ).val( this.dataset.plan_id );
		$( ".price" ).text( this.dataset.base_price );
		total = this.dataset.base_price;
		if ( Pricing.frequency == "annually" ) {
			total = total * 12;
		}
		$( "#total" ).text( total );
	} );

	$( ".--c-billing-frequency-label" ).on( "click", function () {
		$( "#" + this.dataset.radio ).prop( "checked", true );
	} );

	$( ".--c-annually" ).on( "click", function () {
		$( ".billing-frequency-text" ).text( "annually" );
		Pricing.frequency = "annually";
		$( "input[ name=billing_frequency ]" ).val( "annually" );
		$( ".monthly-plan" ).hide();
		$( ".annual-plan" ).show();
	} );

	$( ".--c-monthly" ).on( "click", function () {
		$( ".billing-frequency-text" ).text( "monthly" );
		Pricing.frequency = "monthly";
		$( "input[ name=billing_frequency ]" ).val( "monthly" );
		$( ".monthly-plan" ).show();
		$( ".annual-plan" ).hide();
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
