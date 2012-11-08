<?php
/*
Template Name: Post Dashboard
*/
?>

<?php get_header(); ?>

		<div id="primary">
			<div id="content" role="main">
					
				<?php if (S2MEMBER_CURRENT_USER_ACCESS_LEVEL === 4 || S2MEMBER_CURRENT_USER_ACCESS_LEVEL === 3 || S2MEMBER_CURRENT_USER_ACCESS_LEVEL === 2 || S2MEMBER_CURRENT_USER_ACCESS_LEVEL === 1){ ?>
				    <?php echo do_shortcode('[wpuf_dashboard] '); ?>
				<?php } else if (S2MEMBER_CURRENT_USER_ACCESS_LEVEL === 0){ ?>
					<div class="dash-post-mssg">
				    	You must be a registered Bronze, Silver, Gold or Platinum Member to post information.</br>
				    	Please visit our <a href="<?php echo esc_url( home_url( '/' ) ); ?>membership-options">Membership Options</a> page to sign up and begin posting your history today.
				    	</br></br>
				    	<div class="member-opts-info">
							<span>Become a contributing member today!</span></br>
							You fit into a bigger context than you probably think.
		
							Our past has created our present.  How we are interconnected with our ancestors, all the way down to our parents, is a 
							fascinating area of study.  But have you ever wondered how all of our ancestors may have been related to each other?
					
							</br></br>	
							We aren't just talking about blood relations, or relations by marriage, but relationships founded on geography, neighborhoods, 
							businesses, travels and travails shared by people of a community.
								</br></br>	
							As a member, you are able to access all of the data in our archives that directly and indirectly relate to you who are. With 
							a better understanding of our past, we can have a tighter grasp on who we may become in the future.
						</div>
				    </div>
				<?php } else if(S2MEMBER_CURRENT_USER_ACCESS_LEVEL === -1){ ?>
				    <?php echo do_shortcode('[wpuf_dashboard] '); ?>
				<?php } ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>
