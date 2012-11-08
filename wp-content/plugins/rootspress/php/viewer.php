<?php
/* gedviewer
** Displays information from the mysql gedcom database
**
** 1.       Persons details
** 2.       Marriages and family groups for each spouse
** 3.       Events with source information
** 4.       Facts (attributes) with source information
** 5.       Ancestor tree, 3 generations
** 6.       Map of events for this person
*/

/* Main viewer function
** $ged = ged tree id
** $pid = starting person id
*/

function rp_viewer ($ged, $passed_id) {
    global $rootspressdb, $rp_database, $prefix, $current_user, $permalink;
//get media options for this tree here
         $tree_id = substr($ged, 2);
         $sql = 'SELECT * FROM ' . $rp_database . '.' . 'rp_index WHERE tree_id = ' . $tree_id;
         $result = mysql_query($sql);
         $row = mysql_fetch_array($result);
         $_SESSION['tree_name'] =  $row['tree_name'];
         $_SESSION['media_path'] = $row['media_path'];
         $_SESSION['portraits_path'] = $row['portraits_path'];
         $_SESSION['thumbs'] = $row['thumbs'];
         $_SESSION['ports'] = $row['ports'];

    $block = '';

//    $pid = rp_get_pid($passed_id, $ged);   //this is to allow for default id's being set by a user NOT CURRENTLY USED
    $pid = $passed_id;
    if ($pid === false) {
        $block .= '<p style="color: red;">' . __('Page not initialized. Please notify the administrator', 'rpress_main') . '</p>';
        return $block;
            }
            
//Now process request to set default
     if(isset($_POST['rpress_hidden']) && $_POST['rpress_hidden'] == 'deflt') {
//        get_currentuserinfo();
//        $userid =  $current_user->ID;
 //       update_user_option( $userid, 'rp_default', $pid);
          rp_updt_userdeflt($ged, $pid);
          if (current_user_can( 'administrator' )) {
              $this_page = get_the_ID();
//Rewrite shortcode in this page to contain the pid
              $my_post = array();
              $my_post['ID'] = $this_page;
              $my_post['post_content'] = '[rootsPress ged=' . $ged . ' pid=' . $pid . ']';
  //           wp_update_post( $my_post );
//Update rp_index with default id
              $tree_id = substr($ged,2);
              $sql = "UPDATE " . $rp_database . '.' . "rp_index SET default_id='" . $pid . "' WHERE tree_id = '". $tree_id .  "'";
              $result = mysql_query($sql, $rootspressdb);
          }
          $block .= '<p style="color: red;">' . __('The default is now set to this person', 'rpress_main') . '</p>';
      }

    $prefix = $ged;
//    $rootspressdb = mysql_connect($_SESSION['dbhost'],$_SESSION['dbuser'],$_SESSION['dbpwd']);

    $this_page = get_the_ID();
    $permalink = page_link($ged);

//PRIVACY PRE_CHECK
//    if (!rp_permit($pid)) {
//      $block .= '<h2>' . 'Private: you need to login to view this person' . '</h2><br/>';
//      return $block;
//    }
    $ret = show_personal($pid, $block);
    if ($ret == false) return $block;
    show_families($pid, $block);
    show_events($block);
    show_attribs($block);
//    show_tree($pid, $block);
    rp_show_links($pid, $block);
    show_map($pid, $block);
    return $block;
} //end function

function rp_get_pid ($passed_id, $tree_id) {   //NOT USED
    global $current_user;
    if (isset($_GET['pid'])) return $_GET['pid'];
    if (is_user_logged_in()) {
    get_currentuserinfo();
    $userid =  $current_user->ID;
    $pid_def = rp_get_userdeflt($tree_id);
    if($pid_def) return $pid_def;
    } //end if logged in
    if ($passed_id != '') 
       {
    return $passed_id;
 }
         else return false;
    } //End function
    
function show_notes($field, &$block) {
  if($field == null) return;
  $list =  explode(';', $field);
  foreach ($list as $value) {
     $row = rp_get_note($value);
     if($row['note_note'] != null) {
       $block .= '<strong>Note: </strong>' . nl2br($row['note_note']) ;
       show_sources($row['note_source_ref'], $block);  //get sources for the note
       $block .=  '<br/>';
     }  
    }
} //End function

function rp_get_note($id) {
   global $rootspressdb, $rp_database, $prefix;
   $sql = 'SELECT * FROM '. $rp_database . '.' . $prefix . '_note' .  " WHERE note_gedcomnumber = '". $id. "'";
   $result = rp_sql($sql, 'note');
   return $result;
} //End function

function show_sources($field, &$block) {
  if (!$_SESSION['sources']) return;
  if($field == null) return;
  $content = '';

  $list =  explode(';', $field);
  foreach ($list as $value) {
    $row = rp_get_citn($value);
    if($row['citn_source_xref'] == null) {
       if($row['citn_description'] != null) $content .= $row['citn_description'];
       if ($row['citn_text'] != '')
          $content .= '<strong>-Text: </strong> ' . $row['citn_text']  . '<br/>';
       if ($row['citn_note_ref'] != '')
        $content .= '<strong>-Notex:  ' .$row['citn_note_ref']. '</strong> ';
        show_notes($row['citn_note_ref'], $content);
        $content .= '<br/>';
       if ($row['citn_media_ref'] != '')
        $content .= show_media($row['citn_media_ref'], $value, $content)  . '<br/>';
       if ($row['citn_qual'] != '')
        $content .= '<strong>-Quality: </strong> ' . $row['citn_qual'] . '<br/>';
        }  else {
//Display format with source record
       if ($row['citn_page'] != '')
        $content .= '<strong>-Page: </strong> ' . $row['citn_page']  . '<br/>';
       if ($row['citn_eventfrom'] != '')
        $content .= '<strong>-Event: </strong> ' . $row['citn_eventfrom']  . '<br/>';
       if ($row['citn_text'] != '')
        $content .= '<strong>-Citation text: </strong> ' . $row['citn_text']  . '<br/>';
       show_notes($row['citn_note_ref'], $content);
       show_media($row['citn_media_ref'], $value, $content) ;
       if ($row['citn_qual'] != '')
        $content .= '<strong>-Quality: </strong> ' . $row['citn_qual'] . '<br/>';
//Now display data from source record
       $row2 = rp_get_srce($row['citn_source_xref']);
       if ($row2['srce_title'] != '')
         $content .= '<strong>-Title: </strong> ' . $row2['srce_title'] . '<br/>';
    if ($row2['srce_auth'] != '')
       $content .= '<strong>-Authority: </strong> ' . $row2['srce_auth'] . '<br/>';
    if ($row2['srce_pub_facts'] != '')
       $content .= '<strong>-Pub. facts: </strong> ' . $row2['srce_pub_facts'] . '<br/>';
    if ($row2['srce_text'] != '')
       $content .= '<strong>-Source text: </strong> ' . $row2['srce_text'] . '<br/>';
    if ($row2['srce_pub_facts'] != '')
       $content .= '<strong>-Repository: </strong> ' . $row2['srce_repos'] . '<br/>';

    show_notes($row2['srce_note_ref'], $content);
    show_media($row2['srce_media_ref'], $value, $content);
     }
    rp_showhide($content, 'hide', $block);
    } //end foreach
} //end function

