// Add your custom JS here.
(function($) {
	$(document).ready(function(){
		$('body').removeClass('fade-out');		
	});
	$('.login-toggle').on('click',function(e){
	  e.preventDefault();
	  $('body').toggleClass('login-hide');
	  $(this).find('span').text($(this).find('span').text() == 'Sign Up Now' ? 'Login' : 'Sign Up Now');
	  $(this).siblings('.lines').find('span').text($(this).siblings('.lines').find('span').text() == 'New to Hennepen\'s?' ? 'Already a Member?' : 'New to Hennepen\'s?');
	});
	
})( jQuery );