
// vars are global for use elsewhere on the site
var bind = 'click',
	 pk_main_navigation_container = {},
	 pk_main_navigation_toggle = {},
	 pkMenuHold = false; //need to prevent the touch events from firing too quickly. 

if( pkIsMobile()){
	bind = 'touchstart';
}

jQuery(document).ready(function($) {

	$toggle_btn = $('#mobile-navigation-toggle');
	$mobile_container = $('#mobile-navigation');
	$search_btn = $('#main-navigation .search-btn');

	// binding to touchstart gives much faster response times on mobile devices
	$toggle_btn.on(bind, function(e) {

		$mobile_container.toggleClass('open');

		if($mobile_container.is(':visible')){
			$(this).removeClass('closed').addClass('open');
		}else{
			$(this).removeClass('open').addClass('closed');
		}

	});

	$('.toggle-dropdown').on(bind, function(e) {
		$(this).prev().toggle();

		if($(this).prev().is(':visible')){
			$(this).addClass('down');//.removeClass('fa-angle-down').addClass('fa-angle-up');
		}else{
			$(this).removeClass('down');//.removeClass('fa-angle-up').addClass('fa-angle-down');
		}

	});

	// handle any odd things that may happen on resize
	$(window).resize(function() {
		if($toggle_btn.is(":hidden")){
			$mobile_container.removeClass('open');
			$toggle_btn.removeClass('open').addClass('closed');
		}
	});

	// Toggle search
	$search_btn.click(function() {
		$('#main-navigation .search-btn, #main-navigation .search-wrapper').toggleClass('open');

		if($('#main-navigation .search-btn i').hasClass('fa-search')) {
			$('#main-navigation .search-btn i').addClass('fa-times').removeClass('fa-search');
		}else{
			$('#main-navigation .search-btn i').addClass('fa-search').removeClass('fa-times');
		}
		
	});
	
});


function pkIsMobile() { return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent); }
function pkIsMobileMenuClosed(container) { return !(jQuery(container ? container : pk_main_navigation_container).css('left') == '0px'); }
function pkIsMobileMenuResponsive(container) { return jQuery(container ? container : pk_main_navigation_container).css('position') == 'fixed'; }
function pkCloseMobileNav(container) { }
function pkOpenMobileNav(container) { }
function pkToggleMobileSubNav(li, ul) { }