function show_media($field, $id, &$block) {
//$field value is an obje reference
  if($field == null) return;
  $list =  explode(';', $field);
  foreach ($list as $value) {
    $row = rp_get_mediaobj($value);
    $caption =  $row['obje_title'];
    show_media_files($row['obje_file_ref'], $caption, $id, $block);
    show_notes($row['obje_note_ref'], $block);
    show_sources($row['obje_source_ref'], $block);
  }
} //End function

function show_media_files($field, $caption, $id, &$block){
  if($field == null) return;
  $list =  explode(';', $field);
  foreach ($list as $value) {
    $row = rp_get_media($value);
    if($row['media_title'] != null) $caption = $caption . $row['media_title'];
    set_media($id, $row['media_file']  , $caption, $block);
     }
} //End function

function rp_sql($sql, $type) {
    global $rootspressdb;
    $result=mysql_query($sql, $rootspressdb);
    if ($result === false) {
      echo mysql_errno($rootspressdb). ' ' . mysql_error($rootspressdb) .  '<br/>';
      echo 'Error reading from ' . $type . ' table: ' . '<br/>';
      return false;
        }  else {
      return mysql_fetch_assoc($result);
        }
} //end function

function rp_get_indi($id) {
    global $rootspressdb, $rp_database, $prefix;
    $sql = 'SELECT * FROM '. $rp_database . '.' . $prefix . '_individual' .  " WHERE indi_gedcomnumber = '". $id. "'";
    $result = rp_sql($sql, 'individual');
    return $result;
} //end function

  function rp_get_family($fam_id) {
    global $rootspressdb, $rp_database, $prefix;
    $sql = 'SELECT * FROM '. $rp_database . '.' .$prefix . '_family' .  " WHERE fam_gedcomnumber = '". $fam_id. "'";
    $result = rp_sql($sql, 'family');
    return $result;
 } //end function

 function rp_getone_event($id, $type) {
   global $rootspressdb, $rp_database, $prefix;
   $sql = "SELECT * FROM " . $rp_database . '.' .$prefix . '_event' . " WHERE event_owner_id = '". $id . "' AND event_tagtype = '" . $type.  "' ";
   $result = rp_sql($sql, 'event');
    return $result;
 }   //end function
 
 function rp_get_event($id) {
   global $rootspressdb, $rp_database, $prefix;
   $sql = "SELECT * FROM ". $rp_database . '.' . $prefix . '_event'. " WHERE event_gedcomnumber = '". $id. "'";
   $result = rp_sql($sql, 'event');
   return $result;
 }   //end function
 
 function rp_get_attrib($id) {
   global $rootspressdb, $rp_database, $prefix;
   $sql = "SELECT * FROM ". $rp_database . '.' . $prefix . '_attrib'. " WHERE attrib_gedcomnumber = '". $id. "'";
   $result = rp_sql($sql, 'attrib');
   return $result;
 }   //end function
 
 function rp_get_media($id) {
   global $rootspressdb, $rp_database, $prefix;
   $sql = "SELECT * FROM ". $rp_database . '.' .$prefix.'_media' . " WHERE media_gedcomnumber = '". $id. "'";
   $result = rp_sql($sql, 'media');
   return $result;
 }   //end function
 
 function rp_get_mediaobj($id) {
   global $rootspressdb, $rp_database, $prefix;
   $sql = "SELECT * FROM ". $rp_database . '.' .$prefix.'_obje' . " WHERE obje_gedcomnumber = '". $id. "'";
   $result = rp_sql($sql, 'obje');
   return $result;
 }   //end function

 
 function rp_get_famc($id) {
   global $rootspressdb, $rp_database, $prefix;
   $sql = "SELECT * FROM ". $rp_database . '.' . $prefix . '_family' . " WHERE fam_gedcomnumber = '". $id. "'";
   $result = rp_sql($sql, 'family');
   return $result;
} //end function

function rp_link($id, $text) {
    global $rootspressdb, $prefix;
    $this_page = get_the_ID();
    $permalink = page_link($prefix);
    $link = "<a href = '" . $permalink . "&pid=" . $id .  "&ged=" . $prefix . "&mode=base" . "'>" . $text . '</a>' . '<br/>';
    return $link;
}  //End function

function rp_get_citn($id) {
   global $rootspressdb, $rp_database, $prefix;
   $sql = "SELECT * FROM ". $rp_database . '.' . $prefix . '_citation'. " WHERE citn_gedcomnumber = '". $id. "'";
   $result = rp_sql($sql, 'citation');
   return $result;
 }   //end function
 
function rp_get_srce($id) {
   global $rootspressdb, $rp_database, $prefix;
   $sql = "SELECT * FROM ". $rp_database . '.' . $prefix . '_source'. " WHERE srce_gedcomnumber = '". $id. "'";
   $result = rp_sql($sql, 'source');
   return $result;
 }   //end function
 
function rp_get_name($fullname) {
    $pos1 = strpos($fullname, '/');
   if ($pos1 === false) {
     return $fullname;
   }  else {
     $t1 = substr($fullname, 0, $pos1);
     $pos2 = strpos($fullname, '/', ($pos1+1));
     $t2 = rtrim(substr($fullname, $pos1+1 , ($pos2-$pos1+1)), '/');
     return $t1.' '.$t2;
   }
} //End function

/*
** Function attempts to get a default pid for this (logged on) user for the supplied tree id
** Returns false if no pid found, returns the pid if a tree mtch is found
*/
function rp_get_userdeflt($tree_id) {
     global $current_user;
     get_currentuserinfo();
     $userid =  $current_user->ID;
     $temp = get_user_option('rp_default', $userid);
     if(!$temp) return false;
     $dflt = explode(';', $temp); //get defaults for this user
     if (empty($dflt)) return false;
     $size = sizeof($dflt);
     for ($i=0; $i<$size; $i=$i+2) {
        if($dflt[$i] == $tree_id) return $dflt[$i+1];
       }
     return false;
} // End function

