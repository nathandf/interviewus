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

	var InterviewDeploymentWidget = {
		requirements: {
			1: false,
			2: false,
			3: false,
			4: false,
			5: false
		},

		init: function () {
			this.updateRequirements();
		},

		updateRequirements: function () {
			if ( $( ".deployment-type-radio" ).is( ":checked" ) == true ) {
				this.requirements[ 1 ] = true;
			}

			if ( $( ".interviewee-radio" ).is( ":checked" ) == true ) {
				this.requirements[ 2 ] = true;
			}

			if ( $( ".position-radio" ).val() != undefined ) {
				if ( $( ".position-radio" ).is( ":checked" ) ) {
					this.requirements[ 3 ] = true;
				}
			}

			if ( $( ".position-input" ).val() != undefined ) {
				if (
					$( ".position-input" ).val() != "" &&
					$( ".position-input" ).val() != null
				) {
					this.requirements[ 3 ] = true;
				} else {
					this.requirements[ 3 ] = false;
				}
			}

			if ( $( ".interview-template-radio" ).is( ":checked" ) ) {
				this.requirements[ 4 ] = true;
			}

			this.checkRequirements();
		},

		checkRequirements: function () {
			if (
				this.requirements[ 1 ] == true &&
				this.requirements[ 2 ] == true &&
				this.requirements[ 3 ] == true &&
				this.requirements[ 4 ] == true
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
} );
