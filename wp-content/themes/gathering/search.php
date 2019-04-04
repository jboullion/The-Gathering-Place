<?php 
global $wp_query
	get_header(); 

?>
<div class="wrapper white-back">
	<div class="container">
		<div class="row">
			<div class="col-12 content">
				<?php 
					//let's pretend we have no results if the query string is blank
					if(have_posts() && ! empty($_GET['s'])): 
						$num = $wp_query->post_count;

						while(have_posts()): 
							the_post(); 

							//DO STUFF
							echo $post->post_title;
						endwhile; 
						
						echo '<div class="center-text">';
						//wp_pagenavi();
						echo '</div>';

					else:

						echo '<h2>No Results Found.</h2>';
					endif; 
				?>
			</div>
		</div>
	</div>
</div>

<?php get_footer();
