<?php
	// turn off error reporting
	error_reporting(0);
	@ini_set('display_errors', 0);
	
	// database credentials
	define('DB_NAME', '');
	define('DB_USER', '');
	define('DB_PASSWORD', '');
	define('DB_HOST', '');
	
	// environment variable
	define('ENVIRONMENT', 'live');
	
	// restrict users from editing files within admin
	define('DISALLOW_FILE_EDIT', true);