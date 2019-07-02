$( function () {
	var questionBuilder = {
		questionCount: 1,
		newQuestion: function () {
			this.questionCount++;
			var label, textarea;

			label = "<p class=\"label push-t-sml\">Question " + this.questionCount + "</p>";
			textarea = "<textarea name=\"questions[]\" id=\"question-textarea-" + this.questionCount + "\" class=\"inp textarea inp-full\" required=\"required\"></textarea>";
			question_container = "<div id=\"question-" + this.questionCount + "\">" + label + textarea + "</div>";

			return question_container;
		}
	};

	$( "#add-question" ).on( "click", function () {
		$( "#questions-container" ).append( questionBuilder.newQuestion() );
	} );

	$( function () {
		$( ".--expand" ).on( "click", function () {
			$( ".interview-details-" + this.dataset.interview_id ).toggle( 250 );
			if ( $( this ).text() == "EXPAND" ) {
				$( this ).text( "COLLAPSE" );
			} else {
				$( this ).text( "EXPAND" );
			}
		} );
	} );

	var InterviewDeploymentWidget = {
		requirements: {
			"deployment-type": false,
			"interviewee": false,
			"position": false,
			"template": false,
			"schedule-type": false
		},

		init: function () {
			this.updateRequirements();
		},

		updateRequirements: function () {
			if ( $( ".deployment-type-radio" ).is( ":checked" ) == true ) {
				this.requirements[ "deployment-type" ] = true;
			}

			if ( $( ".interviewee-radio" ).is( ":checked" ) == true ) {
				this.requirements[ "interviewee" ] = true;
			}

			if ( $( ".position-radio" ).val() != undefined ) {
				if ( $( ".position-radio" ).is( ":checked" ) ) {
					this.requirements[ "position" ] = true;
				}
			}

			if ( $( ".position-input" ).val() != undefined ) {
				if (
					$( ".position-input" ).val() != "" &&
					$( ".position-input" ).val() != null
				) {
					this.requirements[ "position" ] = true;
				} else {
					this.requirements[ "position" ] = false;
				}
			}

			if ( $( ".interview-template-radio" ).is( ":checked" ) ) {
				this.requirements[ "template" ] = true;
			}

			if ( $( ".schedule-type-radio" ).is( ":checked" ) ) {
				if ( $( ".schedule-type-radio:checked" ).val() == 1 ) {
					this.requirements[ "schedule-type" ] = true;
				}

				if ( $( ".schedule-type-radio:checked" ).val() == 2 ) {
					if ( $( "#datepicker" ).val() != "" ) {
						this.requirements[ "schedule-type" ] = true;
					} else {
						this.requirements[ "schedule-type" ] = false;
					}
				}
			}

			this.checkRequirements();
		},

		checkRequirements: function () {
			if (
				this.requirements[ "deployment-type" ] == true &&
				this.requirements[ "interviewee" ] == true &&
				this.requirements[ "position" ] == true &&
				this.requirements[ "template" ] == true &&
				this.requirements[ "schedule-type" ] == true
			) {
				return true;
			}

			return false;
		}
	}

	InterviewDeploymentWidget.init();

	$( ".position-input" ).on( "keyup", function () {
		InterviewDeploymentWidget.updateRequirements();
		if ( InterviewDeploymentWidget.checkRequirements() ) {
			$( "#deploy-interview-button" ).prop( "disabled", false );
		} else {
			$( "#deploy-interview-button" ).prop( "disabled", true );
		}
	} );

	$( ".--c-deployment-requirement" ).on( "change", function () {
		InterviewDeploymentWidget.updateRequirements();
		if ( InterviewDeploymentWidget.checkRequirements() ) {
			$( "#deploy-interview-button" ).prop( "disabled", false );
		} else {
			$( "#deploy-interview-button" ).prop( "disabled", true );
		}
	} );

	$( "#schedule" ).on( "click", function () {
		$( "#date-time-picker-container" ).show( 250 );
	} );

	$( "#immediate" ).on( "click", function () {
		$( "#date-time-picker-container" ).hide( 250 );
	} );

	$( "#datepicker" ).datepicker();
} );
