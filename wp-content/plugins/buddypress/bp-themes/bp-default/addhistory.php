<?php
/*
Template Name: Add History
*/
?>

<?php get_header(); ?>

		<div id="primary">
			<div id="content" role="main">

				<?php while ( have_posts() ) : the_post(); ?>



					<div id="add-post-form" class="form-descr-container">
						<div class="form-descr">
							Write your own chapter in the Book of Relations...
						</div>
						
						<?php echo do_shortcode('[wpuf_addpost redirect_after="/membership-options/dashboard/"]'); ?>
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