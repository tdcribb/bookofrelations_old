<?php
/*
Template Name: Member Options
*/
?>
<?php get_header(); ?>

		<div id="primary">
			<div id="content" role="main">
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
				</br></br>
				<div class="payment-options">
					<span>Free Subscriber:</span></br>
					As a Free Subscriber you can search all posted information within the site. The only restriction is not being able to add your own </br>
					historical posts about people, family history, stories, family tree, etc for others to research and view. Sign up for one of the member </br>
					levels below and begin adding your history today.</br>
					
				</div>
				<div class="payment-options">
					<span>Bronze Member:</span></br>
					A one-time fee of $10.95 will gain you access for 30 days to add your family history and stories.</br>
					<?php echo do_shortcode('[s2Member-PayPal-Button level="1" ccaps="" desc="Bronze Member" ps="paypal" lc="" cc="USD" dg="0" ns="1" custom="bookofrelations.com" ta="0" tp="0" tt="D" ra="10.95" rp="1" rt="M" rr="0" rrt="" rra="1" image="default" output="button" /]') ?>
				</div>
				<div class="payment-options">
					<span>Silver Member:</span></br>
					A bi-monthly fee of $19.95 will gain you access to add your family history and stories with unlimited data.</br>
					<?php echo do_shortcode('[s2Member-PayPal-Button level="2" ccaps="" desc="Silver Member" ps="paypal" lc="" cc="USD" dg="0" ns="1" custom="bookofrelations.com" ta="0" tp="0" tt="D" ra="19.95" rp="2" rt="M" rr="1" rrt="" rra="1" image="default" output="button" /]') ?>
				</div>
				<div class="payment-options">
					<span>Gold Member:</span></br>
					A quarterly fee of $27.95 will gain you access to add your family history and stories with unlimited data.</br>
					<?php echo do_shortcode('[s2Member-PayPal-Button level="3" ccaps="" desc="Gold Member" ps="paypal" lc="" cc="USD" dg="0" ns="1" custom="bookofrelations.com" ta="0" tp="0" tt="D" ra="27.95" rp="3" rt="M" rr="1" rrt="" rra="1" image="default" output="button" /]') ?>
				</div>
<!-- 				Platinum Member:
				A yearly fee of $49.95 will gain you access to add your family history and stories with unlimited data.</br>
				<?//php echo do_shortcode('[s2Member-PayPal-Button level="4" ccaps="" desc="Platinum Member" ps="paypal" lc="" cc="USD" dg="0" ns="1" custom="bookofrelations.local" 
				//ta="0" tp="0" tt="D" ra="49.95" rp="1" rt="Y" rr="1" rrt="" rra="1" image="default" output="button" /]') ?>
			</br></br> -->
			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>