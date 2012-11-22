<?php
/*
Template Name: Add History
*/
?>

<?php get_header(); ?>

		<div id="primary">
			<div id="content" role="main">

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', 'page' ); ?>

					<div class="form-descr-container">
						<div class="form-descr">
							Write your own chapter in the Book of Relations...
						</div>
						<?php echo do_shortcode('[user-submitted-posts]'); ?>
					</div>
					
					<div class="post-form-divider"></div>

					<div class="form-descr-container">
						<div class="form-descr">
							Submit your genealogy to Book of Relations ...
						</div>
						<?php echo do_shortcode('[contact-form-7 id="32" title="Add Your History"]') ?><br/>
						
					</div>
				<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>