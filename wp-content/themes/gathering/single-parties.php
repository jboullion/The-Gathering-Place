<?php 
	get_header(); 
	the_post(); 

	pk_set('fields', get_fields());
?>
<div id="party-page" class="page">
	<?php get_template_part('templates/party/sidebar'); ?>
	<?php get_template_part('templates/party/chatarea'); ?>
	<?php get_template_part('templates/party/gamearea'); ?>
</div>
<?php 
get_footer();