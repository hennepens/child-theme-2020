// Add your custom JS here.
(function($) {
	$('.login-toggle').on('click',function(e){
	  console.log('this');
	  e.preventDefault();
	  $('body').toggleClass('login-hide');
	  $(this).find('span').text($(this).find('span').text() == 'Sign Up Now' ? 'Login' : 'Sign Up Now');
	  $(this).siblings('.lines').find('span').text($(this).siblings('.lines').find('span').text() == 'New to Hennepen\'s?' ? 'Already a Member?' : 'New to Hennepen\'s?');
	});
	
	$( document ).ready(function() {
		var cbdReplace = $('.products li.product h2').html();
		cbdReplace = cbdReplace.replace('CBD', 'CBD<br/>');
		cbdReplace2 = cbdReplace.replace('Hemp', 'Hemp<br/>');
		$('.products li.product h2').html(cbdReplace);
		$('.products li.product h2').html(cbdReplace2);
	});
})( jQuery );