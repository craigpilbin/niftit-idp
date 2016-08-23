( function( $ ) {

	$('ul.nav li.dropdown').hover(function() {
	  $(this).find('.dropdown-menu').stop(true, true).fadeIn(500);
	}, function() {
	  $(this).find('.dropdown-menu').stop(true, true).fadeOut(500);
	});
	// $('.navbar-nav>li>a').hover(
	// function(){
		// $(this).parent().removeClass('open').addClass('open');
	// },
	// function(){
		// $(this).parent().removeClass('open');
	// });

} )( jQuery );