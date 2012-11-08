<?php
/*
 Plugin Name: rootsPress
 Plugin URI: http://mikewarland.com/tekhus
 Description: Display linked family history pages with facts, maps, interactive tree and images from a Gedcom file.
 Version: 2.6.4
 Author: Mike Warland
 Author URI: http://mikewarland.com/
 License: GPLv2
 */

/*  Copyright 2012  Mike Warland  (email : mikewarland@sympatico.ca)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
/* This component updated 2012.07.15 */
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
define ("RP_PLUGIN_VER", "2.6.4");
define ("RP_DB_VER", "2.0.0");
static $special = 0;
global $rootspressdb, $rp_database;
$admin_msg = '';
        $rp_trdomain = 'rpress_main';
        $rp_tr_set = false;
/**
 * First, make sure class exists
 */
if (!class_exists("rootsPress")) {
    class rootsPress {
        var $rootsPressVersion = RP_PLUGIN_VER;
        var $plugin_dir;
        /**
         * Constructor
         */
        function __construct() {
            $this->plugin_dir = "wp-content/plugins/" . plugin_basename(dirname(__FILE__)) . "/";
        }
        
        function rp_tr_set() {
          global $rp_trdomain, $rp_tr_set;
          if($rp_tr_set) return;
          load_plugin_textdomain( 'rpress_main', false, dirname( plugin_basename( __FILE__ ) ) . '/localization/main/' );
          $rp_tr_set = true;
        }


        // PLUGIN SHORTCODE HANDLERS

        /**
         * Entry point for the rootsPress shortcode
         *
         * Displays all the details for a person represented in the external mySql file.
         * If the person is deemed as living (based on the age limit option) their details
         * will not be displayed.
         *
         * @param $atts
         * @param $content
         *
         * @return string formatted person page
         *
         * @example [rootsPress ged='rp1'  pid='I113' index='YES']
         */
        function rootsPressHandler( $atts, $content = null ) {
          global $rootspressdb, $rp_database;
        require 'php/viewer.php';
        require 'php/selector.php';
        
        if(isset($atts['pid'])) $rootsPersonId = $atts['pid'];
          else $rootsPersonId = null;
        if(isset($atts['ged'])) $ged = $atts['ged'];
          else $ged = null;

        $options = get_option('rootspress_options');
        $mode = $options['ipage'];
        $tree_id = substr($ged,2);
        
//select row from rp_index to use tree name and default id
        $sql = "SELECT * FROM " . $rp_database . '.' . "rp_index WHERE tree_id = $tree_id";
        $result = mysql_query($sql, $rootspressdb);
        if(!$result) {
        echo $this->rp_show_error(sprintf(__('Error: Could not retrieve tree id=[ %s ] from database', 'rpress_main'), $tree_id));
        return;
        }
        $row = mysql_fetch_array($result, MYSQL_ASSOC);
        $_SESSION['tree_name'] = $row['tree_name'];

        $content = rp_selector($ged, $rootsPersonId, $mode, $row);
        return $content;
        }
        
     function rp_get_names($fullname, &$names) {
    $fullname = trim($fullname);
    $pos1 = strpos($fullname, '/');
   if ($pos1 === false) {
     $names[0] = $fullname;
     $names[1] = '';
   }  else {
     $names[0] = substr($fullname, 0, $pos1);
     $pos2 = strpos($fullname, '/', ($pos1+1));
     $names[1] = rtrim(substr($fullname, $pos1+1 , ($pos2-$pos1+1)), '/');
   }
} //End function


        //PLUGIN FILTERS (NONE)


        //PLUGIN ACTIONS

        /**
         * Called on behalf of the wp_print_styles hook to dynamically
         * insert the style sheets the plugin needs
         *
         * @return void
         */
        function insert_rootsPressStyles() {
            $style_url = "/" . $this->plugin_dir . "css/";
            $style_url_tn =  "/" . $this->plugin_dir . "pgv/css/";
          //wp_register_style( $handle, $src, $deps, $ver, $media )
            wp_register_style('rootsPress-st1', $style_url . 'rp_general.css', false, '1.0', 'screen');
            wp_enqueue_style( 'rootsPress-st1');
            if($_SESSION['lightbox']) {
            wp_register_style('rootsPress-st3',  "/" . $this->plugin_dir . 'pgv/slimbox/css/slimbox2.css', false, '1.0', 'screen');
            wp_enqueue_style( 'rootsPress-st3');
            }
            wp_register_style('rootsPress-st4', $style_url_tn . 'pgvstyle.css', false, '1.0', 'screen');
            wp_enqueue_style( 'rootsPress-st4');

        }
        
        function insert_adminScripts() {
            $js_url = "/" . $this->plugin_dir . "js/";
            wp_register_script('rootsPress-sc10', $js_url . 'rp_general.js', false, '1.0', false);
            wp_enqueue_script( 'rootsPress-sc10');
        }

        
        function rootsPress_wphead() {
             $js_url = "/" . $this->plugin_dir . "js/";
             wp_enqueue_script('jquery', $js_url.'jquery-1.4.2.min.js', false, '1.4.2');
             wp_register_script('rootsPress-sc1', $js_url . 'rp_general.js', false, '1.0', false);
             wp_enqueue_script( 'rootsPress-sc1');
             wp_localize_script( 'rootsPress-sc1', 'rp_siteURL', array( 'siteurl' => get_option('siteurl' ) ));    //used by all local javascript scripts
             wp_localize_script( 'rootsPress-sc1', 'rpress_main', $this->get_language_strings() );    //used by all local javascript scripts
             wp_localize_script( 'rootsPress-sc1',  'rp_locale', get_locale() );
             wp_register_script('rootsPress-sc2', "http://maps.google.com/maps/api/js?key=AIzaSyBLw2JWOTshKtzpfdosW7sOrCCmlI_ZbFc&sensor=false", false, '1.0', false);   //key for local host

             wp_enqueue_script( 'rootsPress-sc2');
             wp_register_script('rootsPress-sc3', $js_url . 'rp_mapper.js', false, '1.0', false);
             wp_enqueue_script( 'rootsPress-sc3');
//             wp_register_script('rootsPress-sc4', "http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js", false, '1.0', false);
//             wp_enqueue_script( 'rootsPress-sc4');

             wp_register_script('rootsPress-sc6', "/" . $this->plugin_dir .'pgv/js/bennolan/behaviour.js', false, '1.0', false);    //for IE
             wp_enqueue_script( 'rootsPress-sc6');
             wp_register_script('rootsPress-sc7', "/" . $this->plugin_dir .'pgv/js/treenav.js', false, '1.0', false);   //for IE
             wp_enqueue_script( 'rootsPress-sc7');
//             wp_localize_script( 'rootsPress-sc7', 'rp_siteURL',array( 'siteurl' => get_option('siteurl') ));
             wp_register_script('rootsPress-sc8', "/" . $this->plugin_dir .'pgv/js/phpgedview2.js', false, '1.0', false);   //for IE
             wp_enqueue_script( 'rootsPress-sc8');
              if($_SESSION['lightbox']) {
             wp_register_script('rootsPress-sc5', "/" . $this->plugin_dir .'pgv/slimbox/js/slimbox2.js', false, '1.0', false);
             wp_enqueue_script( 'rootsPress-sc5');
             wp_register_script('rootsPress-sc9', $js_url . 'tinybox.js', false, '1.0', false);
             wp_enqueue_script( 'rootsPress-sc9');
             wp_enqueue_script(array('jquery', 'editor', 'thickbox', 'media-upload'));

              }
        }

//NOT USED
        function rootsPress_plugins_loaded() {
//        $path = plugins_url('/rootspress/js/');
///       wp_enqueue_script('jquery', $path.'jquery-1.4.2.min.js', false, '1.4.2');
        }
        

        // PLUGIN HELPERS

        /**
         * Common method to get the current page id
         *
         * @return string ID of current page
         */
        function getPageId() {
            global $wp_query;
            $curr_page_id = $wp_query->get_queried_object_id();
            return $curr_page_id;
        }


//PLUGIN REGISTRATION

        /**
         * Install the plugin
         */
        function rootsPressInstall () {
        global $admin_msg, $rootspressdb, $rp_database;
 //       $this-> rp_tr_set(); //establish localization
 
$db_array = array();
if(file_exists(ABSPATH.'/wp-content/plugins/rootspress/db_config.php')) {
include_once ABSPATH.'/wp-content/plugins/rootspress/db_config.php';
$db_array['database'] = RP_DB_NAME;
$db_array['dbuser']   = RP_DB_USER;
$db_array['dbpwd']    = RP_DB_PASSWORD;
$db_array['dbhost']   = RP_DB_HOST;
} else {
$db_array['database'] = DB_NAME;
$db_array['dbuser']   = DB_USER;
$db_array['dbpwd']    = DB_PASSWORD;
$db_array['dbhost']   = DB_HOST; 
}
$rp_database =   $db_array['database'];

   $rootspressdb = @mysql_connect($db_array['dbhost'],$db_array['dbuser'],$db_array['dbpwd'], true);
   if ($rootspressdb === false)
        die("Error: Could not connect to host '" .  $db_array['dbhost'] .  "' for user '" . $db_array['dbuser'] . "'");

//Set default site options or leave as is
                $options = array(
                'lightbox' => true,
                'maptype'  => 'gterr',
                'typech'   => false,
                'sources'  => true,
                'gwidth'   => '400',
                'gheight'  => '300',
                'twidth'   => '600',
                'theight'  => '400',
                'ipage'    => 'home',
                'tnprev1'  => 'ecfcdc', //background color
                'tnprev2'  => '006633', //line colour
                'tnprev3'  => 'c0d6aa', //box colour
                'tnprev4'  => '2e4516'  //box border colour
                );
//             add_option('rootspress_options', $options);
            update_option('rootspress_options', $options);
           $currVersion = get_option('rootsPressVersion');   //get version prior to upgrade
//New install (no prior version)
        	if(!isset($currVersion) || empty($currVersion)) {
            	    add_option('rootsPressVersion', $this->rootsPressVersion);
                    $currVersion = $this->rootsPressVersion;
                    update_option('rootsPressUpgrade', '0'); //will be 0 for new install
                    update_option('rootspress_dbVersion', RP_DB_VER);
                    $make =  $this -> rp_make_index();  //Makes rp_index and also checks that database exists and can be read and written to                    
                    return;
         }

//Manual install with prior version
              $this->rp_installer();
              $make =  $this -> rp_make_index();  //Makes rp_index and also checks that database exists and can be read and written to
              $this->convHome(); //check if rp_index needs updating for 2.6.0 (home page)

        }  //End function

/*
**  This function performs install functions if the version number has changed, otherwise it does nothing
**  MUST BE CALLED PRIOR TO UPDATING THE VERSION NUMBER
*/
        function rp_installer() {
           $currVersion = get_option('rootsPressVersion');   //get version prior to upgrade
//Dont do the installer process if the prior version is missing because it must have been deleted as part of uninstallation
           if(!isset($currVersion) || empty($currVersion)) return false;
           if ($currVersion == RP_PLUGIN_VER) return false;
          
            $currdbVersion = get_option('rootspress_dbVersion');
            if(isset($currdbVersion) && !empty($currdbVersion)) {

/* The existence of dbVersion means we are at rootsPress 2.0.0 or above.
** So no database upgrade is required but when moving to 2.6.0,
** rp_index must be upgraded with the home page text  */

              $upgrade = 0;
              update_option('rootsPressUpgrade', $upgrade);
              update_option('rootspress_dbVersion', RP_DB_VER ); //set db version
      	      update_option('rootsPressVersion', RP_PLUGIN_VER);  //set new rootspress version
     // 	      if(substr($currVersion,0,3) != '2.6') convHome();    //dont need to if already at 2.6.0
              return;
            }
            if(!isset($currdbVersion) || empty($currdbVersion)) {
              switch  (substr($currVersion,0,3)) {
                 case "2.5":  $upgrade = '0'; //DATABASE upgrade not required
                 break;
                 case "2.0":  $upgrade = '0'; //DATABASE upgrade not required
                 break;
                 case "1.6":  $upgrade = '1'; //DATABASE upgrade from 1.6 to 2.0 indicated
                 break;
                 default:     $upgrade = '2'; //DATABASE upgrade from 1.5 to 2.0 indicated
              } //End switch
              
            update_option('rootsPressUpgrade', $upgrade);
      	    update_option('rootsPressVersion', RP_PLUGIN_VER);  //set new rootspress version
              }

        } //End function
        
        function convHome() {
          global $rootspressdb, $rp_database;
        $sql =  "SHOW COLUMNS FROM " . $rp_database . '.' . 'rp_index' . " LIKE 'home_html'";
        $result = mysql_query($sql, $rootspressdb);
        $exists = (mysql_num_rows($result))?TRUE:FALSE;
        if($exists) return;  //home_html already exists in rp_index
         $sql = 'ALTER TABLE ' . $rp_database . '.' . 'rp_index ADD home_html BLOB AFTER secured';   //upgrade rp_index for home page
         $result = mysql_query($sql, $rootspressdb);
         if(!$result) {
           echo $this->rp_show_error(sprintf(__('Error: Unable to upgrade rootsPress index table', 'rpress_main'), $tree_id));
           return false;
         }
//now copy data from home page to rp_index for each tree
         $sql =  "SELECT * FROM " . $rp_database . '.' . "rp_index";
         $result = mysql_query($sql, $rootspressdb);
         if(!$result || mysql_num_rows($result) == 0) {
           return true;
         }
         while($row = mysql_fetch_array($result))
         {
         $home_id = $row['wp_home_id'];
//get page content for this page id
         $page_data = get_page( $home_id );
         $home_data = $page_data->post_content;
//         $home_html = "'" . $home_data . "'";
         $home_html =  "'" . addslashes($home_data).  "'";
//          wp_delete_post($home_id);     //not deleting the home page
         $tree_id = $row['tree_id'];
         $sql = "UPDATE " . $rp_database . '.' . "rp_index SET home_html = $home_html WHERE tree_id = $tree_id ";
         $result2 = mysql_query($sql, $rootspressdb);
         if(!$result2) {
           echo $this->rp_show_error(sprintf(__('Error: Unable to update home page in rootsPress index table', 'rpress_main'), $tree_id));
           return false;
              }
         }

        }  //End function


        /**
         * Uninstall (cleanup) the plugin
         */
        function rootsPressUninstall() {
          global $rootspressdb, $rp_database;
            delete_option('rootsPressVersion');
            delete_option('rootspress_dbVersion');
            delete_option('rootspress_options');
            delete_option('rootsPressUpgrade');

/*
** Now remove all database tables, root page and it's children, rootsPress index
** The database created outside Wordpress is not removed
*/
         $result = mysql_query("SELECT * FROM rp_index");

         while($row = mysql_fetch_array($result))
         {
//remove main tables
         $tree_id = $row['tree_id'];
         $this->rp_remove_table('rp' . $tree_id, $rp_database);
//remove wp pages
          $page =  $row['wp_root_id'];
          wp_delete_post($page);
          $page =  $row['wp_home_id'];
          if($page != null) wp_delete_post($page);

//remove entry from rp_index
          $sql = 'DELETE FROM ' . $rp_database . '.' . "rp_index WHERE tree_id= $tree_id ";
          mysql_query($sql, $rootspressdb);
         }

//Drop rp index
        $sql = 'DROP TABLE ' . $rp_database . '.' . 'rp_index' ;
        mysql_query($sql, $rootspressdb);
} //End function
        
        function rootsPressDeactivate() {
//            delete_option('rootsPressVersion');
//            delete_option('rootspress_options');
            remove_shortcode('rootsPress');
            remove_action('admin_menu', 'rootsPressOptionsPage');
            remove_action('wp_enqueue_scripts', 'insert_rootsPressStyles');
            remove_action('wp_enqueue_scripts', 'rootsPress_wphead');
            return;
        } //End function

    	function rootsPressOptionsPage() {
          global $special;
    add_menu_page('Overview', 'rootsPress', 'administrator', 8, array(&$this, 'rp_overview'));
    $upgrade =  get_option('rootsPressUpgrade');
    if($upgrade >0) {
    add_submenu_page(8, 'Upgrade', '<font color="red">' . __('Upgrade', 'rpress_main') . '</font>', 'administrator', 1, array(&$this, 'rp_upg'));
    return;
   }
    add_submenu_page(8, 'Upgrade', __('Upgrade', 'rpress_main'), 'administrator', 1, array(&$this, 'rp_upg'));
    add_submenu_page(8, 'Options', __('Site options', 'rpress_main'), 'administrator', 2, array(&$this, 'rp_options'));
    add_submenu_page(8, 'Options', __('Tree options', 'rpress_main'), 'administrator', 3, array(&$this, 'rp_options_trees'));
    add_submenu_page(8, 'Add', __('Add tree', 'rpress_main'), 'administrator', 4, array(&$this,'rp_add'));
    add_submenu_page(8, 'Remove', __('Remove trees', 'rpress_main'), 'administrator', 5, array(&$this,'rp_remove'));
    add_submenu_page(8, 'Show', __('Show trees', 'rpress_main'), 'administrator', 6, array(&$this,'rp_show'));
    add_submenu_page(8, 'Show', __('Uninstall', 'rpress_main'), 'administrator', 7, array(&$this,'rp_uninstall'));
    if ($special >=1)
                  add_submenu_page(8, 'MORE', 'MORE'.$special, 'administrator', 9, array(&$this,'rp_upg'));
		}

function rp_upg() {
     global $msg;
     if(isset($_POST['rpress_hidden']) && $_POST['rpress_hidden'] == 'upg') {
             $msg = $this->rp_upg_process();
        }
  
     if ($msg != '') {
          echo '<div class="updated"><p><strong>' . $msg  . '</strong></p></div>';
        }

     ?>

        <form name="rpress_form5" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<input type="hidden" name="rpress_hidden" value="upg">
	<?php    echo '<h3>' . 'rootsPress ' . __('Upgrade', 'rpress_main') . '</h3>';
         $upgrade =  get_option('rootsPressUpgrade');
              switch($upgrade) {
                   case '0': echo __('No upgrades are required at this time.', 'rpress_main');
                break;
                   case '1': echo '<font color="red">' . __('PLEASE READ FIRST', 'rpress_main') . ' <br/></font>' . __('The upgrade process has determined that your database is at level 1.6.0 and needs to be upgraded. Press Upgrade to proceed.', 'rpress_main');
        ?><p class="submit">
        <input class='button-primary' type='submit' name='confirm5' id='button5' value='<?php _e('Upgrade', 'rpress_main')?>' />
        </p>
        </form>
        <?php
                break;
                   case '2': echo '<font color="red">' . __('PLEASE READ FIRST', 'rpress_main') . ' <br/></font>' . __('The upgrade process has determined that your database is at level 1.5.0 and cannot be upgraded. It must be removed and recreated from your gedcom files.', 'rpress_main') . '<br/>' .
                   __e('If you have altered the original Wordpress page structure that rootsPress set up, the upgrade could cause problems with Wordpress. You can preview the action using the Preview button.
                   Press Proceed to remove obsolete tables and pages.', 'rpress_main');
        ?><p class="submit">
        <input class='button-primary' type='submit' name='confirm5' id='button5' value="<?php _e('Proceed', 'rpress_main')?> "/>
        <input class='button-primary' type='submit' name='cancel5' id='button5a' value="<?php _e('Preview', 'rpress_main')?> "/>
        </p>
        </form>
        <?php
                break;
     }

}  //End function

    function rp_upg_process() {
    $msg = '';
    $action = false;
    $upgrade =  get_option('rootsPressUpgrade');
    if (isset($_POST['cancel5']))  $action = false;
    if (isset($_POST['confirm5'])) $action = true;
    switch($upgrade) {
      case '1': $msg = rootsPressUpgrade1($action);
                break;
      case '2': $msg = rootsPressUpgrade2($action);
                break;
    }
//    update_option('rootspress_dbVersion', RP_DB_VER);
$this->rootsPressOptionsPage();
      return $msg;
} //End function

    function rootsPress_plugin_action_links($links, $file) {
     static $this_plugin;
    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }
    if ($file == $this_plugin) {
// The "page" query string value must be equal to the slug of the Settings admin page we defined earlier, which in this case equals "myplugin-settings".
        $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=7">' . __('Uninstall', 'rpress_main') . '</a>';
        array_unshift($links, $settings_link);
    }

    return $links;
}

   function rp_overview()
    {
        global $rp_trdomain, $rootspressdb, $rp_database;
        echo '<h2>' . 'rootsPress ' . __('Plugin Administration', 'rpress_main') . '</h2>';
        echo '<strong>Version='. RP_PLUGIN_VER. '; Database='.$rp_database. '; Locale=' . get_locale() . '</strong><br/><br/>';
//        $this-> rp_tr_set();

        ?>

<p><?php _e('Please refer to the plugin guide for more information.', 'rpress_main'); ?><a title="Guide" href="http://localhost/wordpress/wp-content/plugins/rootspress/rootspress guide.pdf" target="_blank"> <?php _e('Guide', 'rpress_main'); ?></a></p>
<p><?php _e('For more information on plugin administration please go to this site.', 'rpress_main'); ?> <a href="http://www.mikewarland.com/tekhus/"><?php _e('Support and information', 'rpress_main'); ?></a> </p>
        <?php
    }

    function rp_options()
    {
    global $msg;

     if(isset($_POST['rpress_hidden']) && $_POST['rpress_hidden'] == 'opt') {
             $msg = $this->rp_opt_process();
        }

//get saved options
     $saved = get_option('rootspress_options');

//assign options
     if (!empty($saved)) {
         foreach ($saved as $key =>$option)
           $options[$key] = $option;
     }
     
//update options if necessary
     if ($saved != $options)
         update_option('rootspress_options', $options);

//Set variables to be the same as the stored values or the defaults
     $lightbox  = $options['lightbox'];
     if ($lightbox == true) $lt_checked = 'checked';
        else $lt_checked = 'unchecked';
//     $mapper    = $options['mapper'];
//     $age_limit = $options['age_limit'];
     $sources   = $options['sources'];
     $maptype   = $options['maptype'];
     $typech    = $options['typech'];
     $gwidth    = trim($options['gwidth']);
     $gheight   = trim($options['gheight']);
     $twidth    = trim($options['twidth']);
     $theight   = trim($options['theight']);
     $ipage     = $options['ipage'];
     $prv_bkgnd = $options['tnprev1']; // background color
     $prv_line  = $options['tnprev2']; // line colour
     $prv_boxbg = $options['tnprev3']; // box background colour
     $prv_boxbd = $options['tnprev4']; // box border colour

//Now set form  according to stored options
     if ($sources == true) $src_checked = 'checked';
        else $src_checked = 'unchecked';
     if ($typech == true) $typech_checked = 'checked';
        else $typech_checked = 'unchecked';
//     if ($thumbs == true) $tn_checked = 'checked';
//        else $tn_checked = 'unchecked';

     $gstreet_checked = '';
     $gterr_checked = '';
     $ghyb_checked = '';
     $gsat_checked = '';
     
     switch($maptype) {
       case 'gstreet':
            $gstreet_checked = 'checked';
            break;
       case 'gterr':
            $gterr_checked = 'checked';
            break;
       case 'ghyb':
            $ghyb_checked = 'checked';
            break;
       case 'gsat':
            $gsat_checked = 'checked';
            break;
     }

     $ihome_checked = '';
     $ibase_checked = '';
     $iindex_checked = '';
     $itree_checked = '';

     switch($ipage) {
       case 'home':
            $ihome_checked = 'checked';
            break;
       case 'base':
            $ibase_checked = 'checked';
            break;
       case 'indx':
            $iindex_checked = 'checked';
            break;
       case 'tree':
            $itree_checked = 'checked';
            break;
     }
     


     if ($msg != '') {
          echo '<div class="updated"><p><strong>' . $msg  . '</strong></p></div>';
        }
//Now show form
     ?>

        <form name="rpress_form3" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<input type="hidden" name="rpress_hidden" value="opt">
	<?php    echo "<h3>" . 'rootsPress ' .  __( 'Site options', 'rpress_main' ) . "</h3>"; ?>
        <table>
        <tr height="30px"><td width="200px"><?php _e("Check to include Slimbox2 code", 'rpress_main' ); ?></td><td width="400px"><input type="checkbox" name="lightbox" value="lightbox" <?php echo ' ' . $lt_checked; ?> ></td><td><?php _e('Uncheck if you are using your own Lightbox clone plugin.', 'rpress_main'  ); ?></td></tr>
        <tr height="30px"><td><?php _e("Check to include source details ", 'rpress_main' ); ?></td><td><input type="checkbox" name="sources" value="sources" <?php echo ' ' . $src_checked; ?> ></td><td></td></tr>
<?php
echo '<tr height="30px"><td style="border: 0px #cccccc solid; width: 200px">' .  __( 'Initial page', 'rpress_main' ) .'</td>';
echo '<td><input style="width: 40px;" type="radio" name="ipage" value="home"' . $ihome_checked . '>' . __( 'Home', 'rpress_main' );
echo '<input style="width: 40px;" type="radio" name="ipage" value="base"' . $ibase_checked . '>' .  __( 'Fact page', 'rpress_main' );
echo '<input style="width: 40px;" type="radio" name="ipage" value="indx"' . $iindex_checked . '>' . __( 'Index', 'rpress_main' );
echo '<input style="width: 40px;" type="radio" name="ipage" value="tree"' . $itree_checked . '>' . __( 'Interactive tree', 'rpress_main' );
echo '</td></tr></table>';
echo '<hr>';


//Interactive tree style
$tn_persbox =  'background-color: ' . '#'.$prv_boxbg . '; border: ' . '#'.$prv_boxbd . ' solid 2px; padding:1px 2px 1px 2px; margin: 6px 0px 6px 0px; min-height:25px; text-align: left; cursor: pointer; border-radius: 10px; -moz-border-radius: 10px; font-size: 12px; width: 136px;';
$tn_hline   = 'padding: 0px; margin: 0px 0px 0px auto; height: 3px; border-collapse: collapse; vertical-align: middle; background-color: ' . $prv_line . ';"';
$tn_box     = 'padding: 0px; margin: 0px 0px 0px auto; width: 1px; border-collapse: collapse; vertical-align: middle; background-color: ' . $prv_boxbg . ';"' ;

echo '<strong>' . __('Interactive tree style', 'rpress_main') . '</strong>';

echo '<table width="850px">';     //surrounding table
echo '<tr>';
//bgcolor, lncolor, lnthick, bxcolor, bdcolor
echo '<td width=220px>' . __('Background color', 'rpress_main') . '</td><td><input type="text" name="tnopt[]" value="' . $prv_bkgnd . '" size="6"></td>' ;
echo '<td width=220px>' . __('Line color', 'rpress_main') .       '</td><td><input type="text" name="tnopt[]" value="' . $prv_line . '" size="6"></td>';

echo '<td rowspan="2" style="text align: center;">';

//This is the sample box preview
echo '<table id="prv_bg" style="text-align: center; border: 1px solid; background-color: ' . '#'.$prv_bkgnd . '; width: 100px; height: 120px;"><tr>';
echo '<td><hr id="prv_hr1"' . ' style="height: 0px; padding: 0px; margin: 0px; border-width: 1px; border-color: ' . '#'.$prv_line . ' ;"  width="20">';
echo '</td>';
echo '<td>';
echo '<div class="person_box" id="prv_box"' . '" style="'. $tn_persbox . '">';
echo 'Lorum ipsum</div></td>';
echo '<td><hr id="prv_hr2"' . ' style="height: 0px; padding: 0px; margin: 0px; border-width: 1px; border-color: ' . '#'.$prv_line . ' ;"  width="20">';
echo '</td>';
echo '</tr>';
echo '</table>';

echo '</td></tr>';
echo '<td width=100px>' . __('Box color', 'rpress_main') . '</td><td><input type="text" name="tnopt[]" value=' . $prv_boxbg . ' size="6"></td><td>' . __('Box border color', 'rpress_main') . '</td><td><input type="text" name="tnopt[]" value="' . $prv_boxbd . '" size="6"></td></tr>';
echo '<tr>' . '<td></td><td></td><td></td><td></td>' . '<td style="text-align: left;">'; ?><a href="#"  class='button-primary' onclick="tPreview('rpress_form3')";>  <?php _e('Preview', 'rpress_main')?> </a> <?php echo '</td></tr>';
echo '<tr><td>' . __('View width (minimum 600px)', 'rpress_main') . '</td>'  ?> <td><input type="text" name="twidth" value=" <?php echo $twidth; ?> " size="3"></td><td> <?php _e('View height (minimum 400px)', 'rpress_main') . '</td>' ?>  <td><input type="text" name="theight" value=" <?php echo $theight; ?> " size="3">  </td> <td style="text-align: center;"></td></tr>

<?php
echo '</td></tr>';
echo '</table>';

?>


        </table>
<hr>

<?php 
//Google map style
        echo '<strong>' . __('Google map style', 'rpress_main') . '</strong>';  ?>
        <table style="border: 0px #000000 solid">
        <tr height="30px"><td style="border: 0px #cccccc solid; width: 200px"><?php _e('Initial Google map type', 'rpress_main'); ?> </td>
            <td style="border: 0px #cccccc solid; width: 80px;"><input type="radio" name="maptype" value="gstreet" <?php echo ' ' . $gstreet_checked . '>' . __( 'Street', 'rpress_main' )  ?></td>
            <td style="border: 0px #cccccc solid; width: 80px;"><input type="radio" name="maptype" value="gterr" <?php echo ' ' . $gterr_checked . '>' . __( 'Terrain', 'rpress_main' )  ?></td>
            <td style="border: 0px #cccccc solid; width: 80px;"><input type="radio" name="maptype" value="ghyb" <?php echo ' ' . $ghyb_checked . '>' . __( 'Hybrid', 'rpress_main' )  ?></td>
            <td style="border: 0px #cccccc solid; width: 80px;"><input type="radio" name="maptype" value="gsat" <?php echo ' ' . $gsat_checked . '>' . __( 'Satellite', 'rpress_main' )  ?></td>
        </tr>
         </table>
         <table>
        <tr height="30px"><td><?php _e("Map type changes ", 'rpress_main' ); ?></td><td><input type="checkbox" name="typech" value="typech" <?php echo ' ' . $typech_checked; ?> ></td><td></td><td></td><td></td><td><?php _e('Check to allow the user to change the map type.', 'rpress_main' ); ?></td></tr>
        <tr height="30px"><td style="border: 0px #cccccc solid; width: 200px"><?php _e('Google map size', 'rpress_main'); ?></td>
        <td width=20px><?php _e('Width', 'rpress_main'); ?></td><td width=120px><input type="text" name="gwidth" value=" <?php echo $gwidth; ?> " size="3"></td>
        <td width=20px><?php _e('Height', 'rpress_main'); ?></td><td width=120px><input type="text" name="gheight" value=" <?php echo $gheight; ?> " size="3"></td>
        <td><?php _e('You can adjust the map size, for example to fit your theme. The minimum width and height is 200 px.', 'rpress_main'); ?></td></tr>
        </table>

	<p class="submit">
        <input class='button-primary' type='submit' name='submit1' id='mapp_create_btn' value='<?php _e('Update', 'rpress_main')?>' />

        </p>
</form>
<?php
    }
    
    function rp_opt_process() {
    $msg = '';
    //update database with posted values
    $options = array();
//    $options['media_path'] = $_POST['media_path'];
//    $options['portraits_path'] = $_POST['portraits_path'];
    
    if(isset($_POST['lightbox']))  $options['lightbox'] = true;
      else $options['lightbox'] = false;
//    $options['mapper'] = $_POST['mapper'];
//    $options['age_limit'] = $_POST['age_limit'];
    if(isset($_POST['sources']))  $options['sources'] = true;
      else $options['sources'] = false;
    if(isset($_POST['typech']))  $options['typech'] = true;
      else $options['typech'] = false;
//    if(isset($_POST['thumbs']))  $options['thumbs'] = true;
//      else $options['thumbs'] = false;
    if($_POST['gwidth'] >= 200) $options['gwidth'] = trim($_POST['gwidth']);
       else  {
         $options['gwidth'] = 200;
       }
    if($_POST['gheight'] >= 200) $options['gheight'] = trim($_POST['gheight']);
       else  {
         $options['gheight'] = 200;
       }
    if($_POST['twidth'] >= 600) $options['twidth'] = trim($_POST['twidth']);
       else  {
         $options['twidth'] = 600;
       }
    if(trim($_POST['theight']) >= 400) $options['theight'] = trim($_POST['theight']);
       else  {
         $options['theight'] = 400;
       }

    if (isset($_POST['submit1'])) {

//$selected_radio = $_POST['maptype'];
switch ($_POST['maptype']) {
    case 'gstreet':
        $gstreet_checked = 'checked';
        $options['maptype'] = 'gstreet';
        break;
    case 'gterr':
        $gterr_checked = 'checked';
        $options['maptype'] = 'gterr';
        break;
    case 'gsat':
        $gsat_checked = 'checked';
        $options['maptype'] = 'gsat';
        break;
    case 'ghyb':
        $ghyb_checked = 'checked';
        $options['maptype'] = 'ghyb';
        break;
     }
     
switch ($_POST['ipage']) {
    case 'home':
        $ihome_checked = 'checked';
        $options['ipage'] = 'home';
        break;
    case 'base':
        $ibase_checked = 'checked';
        $options['ipage'] = 'base';
        break;
    case 'indx':
        $iindex_checked = 'checked';
        $options['ipage'] = 'indx';
        break;
    case 'tree':
        $itree_checked = 'checked';
        $options['ipage'] = 'tree';
        break;
     }

}
      if(isset($_POST['tnopt'][0])) $options['tnprev1'] = $_POST['tnopt'][0];
      if(isset($_POST['tnopt'][1])) $options['tnprev2'] = $_POST['tnopt'][1];
      if(isset($_POST['tnopt'][2])) $options['tnprev3'] = $_POST['tnopt'][2];
      if(isset($_POST['tnopt'][3])) $options['tnprev4'] = $_POST['tnopt'][3];

    update_option('rootspress_options', $options);
//copy to session variables
         foreach ($options as $key =>$option)
           $_SESSION[$key] = $option;

    $msg .= __('Options have been updated. Some values may have been set to minimums.', 'rpress_main');
          return $msg;
    }

    function rp_options_trees() {
      global $rootspressdb, $rp_database;
        $msg = '';
        echo "<h3>" . 'rootsPress ' . __( 'Tree options', 'rpress_main' ) . "</h3>";

        if(isset($_POST['rpress_hidden']) && $_POST['rpress_hidden'] == 'opt2') {
             $msg = $this->rp_options_trees_process();
        }

        if ($msg != '') {
          echo '<div class="updated"><p><strong>' . $msg  . '</strong></p></div>';
        }
?>
        <form name="rpress_form4" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<input type="hidden" name="rpress_hidden" value="opt2">
<?php

         $result = mysql_query("SELECT * FROM $rp_database.rp_index");
         if(!$result || mysql_num_rows($result) == 0) {
           echo '<div class="updated"><p><strong>' . __('No trees in database', 'rpress_main') . '</strong></p></div>';
           return;
         }

        echo '<p><strong>' . __('Media path', 'rpress_main') . ' </strong>' . __('Define the media path according to where you uploaded the media files. The media path value is pre-pended to the file reference in the Gedcom file after it is adjusted to remove any drive references.', 'rpress_main') . '</p>';
        echo '<p><strong>' . __('NOTE', 'rpress_main') .  ' </strong>' . __('If media objects cannot be found, a default image will be displayed. Hovering over this image will show where the plugin is looking to find the file.', 'rpress_main') . '</p>';
        echo '<p><strong>' . __('Thumbnails', 'rpress_main') . ' </strong>' . __('Do not check this box unless you use a separate thumbnail library.', 'rpress_main') . '</p>';
        echo '<p><strong>' . __('Portraits', 'rpress_main') . ' </strong>' . __('If you are using a separate portraits directory specify the full path here and select the radio button.', 'rpress_main') . '</p>';
        echo '<p>' . __('Refer to the plugin guide for more detail on defining media paths', 'rpress_main') . ' ' . '<a title="Guide" href="http://localhost/wordpress/wp-content/plugins/rootspress/rootspress guide.pdf" target="_blank">' . __('Guide', 'rpress_main') . '</a></p>';
        echo '<hr/>';

         $action = '';
         $j=0;
         
//Now show form

         while($row = mysql_fetch_array($result))
         {
echo '<input type="hidden" name="tree_hidden[]" value=' . $row['tree_id'] . '>';
echo '<h3>'. $row['tree_name'] . ' (id ' . $row['tree_id']  . ')</h3>';
         ?>
<table style="float: left; width: 450px;"><tr><td>
        <table>
        <tr>
        <th colspan="1"><?php _e('Media', 'rpress_main' ); ?></th>
        </tr>
        <tr>
<?php if ($row['thumbs']) $tn_checked = 'checked';
   else $tn_checked = 'unchecked';
   ?>
        <td width=20px><?php _e('Path', 'rpress_main' ); ?></td>
        <td width=120px><input type="text" name="media_path[]" value="<?php echo $row['media_path']; ?>" size="80"></td></tr>
        <tr><td> <?php echo '<input type="checkbox" name="thumbs' . $j . '" value="' . $j . '" '. $tn_checked. '>' ?>  </td><td width=180px><?php _e('Check to use a thumbnail library', 'rpress_main' ); ?></td></tr>
        </table>

 <table style="float: left; table-layout:fixed;" width=450px;>
        <tr>
        <th colspan="3" style="text-align: left;"><?php _e('Portraits', 'rpress_main' ); ?></th>
        </tr>
<?php
     $silh_checked = '';
     $sept_checked = '';
     $file_checked = '';

     switch($row['ports']) {
       case '0':
            $silh_checked = 'checked';
            break;
       case '1':
            $sept_checked = 'checked';
            break;
       case '2':
            $file_checked = 'checked';
            break;
     }
?>
<tr>
<td width=33%><input style="width: 10px;" type="radio" name="ports.<?php echo $j ?>" value="0" <?php echo $silh_checked?> ><?php echo   __( 'Silhouette', 'rpress_main' ) ?></td>
<td width=33%><input style="width: 10px;" type="radio" name="ports.<?php echo $j ?>" value="1" <?php echo $sept_checked?> ><?php echo  __( 'Separate file', 'rpress_main' ) ?></td>
<td width=33%><input style="width: 10px;" type="radio" name="ports.<?php echo $j ?>" value="2" <?php echo $file_checked?> ><?php echo  __( 'Gedcom file', 'rpress_main' ) ?></td>
</tr>

        <tr><td colspan=3 width=90%><?php _e('Path (only required for separate file option)', 'rpress_main' ); ?></td></tr>
        <tr><td width=90%><input type="text" name="portraits_path[]" value="<?php echo $row['portraits_path']; ?>" size="80"></td><td></td><td></td></tr>
         </table>
         
        <table>
        <tr>
        <th colspan="1"><?php _e('Logins', 'rpress_main')?></th>
        </tr><tr>
<?php    if ($row['secured']) $sec_checked = 'checked';
         else $sec_checked = 'unchecked';
   ?>
        <td><?php echo '<input type="checkbox" name="secured' . $j . '" value="' . $j . '" '. $sec_checked. '>' ?></td>
        <td><?php _e('Check to require logins for this tree.', 'rpress_main' ); ?></td>
        </tr>
         </table>
</td></tr>
<tr><td style="background-color:silver" ><i><strong><?php _e('Check to change this tree', 'rpress_main') ?></strong></i> <?php echo '<input type="checkbox" name="actions[]' . $j . '" value="' . $j . '" '. 'unchecked'. '>' ?></i></td></tr>

</table>

<table><tr><td>

        <?php
        echo '<strong>' . __('Home page content', 'rpress_main') . '</strong>';
          $content = $row['home_html'];  //read old content from database
          $media_buttons = false;
          $id = 'mycontent'.$row['tree_id'];    //unique id
//          the_editor($content, $id, null, $media_buttons);
          $settings=array('true', 'false', '$editor_id');
          wp_editor( $content, $id, $settings);
         ?>

</td></tr></table>
<hr/>

<?php
$j++;
         }

?>
        <p class="submit">
        <input class='button-primary' type='submit' name='submit2' id='mapp_create_btn' value='<?php _e('Update', 'rpress_main')?>' />
        </p>
        </form>
<?php
    }

    function rp_options_trees_process() {
      global $rootspressdb, $rp_database;
      $msg = '';

    if(isset($_POST['actions']) && $_POST['rpress_hidden'] == 'opt2')
          $aTree = $_POST['actions'];
           else  $aTree = '';

          if(empty($aTree))  {
             $msg =  __('No trees selected for updating.', 'rpress_main');
             return $msg;
          } else {

         $N = count($aTree);
         if($N>1) $msg = __('The following trees have been updated', 'rpress_main') . ': <br/>';
             else $msg = __('The following tree has been updated', 'rpress_main') . ': <br/>';
    foreach ($aTree as $key => $value) {
         $k = 'thumbs' . $value;
         if (isset($_POST[$k])) $thumbs = "'1'";
             else $thumbs = "'0'";

         $indie = 'ports_'.$value;
         if (isset($_POST[$indie])) $ports = "'" . $_POST[$indie] . "'";
           else $ports = "'0'";

         $k = 'secured' . $value;
         if (isset($_POST[$k])) $secured = "'1'";
             else $secured = "'0'";

         $tree_id = "'" . $_POST['tree_hidden'][$value] . "'";
         $media_path = "'" . $_POST['media_path'][$value] . "'";
         $portraits_path = "'" . $_POST['portraits_path'][$value] . "'";
         $cont = 'mycontent'. $_POST['tree_hidden'][$value] ;   //unique id
         $home_html =  "'" . $_POST[$cont]. "'";

$sql = "UPDATE " . $rp_database . '.' . "rp_index SET media_path= $media_path, portraits_path = $portraits_path, ports = $ports, thumbs = $thumbs, secured = $secured, home_html = $home_html WHERE tree_id = $tree_id ";
         $result = mysql_query($sql, $rootspressdb);
         if(!$result) {
           $msg .=  $this->rp_show_error('Error: Unable to upgrade rootsPress index table', 'rpress_main');
           return $msg;
         }
//Update session variables
if(isset($_POST['media_path'][$value])) $_SESSION['media_path'] = $_POST['media_path'][$value];
  else $_SESSION['media_path'] = null;
if(isset($_POST['portraits_path'][$value])) $_SESSION['portraits_path'] = $_POST['portraits_path'][$value];
  else $_SESSION['portraits_path'] = null;
if(isset($_POST['thumbs'][$value])) $_SESSION['thumbs'] = $_POST['thumbs'][$value];
  else $_SESSION['thumbs'] = 0;
if(isset($_POST['secured'][$value])) $_SESSION['secured'] = $_POST['secured'][$value];
  else $_SESSION['secured'] = 0;

if(substr($_POST['media_path'][$value], 0, 7) == 'http://') $mediapath = $_POST['media_path'][$value];
  else $mediapath = site_url($_POST['media_path'][$value]);
if(substr($_POST['portraits_path'][$value], 0, 7) == 'http://') $portraitspath = $_POST['portraits_path'][$value];
  else $portraitspath = site_url($_POST['portraits_path'][$value]);

         $msg .= ' id=' . $_POST['tree_hidden'][$value] . ' ' . __('The resolved media path is', 'rpress_main') . ' <i>'. $mediapath. '</i>. ' . __('The resolved portraits path is', 'rpress_main') . ' <i>'. $portraitspath. '.</i></br></br>';
             }
             return $msg;
          }

    if (isset($_POST['submit2'])) echo 'options trees submit';

    }   //End function

    function rp_add()
    {
        global $msg, $rootspressdb;
        echo "<h3>" . 'rootsPress ' . __( 'Add tree', 'rpress_main' ) . "</h3>";

//Set defaults
        $rp_tree = '';
        $rp_file = '';
        if(isset($_POST['rpress_hidden']) && $_POST['rpress_hidden'] == 'add') {
             $msg = $this->rp_add_process();
        }

        if ($msg != '') {
          if(strtolower(substr($msg,0,6)) == 'error:') $class= 'error';
             else $class = 'updated';
          echo '<div class="' . $class . '"><p><strong>' . $msg  . '</strong></p></div>';
        }

?>

        <form enctype="multipart/form-data" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="POST">
	<input type="hidden" name="rpress_hidden" value="add">
	<p><strong>
	<?php
//	if(ini_get('safe_mode')) echo 'Your server is operating with safe mode on. You will be limited to a maximum execution time of ' . ini_get('max_execution_time') . ' seconds.<br/>';
        ?>
        </strong></p>

        <p><?php echo '<strong>' .  __('Tree name', 'rpress_main' ) . '</strong>'; ?></p>
        <table style="border: 0px #000000 solid; width: 700px;">
        <tr><td style="border: 0px #cccccc solid; width: 250px"><?php echo  __('Enter the tree name here', 'rpress_main' ) ; ?></td><td><input type="text" name="rpress_gedname" value="<?php echo $rp_tree; ?>" size="40"></td></tr>

        <?php
//Initial values are server option checked, client option unchecked and input file from client disabled
        $server_checked = 'unchecked';
        $client_checked = 'checked';
        ?>
        </table>
        <p><?php echo '<strong>' .  __('Gedcom file', 'rpress_main' ) . '</strong>'; ?></p>
        <p>You can upload the gedcom file from your computer (client).
        If you prefer, for example if your server does not allow a large enough maximum file size, you can use a tool like ftp and pre-load the file to the wordpress uploads folder</p>
        <table>
        <tr><td>File is located on server<input type="radio" onclick="this.form.uploaded.disabled = true; this.form.serverfile.disabled = false; document.getElementById('rp_file_upload').value='';" name="gedfile" value="server" <?php echo ' ' . $server_checked . '>' ?>  </td>
        <td><?php _e( 'File is located on client', 'rpress_main' ) ?><input type="radio" onclick="this.form.uploaded.disabled = false; this.form.serverfile.disabled = true;  this.form.serverfile.value=''; " name="gedfile" value="client" <?php echo ' ' . $client_checked . '>'  ?></td></tr>
        <tr><td><?php _e('Enter the name of the file located in the "uploads" folder', 'rpress_main' ); ?></td><td><input type="text" name="serverfile" value=""  disabled="true"/></td></tr>
        <tr><td><?php _e('Select client file to be uploaded', 'rpress_main' ); ?></td><td><input id="rp_file_upload" name="uploaded" type="file" size="60"/></td></tr>
        </table>
        
        <p><?php echo '<strong>' .  __('Attributes', 'rpress_main' ) . '</strong>'; ?></p>
        <p><?php _e('You can initialize the tree with options from an existing tree, for example if you intend this tree as a replacement, or leave the options as defaults and change them later.', 'rpress_main'); ?>
        <?php _e('Note that if the default person from the tree you use to copy from does not exist in the new database, you will need to change this by selecting the correct person from the index and then resetting the default.', 'rpress_main'); ?></p>

        <table>
        <tr><td style="border: 0px #cccccc solid; width: 250px"><?php _e('Select an existing tree to copy the attributes from', 'rpress_main' ); ?></td><td>
<?php
//here we will populate the select field with entries for each existing tree, the option value being the id
         $result = mysql_query("SELECT * FROM rp_index");
         if($result && mysql_num_rows($result) != 0) {

         echo '<select size="1" name="selectionField" style="width:300px">';
         echo '<option value="'.'dflt'.'" >'. '' . '</option>';
         while($row = mysql_fetch_array($result))
           {
             echo '<option value="'.$row['tree_id'].'" >'. $row['tree_name'] . ' (id='. $row['tree_id'] . ')</option>';
           }
         echo '</select>';
         }  else {
             echo '<i>' . __('No trees in database', 'rpress_main') . '</i>';
         }
?>

        </td></tr>
	</table>

	<p class="submit">
        <input class='button-primary' type='submit' id='mapp_create_btn' value='<?php _e('Add tree', 'rpress_main')?>' />

        </p>
        </form>
<?php
    }
    
        function rp_add_process () {
          global $rootspressdb, $rp_database;
//Before anything else, check if the rp_index table exists and create it if not
        $msg = '';

// $_POST['gedfile'] indicates the type of input file, client or server

//File on server
    if($_POST['gedfile'] == 'server') {
       $upload = wp_upload_dir();
       if($_POST['serverfile'] == null) {
         $msg = 'Error: server file name missing';
         return $msg;
       }
       $filepath = $upload['basedir'] . '/' . $_POST['serverfile'];
       $gedfile2 =  "'" . $_POST['gedfile'] . "'";
       //now look to see if file exists
       if(!file_exists($filepath)) {
         $msg .= 'Error: file "' . $_POST['serverfile']. '" not found in uploads directory';
         return $msg;
       }
     }

//File uploaded from Client
    if($_POST['gedfile'] == 'client') {
        $file = $_FILES['uploaded'];
        $gedfile2 = "'" . $file['name'] . "'";
    $error_text = true; // Show text or number
    define("UPLOAD_ERR_EMPTY",5);
    $upload_errors = array(
    UPLOAD_ERR_OK        => "No errors.", 
    UPLOAD_ERR_INI_SIZE    => "Larger than upload_max_filesize.", 
    UPLOAD_ERR_FORM_SIZE    => "Larger than form MAX_FILE_SIZE.", 
    UPLOAD_ERR_PARTIAL    => "Partial upload.", 
    UPLOAD_ERR_NO_FILE        => "No file.", 
    UPLOAD_ERR_NO_TMP_DIR    => "No temporary directory.",
    UPLOAD_ERR_CANT_WRITE    => "Can't write to disk.", 
    UPLOAD_ERR_EXTENSION     => "File upload stopped by extension.", 
    UPLOAD_ERR_EMPTY        => "File is empty." // add this to avoid an offset 
  );
        if($file['error'] > 0 ) {
            $msg .= 'Error: upload file > ' . $upload_errors[$file['error']];
            return $msg;
        }
        $filepath =  $file['tmp_name'];
}
        if($_POST['gedfile'] != 'client' && $_POST['gedfile'] != 'server') echo "Internal error, gedcom file type not defined";

        $auto_detect_line_endings = ini_get("auto_detect_line_endings");  //save auto_detect_line_endings for later reset
        ini_set("auto_detect_line_endings", true);  //set auto_detect_line_endings to auto for MAC line endings

         if(isset($_POST['rpress_gedname'])) {
            $gedname = $_POST['rpress_gedname'];    //name of tree to be added
            }  else {
            $gedname = '';
         }

         if ($gedname == '') {
           $msg = "Error: Tree name is missing";
           return $msg;
          }

//add new row in index table
           $gedname2 = "'" . $_POST['rpress_gedname'] . "'";
//           $gedfile2 = "'" . $_FILES['uploaded']['name'] . "'";     //but this only exsists if file uploaded from client
           $mainpage_id = $this->addMainPage(0, $gedname, 0);   //ged id, title, parent id
//           $homepage_id = $this->addHomePage($gedname, $mainpage_id);      //not used in 2.6.0

//set security to off initially
           $security = "'" . '0' . "'" ;
           $sql = "INSERT INTO " . $rp_database . '.' . "rp_index (tree_name, tree_file, wp_root_id, secured) VALUES ( $gedname2, $gedfile2, $mainpage_id, $security)";

           $result = mysql_query($sql, $rootspressdb);
           if (!$result) {
             return $this->rp_show_error('Error: failed to add tree to index');
           }
           $gedindx = mysql_insert_id(); //new rp index
           $rp_prefix = 'rp' . $gedindx;
           

//Build tables in rootsPress database
                  $rp_tree = $_POST['rpress_gedname'];

                  $this->rp_build_table($rp_prefix, $rp_database);

/* Read gedcom and populate new database tables
** Assumes prefix is in $rp_prefix and file path is in $filepath
*/
                require("php/gedcom.php");
                $gedreturn = rp_load_ged($rp_prefix, $filepath);
                $auto_detect_line_endings = ini_get("auto_detect_line_endings");
                ini_set("auto_detect_line_endings", $auto_detect_line_endings);   //reset  auto_detect_line_endings
                if($gedreturn != 0) {
//If gedcom.php returned an error, delete the tables and the index
//remove main tables
         $this->rp_remove_table($rp_prefix, $rp_database);
//remove main wp pages
          wp_delete_post($mainpage_id);
//remove entry from rp_index
          $sql = "DELETE FROM rp_index WHERE tree_id= $gedindx ";
          mysql_query($sql, $rootspressdb);
                }

                if($gedreturn == -1) {
                  $msg .= __('Error: the add process failed because the maximum execution time was exceeded', 'rpress_main') . ' (' . $gedname . ')' . '<br/>' ;
                  return $msg;
                }
                
                if($gedreturn == -2) {
                 $msg .= __('Error: internal syntax error while loading data tables', 'rpress_main') . ' (' . $gedname . ')'  ;
                  return $msg;
                }

                if($gedreturn == 0) {
                  $msg .= __('Gedcom file uploaded to database', 'rpress_main') . '<br/>';
                }

  
//Add tree index and default user id to short code. Defaults to first entry in individual table
    $num=1;
    $sql = 'SELECT * FROM '. $rp_database . '.' . $rp_prefix . '_individual' .  " WHERE indi_id = '". $num. "'";
      $result = mysql_query($sql, $rootspressdb);
      $row = mysql_fetch_assoc($result);
      $default_id =  $row['indi_gedcomnumber'];
      $my_post = array();
      $my_post['ID'] = $mainpage_id;
      $my_post['post_content'] = '[rootsPress ged=' . $rp_prefix . ' pid=' . $default_id . ' mode=home]';
      wp_update_post( $my_post );
      
//Also update rp_index with default id
      $sql = "UPDATE " . $rp_database . '.' . "rp_index SET default_id='" . $default_id . "' WHERE tree_id = '". $gedindx.  "'";
      $result = mysql_query($sql, $rootspressdb);

//Add index page
//table prefix, tree name, parent page, reference page
//      $this->addIndexPage($rp_prefix, $rp_tree, $rootpage_id, $mainpage_id);
//        $msg .= "New tree '" . $rp_tree . "' (id=" . $gedindx . ') added.' . '<br/>';
        $msg .= sprintf(__('New tree %s (id= %s) added', 'rpress_main'), $rp_tree, $gedindx)  . '<br/>';
        $msg .= __('This tree contains the following tables', 'rpress_main') . ': ';
//add actual database counts from this database here as part of msg
        $msg .= $this->table_stats($gedindx) . '<br/>';
        $msg_done = null;
//If this tree wants values from a prior, copy them over
       if(isset($_POST['selectionField']) && $_POST['selectionField'] != 'dflt') {
        $selection = "'" .  $_POST['selectionField'] . "'";
        $sql = "SELECT * FROM " . $rp_database . '.' . "rp_index WHERE tree_id = $selection";
        $result1 = mysql_query($sql, $rootspressdb);
        if(!$result1) echo $this->rp_show_error('Error: could not find existing tree options');
        $row1 = mysql_fetch_assoc($result1);
        $default_id =  "'" . $row1['default_id'] .  "'";
        $media_path =  "'" . $row1['media_path'] .  "'";
        $thumbs =  "'" . $row1['thumbs'] .  "'";
        $portraits_path = "'" . $row1['portraits_path'] .  "'";
        $ports =  "'" . $row1['ports'] .  "'";
        $secured =  "'" . $row1['secured'] .  "'";
        $home_html =  "'" . addslashes($row1['home_html']).  "'";
        $sql = "UPDATE " . $rp_database . '.' . "rp_index SET default_id=$default_id, media_path=$media_path,  thumbs=$thumbs, portraits_path=$portraits_path, ports = $ports, secured = $secured, home_html = $home_html WHERE tree_id = $gedindx ";
        $msg_done = sprintf(__('This tree has been updated with attributes from tree  %s  (id= %s)', 'rpress_main'), $row1['tree_name'], $_POST['selectionField']) . '<br/>';
       } else {
 //else write a default home page content
        $home_page  =  __('This is a sample home page which you should edit for your own use.', 'rpress_main')."\r\n";
        $home_page .=  __('By default rootsPress starts with this page but you can set the index, facts page or interactive tree as the starting point.', 'rpress_main').' ';
        $home_page .=  __('Place anything here you wish to be used as an introduction to your Family History including images and text.', 'rpress_main');
        $home_html =  "'" . addslashes($home_page).  "'";
        $sql = "UPDATE " . $rp_database . '.' . "rp_index SET home_html = $home_html WHERE tree_id = $gedindx ";
       }
        $result = mysql_query($sql, $rootspressdb);
        if(!$result) $msg .= $this->rp_show_error('Error: failed to update tree attributes');
           else $msg .= $msg_done;

      return $msg;
        }

//This function creates rp_index if it doesnt already exist
        function rp_make_index() {
//          global $rootspressdb, $rp_database;
          
$db_array = array();
$dbconfig = ABSPATH. '/wp-content/plugins/rootspress/db_config.php';
if(file_exists($dbconfig)) {
      include_once $dbconfig;
$db_array['database'] = RP_DB_NAME;
$db_array['dbuser']   = RP_DB_USER;
$db_array['dbpwd']    = RP_DB_PASSWORD;
$db_array['dbhost']   = RP_DB_HOST;
} else {
$db_array['database'] = DB_NAME;
$db_array['dbuser']   = DB_USER;
$db_array['dbpwd']    = DB_PASSWORD;
$db_array['dbhost']   = DB_HOST; 
}
$rp_database = $db_array['database'];
          $rootspressdb = @mysql_connect($db_array['dbhost'],$db_array['dbuser'],$db_array['dbpwd']);
          if ($rootspressdb === false)
             die("Error: Could not connect to host " .  $db_array['dbhost'] .  "' for user '" . $db_array['dbuser'] . "'");

          $table_name = 'rp_index';
          $sql = "CREATE TABLE IF NOT EXISTS " . $rp_database. '.' . $table_name . " (
	  tree_id mediumint(2) NOT NULL AUTO_INCREMENT,
	  tree_name VARCHAR(25) NOT NULL,
	  tree_file VARCHAR(64),
	  wp_root_id mediumint(4) NOT NULL,
	  wp_home_id mediumint(4) NOT NULL,
	  default_id text,
	  media_path VARCHAR(64),
	  thumbs BOOL,
	  portraits_path VARCHAR(64),
	  ports BOOL,
	  secured BOOL,
	  home_html BLOB,
	  time_stamp TIMESTAMP,
	  PRIMARY KEY (tree_id)
        	);";
          $result1 = mysql_query($sql, $rootspressdb);
//         }
         if ($result1 === FALSE) {
             die(mysql_errno($rootspressdb) . ' ' . mysql_error($rootspressdb) );
             return;
         }
} //End function
        

        function rp_remove()  {
          global $rootspressdb, $rp_database;
        echo "<h3>" . 'rootsPress ' . __( 'Remove trees', 'rpress_main' ) . "</h3>";
        $msg = '';

        if(isset($_POST['rpress_hidden']) && $_POST['rpress_hidden'] == 'rem') {
             $msg = $this->rp_remove_process();
        }
        if ($msg != '') {
          echo '<div class="updated"><p><strong>' . $msg  . '</strong></p></div>';
        }
?>
        <form name="rpress_form6" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<input type="hidden" name="rpress_hidden" value="rem">
	<?php

        $sql = 'SELECT * FROM ' . $rp_database . '.' . 'rp_index';
        $result = mysql_query($sql);
         if(!$result || mysql_num_rows($result) == 0) {
           echo '<div class="updated"><p><strong>' . __('No trees in database', 'rpress_main')  . '</strong></p></div>';
//No trees in database so drop rp index
//        $sql = "DROP TABLE rp_index" ;
//        mysql_query($sql, $rootspressdb);
           return;
         }

        echo __('Check the trees you want to remove and click the button below', 'rpress_main') . '<br />';
        echo '<table>';
        while($row = mysql_fetch_array($result))
         {
         echo '<tr><td>' . $row['tree_name'] . ' (id ' . $row['tree_id'] . ') ' . '</td><td><input type="checkbox" name="gedname[]" value=' . $row['tree_id'] . ' unchecked="yes"/> ' . '</td></tr>';
         }
         echo '</table>';

         ?>
	<hr />
	<p class="submit">
        <input class='button-primary' type='submit' id='mapp_create_btn' value='<?php _e('Remove', 'rpress_main')?>' />

        </p>
</form>

<?php
    }

       function rp_remove_process() {
         global $rootspressdb, $rp_database;
        $msg= '';
       if(isset($_POST['gedname']) && $_POST['rpress_hidden'] == 'rem') {
          $aTree = $_POST['gedname'];    //name of tree to be removed
         }  else {
           $aTree = '';
         }

          if(empty($aTree))  {
             $msg = __('No trees selected for removal.', 'rpress_main');
             return $msg;
          } else {
          $N = count($aTree);
          if($N>1) $msg = __('The following trees have been removed', 'rpress_main') . ': <br/>';
             else $msg = __('The following tree was removed', 'rpress_main') . ': <br/>';
          foreach ($aTree as $value) {
            $rp_prefix = 'rp' . $value;
            $msg .= ' id ' . $value . ',';

//remove main tables
          $this->rp_remove_table($rp_prefix, $rp_database);
          
//get page id of root for following operations
          $sql = 'SELECT * FROM ' . $rp_database . '.' . "rp_index WHERE tree_id = $value";
          $result = mysql_query($sql, $rootspressdb);
          $row = mysql_fetch_array($result, MYSQL_ASSOC);
          $page =  $row['wp_root_id'];
          wp_delete_post($page);
          $page =  $row['wp_home_id'];
          if($page != null) wp_delete_post($page);

//remove entry from rp_index
          $sql = 'DELETE FROM ' . $rp_database . '.' . "rp_index WHERE tree_id= $value";
          $result = mysql_query($sql, $rootspressdb);

             }
          }
        return rtrim($msg, ',');
       }

        function rp_uninstall() {
        global $msg;
        echo "<h3>" . 'rootsPress ' . __( 'Uninstall plugin data', 'rpress_main' ) . "</h3>";
        $msg = $this->rp_uninstall_process();

        if ($msg != '') {
          echo '<div class="updated"><p><strong>' . $msg  . '</strong></p></div>';
          return;
        }
 ?>
        <form name="rpress_form5" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<input type="hidden" name="rpress_hidden" value="uninst">

        <p style="color: red;"><?php _e('WARNING', 'rpress_main'); ?><br/>
        <?php _e('This will remove all plugin data in preparation for complete removal. The process cannot be undone.', 'rpress_main'); ?></p>
        <p class="submit">
        <input class='button-primary' type='submit' id='mapp_create_btn' value="<?php _e('Uninstall', 'rpress_main')?>" />
        </p>
        </form>
<?php
          return;
        } //End function
        
        function rp_uninstall_process() {
        if(isset($_POST['rpress_hidden']) && $_POST['rpress_hidden'] == 'uninst') {
          $msg = __('Plugin data has been removed, you may now de-activate it and delete the files.', 'rpress_main');
          $this->rootsPressUninstall();
        }
         return $msg;
        } //End function
       
 /*NOT USED
        function rp_check_db(&$msg)  {
          return true;
        include_once('db_config.php');
        $rootspressdb = @mysql_connect($_SESSION['dbhost'],$_SESSION['dbuser'],$_SESSION['dbpwd'], true);
        if (!$rootspressdb)  {
        $msg = 'Could not connect to database ' . $_SESSION['dbhost'] . ' with user '. $_SESSION['dbuser'] . ' on server ' . $_SESSION['dbhost'] . ' Please check that the database was defined correctly.';
        return false;
           }

        $result = @mysql_select_db($_SESSION['database'], $rootspressdb);
        if (!$result) {
           $msg = "Database table " . $_SESSION['database'] . " not found. Correct and try again.";
           return false;
         }
        return true;
        } //End function
        */

        function rp_show()  {
          global $rootspressdb, $rp_database;
         echo "<h3>" . 'rootsPress ' . __( 'Show trees', 'rpress_main' ) . "</h3>";

         $sql = 'SELECT * FROM ' . $rp_database . '.' . 'rp_index';
         $result = mysql_query($sql, $rootspressdb);
         if(!$result) echo mysql_errno($rootspressdb) . ' ' . mysql_error($rootspressdb) . '<br/>';
         if(mysql_num_rows($result) == 0) {
           echo '<div class="updated"><p><strong>' . __('No trees in database', 'rpress_main')  . '</strong></p></div>';
           return;
         }
         echo '<table border=1>';
         echo '<tr><th>' . __('Tree name', 'rpress_main') . '</th><th>' . __('Tree id', 'rpress_main') . '</th><th>' . __('Root page', 'rpress_main') . '</th><th>' . __('Time stamp', 'rpress_main') . '</th><th>Contents</td></tr>';
         while($row = mysql_fetch_array($result))
         {
         $stats = $this->table_stats($row['tree_id']);
         echo '<tr><td>' . $row['tree_name'] . '</td><td>' . $row['tree_id'] . '</td><td>' . $row['wp_root_id'] . '</td><td>' . $row['time_stamp'] . '</td><td>' . $stats . '</td></tr>';
         }

         echo '</table>';
    }
    
    function table_stats ($rp_prefix) {
      global $rootspressdb, $rp_database;
      $prefix = 'rp' . $rp_prefix;
      $msg = '';

      $sql = "SELECT COUNT(*) FROM " . $rp_database . '.' . $prefix . '_individual';
      $result = mysql_query($sql, $rootspressdb);
      $msg .= mysql_result($result, 0) . ' ' . __('individuals', 'rpress_main') . ', ';

      $sql = "SELECT COUNT(*) FROM " . $rp_database . '.' . $prefix . '_family';
      $result = mysql_query($sql, $rootspressdb);
      $msg .= mysql_result($result, 0) . ' ' . __('families', 'rpress_main') . ', ';

      $sql = "SELECT COUNT(*) FROM " . $rp_database . '.'. $prefix . '_event';
      $result = mysql_query($sql, $rootspressdb);
      $msg .= mysql_result($result, 0) . ' ' . __('events', 'rpress_main') . ', ';

      $sql = "SELECT COUNT(*) FROM " . $rp_database . '.' . $prefix . '_attrib';
      $result = mysql_query($sql, $rootspressdb);
      $msg .= mysql_result($result, 0) . ' ' . __('attributes', 'rpress_main') . ', ';

      $sql = "SELECT COUNT(*) FROM " . $rp_database . '.' . $prefix . '_citation';
      $result = mysql_query($sql, $rootspressdb);
      $msg .= mysql_result($result, 0) . ' ' . __('citations', 'rpress_main') . ', ';

      $sql = "SELECT COUNT(*) FROM " . $rp_database . '.' . $prefix . '_source';
      $result = mysql_query($sql, $rootspressdb);
      $msg .= mysql_result($result, 0) . ' ' . __('sources', 'rpress_main') . ', ';

      $sql = "SELECT COUNT(*) FROM " . $rp_database . '.' . $prefix . '_places';
      $result = mysql_query($sql, $rootspressdb);
      $msg .= mysql_result($result, 0) . ' ' . __('places', 'rpress_main') . ', ';

      $sql = "SELECT COUNT(*) FROM " . $rp_database . '.' . $prefix . '_obje';
      $result = mysql_query($sql, $rootspressdb);
      $msg .= mysql_result($result, 0) . ' ' . __('media objects', 'rpress_main') . ', ';
      
      $sql = "SELECT COUNT(*) FROM " . $rp_database . '.' . $prefix . '_media';
      $result = mysql_query($sql, $rootspressdb);
      $msg .= mysql_result($result, 0) . ' ' . __('media files', 'rpress_main') . ', ';
      
      $sql = "SELECT COUNT(*) FROM " . $rp_database . '.' . $prefix . '_note';
      $result = mysql_query($sql, $rootspressdb);
      $msg .= mysql_result($result, 0) . ' ' . __('notes', 'rpress_main') ;

      return $msg;
    }

function rp_build_table($prefix, $rp_database) {
  include_once('php/tabadd.php');
  rp_build_tables($prefix, $rp_database);
}

function rp_remove_table($prefix, $rp_database) {
  include_once('php/tabremove.php');
  rp_remove_tables($prefix, $rp_database);
}

        function addRootPage($title) {
//NOT USED
//short code will be added after the index is created, in the add process
            $post_content = "This is a sample home page. Place anything here you wish to be used as an introduction to your Family History including images, text etc. 
            The navigation icons are added by the plugin. Note that by default this will be the first page a user sees but the administration dialog allows you to set the index, facts page or interactive tree as the starting point.";
            $my_post = array();
            $my_post['post_title'] = $title;
            $my_post['post_content'] = $post_content;
            $my_post['post_status'] = 'publish';
            $my_post['post_author'] = 0;
            $my_post['post_type'] = 'page';
            $my_post['ping_status'] = 'closed';
            $my_post['comment_status'] = 'closed';
            $my_post['post_parent'] = 0;

            // Insert the post into the database
            $pageID = wp_insert_post( $my_post );
            return $pageID;
        }

        function addMainPage($ged_id, $title, $parent) {
//short code will be added after the index is created, in the add process
            $my_post = array();
            $my_post['post_title'] = $title;
            $my_post['post_content'] = '[rootsPress ged=' . $ged_id . ' mode=home]';
            $my_post['post_status'] = 'publish';
            $my_post['post_author'] = 0;
            $my_post['post_type'] = 'page';
            $my_post['ping_status'] = 'closed';
            $my_post['comment_status'] = 'closed';
            $my_post['post_parent'] = 0;

            // Insert the post into the database
            $pageID = wp_insert_post( $my_post );
            return $pageID;
        }
        
/*        function addHomePage($title, $parent, $content = null) {
            $my_post = array();
            $my_post['post_title'] = $title . ' home';
            $home_content = "This is a sample home page ($title home) which you should edit for your own use. Place anything here you wish to be used as an introduction to your Family History including images, text etc.
            The navigation icons are added by rootsPress. Note that by default rootsPress starts with this page but the admin dialog allows you to set the index, facts page or interactive tree as the starting point.";
            if($content == null)
                    $my_post['post_content'] = $home_content;
            else    $my_post['post_content'] = $content;
            $my_post['post_status'] = 'private';
            $my_post['post_author'] = 0;
            $my_post['post_type'] = 'page';
            $my_post['ping_status'] = 'closed';
            $my_post['comment_status'] = 'closed';
            $my_post['post_parent'] = $parent;

            // Insert the post into the database
            $pageID = wp_insert_post( $my_post );
            return $pageID;
        }         */
        
       function addIndexPage($prefix, $treename, $parent, $reference) {
            $my_post = array();
            $my_post['post_title'] = __( 'Index of people alphabetically by surname', 'rpress_main' );
            $content = '';
            $this ->build_index($prefix, $content, $reference);
//If setting the index once at tree create, use the first form
//            $my_post['post_content'] = $content; //First form
//If setting the index dynamically use the second form
            $my_post['post_content'] = '[rootsPress ged=' . $prefix . ' index=YES' . ']';  //Second form
            $my_post['post_status'] = 'publish';
            $my_post['post_author'] = 0;
            $my_post['post_type'] = 'page';
            $my_post['ping_status'] = 'closed';
            $my_post['comment_status'] = 'closed';
            $my_post['menu_order'] = 0;
            $my_post['post_parent'] = $parent;

            // Insert the post into the database
            $pageID = wp_insert_post( $my_post );
            return $pageID;
        }

 function build_index($prefix, &$content, $reference) {
   global $rootspressdb, $rp_database;
    $tree_id = substr($prefix,2);
    $sql = "SELECT * FROM rp_index WHERE tree_id = '" . $tree_id . "'";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    $root_page_id = $row['wp_root_id'];
    $permalink = get_permalink($root_page_id);  //used to construct link back to the current page
    $permalink = page_link($prefix);
    $sql = 'SELECT * FROM '. $rp_database . '.' . $prefix . '_individual' ;
    $result=mysql_query($sql, $rootspressdb);
    
    while($row = mysql_fetch_array($result))
         {
    $person_id = $row['indi_gedcomnumber'];
    $myname= $row['indi_fullname'];
    $names = array();
    rootsPress::rp_get_names($myname, $names);
    $data[] = array('first' => $names[0], 'last' => $names[1], 'id' => $person_id);
         }
         
// Obtain a list of columns
    foreach ($data as $key => $row) {
        if ($row['last'] == '') $last[$key]  = '}';  //force to end of sort order
            else $last[$key]  = $row['last'];
        if ($row['first'] == '') $first[$key]  = '}';  //force to end of sort order
            else $first[$key]  = $row['last'];
    }

// Sort the data with last name ascending, first name ascending
// Add $data as the last parameter, to sort by the common key
   array_multisort($last, SORT_ASC, $first, SORT_ASC, $data);
   foreach ($data as $key => $value) {
       $_first = $value['first'] ;
       if($_first == '') $_first = '<i>' .  __( 'Unknown', 'rpress_main' ) . '</i>';
       $_last = $value['last'];
       if($_last == '') $_last = '<i>' .  __( 'Unknown', 'rpress_main' ) . '</i>';
       
//get date of birth
   $sql = "SELECT * FROM " . $rp_database . '.' . $prefix . '_event' . " WHERE event_owner_id = '". $value['id'] . "' AND event_tagtype = '" . 'BIRT' .  "' ";
   $result=mysql_query($sql, $rootspressdb);
   $row = mysql_fetch_array($result);
   if($row['event_date'] != '')
            $birth = '(' . __('born', 'rpress_main') . ' ' .  $row['event_date'] . ')';
   else $birth = '';
   $content .= $_last . ', ' .  $_first . $birth;
   $content .= "<a href = '" . $permalink . "&pid=" . $value['id'] . "&ged=" . $prefix . "&mode=base" . "'>";
   $content .= '<img src="'. WP_PLUGIN_URL . '/rootspress/images/indis.gif"' . ' title="' . __( 'Individual page', 'rpress_main' ) . '" class="rp_icons_indx" '  . '>' ;
   $content .= '</a>';

   $content .= "<a href='" . $permalink . "&pid=" . $value['id'] . "&ged=" . $prefix . "&mode=tree" . "'>";
   $content .= '<img src="'. WP_PLUGIN_URL . '/rootspress/images/gedcom.gif"' . ' title="' .  __( 'Interactive tree', 'rpress_main' ) .'" class="rp_icons_indx" '  . '>' . '</a>';
   $content .= '<br/>';
   }
return $content;
 }

     function rp_delete_pages ($rp_prefix) {
//get $parent id from index file using rp_prefix
//first delete all children then delete parent
           $args = array(
                         'child_of' => $parent,
                         'parent' => $parent,
                         'hierarchical' => 1
             );
            $pages = get_pages($args);  //array of page id's
            foreach ($pages as $pagg) {
              wp_delete_post($pagg);
            }
            wp_delete_post($parent);
  }

function rp_admin_notice(){
  global $admin_msg;
  //use class=error for red message, updated for yellow
  if ($admin_msg != null)
    echo '<div class="error"><p>'.$admin_msg.'</p></div>';
    $admin_msg = null;
}

//Define localization strings for scripts
function get_language_strings() {
  $strings = array(
      'confirm' => __('Click OK to stop loading, cancel to continue.', 'rpress_main'),
      'source' =>  __('Source', 'rpress_main'),
      'noPana' =>  __('No Panoramio images found', 'rpress_main'),
      'noGeog' =>  __('No Geograph images found', 'rpress_main')
      );
      return $strings;
}

function init_plugin() {
   global $rootspressdb, $rp_database;
   $this->rp_tr_set();
$db_array = array();
if(file_exists(ABSPATH.'/wp-content/plugins/rootspress/db_config.php')) {
include_once ABSPATH.'/wp-content/plugins/rootspress/db_config.php';
$db_array['database'] = RP_DB_NAME;
$db_array['dbuser']   = RP_DB_USER;
$db_array['dbpwd']    = RP_DB_PASSWORD;
$db_array['dbhost']   = RP_DB_HOST;
} else {
$db_array['database'] = DB_NAME;
$db_array['dbuser']   = DB_USER;
$db_array['dbpwd']    = DB_PASSWORD;
$db_array['dbhost']   = DB_HOST; 
}
$rp_database =  $db_array['database'];

   $rootspressdb = @mysql_connect($db_array['dbhost'],$db_array['dbuser'],$db_array['dbpwd'], false);
   if ($rootspressdb === false)
        die("Error: Could not connect to host " .  $db_array['dbhost'] .  "' for user '" . $db_array['dbuser'] . "'");
}

        function rp_show_error($msg=null) {
          global $rootspressdb;
           return $msg . '<br/>' . mysql_errno($rootspressdb) . ' ' .  mysql_error($rootspressdb);
        }
/*
function line_end($line) {
   if(strpos($line, "\r\n") !== false) return "\r\n";
   if(strpos($line, "\n\r") !== false) return "\n\r";
   if(strpos($line, "\r") !== false) return "\r";  //its not \r\n or \n\r so must be \r alone
   if(strpos($line, "\n") !== false) return "\n";  //its not \r\n or \n\r so must be \n alone
   return false; //error
}   */

    }
    
        }
