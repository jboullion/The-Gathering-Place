<?php 
	get_header(); 
	the_post(); 
?>

<div id="default-page" class="page">
	<?php //get_template_part('templates/common/featured'); ?>

	<?php get_template_part('templates/common/content'); ?>
</div>

<?php 

get_footer();