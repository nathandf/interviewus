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
		selectedInterviewTemplateTagID: null,
		selectedIntervieweeTagID: null,
		setSelectedInterviewTemplateTagID: function ( id ) {
			this.selectedInterviewTemplateTagID = id;
		},
		setSelectedIntervieweeTagID: function ( id ) {
			this.selectedIntervieweeTagID = id;
		}
	};

	// On click, tick the radio button and add a class to the tag that is related
	// to the radio button
	$( ".deployment-interview-template-tag" ).on( "click", function () {
		$( "#interview-template-radio-" + this.dataset.interview_template_id ).prop( "checked", true );
		$( this ).addClass( "selected-tag" );
		if ( tagTracker.selectedInterviewTemplateTagID != null ) {
			if ( this.id != tagTracker.selectedInterviewTemplateTagID ) {
				$( "#" + tagTracker.selectedInterviewTemplateTagID ).removeClass( "selected-tag" );
			}
		}
		tagTracker.setSelectedInterviewTemplateTagID( "interview-template-tag-" + this.dataset.interview_template_id );
	} );

	$( ".deployment-interviewee-tag" ).on( "click", function () {
		$( "#interviewee-radio-" + this.dataset.interviewee_id ).prop( "checked", true );
		$( this ).addClass( "selected-tag" );
		if ( tagTracker.selectedIntervieweeTagID != null ) {
			if ( this.id != tagTracker.selectedIntervieweeTagID ) {
				$( "#" + tagTracker.selectedIntervieweeTagID ).removeClass( "selected-tag" );
			}
		}
		tagTracker.setSelectedIntervieweeTagID( "interviewee-tag-" + this.dataset.interviewee_id );
	} );

	$( ".sortable" ).sortable({
    	revert: false,
		cursor: "grabbing",
		containment: ".sortable-container",
		handle: ".drag-handle"
    });

	$( ".draggable-y" ).draggable({
		connectToSortable: ".sortable",
		containment: ".sortable-container",
		handle: ".drag-handle"
	});
} );
