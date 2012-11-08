<?php
/**
 * Tree Navigation module.
 * To embed the tree for use in mashups or on blogs use code such as this:
 * <script type="text/javascript" src="http://yourserver/phpgedview/treenav.php?navAjax=embed&rootid=I14&width=400&height=300"></script>
 *
 * Genealogy viewer for use with a rootsPress database
 * Extensive use has been made of code from phpGedView
 *
 * (@version $Id: treenav.php 6879 2010-01-30 11:35:46Z fisharebest $ )
 */
global $rootspressdb, $rp_database;
define ('PGV_ROOT', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR);
//$SERVER_URL= $_SERVER['SERVER_NAME'] . pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME);
//require_once  'config.php';

if (isset($_GET['tnoptions'])) {
  $tn_options = $_GET['tnoptions'];
//echo 'options='.$options['tnprev1'].$options['tnprev2'].$options['tnprev3'].$options['tnprev4'];
$PGV_BG = '#'.substr($tn_options, 0, 6);
$PGV_LINE = '#'.substr($tn_options, 6, 6);
$PGV_BOXBKGND = '#'.substr($tn_options, 12, 6);
$PGV_BOXBORDER =  'solid ' . '#'. substr($tn_options, 18, 6) . ' 2px';

require  'config.php'; // must follow above config settings
}

require_once 'includes/classes/class_treenav.php';

$db_array = array();
if(file_exists('../db_config.php')) {
include_once '../db_config.php';
$db_array['database'] = RP_DB_NAME;
$db_array['dbuser']   = RP_DB_USER;
$db_array['dbpwd']    = RP_DB_PASSWORD;
$db_array['dbhost']   = RP_DB_HOST;
} else {
include_once '../../../../wp-config.php';
$db_array['database'] = DB_NAME;
$db_array['dbuser']   = DB_USER;
$db_array['dbpwd']    = DB_PASSWORD;
$db_array['dbhost']   = DB_HOST;
}
$rp_database = $db_array['database'];

if (isset($_REQUEST['zoom'])) $zoom = $_REQUEST['zoom'];
if (isset($_REQUEST['rootid'])) $rootid = $_REQUEST['rootid'];
if (!empty($_REQUEST['jsname'])) $name = $_REQUEST['jsname'];
if (isset($_REQUEST['gedid'])) $GEDCOM = $_REQUEST['gedid'];
if (isset($_REQUEST['locale'])) $locale = $_REQUEST['locale'];
       else $locale = null;

   $rootspressdb = @mysql_connect($db_array['dbhost'],$db_array['dbuser'],$db_array['dbpwd'], false);
   if ($rootspressdb === false)
        die("Error: Could not connect to host" .  $db_array['dbhost'] .  "' for user '" . $db_array['dbuser'] . "'");

//Get attributes for this tree (NOTE: This is NOT Wordpress dependent)
         $tree_id = substr($GEDCOM, 2);
         $sql = "SELECT * FROM $rp_database.rp_index WHERE tree_id = $tree_id";
         $result = mysql_query($sql, $rootspressdb);
         $row = mysql_fetch_array($result);

         $RP_TREE_NAME =  $row['tree_name'];
         $_SESSION['portraits_path'] = $row['portraits_path'];
         $RP_ROOT_PAGE = $row['wp_root_id'];

         $RP_PORTRAITS = $_SESSION['portraits_path'];
         if($USE_SILHOUETTE != true)  {
           if($RP_PORTRAITS == null) {
             $USE_SILHOUETTE = true;
             }  else  $RP_PORTRAITS  = $_SESSION['portraits_path'] . '/';
         }

$_SESSION['locale'] = $locale;  //set locale for this session
$nav = new TreeNav($rootid, $GEDCOM, $name, $zoom, $locale, $tn_options);
$nav->generations=6;
$nav->zoomLevel-=1;
$nav->drawViewport('', "600px", "400px");
?>
