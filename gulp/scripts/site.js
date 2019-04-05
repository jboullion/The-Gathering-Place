//allow all scripts to use jQuery with the $ handle
var $ = jQuery;

// vars are global for use elsewhere on the site
var	bind = 'click';//('ontouchstart' in document.documentElement ? "touchstart" : "click"); //desktop touchscreens only work with touch when this is set


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

//check to make sure we actually have a gform, otherwise we can just avoid this code.
if($('.gform_wrapper form').length){

	var $form = $('.gform_wrapper form');

	//disable the submit button when the form is submitted
	$form.submit(function(e){
		$('.gform_footer button').prop('disabled', true);
	});

}