/*
** Function searches for an existing user default pid entry.
** If found it tries to match the tree id and if matched, updates the correwsponding pid
** then rewrites the default atring
** If not found it adds a default for this user
*/
function rp_updt_userdeflt($tree_id, $pid) {
     global $current_user;
     get_currentuserinfo();
     $userid =  $current_user->ID;
     $temp = get_user_option('rp_default', $userid);
     if(!$temp) {
        $dflt = array($tree_id, $pid);
        $temp = implode(';', $dflt);
        update_user_option( $userid, 'rp_default', $temp);
        return;
        } else {
//now search array for a tree id match and change value if found
//if not found, add to end
     $dflt = explode(';', $temp); //existing defaults for this user
     $size = sizeof($dflt);
     for ($i=0; $i<$size; $i=$i+2) {
        if($dflt[$i] == $tree_id) {
          $dflt[$i+1] = $pid;
          return;
           }
        }
     $dflt[] = $tree_id;
     $dflt[] = $pid;
     $temp = implode(';', $dflt);
     update_user_option( $userid, 'rp_default', $temp);
     }
return;
} // End function

 function rp_finder($name) {
  if(substr($name,0,7) == 'http://') {
    $headers = @get_headers($name);
    if (preg_match("|200|", $headers[0])) return true;
  else return false;
  }  else {
     if(file_exists($name))  return true;
     else return false;
  }
 } //End function

function rp_portrait($person, $person_id, $name, &$block) {
//ports=0 for silhouette, 1 for separate file, 2 for gedcom file
    $ppath = rtrim($_SESSION['portraits_path'], '/') . '/';  //ensure there is one trailing /
    if ($person['indi_sex'] == 'M') { $type = 'M';
      } else if ($person['indi_sex'] == 'F') { $type = 'F';
         } else { $type = 'U';
    }
    if($_SESSION['ports'] == 0) {
//this code will force the use of a silhouette
    $block .= '<img src="' . WP_PLUGIN_URL .'/rootspress/images/silhouette_' . $type . '.gif' . '" width="150px"' . '>';
    return;
    }

    if($_SESSION['ports'] == 1) {
    $ppath = rtrim($_SESSION['portraits_path'], '/') . '/';  //ensure there is one trailing /
    $imgg = $ppath . $person_id . '.jpg';
//    if(rp_finder($imgg)) $block .= '<p><img src="' . $imgg . '" alt="' . $name . '"' . ' style="width: 150px;" ' . ' /></p>';
    if(rp_finder($imgg)) $block .= '<img src="' . $imgg . '" alt="' . $name . '"' . ' style="width: 100%; min-width: 150px; top: 0; left: 0;" ' . ' />';
        else             $block .= '<img src="' . WP_PLUGIN_URL .'/rootspress/images/silhouette_' . $type . '.gif' . '" width="150px"' . '>';
    return;
   } 
 
    if($_SESSION['ports'] == 2) {
     $med_array = explode(';', $person['indi_media_ref']);
     if($person['indi_media_ref'] == '') {
         $block .= '<img src="' . WP_PLUGIN_URL .'/rootspress/images/silhouette_' . $type . '.gif' . '" width="150px"' . '>';
         return;
        }
     rsort($med_array);   //sort obje refs

    $row = rp_get_mediaobj($med_array[0]);
    $list =  explode(';', $row['obje_file_ref']);  //list of media file refs (assumed not null)
    rsort($list);
    $row = rp_get_media($list[0]);
    $ppath = rtrim($_SESSION['media_path'], '/') ;  //remove trailing /
    $block .= '<img src="' . $ppath . $row['media_file'] . '" alt="' . $name . '"' . ' width="150px"' . '>';
   }
} //End function

function show_personal($person_id, &$block) {
  global $person, $person_name, $permalink, $prefix, $locale;
  $names = array();
    $ppath = rtrim($_SESSION['portraits_path'], '/') . '/';  //ensure there is one trailing /
    $person = rp_get_indi($person_id);  //indiv record array
    $name = rp_get_name($person['indi_fullname']);
    $person_name = $name; // save in global variable
//    $block .= '<h2 class="titleline">' . $_SESSION['tree_name'] . ': (' . $name . ')';

    $block .= '<a href="' . $permalink . '&pid=' . $person_id . '&ged=' . $prefix . '&mode=home"' . '>';
    $block .= '<img title="' . __('Home', 'rpress_main') . '" class="rp_icons" src=' . WP_PLUGIN_URL . '/rootspress/images/home.gif' . '>';
    $block .= '</a>';

    $block .= '<a href="' . $permalink . '&pid=' . $person_id . '&ged=' . $prefix . '&mode=indx"' . '>';
    $block .= '<img title="' . __('Index', 'rpress_main') . '" class="rp_icons" src=' . WP_PLUGIN_URL . '/rootspress/images/index.gif' . '>';
    $block .= '</a>';

    $block .= '<a href="' . $permalink . '&pid=' . $person_id . '&ged=' . $prefix . '&mode=tree"' . '>';
    $block .= '<img title="' . __('Interactive tree', 'rpress_main') . '" class="rp_icons" src=' . WP_PLUGIN_URL . '/rootspress/images/gedcom.png' . '>';
    $block .= '</a>';
    $help_link =  WP_PLUGIN_URL . '/rootspress/pgv/help/help.php';
    $block .=  '<a href="#"  onclick="TINY.box.show({url:' . "'$help_link?id=202&amp;locale=" . $locale . "',width:600,height:700,opacity:50,topsplit:2, close:true})" . '" >';
    $block .= '<img title="' . __('Help', 'rpress_main') . '" class="rp_icons"  src=' . WP_PLUGIN_URL . '/rootspress/images/help.gif' . '>';
    $block .= '</a>';

//    if (is_user_logged_in()) {;      //cannot support this yet
    if (current_user_can( 'administrator' )) {
?>
    <form name="rpress_form3" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    <input type="hidden" name="rpress_hidden" value="deflt">
    <p class="submit">
   <input type="image" src="<?php echo WP_PLUGIN_URL . '/rootspress/images/tag-blue_add2.png' ?>" name="image" title=" <?php echo _e('Click to set as default person', 'rpress_main')?>  " class="rp_icons" value="Set as default person">

    </p>
    </form>
<?php
}
//    $block .= '</h2>';
//      $block .= '</div>';

//    if(rp_permit($person_id) === false) $block .= 'This person marked as private<br/>';

    if (strtolower($name) == 'living') {
     $block .= '<h3>' . __('This person is still living', 'rpress_main') . '</h3>';
     return false;
   }

    $block .= '<div class="rp_container">';
    $block .= '<div class="rp_top">' .  sprintf(__('Personal details of %s ', 'rpress_main'), $name) . '</div>';
    $block .= '<div class="rp_leftbox">';
    rp_portrait($person, $person_id, $name, $block);
    $block .= '<div class="caption">' . $name . '</div>';
    $block .= '</div>';

    $family = rp_get_famc($person['indi_famc']);  //record array of family in which this person is a child
    $father_id = $family['fam_spouse1']; //id of spouse 1 (usually father)
    $mother_id = $family['fam_spouse2']; //id of spouse 2 (usually mother)
    $father =  rp_get_indi($father_id);
    $mother =  rp_get_indi($mother_id);

    $block .= '<div class="rp_rightbox">';
    $block .= '<h4>' . $name . '</h4>';
    $block .= '<p>';
    $event = rp_getone_event($person_id, 'BIRT');
    if ($event['event_date'] == '' && $event['event_place'] =='') $block .= '&nbsp;' ;
    else $block .= __('Born', 'rpress_main') . ': ' . $event['event_date'] . ' ' .  $event['event_place'] ;
    $block .= '</p><p>';
    $event = rp_getone_event($person_id, 'DEAT');
    if ($event['event_date'] == '' && $event['event_place'] =='') $block .= '&nbsp;' ;
    else $block .= __('Died', 'rpress_main') . ': ' . $event['event_date'] . ' ' .  $event['event_place'] ;
    $block .= '</p><p>';
    $block .= __('Father', 'rpress_main') . ': ';
    if($father['indi_fullname'] == '') $block .= '<i>' . __('Unknown', 'rpress_main') . '</i><br/> ';
    else $block .= rp_link($father_id, rp_get_name($father['indi_fullname']));
    $block .= '</p><p>';
    $block .= __('Mother', 'rpress_main') . ': ';
    rp_get_name($mother_id, $names);
    if($mother['indi_fullname'] == '') $block .= '<i>' . __('Unknown', 'rpress_main') . '</i><br/> ';
    else $block .= rp_link($mother_id, rp_get_name($mother['indi_fullname']));
    $block .= '</p>';
    $block .= '</div>';  //end rightbox
//    $block .= '</p>';

//    $block .= '<div class="rp_content">';
//    $block .= '</div>';
    
    if ($person['indi_note_ref'] != '') {
    $block .= '<div class="rp_foot">';
    show_notes($person['indi_note_ref'], $block);
    $block .= '</div>';
    }
    $block .= '<br style="clear:both" />';

    $block .= '</div>';  //end container
    return true;
} //End function

