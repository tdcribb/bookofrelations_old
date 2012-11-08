<?php
if(isset($_GET['locale'])) {
  $lang = $_GET['locale'];
}
   else $lang = null;
// echo 'locale='.$lang;
//SET UP LOCALIZATION
if($lang != null) {
$ret=putenv("LANG=$lang");
//echo getenv('LANG');
$ret=setlocale(LC_ALL, $lang);
//echo setlocale(LC_ALL, 0);
if(!$ret) {
//    echo '<strong>Failure to set help locale for language ' . $lang . '. Default settings will be used.</strong><br/>';
    }
}
$domain = 'rpress_ext';
$fullpath=bindtextdomain($domain, "../../localization/ext/");
//echo $fullpath;
bind_textdomain_codeset($domain, 'UTF-8');
$ret=textdomain($domain);
if(!$ret) echo 'textdomain failed';

//COMMON VALUES
$path = '../wp-content/plugins/rootspress/pgv/images/';
$home_help = '<p><img src="' . $path . 'small/home.gif">'.
 _('Click here to return to the home page') . '</p>';
$indi_help = '<p><img src="' . $path . 'small/indis.gif">'.
_('Click this icon to go to the individuals fact page').'</p>';
$tree_help = '<p><img src="' . $path . 'small/gedcom.gif">'.
_('Click this icon to navigate to the individuals Interactive tree.').'</p>';
$indx_help = '<p><img src="' . $path . 'small/index.gif">'.
_('Click the index icon to show the index of individuals in this tree sorted alphabetically by surname then given name.').'</p>';

//Interactive tree help
$help[201] =  '<h3>' . _('Interactive Tree help') . '</h3>' .
'<strong>' . _('Controls') . '</strong><br/>'.
'<p><img src="' . $path . 'zoomin.gif">'.
_('Click the zoom in icon to make the details larger') . '</p>'.
'<p><img src="' . $path . 'zoomout.gif">'.
_('Click the zoom out icon to make the details smaller') . '</p>'.
$indx_help.
$home_help.
'<p><img src="' . $path . 'red_circle.png">'.
_('Click the red circle icon to center the viewport on the current key person') . '</p>'.
'<br/>'.

'<strong>' . _('Navigating the tree') . '</strong><br/>'.
_('When you are inside the viewport (the cross cursor is showing), you can pan the tree up and down or left and right by holding the left mouse button down and moving the mouse accordingly.') . '<br/>'.
_('Each box shows an individual. The box will be repeated for multiple marriages, these secondary boxes being less opaque.') . ' '.
_('Note than an individual may be duplicated in the tree for example if his or her parents are first cousins. Only the first occurrence will be shown with a link box for the second occurrence.') . '<br/>'.
_('Clicking in a box expands that box to show additional details. Clicking again collapses the box.') . '<br/>'.
_('Within a box you have three items you can click on.') .
'<ul>'.
'<li>' . _('Click on a name to go to the individuals fact page') . '</li>'.
'<li>' . _('Click on the tree icon to set this individal as key person for the tree and center on that person') . '</li>'.
'<li>' . _('Click on a portrait to enlarge it') . '</li>'.
'</ul>';

//Persons fact page help
$help[202] =  '<h3>' . _('Personal page help') . '</h3>' .
'<strong>' . _('Navigation icons') . '</strong><br/>'.
_('Use the icons at the right to navigate to other pages') . '</br>'.

$tree_help .
$indx_help .
$home_help .

 '<strong>' . _('Links') . '</strong><br/>'.
_('You can navigate to other individuals pages by clicking on their name (eg parents or children).') . '<br/>'.
 '<strong>' . _('Events and facts') . '</strong><br/>'.
_('In the events and facts sections, clicking on a thumbnail will expand the image. If the event or fact has associated source information, clicking on the plus sign will expand that source entry. This can be collapsed again by clicking the minus  sign.') . '<br/>'.
 '<strong>' . _('Family Links') . '</strong><br/>'.
_('Family Links shows a table of parents, grandparents and great grandparents. You can click on a name to go to the ancestor\'s fact page') . '<br/>'.
 '<strong>' . _('Event map') . '</strong><br/>'.
_('The event map uses Google Maps to show each recorded event in the database if place data is present in the database.'). '<br/>' .
_('Each event shows as an icon on the map and a legend entry at the right.') . '<br/>'.
_('Note that if events are clustered at one place the icons will be overlaid on the map. They will be separate in the legend.') . '<br/>'.
_('Clicking on an item in the legend table or a marker in the map pops up an information panel with additional details and also two clickable icons.') . '<br/>'.
_('When clicked, these icons (Geograph and Panoramio) show a selection of places around the event place as camera icons. These can also be clicked to show current photographs people have taken in the area.').  '<br/>'.
_('Note, Geograph is only available for UK locations.'). '<br/>';

//Index page help
$help[203] =  '<h3>' . _('Index page help') . '</h3>' .
$home_help .
$indi_help .
$tree_help .
'<br/>';

//Home page help
$help[204] =  '<h3>' . _('Home page help') . '</h3>' .
_('In the Home page, the individual referenced is the root individual. Unless changed by the administrator, that is the first person in the database.') .
$indi_help .
$tree_help .
$indx_help .
'<br/>';

if(isset($_GET['id'])) {
  $id = $_GET['id'] ;
  echo $help[$id];
}
  else echo 'Error: invalid help id passed';

?>