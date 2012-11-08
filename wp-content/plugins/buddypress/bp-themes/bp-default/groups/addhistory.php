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
						Create an entry about a person, story, family history information, etc. to be directly submitted for review and posting.</br>
						Add those names to be created as Categories at the end of the post.
					</div>
					<?php echo do_shortcode('[wpuf_addpost post_type="event"]'); ?>

					<div class="form-descr">
						The following form allows you to submit additional information including GEDCOM files to be imported into the site for</br> 
						family tree information as well as additional documents you would like to be submitted, reviewed, and posted for the public</br> 
						to view. We also offer this as an alternative to creating your own content above, and within two weeks we will have created</br> 
						the post, categories, and tags for you.
					</div>
					<?php echo do_shortcode('[contact-form-7 id="32" title="Add Your History"]') ?>
				<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>