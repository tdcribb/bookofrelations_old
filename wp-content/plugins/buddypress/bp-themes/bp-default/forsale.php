<?php
/*
Template Name: For Sale
*/
?>

<?php get_header(); ?>		
		
		<div id="primary">
			<div id="content" role="main">

				<div id="book-sales-container">
					<?php while ( have_posts() ) : the_post(); ?>
	
						<?php the_content( __( 'content', 'buddypress' ) ); ?>
	
					<?php endwhile; // end of the loop. ?>
				</div>

			</div><!-- #content -->
		</div><!-- #primary -->

		<?php get_sidebar(); ?>

<?php get_footer(); ?>