/* This function retrieves the list of families for this person
** and calls functions to display spousal information
** and children for each marriage (family)
*/
function show_families($person_id, &$block) {
    global $person;
    if ($person['indi_fams'] == '')  return;
 $fam_list = explode(';', $person['indi_fams']); //list of family pointers
 rsort($fam_list);
 $count = 0;
 foreach ($fam_list as $value) {
   show_famgrp($value, $block, ++$count, sizeof($fam_list));
 }
} //Function end

function show_famgrp($fam_id, &$block, $count, $numfam) {
    global $person, $permalink;
    $names = array();
    $block .= '<div class="rp_container">';
    
    $family = rp_get_family($fam_id);
    if ($person['indi_gedcomnumber'] == $family['fam_spouse1'])  $spouse = rp_get_indi($family['fam_spouse2']);
    if ($person['indi_gedcomnumber'] == $family['fam_spouse2'])  $spouse = rp_get_indi($family['fam_spouse1']);
    
    $spouse_name = rp_get_name($spouse['indi_fullname']) ;
    if($spouse_name == null) $spouse_name = '<i>' . __('Unknown', 'rpress_main') . '</i>';
    $name = rp_get_name($person['indi_fullname']) ;

    $group = $name.' and '.$spouse_name;
    $block .= '<div class="rp_top">' .  sprintf(__('Family group of %s ', 'rpress_main'), $group) ;

    if ($numfam > 1) $block .= ' (' . $count . ' of ' . $numfam . ')';
    $event = rp_getone_event($family['fam_gedcomnumber'], 'MARR');
    if ($event['event_date'] != '' || $event['event_place'] != '')
    $block .= '<p style="font-weight: normal">'. __('Married', 'rpress_main') . ': ' . $event['event_date'] . ' ' .  $event['event_place'].'</p>';
    $block .= '</div>';  //end rp_top
    $block .= '<div class="rp_leftbox">';
    rp_portrait($spouse, $spouse['indi_gedcomnumber'], $spouse_name, $block);
    $block .= '<div class="caption">' . $spouse_name . '</div>';
    $block .= '</div>';   //end leftbox

    $family2 = rp_get_famc($spouse['indi_famc']);  //record array of family in which spouse is a child
    $father_id = $family2['fam_spouse1']; //id of spouse 1 (usually father)
    $mother_id = $family2['fam_spouse2']; //id of spouse 2 (usually mother)
    $father =  rp_get_indi($father_id);
    $mother =  rp_get_indi($mother_id);

    $block .= '<div class="rp_rightbox">';
    $block .= '<h4>'. rp_link($spouse['indi_gedcomnumber'], rp_get_name($spouse['indi_fullname'])). '</h4>';

    $block .= '<p>';
    $event = rp_getone_event($spouse['indi_gedcomnumber'], 'BIRT');
    if ($event['event_date'] == '' && $event['event_place'] =='') $block .= '&nbsp;' ;
    else $block .= __('Born', 'rpress_main') . ': ' . $event['event_date']  . ': ' .  $event['event_place'] ;
    $block .= '</p><p>';
    $event = rp_getone_event($spouse['indi_gedcomnumber'], 'DEAT');
    if ($event['event_date'] == '' && $event['event_place'] =='') $block .= '&nbsp;' ;
    else $block .= __('Died', 'rpress_main') . ': ' . $event['event_date'] . ' ' .  $event['event_place'] ;
    $block .= '</p><p>';
    $block .= __('Father', 'rpress_main') . ': ';
    if($father['indi_fullname'] == '') $block .= '<i>' . __('Unknown', 'rpress_main') . '</i><br/> ';
    else $block .= rp_link($father_id, rp_get_name($father['indi_fullname']));
    $block .= '</p><p>';
    $block .= __('Mother', 'rpress_main') . ': ';
    rp_get_name($mother_id, $names);
    if($mother['indi_fullname'] == '') $block .= '<i>' . __('Unknown', 'rpress_main') . '</i><br/> ';
    else $block .= rp_link($mother_id, rp_get_name($mother['indi_fullname']));
    $block .= '</p>';
    $block .= '</div>';   //end rightbox

    $block .= '</div>'; //end container

    $family = rp_get_family($fam_id, $block);
    $block .= '<div class="rp_container">';
    show_children($family, $group, $block);
    $block .='<br style="clear:both" />';
    $block .= '</div>';


} //End function

