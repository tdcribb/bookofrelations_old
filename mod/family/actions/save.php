<?php

gatekeeper();

$guid = get_input('guid', get_loggedin_userid());

$user = get_entity($guid);

if ($user instanceof ElggUser && $user->canEdit()) {

	$father = get_input('father', 0);
	$mother = get_input('mother', 0);
	$partner = get_input('partner', 0);

	$relatives = array(
		'brother' => get_input('brothers', array()),
		'sister' => get_input('sisters', array()),
		'son' => get_input('sons', array()),
		'daughter' => get_input('daughters', array())
	);

	$family = family_get_relatives($guid);

	if ($father > 0) {
		family_set_relative($guid, 'father', $father);
	} else if ($family['father'] > 0) {
		remove_entity_relationship($guid, 'father', $family['father']);
	}

	if ($mother > 0) {
		family_set_relative($guid, 'mother', $mother);
	} else if ($family['mother'] > 0) {
		remove_entity_relationship($guid, 'mother', $family['mother']);
	}

	if ($partner > 0) {
		family_set_relative($guid, 'partner', $partner);
	} else if ($family['partner'] > 0) {
		remove_entity_relationship($guid, 'partner', $family['partner']);
	}

	foreach ($relatives as $relation => $people) {
		family_add_relatives($guid, $relation, array_diff($people, $family[$relation]));
		family_delete_relatives($guid, $relation, array_diff($family[$relation], $people));
	}

}

system_message(elgg_echo('family:saved'));

forward($user->getURL());
