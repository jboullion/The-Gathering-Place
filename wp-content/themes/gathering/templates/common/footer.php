<?php 
	$options = pk_get('options');
?>
<section id="footer" class="wrapper no-print">
	<div class="container">
		<div class="row">
			<div class="col-6">
				<div class="logo">
					<a href="/">
						<img class="desktop" src="<?php echo $options['logo']['url']; ?>" data-rjs="2" alt="<?php bloginfo('name'); ?>" />
					</a>
				</div>
				<div class="tagline">
					<?php echo '<p>'.get_bloginfo('name').'</p>'; ?>
					<?php echo '<span>'.get_bloginfo('description').'</span>'; ?>
				</div>
			</div>
			<div class="col-6">
				<div class="social">
					<a class="facebook" href="<?php echo $options['facebook_url']; ?>" target="_blank"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>
				</div>	
			</div>
			
		</div>

		<div class="row">
			<div class="col-12 copyright">
				&copy;<?php echo date('Y').' '.get_bloginfo('name'); ?>. All rights reserved.
			</div>
		</div>
	</div>
</section>