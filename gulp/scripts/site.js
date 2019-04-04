//allow all scripts to use jQuery with the $ handle
var $ = jQuery;

// vars are global for use elsewhere on the site
var	bind = 'click';//('ontouchstart' in document.documentElement ? "touchstart" : "click"); //desktop touchscreens only work with touch when this is set
	$toggle_btn = $('#mobile-navigation-toggle'),
	$mobile_container = $('#mobile-navigation'),
	isScrolling = false; //used in the smooth scroll function


//SMOOTH SCROLLING
var smooth_scroll_site_offset = 0,
	doNotScroll = ".carousel-control, [data-lity], .nav-tabs a"; 

$('a[href*=\\#]:not([href=\\#])').not( doNotScroll ).click(function() {
	if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
		var target = $(this.hash),
			target = target.length ? target : $('[name=' + this.hash.slice(1) +']');

		if(target.length) {
			isScrolling = true;
			$('html,body').animate({
				scrollTop: target.offset().top - ($(this).attr('data-scroll-offset') ? $(this).attr('data-scroll-offset') : 0) - smooth_scroll_site_offset
			}, 1000, function() {
				isScrolling = false;
			});
		
		return false;
		}
	}
});

/**
 * Initialize Lazyload
 * http://jquery.eisbehr.de/lazy/
 */
$(function() {
	$('.lazy').Lazy();
});

/**
 * Limit numeric inputs
 *
 * Accepts: 0-9, ".", ",", backspace, arrow keys, tab, and delete
 * 
 */
$('body').on('keydown', '.number-input', function(e){
	//console.log('Number input testing: '+e.which);

	if (!((e.which >= 48 && e.which <= 57) || (e.which >= 96 && e.which <= 105) || e.which == 110 || (e.which == 173 || e.which == 109) || e.which == 188 || e.which == 190 || e.which == 8 || e.which == 9 || e.which == 37 || e.which == 39 || e.which == 46 )) {
		return false;
	}

});

//RESIZE ACTIONS
//When you need to take action on resize. Uses debounce functionality
//leave commented out unless needed.
/*
var resizeTimer = null;

$(window).on('resize', function(e) {

  clearTimeout(resizeTimer);
  resizeTimer = setTimeout(function() {

  }, 100);

});
*/

/**
 * GRAVITY FORMS
 *
 * Gravity forms need's a little love to work how we want. Put that code here
 */
//Default functionality for file upload form fields
$(document).bind('gform_post_render', function(){
	$(".gfield.file input[type='file']").on('change', function() {
		var filename = $(this).val().replace("C:\\fakepath\\", "");
		$(this).parent().parent().find('label').attr('data-content',filename);
	});

	//Setup some JS validation for the gravity forms. Specifically targeting billing values.
	$('.address_zip input').addClass('number-input').attr('maxlength', 5);
	$('.ginput_container_creditcard .ginput_full').first().find('input').addClass('number-input').attr('maxlength', 19);
	$('.ginput_cardinfo_right input').addClass('number-input').attr('maxlength', 4);

});


//check to make sure we actually have a gform, otherwise we can just avoid this code.
if($('.gform_wrapper form').length){

	var $form = $('.gform_wrapper form');

	//disable the submit button when the form is submitted
	$form.submit(function(e){
		$('.gform_footer button').prop('disabled', true);
	});

}
