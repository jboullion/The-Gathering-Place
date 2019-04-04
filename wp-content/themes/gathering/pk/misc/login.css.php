<?php if(!defined('ABSPATH')) { die(); } ?>
#login {
	padding-top:<?php echo ($height * 2); ?>px !important;
	margin:0 auto !important;
}

#login h1 {
	top:<?php echo floor($height / 2); ?>px !important;
	left:0 !important;
	width:100% !important;
	height:auto !important;
	margin:0 auto !important;
	padding:0 !important;
	overflow:hidden !important;
	position:absolute !important;
}

#login h1 a {
	background-image:url(<?php echo pk_theme_relative($logo); ?>) !important;
	background-size:<?php echo $width; ?>px <?php echo $height; ?>px !important;
	width:<?php echo $width; ?>px !important;
	height:<?php echo $height; ?>px !important;
	margin:0 auto !important;
	padding:0 !important;
	display:block !important;
	position:relative !important;
}