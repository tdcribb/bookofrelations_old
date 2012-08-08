<?php
/**
 * Walled garden login
 */

$title = elgg_get_site_entity()->name;
$welcome = elgg_echo('walled_garden:welcome');
$welcome .= ': <br/>' . $title;

$menu = elgg_view_menu('walled_garden', array(
	'sort_by' => 'priority',
	'class' => 'elgg-menu-general elgg-menu-hz',
));

$login_box = elgg_view('core/account/login_box', array('module' => 'walledgarden-login'));

echo <<<HTML
<div class="elgg-col elgg-col-1of2">
	<div class="elgg-inner">
		<div class="elgg-heading-walledgarden">
		  <img class="login-logo-med" width="75" height="80" src="/images/general/logo_wht_sm.jpg" ><div class="AG-70 title-ook">ook</div>
		  <div class="title-second AG-38">of Relations</div>
		</div>
		<div id="login-footer-links">$menu</div>
	</div>
</div>
<div class="elgg-col elgg-col-1of2">
	<div class="elgg-inner">
		$login_box
	</div>
</div>    
<script> 
  $('body').css('background', '#800000');
</script>
HTML;