function show_children($family, $group, &$block) {
    if ($family['fam_child_ref'] == '') return;
    $block .= '<div class="rp_container" style="border: none;">';
    $child_list = explode(';', $family['fam_child_ref']); //list of child pointers
    $block .= '<div class="rp_top">' . sprintf(__('Children of %s ', 'rpress_main'), $group) . '</div>';

    $block .= '<table class="rp_table_fam">';
       foreach ($child_list as $value) {
         $child = rp_get_indi($value);
    $block .= '<tr><td class=col1_fam>' . rp_link($value, rp_get_name($child['indi_fullname'])) . '</td>';

    $event = rp_getone_event($value, 'BIRT');
    $ablock = __('Born', 'rpress_main') . ': ' . $event['event_date'] . ' ' .  $event['event_place'];
    $event = rp_getone_event($value, 'DEAT');
    if ($event['event_date'] == '' && $event['event_place'] =='') $bblock = '&nbsp;' ;
    else $bblock = __('Died', 'rpress_main') . ': ' . $event['event_date'] . ', ' .  $event['event_place'];

$block .= '<td class=col2_fam>' . $ablock . '<br/>' . $bblock . '</td>';
$block .= '<td class=col3_fam>' . $child['indi_sex'] . '</td></tr>';
       }
$block .= '</table>';

//    $block .= '<div id="rp_foot">';
    $block .= '</div>';   //end container
} //End function

function show_events(&$block) {
     global $person, $person_name;
    if($person['indi_event_ref'] == '') return;
    $block .= '<div class="rp_container">';
    $block .= '<div class="rp_top">' .  sprintf(__('Events for  %s ', 'rpress_main'), $person_name) . '</div>';
    $block .= '<table class="rp_table_facts">';
//Get list of individuals events
     $ref_list = explode(';', $person['indi_event_ref']);
//Get list of family events for each family
if ($person['indi_fams'] != '')
     $fam_list = explode(';', $person['indi_fams']); //list of family spouse pointers
     else $fam_list = array();
     $ref_list2 = array(); //will hold a list of xref's from the event list for each family of this person

     $size = sizeof($ref_list2);
     for ($i=0; $i<$size; $i++) {
       $family = rp_get_family($ref_list2[$i]);  //returns family record
       $temp = explode(';', $family['fam_event_ref']);
       $ref_list2 = array_merge($ref_list2, $temp);   //build a single array of events from all families
     }

//Build single event array
     $ref_list = array_merge($ref_list, $ref_list2);
     $size = sizeof($ref_list);
     for ($i=0; $i<$size; $i++) {
        $ev_id = $ref_list[$i];
        $row = rp_get_event($ref_list[$i]);
        $ev_type = long_event($row['event_tagtype']);
        if ($row['event_tagtype'] == 'EVEN') $ev_type = $row['event_type'];
        $block .= '<tr><td class=col1_facts>' . $ev_type . '</td>';

        $block .= '<td class=col2_facts>' . $ev_type . ' ' . $row['event_date'] . ', ' .  $row['event_place'] . '<br/>' ;
if ($row['event_cause'] != '') $block .= 'Cause: ' . $row['event_cause'] . '<br/>';
      show_notes($row['event_note_ref'], $block);
      show_media($row['event_media_ref'], $ev_id, $block);
      show_sources($row['event_source_ref'], $block);
      $block .= '</td></tr>';
     }
    $block .= '</table>';
    $block .= '</div>';
} //End function

function show_attribs(&$block) {
     global $person, $person_name;
     if($person['indi_attrib_ref'] == '') return;
    $block .= '<div class="rp_container">';
    $block .= '<div class="rp_top">' .  sprintf(__('Facts for  %s ', 'rpress_main'), $person_name) . '</div>';
    $block .= '<table class="rp_table_facts">';

     $ref_list = explode(';', $person['indi_attrib_ref']);
     $size = sizeof($ref_list);
     for ($i=0; $i<$size; $i++) {
        $ev_id = $ref_list[$i];
        $row = rp_get_attrib($ref_list[$i]);
        $ev_type = long_attrib($row['attrib_tagtype']);
        if ($row['attrib_tagtype'] == 'FACT') $ev_type = $row['attrib_type'];
        $block .= '<tr><td class=col1_facts>' . $ev_type . '</td>';
        $block .= '<td class=col2_facts>';
        if ($row['attrib_date'] != '' && $row['attrib_place'] != '') {
          $block .= $row['attrib_date'] . ', ' .  $row['attrib_place'] . '<br/>' ;
          } 
        if ($row['attrib_date'] != '' && $row['attrib_place'] == '') {
          $block .= $row['attrib_date'] . '<br/>' ;
          }
        if ($row['attrib_date'] == '' && $row['attrib_place'] != '') {
          $block .= $row['attrib_place'] . '<br/>' ;
          }        
        $block .= $row['attrib_content'] . '<br/>';
        show_notes($row['attrib_note_ref'], $block);
        show_media($row['attrib_media_ref'], $ev_id, $block);
        show_sources($row['attrib_source_ref'], $block);
   $block .= '</td></tr>';
     }
   $block .= '</table>';
   $block .= '</div>';
} //End function

function rp_showhide($content, $init, &$block) {
  static $divid;
  $divid++;
   if ($init == 'show') {
     $disp = 'block';
     $img = 'minus';
     $title = '';
   }
     else {
       $disp = 'none';
       $img = 'plus';
       $title = __('Source', 'rpress_main');
     }

 $block .= '<div id=hd_' . $divid . '>';
 $block .= '<a id=im_' . $divid . ' href="javascript:rp_toggle' . "('con_" . $divid . "','" . "im_" . $divid . "');" . '">';
 $img = $_SESSION['wp_site_url'].'/wp-content/plugins/rootspress/images/' . $img . ".png";
 $block .= '<img src="' . $img . '" /><strong>' . $title . '</strong></a>';
// $block .= '<strong></strong>';
 $block .= '</div>';
 $block .= '<div id=con_' . $divid . ' style="display: ' . $disp . ';">';
 $block .= $content;
 $block .= '</div>';
}

function rp_get_parents($id) {
    $person = rp_get_indi($id);  //indiv record array
    $family = rp_get_famc($person['indi_famc']);  //record array of family in which this person is a child
    $temp =array();
    $temp[0] = $family['fam_spouse1']; //id of spouse 1 (usually father)
    $temp[1] = $family['fam_spouse2']; //id of spouse 2 (usually mother)
    return $temp;
}

