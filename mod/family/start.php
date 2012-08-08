<?php

function family_init() {

	register_page_handler('family', 'family_page_handler');

	elgg_extend_view('css', 'family/css');

	add_widget_type('family', elgg_echo('family:widget:title'), elgg_echo('family:widget:description'), 'profile');

}

function family_page_handler($page) {

	if (isset($page[0])) {
		$user = get_entity((int)$page[0]);
	}

	if (!($user instanceof ElggUser)) {
		$user = get_loggedin_user();
	}

	$body = elgg_view('family/tree', array('user' => $user));

	page_draw(elgg_echo('family:title'), elgg_view_layout('one_column', $body));

}

function family_get_relatives($guid) {
	global $CONFIG;

	$guid = (int)$guid;

	$query = "SELECT * FROM {$CONFIG->dbprefix}entity_relationships WHERE guid_one = $guid AND relationship IN ('father', 'mother', 'partner', 'son', 'daughter', 'brother', 'sister')";

	$relatives = get_data($query);

	$family = array(
		'father' => 0,
		'mother' => 0,
		'partner' => 0,
		'brother' => array(),
		'sister' => array(),
		'son' => array(),
		'daughter' => array()
	);

	if (is_array($relatives) && count($relatives) > 0) {
		foreach ($relatives as $relative) {
			switch ($relative->relationship) {
				case 'father':
				case 'mother':
				case 'partner':
					$family[$relative->relationship] = $relative->guid_two;
					break;
				default:
					$family[$relative->relationship][] = $relative->guid_two;
			}
		}
	}

	return $family;
}

function family_set_relative($guid, $relation, $relative_guid) {
	$relative = get_entity($relative_guid);
	if ($relative instanceof ElggUser) {
		add_entity_relationship($guid, $relation, $relative_guid);
		return true;
	}
	return false;
}

function family_add_relatives($guid, $relation, $relatives_guid) {
	if (is_array($relatives_guid) && count($relatives_guid) > 0) {
		foreach ($relatives_guid as $relative_guid) {
			family_set_relative($guid, $relation, $relative_guid);
		}
	}
}

function family_delete_relatives($guid, $relation, $relatives_guid) {
	if (is_array($relatives_guid) && count($relatives_guid) > 0) {
		foreach ($relatives_guid as $relative_guid) {
			remove_entity_relationship($guid, $relation, $relative_guid);
		}
	}
}

register_action('family/save', FALSE, "{$CONFIG->pluginspath}/family/actions/save.php");

register_elgg_event_handler('init', 'system', 'family_init');
