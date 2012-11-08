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
					<div class="form-descr">
						Create an entry about a person, story, family history information, book excerpt, etc. to be directly submitted for review and posting.
						Your post will be viewable within 48 hours or less.
					</div>
					<?php echo do_shortcode('[user-submitted-posts]'); ?>
					
					<div class="post-form-divider"></div>

					<div class="form-descr">
						The following form allows you to submit additional information including family tree files (GEDCOM), digital family books, or
						other family information you would like to be submitted, reviewed, and posted for your family and friends to view.
						You can use this as an alternative to creating your own content above, and within two weeks we will have created
						the post, categories, and tags for you. If you need help in digitizing a family book, please contact our
						<a href="mailto:support@bookofrelations.com">Support</a> department. Additional fees may apply for digitizing books.
					</div>
					<?php echo do_shortcode('[contact-form-7 id="32" title="Add Your History"]') ?>
				<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>