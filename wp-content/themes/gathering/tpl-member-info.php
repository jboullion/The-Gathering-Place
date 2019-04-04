<?php 
	/* Template Name: Membership Info Template */
	get_header(); 
	the_post(); 

	pk_set('fields', get_fields());
?>

<div id="member-info-page" class="page">
	<?php get_template_part('templates/common/featured'); ?>
	<?php get_template_part('templates/common/content'); ?>
	<?php get_template_part('templates/common/content', 'rows'); ?>
	<?php get_template_part('templates/common/memberships'); ?>
</div>

<?php 
get_footer();