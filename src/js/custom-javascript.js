// Add your custom JS here.
(function($) {
	$('.login-toggle').on('click',function(e){
	  console.log('this');
	  e.preventDefault();
	  $('body').toggleClass('login-hide');
	  $(this).find('span').text($(this).find('span').text() == 'Sign Up Now' ? 'Login' : 'Sign Up Now');
	  $(this).siblings('.lines').find('span').text($(this).siblings('.lines').find('span').text() == 'New to Hennepen\'s?' ? 'Already a Member?' : 'New to Hennepen\'s?');
	});

	$('.video-banner a').click(function(e) {
		e.preventDefault();
		console.log('Clicked the video banner!');
    	
  	$('#video_container').html('<iframe src="https://player.vimeo.com/video/485224796?title=1&amp;byline=1&amp;portrait=1&amp;autoplay=true" width="100%" height="720" frameborder="0"></iframe>');
  
});


	
	
})( jQuery );