<?php
/**
* Database access and support functions.
*
*/
   function PrintReady($str, $type='U') {
        return $str;
	}

    function rp_sql($sql, $type) {
    global $rootspressdb;
    $result=mysql_query($sql, $rootspressdb);
    if ($result === false) {
      echo 'Error reading from ' . $type . ' table: ' . mysql_error() . '<br/>';
      return false;
        }  else {
      return mysql_fetch_assoc($result);
        }
} //end function

function rp_fetch_person_record($id, $prefix) {
    global $rootspressdb,  $rp_database, $GEDCOM;
    $prefix = $GEDCOM;
    $sql = 'SELECT * FROM '. $rp_database . '.' . $prefix . '_individual' .  " WHERE indi_gedcomnumber = '". $id. "'";
    $result = rp_sql($sql, 'individual');
    return $result;
} //end function

function rp_fetch_family_record($id, $prefix) {
    global $rootspressdb,  $rp_database, $GEDCOM;
    $prefix = $GEDCOM;
    $sql = 'SELECT * FROM '. $rp_database . '.' . $prefix . '_family' .  " WHERE fam_gedcomnumber = '". $id. "'";
    $result = rp_sql($sql, 'family');
    return $result;
} //end function

 function rp_getone_event($id, $prefix, $type) {
   global $rootspressdb,  $rp_database;
   $sql = "SELECT * FROM " . $rp_database . '.' . $prefix . '_event' . " WHERE event_owner_id = '". $id . "' AND event_tagtype = '" . $type.  "' ";
   $result = rp_sql($sql, 'event');
    return $result;
 }   //end function
 

 function rp_portrait_tree($pfile, $caption, $type, $lightbox=true) {
  global $RP_PORTRAITS, $SERVER_URL, $PGV_IMAGE_DIR, $PGV_IMAGES;
  if ($lightbox) $lightbox_str = ' rel="slim_box"';
     else $lightbox_str = ' ';

  $block = '<span id="portraitX">' ;
  $imgg = $RP_PORTRAITS . $pfile;
  if(substr($imgg,0,7) != 'http://') {
        $imgg = site_url() . '/' . $imgg;
  }
  if (rp_finder($imgg)){
    $block .=  '<a href="#" onclick="jQuery.slimbox(' . "'" . $imgg . "','" . $caption . "');" . '">';
    $img_str =  '<img class="portrait" '. 'src="' . $imgg .  '"' . '>';
    $block .= $img_str . '</a>'. '</span>';
   }  else  {
            if ($type == 'F') {
		$thumbtype = $SERVER_URL.$PGV_IMAGE_DIR."/".$PGV_IMAGES["default_image_F"]["other"];
			}
		else if ($type == 'M') {
		    $thumbtype = $SERVER_URL.$PGV_IMAGE_DIR."/".$PGV_IMAGES["default_image_M"]["other"];
			}
		else {
		    $thumbtype = $SERVER_URL.$PGV_IMAGE_DIR."/".$PGV_IMAGES["default_image_U"]["other"];
			}
     
       $img_str =  '<img class="portrait" '. 'src="' . $thumbtype . '"' . '>';
       $block .= $img_str . '</span>';
     }
     
  return $block;
} //End function

 function rp_print_fact($label, $date, $place) {
    $factlabel = '<b>' . $label . ': </b>';
    if ($date == null && $place == null) {
       return;
    }
    if ($place == null) {
      echo $factlabel . $date;
      return;
    }
    if ($date == null) {
      echo $factlabel . $place;
      return;
    }  else {
      echo $factlabel . $date . ', ' . $place;
      return;
    }
} //End function

/* Function to check if a person box being placed in treenav
** has a duplicate id to one already placed  (id is the person id, not a DOM id)
** NOTE When invoking this function as a look ahead, eg for the father, set updt to false
*/
 function is_dup($id, $updt = true) {
    static $id_array2;
    if($updt==null) $updt=false;
    if ( is_null($id_array2) ) $id_array2 = array();
    $max = count($id_array2);
    for ($i=0; $i<$max; $i++) {
       if($id == $id_array2[$i]) return true;
    }
    if($updt === true) $id_array2[] = $id;
    return false;
 } //End function
 
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

?>