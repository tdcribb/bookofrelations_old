<?php
/*
Template Name: Homepage
*/
?>
<?php get_header(); ?>		
		
		<div id="primary">
			<div id="content" role="main">

				<div class="homepage-info">

					<div class="hp-left">
						<div id="hp-login-container">
							<?php if ( is_user_logged_in() ) : ?>

								<?php do_action( 'bp_before_sidebar_me' ); ?>

								<div id="sidebar-me">
									<a href="<?php echo bp_loggedin_user_domain(); ?>">
										<?php bp_loggedin_user_avatar( 'type=thumb&width=40&height=40' ); ?>
									</a>

									<h4><?php echo bp_core_get_userlink( bp_loggedin_user_id() ); ?></h4>
									<a class="button logout" href="<?php echo wp_logout_url( wp_guess_url() ); ?>"> <?php _e( 'Log Out', 'buddypress' ); ?></a>

									<?php do_action( 'bp_sidebar_me' ); ?></br>
									<div class="user-sidebar-level"><?php echo S2MEMBER_CURRENT_USER_ACCESS_LABEL; ?></div>
									<div class="user-sidebar-mssgs">Unread Messages: <a href="<?php echo bp_loggedin_user_domain();?>messages/"><?php echo messages_get_unread_count(); ?></a></div>
								</div>

								<?php do_action( 'bp_after_sidebar_me' ); ?>

								<?php if ( bp_is_active( 'messages' ) ) : ?>
									<?php bp_message_get_notices(); /* Site wide notices to all users */ ?>
								<?php endif; ?>

								<?php else : ?>

								<?php do_action( 'bp_before_sidebar_login_form' ); ?>

								<form name="login-form" id="sidebar-login-form" class="standard-form" action="<?php echo site_url( 'wp-login.php', 'login_post' ); ?>" method="post">
									<!-- <span class="sidebar-login-title">LOGIN</span> -->
									<div class="hp-login-username hp-login">
										<label><?php _e( 'Username', 'buddypress' ); ?><br />
										<input type="text" name="log" id="sidebar-user-login" class="input" value="<?php if ( isset( $user_login) ) echo esc_attr(stripslashes($user_login)); ?>" tabindex="97" /></label>
									</div>
									<div class="hp-login-pass hp-login">
										<label><?php _e( 'Password', 'buddypress' ); ?><br />
										<input type="password" name="pwd" id="sidebar-user-pass" class="input" value="" tabindex="98" /></label>
									</div>
									<div class="hp-forget">
										<p class="forgetmenot"><label><input name="rememberme" type="checkbox" id="sidebar-rememberme" value="forever" tabindex="99" /> <?php _e( 'Remember Me', 'buddypress' ); ?></label></p>
									</div>
									<?php do_action( 'bp_sidebar_login_form' ); ?>
									<div class="hp-login-btn">
										<input type="submit" name="wp-submit" id="sidebar-wp-submit" value="<?php _e( 'Log In', 'buddypress' ); ?>" tabindex="100" />
										<input type="hidden" name="testcookie" value="1" />
									</div>
								</form>

								<?php do_action( 'bp_after_sidebar_login_form' ); ?>

							<?php endif; ?>
						</div>
						<img id="hp-book" src="/wp-content/images/tilted-book.png" />
						
					</div>

					<div class="hp-right">
						<div id="hp-vid">
							<iframe width="350" height="225" src="http://www.youtube.com/embed/6LqKnnV7jGE?feature=player_detailpage" frameborder="0" allowfullscreen></iframe>
						</div>
	
						<div class="hp-comments">
							Register today to gain access to the FULL Library of Family Stories.</br> 
							<span>It's FREE!</span>
						</div>
	
						<!-- <div id="hp-register-free">
							<a href="/register">
								<img src="/wp-content/images/hp-subscr-btn.png" />
							</a>
						</div> -->
	
						


					<?php global $bp; ?>
					<?php if(empty($bp->signup->step)) :
						$bp->signup->step='request-details';?>

						<form action="<?php echo bp_get_root_domain().'/'.BP_REGISTER_SLUG;?>" name="signup_form" id="signup_form" class="standard-form hp-register-form" method="post" enctype="multipart/form-data">
				
							<?php if ( 'request-details' == bp_get_current_signup_step() ) : ?>
				
								<?php do_action( 'template_notices' ); ?>
				
								<?php do_action( 'bp_before_account_details_fields' ); ?>
				
								<div class="register-section" id="basic-details-section">
				
									<?php /***** Basic Account Details ******/ ?>
				
									<!-- <h4><//?php _e( 'Account Details', 'buddypress' ); ?></h4> -->
									<div class="reg-form-input">
										<label for="signup_username"><?php _e( 'Username', 'buddypress' ); ?> <?php _e( '(required)', 'buddypress' ); ?>NO spaces</label>
										<?php do_action( 'bp_signup_username_errors' ); ?>
										<input type="text" name="signup_username" id="signup_username" value="<?php bp_signup_username_value(); ?>" />
									</div>
									<div class="reg-form-input">
										<label for="signup_email"><?php _e( 'Email Address', 'buddypress' ); ?> <?php _e( '(required)', 'buddypress' ); ?></label>
										<?php do_action( 'bp_signup_email_errors' ); ?>
										<input type="text" name="signup_email" id="signup_email" value="<?php bp_signup_email_value(); ?>" />
									</div>
									<div class="reg-form-input">
										<label for="signup_password"><?php _e( 'Choose a Password', 'buddypress' ); ?> <?php _e( '(required)', 'buddypress' ); ?></label>
										<?php do_action( 'bp_signup_password_errors' ); ?>
										<input type="password" name="signup_password" id="signup_password" value="" />
									</div>
									<div class="reg-form-input">
										<label for="signup_password_confirm"><?php _e( 'Confirm Password', 'buddypress' ); ?> <?php _e( '(required)', 'buddypress' ); ?></label>
										<?php do_action( 'bp_signup_password_confirm_errors' ); ?>
										<input type="password" name="signup_password_confirm" id="signup_password_confirm" value="" />
									</div>
								</div><!-- #basic-details-section -->
				
								<?php do_action( 'bp_after_account_details_fields' ); ?>
				
								<?php /***** Extra Profile Details ******/ ?>
				
								<?php if ( bp_is_active( 'xprofile' ) ) : ?>
				
									<?php do_action( 'bp_before_signup_profile_fields' ); ?>
				
									<div class="register-section" id="profile-details-section">
				
										<!-- <h4><//?php _e( 'Profile Details', 'buddypress' ); ?></h4> -->
				
										<?php /* Use the profile field loop to render input fields for the 'base' profile field group */ ?>
										<?php if ( bp_is_active( 'xprofile' ) ) : if ( bp_has_profile( 'profile_group_id=1' ) ) : while ( bp_profile_groups() ) : bp_the_profile_group(); ?>
				
										<?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>
											
											<div class="editfield" rel="<?php echo bp_the_profile_field_name(); ?>">
				
												<?php if ( 'textbox' == bp_get_the_profile_field_type() ) : ?>
				
													<label for="<?php bp_the_profile_field_input_name(); ?>"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', 'buddypress' ); ?><?php endif; ?></label>
													<?php do_action( 'bp_' . bp_get_the_profile_field_input_name() . '_errors' ); ?>
													<input type="text" name="<?php bp_the_profile_field_input_name(); ?>" id="<?php bp_the_profile_field_input_name(); ?>" value="<?php bp_the_profile_field_edit_value(); ?>" />
				
												<?php endif; ?>
				
												<?php if ( 'textarea' == bp_get_the_profile_field_type() ) : ?>
				
													<label for="<?php bp_the_profile_field_input_name(); ?>"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', 'buddypress' ); ?><?php endif; ?></label>
													<?php do_action( 'bp_' . bp_get_the_profile_field_input_name() . '_errors' ); ?>
													<textarea rows="5" cols="40" name="<?php bp_the_profile_field_input_name(); ?>" id="<?php bp_the_profile_field_input_name(); ?>"><?php bp_the_profile_field_edit_value(); ?></textarea>
				
												<?php endif; ?>
				
												<?php if ( 'selectbox' == bp_get_the_profile_field_type() ) : ?>
				
													<label for="<?php bp_the_profile_field_input_name(); ?>"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', 'buddypress' ); ?><?php endif; ?></label>
													<?php do_action( 'bp_' . bp_get_the_profile_field_input_name() . '_errors' ); ?>
													<select name="<?php bp_the_profile_field_input_name(); ?>" id="<?php bp_the_profile_field_input_name(); ?>">
														<?php bp_the_profile_field_options(); ?>
													</select>
				
												<?php endif; ?>
				
												<?php if ( 'multiselectbox' == bp_get_the_profile_field_type() ) : ?>
				
													<label for="<?php bp_the_profile_field_input_name(); ?>"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', 'buddypress' ); ?><?php endif; ?></label>
													<?php do_action( 'bp_' . bp_get_the_profile_field_input_name() . '_errors' ); ?>
													<select name="<?php bp_the_profile_field_input_name(); ?>" id="<?php bp_the_profile_field_input_name(); ?>" multiple="multiple">
														<?php bp_the_profile_field_options(); ?>
													</select>
				
												<?php endif; ?>
				
												<?php if ( 'radio' == bp_get_the_profile_field_type() ) : ?>
				
													<div class="radio">
														<span class="label"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', 'buddypress' ); ?><?php endif; ?></span>
				
														<?php do_action( 'bp_' . bp_get_the_profile_field_input_name() . '_errors' ); ?>
														<?php bp_the_profile_field_options(); ?>
				
														<?php if ( !bp_get_the_profile_field_is_required() ) : ?>
															<a class="clear-value" href="javascript:clear( '<?php bp_the_profile_field_input_name(); ?>' );"><?php _e( 'Clear', 'buddypress' ); ?></a>
														<?php endif; ?>
													</div>
				
												<?php endif; ?>
				
												<?php if ( 'checkbox' == bp_get_the_profile_field_type() ) : ?>
				
													<div class="checkbox">
														<span class="label"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', 'buddypress' ); ?><?php endif; ?></span>
				
														<?php do_action( 'bp_' . bp_get_the_profile_field_input_name() . '_errors' ); ?>
														<?php bp_the_profile_field_options(); ?>
													</div>
				
												<?php endif; ?>
				
												<?php if ( 'datebox' == bp_get_the_profile_field_type() ) : ?>
				
													<div class="datebox">
														<label for="<?php bp_the_profile_field_input_name(); ?>_day"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', 'buddypress' ); ?><?php endif; ?></label>
														<?php do_action( 'bp_' . bp_get_the_profile_field_input_name() . '_errors' ); ?>
				
														<select name="<?php bp_the_profile_field_input_name(); ?>_day" id="<?php bp_the_profile_field_input_name(); ?>_day">
															<?php bp_the_profile_field_options( 'type=day' ); ?>
														</select>
				
														<select name="<?php bp_the_profile_field_input_name(); ?>_month" id="<?php bp_the_profile_field_input_name(); ?>_month">
															<?php bp_the_profile_field_options( 'type=month' ); ?>
														</select>
				
														<select name="<?php bp_the_profile_field_input_name(); ?>_year" id="<?php bp_the_profile_field_input_name(); ?>_year">
															<?php bp_the_profile_field_options( 'type=year' ); ?>
														</select>
													</div>
				
												<?php endif; ?>
												
												<?php if ( bp_current_user_can( 'bp_xprofile_change_field_visibility' ) ) : ?>
													<p class="field-visibility-settings-toggle" id="field-visibility-settings-toggle-<?php bp_the_profile_field_id() ?>">
														<?php printf( __( 'This field can be seen by: <span class="current-visibility-level">%s</span>', 'buddypress' ), bp_get_the_profile_field_visibility_level_label() ) ?> <a href="#" class="visibility-toggle-link">Change</a>
													</p>
													
													<div class="field-visibility-settings" id="field-visibility-settings-<?php bp_the_profile_field_id() ?>">
														<fieldset>
															<legend><?php _e( 'Who can see this field?', 'buddypress' ) ?></legend>
														
															<?php bp_profile_visibility_radio_buttons() ?>
														
														</fieldset>
														<a class="field-visibility-settings-close" href="#"><?php _e( 'Close', 'buddypress' ) ?></a>
														
													</div>
												<?php else : ?>
													<p class="field-visibility-settings-notoggle" id="field-visibility-settings-toggle-<?php bp_the_profile_field_id() ?>">
														<?php printf( __( 'This field can be seen by: <span class="current-visibility-level">%s</span>', 'buddypress' ), bp_get_the_profile_field_visibility_level_label() ) ?>
													</p>			
												<?php endif ?>
				
				
												<?php do_action( 'bp_custom_profile_edit_fields' ); ?>
				
												<p class="description"><?php bp_the_profile_field_description(); ?></p>
				
											</div>
											
										<?php endwhile; ?>
				
										<input type="hidden" name="signup_profile_field_ids" id="signup_profile_field_ids" value="<?php bp_the_profile_group_field_ids(); ?>" />
				
										<?php endwhile; endif; endif; ?>
				
									</div><!-- #profile-details-section -->
				
									<!-- <//?php do_action( 'bp_after_signup_profile_fields' ); ?> -->
				
								<?php endif; ?>
				
								<div id="agree-terms"><input type="checkbox" class="agree-checkbox" />
									I agree to the <span class="reg-accept-terms policy-link">Terms of Use and Privacy Policy</span> for Book of Relations.
								</div>
				
								<?php do_action( 'bp_before_registration_submit_buttons' ); ?>
				

								<div class="submit">
									<div id="cover-submit"></div>
									<input type="submit" name="signup_submit" id="signup_submit" value="REGISTER" />
								</div>
				
								<?php do_action( 'bp_after_registration_submit_buttons' ); ?>
				
								<?php wp_nonce_field( 'bp_new_signup' ); ?>
				
							<?php endif; // request-details signup step ?>
				
							<?php if ( 'completed-confirmation' == bp_get_current_signup_step() ) : ?>
				
								<h2><?php _e( 'Sign Up Complete!', 'buddypress' ); ?></h2>
				
								<?php do_action( 'template_notices' ); ?>
								<?php do_action( 'bp_before_registration_confirmed' ); ?>
				
								<?php if ( bp_registration_needs_activation() ) : ?>
									<p><?php _e( 'You have successfully created your account! To begin using this site you will need to activate your account via the email we have just sent to your address.', 'buddypress' ); ?></p>
								<?php else : ?>
									<p><?php _e( 'You have successfully created your account! Please log in using the username and password you have just created.', 'buddypress' ); ?></p>
								<?php endif; ?>
				
								<?php do_action( 'bp_after_registration_confirmed' ); ?>
				
							<?php endif; // completed-confirmation signup step ?>
				
							<?php do_action( 'bp_custom_signup_steps' ); ?>
				
						</form>
					<?php endif; ?>



					<div class="hp-reg-publish">
							Or register as a Publishing Member to begin adding your own chapters into the Book of Relations.
						</div>
						<a href="/membership-options">
							<img id="member-reg-btn" src="/wp-content/images/hp-member-btn.png" />
						</a>
					</div>

				</div>

			</div><!-- #content -->
		</div><!-- #primary -->

		

<?php get_footer(); ?>