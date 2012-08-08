<?php

$guid = $vars['entity']->owner_guid;

$family = family_get_relatives($guid);

$size = $vars['entity']->icon_size;

$brothers = array_merge($family['brother'], $family['sister']);
$sons = array_merge($family['son'], $family['daughter']);
?>
<div id="family_widget" class="contentWrapper">
<?php
	if ($family['father'] > 0 || $family['mother'] > 0) {
		echo '<div>' . elgg_echo('family:parents') . '</div>';
		if ($family['father'] > 0) {
			echo elgg_view('profile/icon', array('entity' => get_user($family['father']), 'size' => $size));
		}
		if ($family['mother'] > 0) {
			echo elgg_view('profile/icon', array('entity' => get_user($family['mother']), 'size' => $size));
		}
		echo '<div class="clearfloat"></div>';
	}

	if (count($brothers) > 0) {
		echo '<div>' . elgg_echo('family:brothers:and:sisters') . '</div>';
		foreach($brothers as $brother) {
			echo elgg_view('profile/icon', array('entity' => get_user($brother), 'size' => $size));
		}
		echo '<div class="clearfloat"></div>';
	}

	if ($family['partner'] > 0) {
		echo '<div>' . elgg_echo('family:parner') . '</div>';
		echo elgg_view('profile/icon', array('entity' => get_user($family['partner']), 'size' => $size));
		echo '<div class="clearfloat"></div>';
	}

	if (count($sons) > 0) {
		echo '<div>' . elgg_echo('family:sons:and:daughters') . '</div>';
		foreach($sons as $son) {
			echo elgg_view('profile/icon', array('entity' => get_user($son), 'size' => $size));
		}
		echo '<div class="clearfloat"></div>';
	}

	if ($vars['entity']->canEdit()) {
		echo elgg_view('output/url', array('href' => "{$vars['url']}pg/family/$guid", 'text' => elgg_echo('family:edit'), 'class' => 'edit-family'));
		echo '<div class="clearfloat"></div>';
} ?>
</div>