function rp_show_links($id, &$block) {
   global $rootspressdb, $rp_database, $prefix, $person_name;
    $temp = rp_get_parents($id);
    
    if($temp[0] != null || $temp[1] != null) {
    $block .=  '<div class="rp_container">';
    $block .=  '<div class="rp_top">' . sprintf(__('Family links for  %s ', 'rpress_main'), $person_name) . '</div>';

    $block .= '<table width="100%" style="border: 1px solid gray;">';
    $block .= '<tr> <th style="border: 1px solid gray; font-weight:bold; width: 33%; "  >' . __('Parents', 'rpress_main') . '</th>
                <th style="border: 1px solid gray; font-weight:bold;  width: 33%; " >' . __('GrandParents', 'rpress_main') . '</th>
                <th style="border: 1px solid gray; font-weight:bold; width: 33%; " >' . __('Greatgrandparents', 'rpress_main') . '</th> </tr>';
    }  else return;

    $father =  rp_get_indi($temp[0]);
    $mother =  rp_get_indi($temp[1]);

    if($father['indi_fullname'] == null) $f = ' <br/> ';
    else $f = rp_link($temp[0], rp_get_name($father['indi_fullname']));
    if($mother['indi_fullname'] == null) $m = '<i> </i><br/> ';
    else $m = rp_link($temp[1], rp_get_name($mother['indi_fullname']));
    
    $t2 = rp_get_parents($temp[0]);
    $father =  rp_get_indi($t2[0]);
    $mother =  rp_get_indi($t2[1]);

    if($father['indi_fullname'] == null) $ff = '<i> </i><br/> ';
    else $ff = rp_link($t2[0], rp_get_name($father['indi_fullname']));
    if($mother['indi_fullname'] == null) $fm = '<i> </i><br/> ';
    else $fm = rp_link($t2[1], rp_get_name($mother['indi_fullname']));

    $t3 = rp_get_parents($t2[0]);
    $father =  rp_get_indi($t3[0]);
    $mother =  rp_get_indi($t3[1]);
    
    if($father['indi_fullname'] == null) $fff = '<i> </i><br/> ';
    else $fff = rp_link($t3[0], rp_get_name($father['indi_fullname']));
    if($mother['indi_fullname'] == null) $ffm = '<i> </i><br/> ';
    else $ffm = rp_link($t3[1], rp_get_name($mother['indi_fullname']));
    
    $t4 = rp_get_parents($t2[1]);
    $father =  rp_get_indi($t4[0]);
    $mother =  rp_get_indi($t4[1]);
    
    if($father['indi_fullname'] == null) $fmf = '<i> </i><br/> ';
    else $fmf = rp_link($t4[0], rp_get_name($father['indi_fullname']));
    if($mother['indi_fullname'] == null) $fmm = '<i> </i><br/> ';
    else $fmm = rp_link($t4[1], rp_get_name($mother['indi_fullname']));
    
    $t2 = rp_get_parents($temp[1]);
    $father =  rp_get_indi($t2[0]);
    $mother =  rp_get_indi($t2[1]);
    
    if($father['indi_fullname'] == null) $mf = '<i> </i><br/> ';
    else $mf = rp_link($t2[0], rp_get_name($father['indi_fullname']));
    if($mother['indi_fullname'] == null) $mm = '<i> </i><br/> ';
    else $mm = rp_link($t2[1], rp_get_name($mother['indi_fullname']));
    
    $t3 = rp_get_parents($t2[0]);
    $father =  rp_get_indi($t3[0]);
    $mother =  rp_get_indi($t3[1]);
    
    if($father['indi_fullname'] == null) $mff = '<i> </i><br/> ';
    else $mff = rp_link($t3[0], rp_get_name($father['indi_fullname']));
    if($mother['indi_fullname'] == null) $mfm = '<i> </i><br/> ';
    else $mfm = rp_link($t3[1], rp_get_name($mother['indi_fullname']));
    
    $t4 = rp_get_parents($t2[1]);
    $father =  rp_get_indi($t4[0]);
    $mother =  rp_get_indi($t4[1]);
    
    if($father['indi_fullname'] == null) $mmf = '<i> </i><br/> ';
    else $mmf = rp_link($t4[0], rp_get_name($father['indi_fullname']));
    if($mother['indi_fullname'] == null) $mmm = '<i> </i><br/> ';
    else $mmm = rp_link($t4[1], rp_get_name($mother['indi_fullname']));

$block .= '  <tr>';
$block .= '    <td style="border: 1px solid gray;" rowspan="4">'.$f.'</td>';
$block .= '    <td style="border: 1px solid gray;" rowspan="2">'.$ff.'</td>';
$block .= '    <td style="border: 1px solid gray;">'.$fff.'</td>';
$block .= '</tr>';
$block .= '<tr><td style="border: 1px solid gray;" >'.$ffm.'</td></tr>';
$block .= '<tr>';
$block .= '    <td style="border: 1px solid gray;" rowspan="2">'.$fm.'</td>';
$block .= '    <td style="border: 1px solid gray;">'.$fmf.'</td>';
$block .= '  </tr>';
$block .= '<tr><td style="border: 1px solid gray;">'.$fmm.'</td>';
$block .= '</tr>';

$block .= '  <tr>';
$block .= '    <td style="border: 1px solid gray;" rowspan="4">'.$m.'</td>';
$block .= '    <td style="border: 1px solid gray;" rowspan="2">'.$mf.'</td>';
$block .= '    <td style="border: 1px solid gray;">'.$mff.'</td>';
$block .= '</tr>';
$block .= '<tr><td style="border: 1px solid gray;" >'.$mfm.'</td></tr>';
$block .= '<tr>';
$block .= '    <td style="border: 1px solid gray;" rowspan="2">'.$mm.'</td>';
$block .= '    <td style="border: 1px solid gray;">'.$mmf.'</td>';
$block .= '  </tr>';
$block .= '<tr><td style="border: 1px solid gray;">'.$mmm.'</td>';
$block .= '</tr>';

$block .= '</table>';
    
$block .= '</div>';
}

function show_tree($id, &$block) {
   global $rootspressdb, $prefix, $person_name;
    $block .=  '<div class="rp_container">';
    $block .=  '<div class="rp_top">Ancestor tree for ' . $person_name . '</div>';

include('ancestry_config.php');
$id_array = get_ancestry($id, $prefix); //return person plus 6 ancestor id's in array

$rp_atree = 'rp_prefix=' . $prefix . '&';
$rp_atree .= 'rp_id1=' .  $id_array[1] . '&';
$rp_atree .= 'rp_id2=' .  $id_array[2] . '&';
$rp_atree .= 'rp_id3=' .  $id_array[3] . '&';
$rp_atree .= 'rp_id4=' .  $id_array[4] . '&';
$rp_atree .= 'rp_id5=' .  $id_array[5] . '&';
$rp_atree .= 'rp_id6=' .  $id_array[6] . '&';
$rp_atree .= 'rp_id7=' .  $id_array[7];

  $block .=  '<img class="rp_tree" src=' . WP_PLUGIN_URL . '/rootspress/php/ancestry.php?' . $rp_atree  . ' width="' . $imgW . '" height="' . $imgH . '" name="Image" usemap="#boxmap"  />';
  $block .=  '<map name="boxmap">';
    $this_page = get_the_ID();
    $permalink = page_link($prefix);
    $link = $permalink . "&pid=";
   for ($i=1; $i<8; $i++) {
     if ($id_array[$i] != 'void') {
    $block .=  '<area shape="rect" coords="';
    $block .= $box[$i]['X'] . ',' . $box[$i]['Y'] . ','. ($box[$i]['X']+$boxW) . ',' . ($box[$i]['Y']+$boxH);
    $block .= '" href="' . $link . $id_array[$i] . '" />';
        }
   }
   
   $block .=  '</map>';
   $block .= '</div>';  //end container
} //End function

