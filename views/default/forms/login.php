<?php
/**
 * Elgg login form
 *
 * @package Elgg
 * @subpackage Core
 */
?>

<div>
	<label>Username or Email</label>
	<?php echo elgg_view('input/text', array(
		'name' => 'username',
		'class' => 'elgg-autofocus',
		));
	?>
</div>
<div>
	<label><?php echo elgg_echo('password'); ?></label>
	<?php echo elgg_view('input/password', array('name' => 'password')); ?>
</div>

<?php echo elgg_view('login/extend', $vars); ?>

<div class="elgg-foot book-login">
	<label class="mtm float-alt">
		<input type="checkbox" name="persistent" value="true" />
		<?php echo elgg_echo('user:persistent'); ?>
	</label>
	
	<?php echo elgg_view('input/submit', array('value' => elgg_echo('login'))); ?>
	
	<?php 
	if (isset($vars['returntoreferer'])) {
		echo elgg_view('input/hidden', array('name' => 'returntoreferer', 'value' => 'true'));
	}
	?>

	<ul class="elgg-menu elgg-menu-general mtm">
	<?php
		if (elgg_get_config('allow_registration')) {
			echo '<li><a class="registration_link" href="' . elgg_get_site_url() . 'register">REGISTER FOR FREE</a></li>';
		}
	?>
		<li><a class="forgot_link" href="<?php echo elgg_get_site_url(); ?>forgotpassword">
			Lost Password
		</a></li>
	</ul>
</div>    
     
<script>
  $('.book-login .elgg-button-submit').attr('value', '');
</script>