<?php
	/* Template Name: Homepage Template */
	get_header(); 
	the_post();

	pk_set('fields', get_fields());
?>
<?php get_template_part('templates/common/header'); ?>
<div id="home-page" class="page">
	<?php get_template_part('templates/common/featured'); ?>
	<?php get_template_part('templates/common/content'); ?>
</div>
<?php get_template_part('templates/common/modal-login'); ?>
<?php 
	get_template_part('templates/common/footer');
	get_footer();