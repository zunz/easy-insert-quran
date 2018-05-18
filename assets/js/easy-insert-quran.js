jQuery(function($){
	
	$('.ins-q-audio audio').on('ended', function(){
		var nextAudio = $(this).closest('.ins-q-entry').next('.ins-q-entry').find('audio');
		if(nextAudio.length > 0) {
			nextAudio[0].currentTime = 0;
			nextAudio.trigger('play');
		}
	});
	$('.ins-q-audio audio').on('play', function(){
		$('.ins-q-audio audio').not(this).trigger('pause');
	});
});