<?php
/*
** REMOVE 1.5 level database as part of the upgrade to 2.0
*/
 	function rootsPressupgrade2($action = true ) {
          $msg = '';
//Remove all database tables, Wordpress root page and it's children, rootsPress index
         $rootspressdb = mysql_connect($_SESSION['dbhost'],$_SESSION['dbuser'],$_SESSION['dbpwd'], true);
         mysql_select_db($_SESSION['database'], $rootspressdb);
         $result = mysql_query("SELECT * FROM rp_index");

 //        $msg .= 'Deleting rootsPress 1.5 tables for trees ';
         while($row = mysql_fetch_array($result))
         {
//           $msg .= 'Processing tree ';
//remove main tables (all 1.5 level tables)
        $tree_id = $row['tree_id'];
        $t_prefix = 'rp'. $tree_id . '_';
        $sql = "DROP TABLE " . 
        $t_prefix . "individual" . ', ' .
        $t_prefix . "family" . ', ' .
        $t_prefix . "event" . ', ' .
        $t_prefix . "attrib" . ', ' .
        $t_prefix . "media" . ', ' .
        $t_prefix . "citation" . ', ' .
        $t_prefix . "source" . ', ' .
        $t_prefix . "places";
        if ($action === true) {
	    $result2 = @mysql_query($sql, $rootspressdb);
	    if ($result2 === FALSE) echo "FAILED TO DELETE ALL THE 1.5 level TABLES ". $t_prefix . ' ' . mysql_error();
            }  else {
           $msg .= 'Deleting rootsPress 1.5 tables for tree '.$tree_id.' ';
           }
//   }
   $msg .= '<br/>';
//remove wp pages
          $root =  $row['wp_root_id'];
          $args = array(
                 'child_of' => $root
             );
            $pages = get_pages($args);  //array of page id's
            if ($action !== true) $msg .= 'Removing Wordpress pages ';
            foreach ($pages as $pagg) {
              if ($action === true) wp_delete_post($pagg->ID, true);
                else $msg .= $pagg->ID . ' ';
            }
            if ($action === true) wp_delete_post($root, true);
              else $msg .= $root.'<br/>';
         }
            $msg .= '<br/>';

//Drop rp index
        $sql = "DROP TABLE rp_index" ;
        if ($action === true) {
        mysql_query($sql, $rootspressdb);
        update_option('rootsPressUpgrade', '0'); //Indicate upgrade complete
        }  else {
          $msg .= 'Removing rootsPress database';
        }
        if ($action === true) {
              update_option('rootspress_dbVersion', DB_VER ); //set db version
              $msg = 'The rootsPress database is now current';
        }
 return $msg;
 }

/*
** UPGRADE 1.6 level database to level 2.0.
*/
	function rootsPressupgrade1($action = true) {
         $rootspressdb = mysql_connect($_SESSION['dbhost'],$_SESSION['dbuser'],$_SESSION['dbpwd'], true);
         mysql_select_db($_SESSION['database'], $rootspressdb);

//Add new columns to 1.6 table
         $sql = 'ALTER TABLE rp_index ADD default_id TEXT AFTER wp_root_id';
         mysql_query($sql, $rootspressdb);
         $sql = 'ALTER TABLE rp_index ADD wp_home_id MEDIUMINT(4) AFTER wp_root_id';
         mysql_query($sql, $rootspressdb);
         $sql = 'ALTER TABLE rp_index ADD secured TINYINT(1) AFTER ports';
         mysql_query($sql, $rootspressdb);
         $sql = 'ALTER TABLE rp_index ADD home_html BLOB AFTER secured';   //upgrade rp_index for home page
         mysql_query($sql, $rootspressdb);
//Read each row in rp_index to update each tree data
         $result = mysql_query("SELECT * FROM rp_index");
         while ($row = mysql_fetch_array($result)) {
//In 1.6 the People page is the root page, it's parent is the "main" page which holds a description and the index is a child of the parent page
             $people = $row['wp_root_id'];   //get people page
             $gedname = $row['tree_name'];
             $tree_id = $row['tree_id'];

//get content of People page (for default id)
             $page_data = get_page( $people );
             $parent = $page_data->post_parent;

 //            $content = apply_filters('the_content', $page_data->post_content);
// echo  $page_data->post_content;
             $content = $page_data->post_content;
             $pos1 = strpos($content, 'pid=');
             if($pos1===false) echo 'This page does not contain a default id'.$content;
             $pos2 = strpos($content, ']', $pos1);
             $default_id = substr($content, ($pos1+4), ($pos2-$pos1-4));
//Get content of main page for description

             $parent_page = get_page($parent);
             if($parent_page->ID == null || $parent_page->ID == 0) {
               $msg = 'Parent page is null or zero. Upgrade has been stopped.';
               return $msg;
             }

             $description = $parent_page->post_content;
//Remove child pages & index (ie all children of root page)
             $args = array(
                         'child_of' => $parent
             );

             $pages = get_pages($args);  //array of page id's
             foreach ($pages as $pagg) {
               wp_delete_post($pagg->ID, true);
             }

             wp_delete_post($parent, true);   //remove root page

//Build new pages
           $newged = 'rp' . $tree_id;
           $mainpage_id = rootsPress::addMainPage($newged, $gedname, 0);   //ged id, title, parent id
//create new home page
           $homepage_id = rootsPress::addHomePage($gedname, $mainpage_id, $description);     //BUT ADD CONTENT TAKEN FROM OLD PAGE , ie modify addHomePage
           $secured = 0; //default to not secure
//Add new data to rp_index
      $sql = "UPDATE rp_index SET default_id='" . $default_id . "' , wp_home_id = '" . $homepage_id . "' , wp_root_id = '" .  $mainpage_id  . "' , secured = '" . $secured . "'  WHERE tree_id = '". $tree_id.  "'";
           mysql_query($sql, $rootspressdb);
     } //End while
            update_option('rootsPressUpgrade', '0'); //Indicate upgrade complete
            update_option('rootspress_dbVersion', DB_VER ); //set db version
            $msg = 'rootsPress data has been upgraded to the current database.';
            return $msg;
} //End function
?>