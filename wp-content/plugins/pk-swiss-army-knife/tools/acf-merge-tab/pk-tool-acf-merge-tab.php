<?php

	//Combines any ACF tabs on an admin page in to the first one
	/*
	* Ideally you want to make a new custom field group with nothing but a tab
	* on it. This is will function as your container for any other tabs it finds 
	* on the page. Also make sure that blank tab field group is the FIRST field group
	* being displayed on that page. TODO:Make it so there has to be a field group tab
	* with a class of "options"
	*/
	add_action('admin_footer', 'pk_tool_acf_tab_merge');
	function pk_tool_acf_tab_merge(){
		$screen = get_current_screen();
		if ( $screen->base == 'post' ) {
			echo apply_filters('pk_tool_acf_tab_merge_content','
			<!-- ACF Merge Tabs -->
			<script>		

				var $boxes = jQuery(".postbox .acf-field-tab").parent(".inside");

				if ( $boxes.length > 1 ) {

				    var $firstBox = $boxes.first();

				    $boxes.not($firstBox).each(function(){
					    jQuery(this).children().appendTo($firstBox);
					    jQuery(this).parent(".postbox").remove();				    
				    });

				   $firstBox.find("div").first().remove();
				}
				
			</script>');
		}
	}