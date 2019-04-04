<?php 
	global $post;

	$fields = pk_get('fields');
	$options = pk_get('options');
	
	if(has_post_thumbnail()){
		$featured_image = get_the_post_thumbnail_url( $post, 'full' );
	}else{
		$featured_image = $options['default_featured_image']['url'];
	}
?>
<section class="wrapper no-print setup-background" id="featured" style="background-image: url(<?php echo $featured_image; ?>);"></section>