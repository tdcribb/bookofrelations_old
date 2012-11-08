=== Optimized Dropdown Menus ===
Contributors: maor
Donate link: http://maorchasen.com/plugins/optimized-dropdown-menus/
Author URI: http://maorchasen.com
Tags: widget-only, widget, seo, menu
Requires at least: 3.0
Tested up to: 3.1.3
Stable tag: 1.0

Create "spiderable" drop-down menus that every search engine will scan!

== Description ==

By using Optimized Dropdown Menus Widget, you’ll be able to create dropdown menus that are searchable or “spiderable” by search engine bots.

= So how does it work? =

Basically, it works like this:

The menu's markup code is being printed as an ordinary nested menu. e.g.

`
<ul>
	<li><a href="http://example.com/page">Some Random Page</a></li>
	<li><a href="http://example.com/page-another">Another Page</a></li>
	<li><a href="http://example.com/yao">Yet Another One</a></li>
</ul>
`

Since most browsers support JavaScript, using the widget, the markup would be translated into something like this

`
<select>
	<option value="http://example.com/page">Some Random Page</option>
	...
	<option value="http://example.com/page">Something else</option>
</select>
`

The tricky part here is, that search engines do not usually interpret JavaScript, all they will be able to scan is the nested menu. That way they will reach out for those pages, and most probably will crawl those pages as well.

= Features set for the next versions =

Soon there will be an option for developers to use the same functionality via the code. e.g.
`<?php optimized_dropdown_menu( $menu_args ); ?>`

== Installation ==

Install Optimized Dropdown Menus in 3 easy steps

1. Upload the plugin folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Appearance > Widgets and drag the widget ("Optimized Dropdown Menu") to any sidebar you wish

== Frequently Asked Questions ==

= How do I add a menu? =

First you should set up a menu before you attempt to use the widget. In order to do that, go to Appearance > Menus and create a new menu. When you're finished creating and saving the new menu, go back to Appearance > Widgets and select the appropriate menu.

== Screenshots ==

1. Widget Settings
2. How it looks on the front-end

== Changelog ==

= 1.0 =
* Initial release.
