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

	var tagTracker = {
		selectedTagID: null,
		setSelectedTagID: function ( id ) {
			this.selectedTagID = id;
		}
	};

	// On click, tick the radio button and add a class to the tag that is related
	// to the radio button
	$( ".deployment-interview-template-tag" ).on( "click", function () {
		$( "#interview-template-radio-" + this.dataset.interview_template_id ).prop( "checked", true );
		$( this ).addClass( "selected-tag" );
		if ( tagTracker.selectedTagID != null ) {
			if ( this.id != tagTracker.selectedTagID ) {
				$( "#" + tagTracker.selectedTagID ).removeClass( "selected-tag" );
			}
		}
		tagTracker.setSelectedTagID( "interview-template-tag-" + this.dataset.interview_template_id );
	} );
} );
