<?php
$none = array('0' => elgg_echo('family:select:person'));
?>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$("a.plus").click(function(){
		var field = $(this).parent().find("select:last");
		$(this).before(field.clone().val(0));
	});
});
</script>
<h1><?php echo elgg_echo('family:title'); ?></h1>
<div id="family-tree-editor" class="contentWrapper">
	<h2><?php echo elgg_echo('family:parents'); ?></h2>
	<fieldset>
		<legend><?php echo elgg_echo('family:father'); ?></legend>
		<?php
			echo elgg_view('input/pulldown', array('internalname' => 'father', 'options_values' => ($none + $vars['males']), 'value' => $vars['father']));
		?>
	</fieldset>
	<fieldset>
		<legend><?php echo elgg_echo('family:mother'); ?></legend>
		<?php
			echo elgg_view('input/pulldown', array('internalname' => 'mother', 'options_values' => ($none + $vars['females']), 'value' => $vars['mother']));
		?>
	</fieldset>
	<br />
	<h2><?php echo elgg_echo('family:brothers:and:sisters'); ?></h2>
	<fieldset>
		<legend><?php echo elgg_echo('family:brothers'); ?></legend>
		<?php
			if (count($vars['brother']) > 0) {
				foreach ($vars['brother'] as $brother) {
					echo elgg_view('input/pulldown', array('internalname' => 'brothers[]', 'options_values' => ($none + $vars['males']), 'value' => $brother));
				}
			} else {
				echo elgg_view('input/pulldown', array('internalname' => 'brothers[]', 'options_values' => ($none + $vars['males'])));
			}
		?><a href="#" class="plus">[+]</a>
	</fieldset>
	<fieldset>
		<legend><?php echo elgg_echo('family:sisters'); ?></legend>
		<?php
			if (count($vars['sister']) > 0) {
				foreach ($vars['sister'] as $sister) {
					echo elgg_view('input/pulldown', array('internalname' => 'sisters[]', 'options_values' => ($none + $vars['females']), 'value' => $sister));
				}
			} else {
				echo elgg_view('input/pulldown', array('internalname' => 'sisters[]', 'options_values' => ($none + $vars['females'])));
			}
		?><a href="#" class="plus">[+]</a>
	</fieldset>
	<br />
	<h2><?php
		if ($vars['gender'] == 'male') {
			echo elgg_echo('family:wife');
			$partner_field = elgg_view('input/pulldown', array('internalname' => 'partner', 'options_values' => ($none + $vars['females']), 'value' => $vars['partner']));
		} else if ($vars['gender'] == 'female') {
			echo elgg_echo('family:husband');
			$partner_field = elgg_view('input/pulldown', array('internalname' => 'partner', 'options_values' => ($none + $vars['males']), 'value' => $vars['partner']));
		} else {
			echo elgg_echo('family:partner');
			$partner_field = elgg_view('input/pulldown', array('internalname' => 'partner', 'options_values' => ($none + $vars['males'] + $vars['females']), 'value' => $vars['partner']));
		}
	?></h2>
	<fieldset><?php echo $partner_field; ?></fieldset>
	<br />
	<h2><?php echo elgg_echo('family:sons:and:daughters'); ?></h2>
	<fieldset>
		<legend><?php echo elgg_echo('family:sons'); ?></legend>
		<?php
			if (count($vars['son']) > 0) {
				foreach ($vars['son'] as $son) {
					echo elgg_view('input/pulldown', array('internalname' => 'sons[]', 'options_values' => ($none + $vars['males']), 'value' => $son));
				}
			} else {
				echo elgg_view('input/pulldown', array('internalname' => 'sons[]', 'options_values' => ($none + $vars['males'])));
			}
		?><a href="#" class="plus">[+]</a>
	</fieldset>
	<fieldset>
		<legend><?php echo elgg_echo('family:daughters'); ?></legend>
		<?php
			if (count($vars['daughter']) > 0) {
				foreach ($vars['daughter'] as $daughter) {
					echo elgg_view('input/pulldown', array('internalname' => 'daughters[]', 'options_values' => ($none + $vars['females']), 'value' => $daughter));
				}
			} else {
				echo elgg_view('input/pulldown', array('internalname' => 'daughters[]', 'options_values' => ($none + $vars['females'])));
			}
		?><a href="#" class="plus">[+]</a>
	</fieldset>
	<br /><br />
	<?php
		echo elgg_view('input/hidden', array('internalname' => 'guid', 'value' => $vars['user']->guid));
		echo elgg_view('input/submit', array('value' => elgg_echo('save')));
	?>
</div>