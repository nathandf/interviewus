$( function () {
	var audioElement = document.createElement( "audio" );
	audioElement.setAttribute( "src", "http://localhost/interviewus.net/public/static/audio/ding.wav" );

	audioElement.addEventListener( "ended", function() {
		this.pause();
	}, false );

	$( ".--play" ).on( "click", function () {
		audioElement.play();
	} );
} );
