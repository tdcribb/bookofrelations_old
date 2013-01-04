<?php
/*
Template Name: Member Options
*/
?>
<?php get_header(); ?>

		<div id="primary">
			<div id="content" role="main">

				<div id="member-optns-info">
					<div id="member-optns-title">Choose Your Membership Level</div>
					<div id="optn-1" class="optn-container">
						<div class="optn-header">
							<div class="optn-title">Archivist</div>
							<div class="optn-subtitle">Publishing Member</div>
						</div>
						<div class="optn-details">
							<ul>
								<li>Search and Read Stories</li>
								<li>Publish up to 4 articles a month (maximum 2,500 words, about 10 pages)</li>
								<li>4 documents/images</li>
								<li>1 Video Upload</li>
								<li>Access to add your Family History</li>
								<li>Access to Digitized Family Books (When available)</li>

							</ul>
						</div>
						<div class="optn-price">
							<div class="click-text">Click to Subscribe</div>
							<div class="optn-price-text">$9.95 per month</div>
							<?php echo do_shortcode('[s2Member-PayPal-Button level="1" ccaps="" desc="Archivist - Publishing Member" ps="paypal" lc="" cc="USD" dg="0" ns="1" 
					custom="bookofrelations.com" ta="0" tp="0" tt="D" ra="9.95" rp="1" rt="M" rr="1" rrt="" rra="1" image="default" output="button" /]') ?>
						</div>
					</div>
					<div id="optn-2" class="optn-container">
						<img class="starburst" src="/wp-content/images/memberoptions/optionburst.PNG" />
						<div class="optn-header">
							<div class="optn-title">Curator</div>
							<div class="optn-subtitle">Publishing Member</div>
						</div>
						<div class="optn-details">
							<ul>
								<li>Search and Read Stories</li>
								<li>Publish up to 10 articles a month (maximum 10,000 words, about 40 pages)</li>
								<li>10 documents/images</li>
								<li>3 Video Upload</li>
								<li>Access to add your Family History</li>
								<li>Access to Digitized Family Books (When available)</li>

							</ul>
						</div>
						<div class="optn-price">
							<div class="click-text">Click to Subscribe</div>
							<div class="optn-price-text">$19.95 per month</div>
							<?php echo do_shortcode('[s2Member-PayPal-Button level="2" ccaps="" desc="Curator - Publishing Member" ps="paypal" lc="" cc="USD" dg="0" ns="1" 
					custom="bookofrelations.com" ta="0" tp="0" tt="D" ra="19.95" rp="1" rt="M" rr="1" rrt="" rra="1" image="default" output="button" /]') ?> 
						</div>
					</div>
					<div id="optn-3" class="optn-container">
						<img class="starburst" src="/wp-content/images/memberoptions/bestvalueburst.png" />
						<div class="optn-header">
							<div class="optn-title">Historian</div>
							<div class="optn-subtitle">Publishing Member</div>
						</div>
						<div class="optn-details">
							<ul>
								<li>Search and Read Stories</li>
								<li>Publish an UNLIMITED Number of Articles per month</li>
								<li>100 documents/images</li>
								<li>25 Video Upload</li>
								<li>Access to add your Family History</li>
								<li>Access to Digitized Family Books (When available)</li>

							</ul>
						</div>
						<div class="optn-price">
							<div class="click-text">Click to Subscribe</div>
							<div class="optn-price-text">$39.95 per month</div>
							<?php echo do_shortcode('[s2Member-PayPal-Button level="3" ccaps="" desc="Master Historian - Publishing Member" ps="paypal" lc="" cc="USD" dg="0" ns="1" 
					custom="bookofrelations.com" ta="0" tp="0" tt="D" ra="39.95" rp="1" rt="M" rr="1" rrt="" rra="1" image="default" output="button" /]') ?> 
						</div>
					</div>
					<div id="optn-free" class="optn-free">
						<a href="/register">
							<div class="optn-free-button">
								<div class="optn-free-button-text">Subscribe For</div>
								<div class="optn-free-text">FREE</div>
							</div>
						</a>
						<div class="optn-free-detail">
							As a Free Subscriber you can search all posted information within the site and use the site to 
							communicate with other members. Find historical posts about people, family history, stories, family trees, etc.
						</div>
					</div>
				</div>

				</br>
				
			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>