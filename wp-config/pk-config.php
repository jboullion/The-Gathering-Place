<?php
	$config_directory = dirname(__FILE__).'/';
	
	// include each file if the previous isn't found
	if(file_exists($dev_config = $config_directory.'development-config.php')) {
		require_once($dev_config);
	} elseif(file_exists($preview_config = $config_directory.'preview-config.php')) {
		require_once($preview_config);
	} elseif(file_exists($live_config = $config_directory.'live-config.php')) {
		require_once($live_config);
	} else {
		die('No configuration file found.');
	}

	// if they haven't ran the install yet, don't let them past this point
	if(!DB_USER) { die('<h1>Please run the setup script as detailed in the <a href="http://wiki.devpki.us/Setting_Up_A_New_Wordpress_Site" target="_blank">Powderkeg Wiki</a>.</h1>'); }