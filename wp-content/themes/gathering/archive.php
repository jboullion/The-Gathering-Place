<?php 
	get_header(); 

?>
<div class="wrapper white-back" id="archive-page">
	<div class="container">
		<div class="row">
			<div class="col-12 content">
				<?php 
					
					if(have_posts()): 

						while(have_posts()): 
							the_post(); 

							$date = get_the_date('l, F j, Y');
							$link = get_the_permalink();

							//DO STUFF
							the_content();
						endwhile; 
						
						echo '<div class="pagination">';

						echo paginate_links( array(
							'format' => 'page/%#%',
							'current' => max( 1, get_query_var('paged') ),
							'total' => $wp_query->max_num_pages,
							'prev_text' => __('&lsaquo;'),
							'next_text' => __('&rsaquo;')
						) );
						
						echo '</div>';

					else:

						echo '<h2>No Results Found.</h2>';
					endif; 
				?>
			</div>
		</div>
	</div>
</div>

<?php 
	get_footer();
