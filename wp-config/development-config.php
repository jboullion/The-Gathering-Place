<?php
	//turn on error reporting
	error_reporting(E_ALL ^ E_NOTICE);
	@ini_set('display_errors', 1);

	// database credentials
	define('DB_NAME', 'gathering');
	define('DB_USER', 'root');
	define('DB_PASSWORD', '');
	define('DB_HOST', 'localhost');

	//set an environment variable
	define('ENVIRONMENT', 'dev');

	// override default directory/file permissions (give group write permissions)
	define('FS_CHMOD_DIR', (02775 & ~ umask()));
	define('FS_CHMOD_FILE', (0664 & ~ umask()));

	// override home/site url
	define('WP_HOME', 'http://'.$_SERVER['HTTP_HOST']);
	define('WP_SITEURL', 'http://'.$_SERVER['HTTP_HOST']);