/**
 * Second, instantiate a reference to an instance of the class
 */
if (class_exists("rootsPress")) {
    $rootsPressplugin = new  rootsPress();
}

/**
 * Third, activate the plugin and any actions or filters
 */
if (isset($rootsPressplugin)) {
    register_activation_hook(__FILE__,array(&$rootsPressplugin, 'rootsPressInstall'));
    register_deactivation_hook(__FILE__, array(&$rootsPressplugin, 'rootsPressDeactivate') ) ;
    add_action('init', array(&$rootsPressplugin, 'init_plugin'));
    add_action('admin_enqueue_scripts', array(&$rootsPressplugin, 'insert_adminScripts'));
    add_action('admin_notices', array(&$rootsPressplugin, 'rp_admin_notice'));
    add_action('admin_menu', array(&$rootsPressplugin, 'rootsPressOptionsPage'));
    add_filter('plugin_action_links', array(&$rootsPressplugin, 'rootsPress_plugin_action_links') , 10,2);
//    add_action('plugins_loaded', array(&$rootsPressplugin, 'rootsPress_plugins_loaded'));
    add_action('wp_enqueue_scripts', array(&$rootsPressplugin, 'insert_rootsPressStyles'));
    add_action('wp_enqueue_scripts', array(&$rootsPressplugin, 'rootsPress_wphead'));
    add_shortcode('rootsPress', array(&$rootsPressplugin, 'rootsPressHandler'));
}

include_once('php/upgrade.php');  //include upgrade functions

//Copy saved options to session var  to avoided repeated wp-db calls
$temp = get_option('rootspress_options');
if($temp) {
         foreach ($temp as $key =>$option)
           $_SESSION[$key] = trim($option);
}

           rootsPress::rp_installer();

//Set admin notice if upgrade required
$upgrade = get_option('rootsPressUpgrade');
            if ($upgrade == 0) {
              $admin_msg = '';
            } else {
              $admin_msg =   'rootsPress ' .  __( 'Upgrade action is required ', 'rpress_main' );
            }

$_SESSION['wp_site_url'] = site_url();

?>
