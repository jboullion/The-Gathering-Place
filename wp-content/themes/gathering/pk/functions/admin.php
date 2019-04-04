<?php
	
	// if we aren't in admin, get out
	if(!is_admin()) { return; }
	
	// removes unnessary WordPress dashboard widgets
	add_action('wp_dashboard_setup', 'pk_remove_dashboard_widgets');
	function pk_remove_dashboard_widgets() {
		remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
		remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');
		remove_meta_box('dashboard_primary', 'dashboard', 'side');
		remove_meta_box('dashboard_secondary', 'dashboard', 'side');
		remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
		remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
	}


	// displays custom error message whenever passed via the URL string variable 'pke'
	add_action('admin_notices', 'pk_custom_messages');
	function pk_custom_messages() {
		if($_GET['pke']) {
			echo '<div class="error"><p>'.$_GET['pke'].'</p></div>';
		}
		if($_GET['pkm_success']) {
			echo '<div class="updated"><p>'.$_GET['pkm_success'].'</p></div>';
		}
	}
	
	// restrict admins from being edited by the common folk
	add_action('admin_head', 'pk_restrict_user_edit');
	function pk_restrict_user_edit() {
		global $current_screen;
		
		$error_key = '';
		$edit_user_id = 0;
		
		// determine which screen they're trying to access
		if($current_screen->base == 'user-edit') {
			$edit_user_id = $_GET['user_id'];
			$error_key = 'edit';
		} elseif($current_screen->base == 'users' && $_GET['action'] == 'delete') {
			$edit_user_id = $_GET['user'];
			$error_key = 'delete';
		}
		
		if($edit_user_id) {
			if(!pk_is_admin()) {
				$edit_user = get_userdata($edit_user_id);
				if(in_array('administrator', $edit_user->roles)) {
					header('Location: /wp-admin/users.php?pke='.urlencode('You do not have the required permissions to '.$error_key.' that user.')); exit();
				}
			}
		}
	}
	
	// removes the role of administrator from the user role dropdown
	add_action('editable_roles', 'pk_update_roles_dropdown');
	function pk_update_roles_dropdown($roles){
		ksort($roles);
		
		if(!pk_is_admin()) {
			unset($roles['administrator']);
		}
		
		return $roles;
	}