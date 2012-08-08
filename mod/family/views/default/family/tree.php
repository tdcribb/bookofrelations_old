<?php

$gender_field_name = get_plugin_setting('gender_field_name', 'family');

$friends = $vars['user']->getFriends();

$males = $females = array();
$undefined = array();

$gender = 'undefined';

if ($gender_field_name != NULL) {

	$gender_field_male = get_plugin_setting('gender_field_male', 'family');
	$gender_field_female = get_plugin_setting('gender_field_female', 'family');

	foreach ($friends as $friend) {
		if ($friend->$gender_field_name == $gender_field_male) {
			$males[$friend->guid] = $friend->name;
		} else if ($friend->$gender_field_name == $gender_field_female) {
			$females[$friend->guid] = $friend->name;
		} else {
			$undefined[$friend->guid] = $friend->name;
		}
	}

	if ($vars['user']->$gender_field_name == $gender_field_male) {
		$gender = 'male';
	} else if ($vars['user']->$gender_field_name == $gender_field_male) {
		$gender = 'female';
	}

} else {

	foreach ($friends as $friend) {
		$undefined[$friend->guid] = $friend->name;
	}

}

$males = $males + $undefined;
$females = $females + $undefined;

$family = family_get_relatives($vars['user']->getGUID());

echo elgg_view('input/form', array(
	'action' => "{$vars['url']}action/family/save",
	'body' => elgg_view('forms/family/tree', array_merge($family, array('males' => $males, 'females' => $females, 'user' => $vars['user'], 'gender' => $gender)))
));

