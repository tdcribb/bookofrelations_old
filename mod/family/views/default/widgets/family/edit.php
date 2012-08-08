<?php
$icon_sizes = array('small', 'tiny');
if ($vars['entity']->icon_size == NULL) {
	$vars['entity']->icon_size = 'small';
}
?>
<p>
	<?php echo elgg_echo("family:icon_size"); ?>
	<select name="params[icon_size]">
	<?php
	foreach ($icon_sizes as $size) {
		$selected = '';
		if ($vars['entity']->icon_size == $size) {
			$selected = 'selected="selected"';
		}
		$label = elgg_echo("friends:$size");
		echo "<option value=\"$size\"$selected>$label</option>";
	}
	?>
	</select>
</p>