function show_map($id, &$block) {
  global $rootspressdb, $rp_database, $prefix, $person, $person_name;
  include 'mapper.php';
//Prescan of events with coordinates to determine if we should show a map or not
     $ref_list = explode(';', $person['indi_event_ref']);
     $count = 0;
     $size = sizeof($ref_list);
     for ($i=0; $i<$size; $i++) {
        $row = rp_get_event($ref_list[$i]);
        if ($row['event_tagtype'] == 'EVEN') $ev_type = $row['event_type'];
        $place =  $row['event_place'];
        $sql = 'SELECT * FROM '. $rp_database . '.' . $prefix . '_places' . ' WHERE places_place = ' . '"' . $place . '"';
        $row2 = rp_sql($sql, 'places');
        if ($row2['places_lati'] != null &&  $row2['places_lati'] != null) $count++;
     }
     if ($count ==0) return;
   $block .=  '<div class="rp_container">';
   $block .=  '<div class="rp_top">' . sprintf(__('Event map for  %s ', 'rpress_main'), $person_name) ; 
   $block .=  '</div>';
   $block .=   '<p style="color: red;">' . __('NOTE: Multiple markers at the same location may not show separately on the map. Use the legend at the right to select the event', 'rpress_main') . '</p>';
// $block .= '<a href="javascript:geogrmv();">Remove cam markers</a>';
   $Layer = '';
   addMapLayer($Layer, $person);
   $block .= $Layer;
//Now add markers from events data
     $ref_list = explode(';', $person['indi_event_ref']);
     $size = sizeof($ref_list);
     for ($i=0; $i<$size; $i++) {
        $row = rp_get_event($ref_list[$i]);
        $ev_type = long_event($row['event_tagtype']);
        if ($row['event_tagtype'] == 'EVEN') $ev_type = $row['event_type'];
        $place =  $row['event_place'];
        $sql = 'SELECT * FROM '. $rp_database . '.' . $prefix . '_places' . ' WHERE places_place = ' . '"' . $place . '"';
        $row2 = rp_sql($sql, 'places');
//convert lat with N, S prefix to +/- and lon with E, W to +/-
        $lat_srch = array('N', 'S');
        $lat_rep = array('', '-');
        $lon_srch = array('E', 'W');
        $lon_rep = array('', '-');

        if ($row2['places_lati'] != null &&  $row2['places_lati'] != null) {
           $lati = str_replace($lat_srch, $lat_rep, $row2['places_lati']);
           $long = str_replace($lon_srch, $lon_rep, $row2['places_long']);
           $title = $person['indi_givnname'] . ' ' . $person['indi_surname'] . ', ' . $ev_type;
           $descr = $row['event_date'] . ' ' . $row['event_place'];
        }

      }

//Invoke mapload through small image onload
   $block .=  '<img src="' . WP_PLUGIN_URL . '/rootspress/images/1x1.gif' . '"'   . ' onload="mapload()' . ';"'. ' style="display: none; "' . '>';
//echo  'style="width:'. $_SESSION['gwidth'] . 'px; height: '. $_SESSION['gheight']. 'px;"';
$block .= '<div id="map_canvas" class="rp_map" style="width: '. $_SESSION['gwidth'] . 'px; height: '. $_SESSION['gheight']. 'px;"></div>';
$block .= '<div id="side_bar" class="rp_map_sidebar" style="float: left;"></div>';
$block .= '<p style="clear: both; font-size: smaller"><img src="' . WP_PLUGIN_URL . '/rootspress/images/geograph_logo.gif" style="width: 96px; height: 24px;"> ' . __('All photo submissions are copyright their respective owners and licensed under a Creative Commons licence.', 'rpress_main') . ' <i>' . __('Geograph images are only available for the UK', 'rpress_main') . '</i><br/>';
$block .= '<img src="' . WP_PLUGIN_URL . '/rootspress/images/panoramio_logo.png" style="width: 96px; height: 18px;">' . __('Photos provided by Panoramio. Photos are under the copyright of their owners.', 'rpress_main') . '</p>' ;
$block .= '</div>';



} //End function


function set_media($ev_id, $mediafile, $caption, &$block) {
//  if ($_SESSION['lightbox']) $lightbox_str = ' rel="lightbox[album' . $ev_id . ']"';
//     else $lightbox_str = ' ';
  $lightbox_str = ' rel="lightbox[album' . $ev_id . ']"';
  $pref2 = rtrim($_SESSION['media_path'], '/');
  $pref1 = $pref2 . '/thumbs' ;
  $suff = str_replace("\\", "/", $mediafile);

  if ($_SESSION['thumbs']) $img_str = '<img src="' . $pref1 . $suff. '"' .  '>' ;
     else $img_str =  '<img width="100px" src="' . $pref2 . $suff. '"' .  '>';

//  if ($_SESSION['thumbs']) echo 'img str='.$pref1 . $suff ;
//     else echo 'img str=' . $pref2 . $suff;

    $imgg = $pref2 . $suff;
    if(!rp_finder($imgg)) {
        $block .=  '<img src="' . WP_PLUGIN_URL . '/rootspress/images/no_image.gif" title="' . $imgg   . '">';
        $block .= '</a><br/>' . '<i>' . $caption . '</i>' . '<br/>';
    } else {
$block .= '<a href="' . $pref2 . $suff . '"' . $lightbox_str . ' title="' . $caption . '">';
$block .= $img_str;
$block .= '</a><br/>' . '<i>' . $caption . '</i>' . '<br/>';
    }
} //End function
 
function long_event($type) {
   $event_type = array('BIRT' => __('Birth', 'rpress_main', 'rpress_main'),
                       'CHR'  => __('Christening', 'rpress_main'),
                       'DEAT' => __('Death', 'rpress_main'),
                       'CENS' => __('Census', 'rpress_main'),
                       'BURI' => __('Burial', 'rpress_main'),
                       'CREM' => __('Cremation', 'rpress_main'),
                       'ADOP' => __('Adoption', 'rpress_main'),
                       'BAPM' => __('Baptism', 'rpress_main'),
                       'BARM' => __('Bar Mitzvah', 'rpress_main'),
                       'BASM' => __('Bat Mitzvah', 'rpress_main'),
                       'BLES' => __('Blessing', 'rpress_main'),
                       'CHRA' => __('Adult Christening', 'rpress_main'),
                       'CONF' => __('Confirmation', 'rpress_main'),
                       'FCOM' => __('First Communion', 'rpress_main'),
                       'ORDN' => __('Ordination', 'rpress_main'),
                       'NATU' => __('Naturalization', 'rpress_main'),
                       'EMIG' => __('Emigration', 'rpress_main'),
                       'IMMI' => __('Immigration', 'rpress_main'),
                       'PROB' => __('Probate', 'rpress_main'),
                       'WILL' => __('Will', 'rpress_main'),
                       'GRAD' => __('Graduation', 'rpress_main'),
                       'RETI' => __('Retirement', 'rpress_main'),
                       'MARR' => __('Marriage', 'rpress_main'),
                       'ENGA' => __('Engagement', 'rpress_main'),
                       'MARB' => __('Marriage Bann', 'rpress_main'),
                       'MARC' => __('Marriage Contract', 'rpress_main'),
                       'MARL' => __('Marriage License', 'rpress_main'),
                       'MARS' => __('Marriage Settlement', 'rpress_main'),
                       'RESI' => __('Residence', 'rpress_main'),
                       'ANUL' => __('Annulment', 'rpress_main'),
                       'DIV'  => __('Divorce', 'rpress_main'),
                       'DIVF' => __('Divorce filed', 'rpress_main'),
                       'EVEN' => __('General event', 'rpress_main')
                       );
   $result =  $event_type[$type];
   if (!isset($result)) $result = $type;
   return $result;
}

