<!DOCTYPE html>
<html>
	<head>
	<meta charset="utf-8>	
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
		<title>Vanilla JS Game Board</title>
		<link rel="profile" href="http://gmpg.org/xfn/11">
		
		<!-- Favicons -->
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/favicons/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/favicons/favicon-16x16.png">
		<link rel="manifest" href="/favicons/site.webmanifest">
		<link rel="mask-icon" href="/favicons/safari-pinned-tab.svg" color="#13537d">
		<meta name="msapplication-TileColor" content="#2b5797">
		<meta name="theme-color" content="#ffffff">
		<link rel='stylesheet' id='live-css'  href='styles/dev.css' type='text/css' media='all' />
		<script src="https://code.jquery.com/jquery-3.4.0.min.js"
				integrity="sha256-BJeo0qm959uMBGb65z40ejJYGSgR7REI4+CW1fNKwOg="
				crossorigin="anonymous"></script>
	</head>
	<body>
		<style>
			/* Undoing some styling from our WP version */
			#party-page { height: auto; display: block; }
			#party-page #party-gamearea { max-width: none; width: 100%; }
			#party-page #party-gamearea #gamearea-map { margin: 0 auto; }
		</style>
		<div id="party-page" class="page">
			<?php //include 'chatarea.php'; ?>
			<?php include 'gamearea.php'; ?>
		</div>

		<script type='text/javascript' src='js/live.js' defer></script>
		<script type='text/javascript' src='js/live-party.js' defer></script>
	</body>
</html>