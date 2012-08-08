<?php
    // Load Elgg engine
    include_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

//	If you want the page visible for the logged-in users only, uncomment the following line with gatekeeper() function
	gatekeeper();

	$body = elgg_view('htmlpage/elements/content');
	$support = elgg_view('htmlpage/elements/support');

	$params = array(
		'content' => $body,
		'support' => $support
	);
	$body = elgg_view_layout('one_column', $params);

	echo elgg_view_page($title, $body);

?>
