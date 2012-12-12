<?php
/*
Template Name: Member Options
*/
?>
<?php get_header(); ?>

		<div id="primary">
			<div id="content" role="main">
				<div class="member-opts-info">
					<span>Don't let your family history be forgotten...</br>Become a contributing member today!</span></br></br>
					You fit into a bigger context than you probably think.

					Our past has created our present.  How we are interconnected with our ancestors, all the way down to our parents, is a 
					fascinating area of study.  But have you ever wondered how all of our ancestors may have been related to each other?
			
					</br></br>	
					We aren't just talking about blood relations, or relations by marriage, but relationships founded on geography, neighborhoods, 
					businesses, travels and travails shared by people of a community.
						</br></br>	
					As a member, you are able to access all of the data in our archives that directly and indirectly relate to who you are. With 
					a better understanding of our past, we can have a tighter grasp on who we may become in the future.
				</div>
				<div class="payment-options">
					<span>Publishing Membership: Archivist</span></br>
					A monthly fee of $9.95 will gain you access to add your family history, </br>
					digitized family books, family trees, and stories. This membership level </br>
					allows you to publish up to 4 articles a month with a maximum 2,500 words and </br>
					include 4 documents/images and 1 video per article. </br>
					
					<?php echo do_shortcode('[s2Member-PayPal-Button level="1" ccaps="" desc="Archivist - Publishing Member" ps="paypal" lc="" cc="USD" dg="0" ns="1" 
					custom="bookofrelations.com" ta="0" tp="0" tt="D" ra="9.95" rp="1" rt="M" rr="1" rrt="" rra="1" image="default" output="button" /]') ?>
					</br>
				</div>
				<div class="payment-options">
					<span>Publishing Membership: Curator</span></br>
					A monthly fee of $19.95 will gain you access to add your family history, </br>
					digitized family books, family trees, and stories. This membership level </br>
					allows you to publish up to 10 articles a month with a maximum 10,000 words and </br>
					include 10 documents/images and 3 videos per article. </br>
					
					<?php echo do_shortcode('[s2Member-PayPal-Button level="2" ccaps="" desc="Curator - Publishing Member" ps="paypal" lc="" cc="USD" dg="0" ns="1" 
					custom="bookofrelations.com" ta="0" tp="0" tt="D" ra="19.95" rp="1" rt="M" rr="1" rrt="" rra="1" image="default" output="button" /]') ?> 
					</br>
				</div>
				<div class="payment-options">
					<span>Publishing Membership: Master Historian</span></br>
					A monthly fee of $39.95 will gain you access to add your family history, </br>
					digitized family books, family trees, and stories with unlimited submissions. </br>
					
					<?php echo do_shortcode('[s2Member-PayPal-Button level="3" ccaps="" desc="Master Historian - Publishing Member" ps="paypal" lc="" cc="USD" dg="0" ns="1" 
					custom="bookofrelations.com" ta="0" tp="0" tt="D" ra="39.95" rp="1" rt="M" rr="1" rrt="" rra="1" image="default" output="button" /]') ?> 
					</br>
				</div>
				<div class="payment-options">
					<span>Free Subscriber:</span></br>
					As a Free Subscriber you can search all posted information within the site and use the site to communicate with </br>
					other members. Find historical posts about people, family history, stories, family trees, etc.</br></br>
					<a class="member-options-register button" href="<?php echo esc_url( home_url( '/' ) ); ?>register/" tabindex="10">Register for Free</a></br>
					
				</div>
				</br>
				
			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>