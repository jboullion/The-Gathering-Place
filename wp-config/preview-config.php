<?php
	// turn off error reporting
	error_reporting(0);
	@ini_set('display_errors', 0);
	
	// database credentials
	define('DB_NAME', '');
	define('DB_USER', 'econoweb');
	define('DB_PASSWORD', 'dewbedewbedew');
	define('DB_HOST', 'localhost');

	// environment variable
	define('ENVIRONMENT', 'preview');
	
	// restrict users from editing files within admin
	define('DISALLOW_FILE_EDIT', true);