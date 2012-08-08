<?php
	elgg_register_event_handler('init','system','htmlpage_init');

function htmlpage_init() {

	// Extend system CSS with our own styles
	elgg_extend_view('css/elgg', 'htmlpage/css');
        $css_url = 'mod/myplugin/vendor/special.css';
        elgg_register_css('special', $css_url);
        elgg_load_css('special');

	// Register navigation menu
	$navlink = new ElggMenuItem('familytree', 'Family Tree', 'familytree');
	elgg_register_menu_item('site', $navlink);

	elgg_register_page_handler('familytree', 'htmlpage_page_handler');
}		


/**
 * Page handler function
 * 
 * @param array $page Page URL segments
 * @return bool
 */
function htmlpage_page_handler($page) {

//	If you want the page visible for the logged-in users only, uncomment the following line with gatekeeper() function
	gatekeeper();

	$body .= elgg_view('htmlpage/elements/content');
	$body .= elgg_view('htmlpage/elements/support');

	$params = array(
		'content' => $body,
	);
	$body = elgg_view_layout('one_column', $params);

	echo elgg_view_page($title, $body);
	return true;
}