		</div> <!-- #container -->

		<?php do_action( 'bp_after_container' ); ?>
		<?php do_action( 'bp_before_footer'   ); ?>

		<div id="footer">
			<div id="footer-link-cont">
				<div id="policy" class="footer-links policy-link">
					Policy and Guidelines
				</div>
				<div id="faq" class="footer-links faq-link">
					FAQ
				</div>
				<div id="member" class="footer-links member-link">
					Membership Info
				</div>
				<div class="footer-links copy">
					&#169; Copyright, Lucas and Cribb, LLC
				</div>
			</div>

<!-- 			<//?php if ( is_active_sidebar( 'first-footer-widget-area' ) || is_active_sidebar( 'second-footer-widget-area' ) || is_active_sidebar( 'third-footer-widget-area' ) || is_active_sidebar( 'fourth-footer-widget-area' ) ) : ?>
				<div id="footer-widgets">
					<//?php get_sidebar( 'footer' ); ?>
				</div>
			<//?php endif; ?>

			<div id="site-generator" role="contentinfo">
				<//?php do_action( 'bp_dtheme_credits' ); ?>
				<p><//?php printf( __( 'Proudly powered by <a href="%1$s">WordPress</a> and <a href="%2$s">BuddyPress</a>.', 'buddypress' ), 'http://wordpress.org', 'http://buddypress.org' ); ?></p>
			</div>

			<//?php do_action( 'bp_footer' ); ?> -->

		</div><!-- #footer -->

		<?php do_action( 'bp_after_footer' ); ?>

		<?php wp_footer(); ?>

		<div id="policy-overlay" class="overlay">
			<img class="x-close" src="<?php echo esc_url( home_url( '/' ) ); ?>wp-content/images/closex.png ?>">
			<div class="inner-container">
				<span class="overlay-titles">Policy and Guidelines</span></br></br>
					Coming Soon...
			</div>
		</div>

		<div id="faq-overlay" class="overlay">
			<img class="x-close" src="<?php echo esc_url( home_url( '/' ) ); ?>wp-content/images/closex.png ?>">
			<div class="inner-container">
				<span class="overlay-titles">Frequently Asked Questions</span></br></br>
				What is a GEDCOM File?</br>
				How do I become a Publishing Member so I can add my own family posts?</br>
				I have a Publishing Membership, how do I submit my family information to be published on this site?</br>
				How do I find a family tree?</br>
				</br></br>
				<span class="overlay-subtitles">What is a GEDCOM file?</span></br>
					GEDCOM files are a type of document developed by The Church of Jesus Christ of Latter-day Saints to be used
					in genealogical data which can be imported and exported by many different software systems. For a more complete
					explanataion please visit this link: <a target="blank" href="http://en.wikipedia.org/wiki/GEDCOM">GEDCOM</a>
					
					If you need a suggested free software system to help you in the creation of your family tree and the GEDCOM file
					needed for importing into this site, we recommend <a target="blank" href="http://gramps-project.org/">Gramps</a>. It can be used on both
					Macs and Windows.
				</br> </br>
				<span class="overlay-subtitles">How do I become a Publishing Member so I can add my own family posts?</span></br>
					If you would like to post your own history, stories, or family trees, visit our
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>membership-option">Membership Options</a> page to register for our Publishing Membership
					account and begin adding your family history today.
				</br> </br>
				<span class="overlay-subtitles">I have a Publishing Membership, how do I submit my family information to be published on this site?</span></br>
					Now that you are a Publishing Member of Book of Relations, visit 
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>membership-options/add-your-history/">Add Your History</a> under the Membership Options menu tab.
					There are two ways in which to submit your family information. You can either submit a post via the top form on this page. Your Title, Description and Tags
					will be added as you have inputted this information. Please allow up to 24 hours for these posts to be published. Categories may be added for you by our staff. The second or additional options is to use the second
					form to submit your post, story, or other as well as attach documents you wish to include in your submission. This could include Images, Documents, 
					Digitized Family Books, and Family Trees in which a <a target="blank" href="http://en.wikipedia.org/wiki/GEDCOM">GEDCOM</a> file would be required. If information
					is 	submitted via the second form, it will take one to two full weeks for this to be analyzed, posts/categories/tags/etc created, and the final publishing
					of the information to be completed. You can utilize these forms as much as you want as long as you are a Publishing Member. 
				</br> </br>
				<span class="overlay-subtitles">How do I find a family tree?</span></br>
					If you know the name of the person or surname (last name) you wish to view, you can either perform a search in any of the search fields located in the top right
					of the screen or in the toolbar on the right hand side of the page under the main navigation tabs. Your second option is the locate the dropdown 
					menu under "Family Trees" located in the sidebar on the right of your screen. Select the name you want to view and click 'GO'.
			</div>
		</div>

		<div id="member-overlay" class="overlay">
			<img class="x-close" src="<?php echo esc_url( home_url( '/' ) ); ?>wp-content/images/closex.png ?>">
			<div class="inner-container">
				<span class="overlay-titles">Member Information</span></br></br>
					As a Publishing Member of Book of Relations, you will enjoy the freedom to add your family history and stories as often and as much as you like. Build
					your own family tree, tell those stories you heard as a child, or simply begin a biography. The freedom is yours to share this information and have it
					to share with generations.</br></br>
					If you have questions that our <span class="faq-link">FAQ</a> does not answer, please feel free to email our 
					<a href="mailto:support@bookofrelations.com">Support</a> department. We will respond to your needs within 48 hours or less and help in any way we can
					to clarify any questions you may have.</br></br>
				<span class="overlay-subtitles">Subsription Cancellation</span></br>
					If you want to cancel your bi-monthly subscription to the site, simply click on the button below.</br>
					<?php echo do_shortcode('[s2Member-PayPal-Button cancel="1" image="default" output="anchor" /]') ?>
			</div>
		</div>
		<div id="page-fade"></div>
	</body>

</html>