<?php 
	/* Template Name: Contact Template */
	get_header(); 
	the_post(); 

	pk_set('fields', get_fields());
?>

<div id="contact-page" class="page">
	<?php get_template_part('templates/common/featured'); ?>
	<?php get_template_part('templates/contact/contact'); ?>
</div>

<?php 
get_footer();