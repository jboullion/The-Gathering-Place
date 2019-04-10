<?php 
	get_header(); 
	the_post(); 

	pk_set('fields', get_fields());

	if(ENVIRONMENT != 'dev') {
		wp_enqueue_script('jquery.party', get_stylesheet_directory_uri() . '/js/live-party.js', 'jQuery', filemtime(get_stylesheet_directory().'/js/live.js'));
	} else {
		wp_enqueue_script('jquery.party', get_stylesheet_directory_uri().'/js/dev-party.js', 'jQuery', time());
	}
?>
<div id="party-page" class="page">
	<?php get_template_part('templates/party/sidebar'); ?>
	<?php get_template_part('templates/party/chatarea'); ?>
	<?php get_template_part('templates/party/gamearea'); ?>
</div>
<?php 
get_footer();