function long_attrib($type) {
   $attrib_type = array('OCCU' => __('Occupation', 'rpress_main'),
                        'CAST' => __('Caste', 'rpress_main'),
                        'DSCR' => __('Physical description', 'rpress_main'),
                        'EDUC' => __('Education', 'rpress_main'),
                        'IDNO' => __('National ID number', 'rpress_main'),
                        'NATI' => __('National origin', 'rpress_main'),
                        'NCHI' => __('Number of children', 'rpress_main'),
                        'NMR'  => __('Number of marriages', 'rpress_main'),
                        'PROP' => __('Possessions', 'rpress_main'),
                        'RELI' => __('Religious affiliation', 'rpress_main'),
                        'RESI' => __('Residence', 'rpress_main'),
                        'SSN'  => __('Social Security Number', 'rpress_main'),
                        'TITL' => __('Nobility', 'rpress_main')
                       );
   $result =  $attrib_type[$type];
   if (!isset($result)) $result = $type;
   return $result;
}

//NOT USED
function get_ancestry($id, $prefix) {
    global $rootspressdb;
    $temp = array();
    $temp[0] = $prefix;
    $temp[1] = $id;

    $sql = 'SELECT * FROM '. $prefix . '_individual' .  " WHERE indi_gedcomnumber = '". $id. "'";
    $result = rp_sql($sql, 'individual');
    $fid = $result['indi_famc'];
    $sql = "SELECT * FROM ". $rp_database . '.' . $prefix . '_family' . " WHERE fam_gedcomnumber = '". $fid. "'";
    $result = rp_sql($sql, 'family');
    $temp[2] = $result['fam_spouse1'];
         if ($temp[2] == null) $temp[2] = 'void';
    $temp[3] = $result['fam_spouse2'];
         if ($temp[3] == null) $temp[3] = 'void';
    $sql = 'SELECT * FROM '. $prefix . '_individual' .  " WHERE indi_gedcomnumber = '". $temp[2]. "'";
    $result = rp_sql($sql, 'individual');
    $fid = $result['indi_famc'];
    $sql = "SELECT * FROM ". $rp_database . '.' . $prefix . '_family' . " WHERE fam_gedcomnumber = '". $fid. "'";
    $result = rp_sql($sql, 'family');
    $temp[4] = $result['fam_spouse1'];
         if ($temp[4] == null) $temp[4] = 'void';
    $temp[5] = $result['fam_spouse2'];
         if ($temp[5] == null) $temp[5] = 'void';
    
    $sql = 'SELECT * FROM '. $prefix . '_individual' .  " WHERE indi_gedcomnumber = '". $temp[3]. "'";
    $result = rp_sql($sql, 'individual');
    $fid = $result['indi_famc'];
    $sql = "SELECT * FROM ". $rp_database . '.' . $prefix . '_family' . " WHERE fam_gedcomnumber = '". $fid. "'";
    $result = rp_sql($sql, 'family');
    $temp[6] = $result['fam_spouse1'];
         if ($temp[6] == null) $temp[6] = 'void';
    $temp[7] = $result['fam_spouse2'];
        if ($temp[7] == null) $temp[7] = 'void';

    return $temp;
} //End function

 function page_link ($ged_id) {
//get page id of root
    global $rootspressdb, $rp_database;
    $tree_id = substr($ged_id, 2);
    $sql = "SELECT * FROM " . $rp_database . '.' . "rp_index WHERE tree_id = '$tree_id'";
    $result = mysql_query($sql, $rootspressdb);
    $row = mysql_fetch_array($result, MYSQL_ASSOC);
    return site_url() . '/?page_id=' . $row['wp_root_id'];
 }

 function security ($ged_id) {
    global $rootspressdb, $rp_database;
//get secured state of root
    $tree_id = substr($ged_id, 2);
    $sql = "SELECT * FROM " . $rp_database . '.' . "rp_index WHERE tree_id = '$tree_id'";
    $result = mysql_query($sql, $rootspressdb);
    $row = mysql_fetch_array($result, MYSQL_ASSOC);
    return $row['secured'];
 }

/*  This function checks events of the individual and possibly children too
**  to determine living status
**  NOT USED
*/
function rp_permit($pid) {
  return true;
               $event = rp_getone_event($pid, 'DEAT');
               if ($event == '') {
                 return false;
               }
                else {
                     $event = rp_getone_event($pid, 'BIRT');
                     $temp = getdate();
                     $age = $temp['year']-date_check($event['event_date']);
                     echo 'Age=' . $age . ' limit=' . $_SESSION['age_limit'];
                     if ($age >= $_SESSION['age_limit']) return true;
                        else   return false;
                  }
} //End function

function date_check($date) {
  $d_array = explode(' ', $date);
  $size = sizeof($d_array);
  if($d_array[0] == 'AFT' || $d_array[0] == 'FROM') return false;

  if($d_array[0] == 'BET') {
    for($i=0; $i<$size; $i++) {
       if($d_array[$i] == 'AND') {
//now find year in remaining array elements
         if(($size-$i) == 2) {
          $year = $d_array[5];
          return $year;
          }
          if(($size-$i) == 3) {
          $year = $d_array[6];
          return $year;
          }
          if(($size-$i) == 4) {
          $year = $d_array[7];
          return $year;
          }
        }
     }
   }

   if($d_array[0] == 'ABT' ||  $d_array[0] == 'CAL' || $d_array[0] == 'EST' || $d_array[0] == 'BEF' ) {
//now find year in remaining array elements
      if($size == 2) {
        $year = $d_array[1];
        return $year;
        }
      if($size == 3) {
        $year = $d_array[2];
        return $year;
        }
      if($size == 4) {
        $year = $d_array[3];
        return $year;
        }
     }  else {
        if($size == 1) {
        $year = $d_array[0];
        return $year;
        }
      if($size == 2) {
        $year = $d_array[1];
        return $year;
        }
      if($size == 3) {
        $year = $d_array[2];
        return $year;
        }
     }

} //End function

?>