<p>
	<label><?php echo elgg_echo('family:gender:field:name'); ?></label>
	<br />
	&nbsp;&nbsp;<?php echo elgg_echo('family:gender:field:name:example'); ?>
	<br />
	&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="params[gender_field_name]" value="<?php echo $vars['entity']->gender_field_name; ?>" />
</p>
<br />
<p>
	<label><?php echo elgg_echo('family:gender:field:values'); ?></label>
	<br />
	&nbsp;&nbsp;<?php echo elgg_echo('family:gender:field:male'); ?>
	<br />
	&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="params[gender_field_male]" value="<?php echo $vars['entity']->gender_field_male; ?>" />
	<br />
	&nbsp;<?php echo elgg_echo('family:gender:field:female'); ?>
	<br />
	&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="params[gender_field_female]" value="<?php echo $vars['entity']->gender_field_female; ?>" />
</p>