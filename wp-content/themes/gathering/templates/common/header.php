<?php 
	$options = pk_get('options');
?>
<section id="header" class="">
	<div class="logo">
		<a href="/">
			<img class="desktop" src="<?php echo $options['logo']['url']; ?>" data-rjs="2" alt="<?php bloginfo('name'); ?>" />
		</a>
	</div>
	<div class="tagline">
		<?php echo '<p>'.get_bloginfo('name').'</p>'; ?>
		<?php echo '<span>'.get_bloginfo('description').'</span>'; ?>
	</div>
	<nav id="main-navigation" role="navigation">
		<ul id="menu-main-navigation-menu" class="navbar-nav navbar-expand">
			<?php if (is_user_logged_in()) { ?>
				<li class="login"><a href="<?php echo wp_logout_url( home_url() ); ?>">Logout</a></li>
			<?php } else { ?>
				<li class="sign-up"><a href="#">Sign Up</a></li>
				<li class="login"><a href="#" data-toggle="modal" data-target="#login-modal">Login</a></li>
			<?php } ?>
		</ul>
	</nav>
	<div class="clearfix"></div>
</section>