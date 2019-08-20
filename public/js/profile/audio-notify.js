$( function () {
	var audioElement = document.createElement( "audio" );
	audioElement.setAttribute( "src", "http://localhost/interviewus.net/public/static/audio/notification.wav" );

	$( audioElement ).on( "ended", function () {
		this.pause();
	}, false );

	$( ".--play" ).on( "click", function () {
		audioElement.play();
	} );
} );
