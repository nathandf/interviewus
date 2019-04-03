$( function () {
	var questionBuilder = {
		questionCount: 1,
		newQuestion: function () {
			this.questionCount++;
			var label, textarea;

			label = "<p class=\"label push-t-sml\">Question " + this.questionCount + "</p>";
			textarea = "<textarea name=\"questions[]\" id=\"question-textarea-" + this.questionCount + "\" class=\"inp textarea inp-full\"></textarea>";
			question_container = "<div id=\"question-" + this.questionCount + "\">" + label + textarea + "</div>";

			return question_container;
		}
	};

	$( "#add-question" ).on( "click", function () {
		$( "#questions-container" ).append( questionBuilder.newQuestion() );
	} );
} );
