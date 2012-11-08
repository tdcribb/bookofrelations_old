<?php
/* This component updated 2012.04.30 */
//ENTER WITH TABLE PREFIX IN $rp_prefix AND GED INPUT FILE NAME IN $filepath
//      $rp_prefix = 'rp1';
//      $filepath = 'test2.ged';

define ('MY_DEBUG', 'NO');    //set to YES to output debug information
define ('MY_SQL_WRITE', 'YES');    //set to NO to suppress mysql writes

function log_me($message) {
    if (WP_DEBUG === true) {
        if (is_array($message) || is_object($message)) {
            error_log(print_r($message, true));
        } else {
            error_log($message);
        }
    }
}

function my_debug($msg) {
//  if(MY_DEBUG == 'YES') echo $msg . '<br/>';
    if(MY_DEBUG == 'YES')  error_log($msg);
}

function parse2($line, $line_count, &$parsed) {
        $parsed['level'] = '';
        $parsed['tag'] = '';
        $parsed['xref'] = '';
        $parsed['content'] = '';
        $line = trim($line, "\n\r"); //trim cr and new line from end
        $line = ltrim($line, " ");  //trim spaces from start

        $pos0 = 0;
        $pos1 = strpos($line, " ", 0);   //look for first delim
        if ($pos1 === false) {
          echo "Critical error. No space found in line " . $line_count . ' ';
          die();
          }
        if ($pos1 == 0) {
        echo "Critical error. Line " . $line_count . " starts in a space"; die();
          }
        $field = substr($line, $pos0, $pos1-$pos0);
        $parsed['level'] = $field;
        $pos0 = $pos1+1;
        $pos1 = strpos($line, " ", $pos0);   //look for second delim
        if ($pos1 === false) {
          $temp = Strlen($line)-$pos0;
          $field = substr($line, $pos0, $temp);
          $parsed['tag'] = $field;
          return true;
          }
        $temp = $pos1-$pos0;
        $field = substr($line, $pos0, $temp);
        if (substr($field, 0, 1) == '@') {
        $F_xref = true;
        $parsed['xref'] = $field;
        }    else {
        $F_xref = false;
        }

        if($F_xref) {
        $pos0 = $pos1+1;
        $pos1 = strpos($line, " ", $pos0);
        if ($pos1 === false) {
        $field  = substr($line, $pos0);
        $parsed['tag'] = $field;
           return;
            }
        $temp = $pos1-$pos0;
        $field = substr($line, $pos0, $temp);
        $parsed['tag'] = $field;
        if ($pos1 === false) return;
        } else {
           $parsed['tag'] = $field;
          }
//***********
        $pos0 = $pos1+1;
        $pos1 = strpos($line, " ", $pos0);
        $field  = substr($line, $pos0);
        $parsed['content'] = $field;
        return;
        }

/* Accumulates input in case we encounter a CONT or CONC tag
** if not, simply returns the previous input line
** if so, concatenates the CONT or CONC and returns false (no output ready)
*/
function accumulator($tag, $name, $content, &$output) {
  global $saved;
     if ($tag != 'CONT' && $tag != 'CONC') {
         $output['content'] = $saved['content'];
         $output['name'] = $saved['name'];
         $saved['content'] = $content;
         $saved['name'] = $name;
         build_output($output);
         return true;
         } else {
           if ($tag == 'CONC') $saved['content'] .= $content;
           if ($tag == 'CONT') $saved['content'] .= "\r\n" . $content ;
         $output['content'] = '';
         return false;
         }

} // end of function

/*function write_out($field, $field_title)  {
    echo count($field);
      if  (empty($field)) return false; // nothing to write
      echo 'TABLE=' . $field_title . '<br/>';
      foreach ($field as $key => $value) {
        echo "field is $key; content is $value" . '<br/>';
      } //end foreach
} */

function build_output($output) {
  global $fields;
  $tempn = strpos($output['name'], '_');
/*NOTE: The tag string can be invalid when a CONT or CONC follows an unsupported tag
        for example the ADDR tag in the HEAD record.
        This will be ignored for now.
        */
//  if ($tempn === false) echo 'Internal error: invalid tag string, underscore missing:' . $output['name'] . '<br/>';
      $temp = substr($output['name'], 0, $tempn); // form record name
      $temp_name = substr($output['name'], (1+$tempn)); //form associative field name
      
//Form record variables from record name and associative name
//echo strToHex($temp) . '<br/>';
  switch ($temp) {
    case "HEAD":
        $fields['HEAD_' . $temp_name] = $output['content'];
        break;
    case "INDI":
        $fields['INDI_' . $temp_name] = $output['content'];
        break;
    case "FAM":
        $fields['FAM_' . $temp_name] = $output['content'];
        break;
    case "ZOUR":
        $fields['ZOUR_' . $temp_name] = $output['content'];
        break;
    case "ZNOTE":
        $fields['ZNOTE_' . $temp_name] = $output['content'];
        break;
    case "ZPLAC":
        $fields['ZPLAC_' . $temp_name] = $output['content'];
        break;
    case "ZOBJE":
        $fields['ZOBJE_' . $temp_name] = $output['content'];
        break;
    case "ZREPO":
        $fields['ZREPO_' . $temp_name] = $output['content'];
        break;
    default:
       my_debug("Record type=" . $temp . $output['name'] . ' (' . strToHex($temp) .') ignored ');
     }
}

function strToHex($string)
{
    $hex='';
    for ($i=0; $i < strlen($string); $i++) {
        $hex .= dechex(ord($string[$i]));
        }
    return $hex;
}

function bucketizer($xref, $tag, $level, $name) {
   static $bucket;
   static $item_count;
   if (!is_array($bucket)) {
     $bucket = array();
   }
//check existing buckets to see if any should be closed IN REVERSE ORDER
$b_size = sizeof($bucket);
for ($j=($b_size-1); $j>=0; $j--) {
  if(isset($bucket[$j]['level'])) { //Not all levels will be set in a bucket
   if ($level <= $bucket[$j]['level']) {
       if ($j==0 || !isset($bucket[$j-1]['xref'])) $owref = '';
          else $owref =  $bucket[$j-1]['xref'];
       write_bucket($bucket[$j]['tag'], $bucket[$j]['xref'], $bucket[$j]['name'], $bucket[$j]['level'], $owref); //writeout contents of bucket
       $this_level = $bucket[$j]['level'];
       unset($bucket[$this_level]);  //remove this bucket
        }
  } //End isset
} //End for

      $tag_result =  tagcheck($tag);
//Check if this tag indicates a new bucket  and if so add a new bucket at this level
    if($tag_result !== false) {
      if ($tag_result == 1) {  //tag_result=1 means this is not a record level item
        $bucket[$level]['xref'] = 'X' . ++$item_count; //generate internal xref
      } else {
         $bucket[$level]['xref'] = $xref;
      }

      $bucket[$level]['tag'] = $tag;
      $bucket[$level]['level'] = $level;
      $bucket[$level]['name'] = $name;
     }
//BUCKET DUMP
//echo 'BUCKET DUMP<br/>';
//       foreach($bucket as $value) {
//         echo 'Bucket ' . $value['level'] . ' tag=' . $value['tag'] . ' xref=' . $value['xref'] . ' name=' . $value['name'] . '<br/>';
//       }

}

/* Checks a tag against a table to see if it is a bucket trigger
** The array is used to signal a new bucket.
** The entries correspond to the tables in the database
** either uniquely or as part of a group (eg family events)
** the value indicates if an internal xref needs to be generated
*/

function tagcheck($tag) {
$tagarray = array(
  'INDI' => 0,
  'FAM'  => 0,
  'HEAD' => 0,
  'ZOUR' => 0,
  'ZREPO' => 0,
  'ZNOTE' => 0,
  'ZOBJE' => 0,
  'SOUR' => 1,
  'FAMS' => 0,
  'CHIL' => 0,
  'BIRT' => 1,
  'CHR'  => 1,
  'DEAT' => 1,
  'BURI' => 1,
  'CREM' => 1,
  'ADOP' => 1,
  'BAPM' => 1,
  'NATU' => 1,
  'EMIG' => 1,
  'IMMI' => 1,
  'CENS' => 1,
  'PROB' => 1,
  'WILL' => 1,
  'EVEN' => 1,
  'OBJE' => 1,
  'ANUL' => 1,
  'DIV'  => 1,
  'DIVF' => 1,
  'ENGA' => 1,
  'MARB' => 1,
  'MARC' => 1,
  'MARR' => 1,
  'MARL' => 1,
  'MARS' => 1,
  'RESI' => 1,
  'CAST' => 1,
  'DSCR' => 1,
  'EDUC' => 1,
  'IDNO' => 1,
  'NATI' => 1,
  'NCHI' => 1,
  'NMR'  => 1,
  'OCCU' => 1,
  'PROP' => 1,
  'RELI' => 1,
  'SSN'  => 1,
  'TITL' => 1,
  'FACT' => 1,
  'NOTE' => 1,
  'PLAC' => 1,
  'YAUX' => 1,
  'DATA' => 1,
  'TEXT' => 1,
  'FILE' => 1,
  'REFN' => 0,
//  '_TYPE' =>1,
  'ZPLAC' => 1   );

   if (!isset($tagarray[$tag])) return false;   //tag not recognized, not a bucket element
     else return $tagarray[$tag];  //return 1 to indicate an internal xref is required, 0 otherwise
}

function write_bucket($tag, $xref, $name, $level, $owner_xref) {
    global $fields;
//Extract record type from name
    $temp = strpos($name, '_'); //posn of first underscore
    $rectype = substr($name, 0, strpos($name, '_')); //extract up to first underscore
    my_debug('OUTPUT tag=' . $tag . ' for xref=(' . $xref . ') name=' . $name . ' level=' . $level . ' owner xref=' . $owner_xref);
//    my_debug('... contents=' . $fields[$name]);
    write_route($xref, $rectype, $tag, $name, $level, $owner_xref);

 }  //End function write_bucket
 
function write_route($xref, $rectype, $tag, $name, $level, $owner_xref) {
   global $fields;
   include 'route_table.php';
   if (!isset($route_table[$tag])) return false;   //tag not recognized
/* Routing value
** 0=write_head
** 1=write_indi
** 2=write_fam
** 3=write_event
** 4=write_attrib
** 5=write_obje
** 6=write_zource (source record)
** 7=write_citn
** 8=write_spouses
** 9=write_children
** 10=write_place
** 11=write_repo
** 12=write_znote
** 13=write_zobje (OBJE record)
** 14=write_note
** 15=write_media file
*/
switch ($route_table[$tag]) {
    case '16':
      write_head($rectype, $tag, $name, $level);
      break;
    case '1':
      $fields['INDI_XREF_'] = $xref;
      write_indi($rectype, $tag, $name, $level);
      break;
    case '2':
      $fields['FAM_XREF_'] = $xref;
      write_fam($rectype, $tag, $name, $level);
      break;
    case '3':
      write_event($rectype, $tag, $name, $level, $xref, $owner_xref);
      break;
    case '4':
      write_attrib($rectype, $tag, $name, $level, $xref, $owner_xref);
      break;
    case '5':
      write_obje($rectype, $tag, $name, $level, $xref, $owner_xref);
      break;
    case '6':
      $fields['ZOUR_XREF_'] = $xref;
      write_zource($rectype, $tag, $name, $level);
      break;
    case '7':
      write_citn($rectype, $tag, $name, $level, $xref, $owner_xref);
      break;
    case '8':
      write_spouses($rectype, $tag, $name, $level);
      break;
    case '9':
      write_children($rectype, $tag, $name, $level);
      break;
    case '10':
      write_place($rectype, $tag, $name, $level, $xref, $owner_xref);
      break;
    case '11':
      $fields['ZREPO_XREF_'] = $xref;
      write_repo($rectype, $tag, $name, $level);
      break;
    case '12':
      $fields['ZNOTE_XREF_'] = $xref;
      write_znote($rectype, $tag, $name, $level);
      break;
    case '13':
      $fields['ZOBJE_XREF_'] = $xref;
      write_zobje($rectype, $tag, $name, $level);
      break;
    case '14':
      write_note($rectype, $tag, $name, $level, $xref, $owner_xref);
      break;
    case '15':
      write_media($rectype, $tag, $name, $level, $xref, $owner_xref);
      break;
    case '17':
      write_refn($rectype, $tag, $name, $level, $xref, $owner_xref);
      break;
} //end switch
   return true;
} //End function  write_route

/* This function handles tag names set up in fields and extras arrays
*/
function rp_getfield(&$array, $field_name, $escape='') {
   $temp = ''; //default if key does not exist
   if(array_key_exists($field_name, $array)) {
      $temp = $array[$field_name];
         if($escape != '') $temp = mysql_real_escape_string($temp, $escape);
      $array[$field_name] = '';
    }
return $temp;
} //End funtion

/* This function handles accumulation of ref values
*/
function rp_getref(&$array, $field_name) {
   $temp = ''; //default if key does not exist
   if(array_key_exists($field_name, $array)) {
      $temp = $array[$field_name];
    }
return $temp;
} //End funtion

function write_head($rectype, $type, $name, $level) {
  global $fields, $extras, $rootspressdb, $rp_prefix, $system_id;
} //End function

function write_indi($rectype, $type, $name, $level) {
  global $fields, $extras, $rootspressdb, $rp_prefix;
        $retn = $fields['INDI_XREF_'];
        $line = '';
	$line .= "'" . rp_getfield($fields, 'INDI_XREF_') . "',";
	$line .= "'" . trim(rp_getfield($fields, 'INDI_FAMC_'), '@'). "',";
        $line .= "'" . rtrim(rp_getfield($extras, 'INDI_FAMS_LIST_'), ';'). "',";
	$line .= "'" . rp_getfield($fields, 'INDI_NAME_GIVN_', $rootspressdb). "',";
        $line .= "'" . rp_getfield($fields, 'INDI_NAME_SURN_', $rootspressdb). "',";
	$line .= "'" . rp_getfield($fields, 'INDI_NAME_NPFX_'). "',";
	$line .= "'" . rp_getfield($fields, 'INDI_NAME_SPFX_'). "',";
	$line .= "'" . rp_getfield($fields, 'INDI_NAME_', $rootspressdb). "',";
        $line .= "'" . rp_getfield($fields, 'INDI_NAME_NICK_'). "',";
        $line .= "'" . rp_getfield($fields, 'INDI_SEX_'). "',";
        $line .= "'" . rtrim(rp_getfield($extras, 'INDI_EVENT_REF_'), ';'). "',";
        $line .= "'" . rtrim(rp_getfield($extras, 'INDI_ATTR_REF_'), ';'). "',";
        $line .= "'" . rtrim(rp_getfield($extras, 'INDI_REFN_LIST_'), ';'). "',";
 	$line .= "'" . rtrim(rp_getfield($extras, 'INDI_NOTE_REF_'), ';'). "',";
        $line .= "'" . rtrim(rp_getfield($extras, 'INDI_SOUR_REF_'), ';'). "',";
	$line .= "'" . rtrim(rp_getfield($extras, 'INDI_OBJE_REF_'), ';'). "'";
	data_write('individual', null , $line);
        return $retn;
}


function write_fam($rectype, $type, $name, $level) {
  global $fields, $extras, $rootspressdb, $rp_prefix;
    $retn = $fields['FAM_XREF_'];
        $line = '';
	$line .= "'" . rp_getfield($fields, 'FAM_XREF_'). "',";
        $line .= "'" . trim(rp_getfield($fields, 'FAM_HUSB_'), '@'). "',";
        $line .= "'" . trim(rp_getfield($fields, 'FAM_WIFE_'), '@'). "',";
        $line .= "'" . rtrim(rp_getfield($extras, 'FAM_CHIL_LIST_'), ';'). "',";
        $line .= "'" . rtrim(rp_getfield($extras, 'FAM_EVENT_REF_'), ';'). "',";
        $line .= "'" . rtrim(rp_getfield($extras, 'FAM_REFN_LIST_'), ';'). "',";
 	$line .= "'" . rtrim(rp_getfield($fields, 'FAM_NOTE_'), ';'). "',";
        $line .= "'" . rtrim(rp_getfield($extras, 'FAM_SOUR_REF_'), ';'). "',";
	$line .= "'" . rtrim(rp_getfield($extras, 'FAM_OBJE_REF_'), ';'). "'";
        data_write('family', null, $line);
        return $retn;
}

//Write source record (SOUR)
function write_zource() {
  global $fields, $extras, $rootspressdb, $rp_prefix;
    $retn = $fields['ZOUR_XREF_'];
        $line = '';
	$line .= "'"  . rp_getfield($fields, 'ZOUR_XREF_'). "',";
	$line .= "'"  . rp_getfield($fields, 'ZOUR_ABBR_', $rootspressdb). "',";
        $line .= "'"  . rp_getfield($fields, 'ZOUR_AUTH_', $rootspressdb). "',";
        $line .= "'"  . rp_getfield($fields, 'ZOUR_TITL_', $rootspressdb). "',";
	$line .= "'"  . rp_getfield($fields, 'ZOUR_PUBL_', $rootspressdb). "',";
        $line .= "'"  . rp_getfield($fields, 'ZOUR_TEXT_', $rootspressdb). "',";
        $line .= "'"  . trim(rp_getfield($fields, 'ZOUR_REPO_'), '@'). "',";
        $line .= "'"  . rtrim(rp_getfield($extras, 'ZOUR_REFN_LIST_'), ';'). "',";
	$line .= "'"  . rtrim(rp_getfield($extras, 'ZOUR_NOTE_REF_', $rootspressdb), ';'). "',";
	$line .= "'"  . rp_getfield($extras, 'ZOUR_OBJE_REF_'). "'";
        data_write('source', null, $line);
        return $retn;
}

function write_event($rectype, $type, $name, $level, $my_xref, $owner_xref) {
    global $fields, $extras, $rootspressdb, $rp_prefix;
    static $item_count;
    $my_xref2 = 'E@' . ++$item_count;  //assign an internal xref
    if ($type == 'EVEN' && $name != 'INDI_EVEN_') return; //Ensure EVEN tag is an event of an indi record
//place event id into list for owner write
        $extras[$rectype . '_EVENT_REF_'] =  rp_getref($extras, $rectype . '_EVENT_REF_') . $my_xref2 . ';' ;
        $line = '';
        $line .= "'" . $my_xref2. "',";
	$line .= "'" .  $owner_xref. "',";
        $line .= "'" .  $type. "',";
        $line .= "'" .  rp_getfield($fields, $rectype . '_' . $type . '_', $rootspressdb). "',";      //content
        $line .= "'" .  rp_getfield($fields, $rectype . '_' . $type . '_TYPE_'). "',";
        $line .= "'" .  rp_getfield($fields, $rectype . '_' . $type . '_DATE_'). "',";
	$line .= "'" .  rp_getfield($fields, $rectype . '_' . $type . '_PLAC_', $rootspressdb). "',";
	$line .= "'" .  rp_getfield($fields, $rectype . '_' . $type . '_CAUS_'). "',";
	$line .= "'" .  rtrim(rp_getfield($extras, $rectype . '_' . $type . '_NOTE_REF_'), ';'). "',";
	$line .= "'" .  rtrim(rp_getfield($extras, $rectype . '_' . $type . '_SOUR_REF_'), ';'). "',";
	$line .= "'" .  rtrim(rp_getfield($extras, $rectype . '_' . $type . '_OBJE_REF_'), ';'). "'";
        data_write('event', null, $line);
        return;
}

function write_attrib($rectype, $type, $name, $level, $my_xref, $owner_xref) {
    global $fields, $extras, $rootspressdb, $rp_prefix;
    static $item_count;
    $my_xref2 = 'A@' . ++$item_count;  //assign an internal xref
    if ($type == 'TITL' && $name != 'INDI_TITL_') return; //Ensure TITL tag is an attribute of an indi record
//place event id into list for owner write
        $extras[$rectype . '_ATTR_REF_'] =  rp_getref($extras, $rectype . '_ATTR_REF_') . $my_xref2 . ';' ;
        $line = '';
        $line .= "'" . $my_xref2. "',";
        $line .= "'" .  $owner_xref. "',";
        $line .= "'" . $type. "',";
        $line .= "'" . rp_getfield($fields, $rectype . '_' . $type . '_', $rootspressdb). "',";
        $line .= "'" . rp_getfield($fields, $rectype . '_' . $type . '_TYPE_'). "',";
        $line .= "'" . rp_getfield($fields, $rectype . '_' . $type . '_DATE_'). "',";
        $line .= "'" . rp_getfield($fields, $rectype . '_' . $type . '_PLAC_', $rootspressdb). "',";
        $line .= "'" . rtrim(rp_getfield($extras, $rectype . '_' . $type . '_NOTE_REF_'), ';'). "',";
        $line .= "'" . rtrim(rp_getfield($extras, $rectype . '_' . $type . '_SOUR_REF_'), ';'). "',";
        $line .= "'" . rtrim(rp_getfield($extras, $rectype . '_' . $type . '_OBJE_REF_'), ';'). "'";
        data_write('attrib', null, $line);
        return;
}

function write_spouses($rectype, $type, $name, $level) {
  global $fields, $extras, $rootspressdb, $owner_xref, $media_id;
        $extras[$name . 'LIST_'] =  rp_getref($extras, $name . 'LIST_') . trim(rp_getfield($fields, $name), '@') . ';' ; //place spouse id into list for owner write
        $fields[$name . 'FAMS_'] = '';
}  

function write_children($rectype, $type, $name, $level) {
  global $fields, $extras, $rootspressdb, $owner_xref, $media_id;
        $extras[$name . 'LIST_'] =  rp_getref($extras, $name . 'LIST_') . trim(rp_getfield($fields, $name), '@') . ';' ; //place child id into list for owner write
        $fields[$name . 'CHIL_'] = '';
}

function write_refn($rectype, $type, $name, $level) {
  global $fields, $extras, $rootspressdb, $owner_xref, $media_id;
        $extras[$name . 'LIST_'] =  rp_getref($extras, $name . 'LIST_') . trim(rp_getfield($fields, $name), '@') . ';' ; //place refn id into list for owner write
        $fields[$name . 'REFN_'] = '';
}

//Processes an OBJE element
function write_obje($rectype, $type, $name, $level, $my_xref, $owner_xref) {
  global $fields, $extras, $rootspressdb, $rp_prefix, $system_id;
  static $item_count;
//If this is a media link, store in reference field, do not write any media table row
$temp = rp_getref($fields, $name. 'YAUX_');
if(substr($temp, 0, 1) == '@') {
  $extras[$name . 'REF_'] = rp_getref($extras, $name . 'REF_') . trim(rp_getfield($fields, $name . 'YAUX_'), '@') . ';' ;
  return;
}

//Set up media type
if(strtolower($system_id) == 'rootsmagic') $mtype = rp_getfield($fields, $name . 'FORM__TYPE_'); //RootsMagic
        else $mtype = rp_getfield($fields, $name . 'FORM_MEDI_');

//Set up media title
if(rp_getref($fields, $name . 'TITL_') != null) $mtitle = rp_getfield($fields, $name . 'TITL_', $rootspressdb);
   else $mtitle = rp_getfield($fields, $name . 'FORM_TITL_');
        $my_xref2 = 'O@' . ++$item_count;  //assign an internal xref
        $extras[$name . 'REF_'] = rp_getref($extras, $name . 'REF_') . $my_xref2 . ';' ; //put media id into list for owner write
        $line = '';
	$line .= "'" . $my_xref2. "',";
	$line .= "'". "',";
	$line .= "'" . rtrim(rp_getfield($extras, $name . 'FILE_REF_'), ';'). "',";
        $line .= "'" . $mtitle. "',";
        $line .= "'" . $mtype. "',";
	$line .= "'". "',";
	$line .= "'". "',";
	$line .= "'". "'";
        data_write('obje', null, $line);
        return;
}  //function end

//Write media file
function write_media($rectype, $type, $name, $level, $my_xref, $owner_xref) {
  global $fields, $extras, $rootspressdb, $rp_prefix, $system_id;
  static $item_count;
 //Remove drive letter from start of file name if needed
        $t_file = rp_getfield($fields, $name);
        $pos = strpos($t_file, ':');
        if ($pos !== false) $t_file = substr($t_file, 1+$pos); //skip over directory value if any
        $t_file = str_replace("\\", "/", $t_file);

        $my_xref2 = 'M@' . ++$item_count;  //assign an internal xref
        $extras[$name . 'REF_'] =  rp_getref($extras, $name . 'REF_') . $my_xref2 . ';' ; //place note id into list for owner write
        $line = '';
	$line .= "'" . $my_xref2. "',";
	$line .= "'" . $owner_xref. "',";
	$line .= "'" . mysql_real_escape_string($t_file, $rootspressdb). "',";
	$line .= "'" . rp_getfield($fields, $name . 'FORM_'). "',";
	$line .= "'" . rp_getfield($fields, $name . 'FORM_MEDI_'). "',";
	$line .= "'" . rp_getfield($fields, $name . 'FORM_TITL_', $rootspressdb). "'";
        data_write('media', null, $line);
}  //function end

function write_note($rectype, $type, $name, $level, $my_xref, $owner_xref) {
  global $fields, $extras, $rootspressdb, $rp_prefix;
  static $item_count;
//If this is a link, store in reference field, do not write any table row
$temp = rp_getref($fields, $name . 'YAUX_');
if(substr($temp, 0, 1) == '@') {
  $extras[$name . 'REF_'] =   rp_getref($extras, $name . 'REF_') . trim(rp_getfield($fields, $name . 'YAUX_'), '@') . ';' ;
  return;
}
        $my_xref2 = 'N@' . ++$item_count;  //assign an internal xref
        $extras[$name . 'REF_'] =  rp_getref($extras, $name . 'REF_') . $my_xref2 . ';' ; //place note id into list for owner write
        $line = '';
	$line .= "'" . $my_xref2. "',";
	$line .= "'" . rp_getfield($fields, $name . 'YAUX_', $rootspressdb). "',";
	$line .= "'". "',";
	$line .= "'". "'";
        data_write('note', null, $line);
} //function end

function write_citn($rectype, $type, $name, $level, $my_xref, $owner_xref) {
  global $fields, $extras, $rootspressdb, $rp_prefix, $system_id;
  static $item_count;
//SPECIAL CASE: SOUR field in HEAD gives system id for extensions
  if ($name == 'HEAD_SOUR_') {
    $system_id = rp_getfield($fields, 'HEAD_SOUR_YAUX_');
    return;
    }
  $my_xref2 = 'C@' . ++$item_count;  //assign an internal xref
  $temp = rp_getref($fields, $name . 'YAUX_');
  if(substr($temp, 0, 1) == '@') write_citn1($rectype, $type, $name, $level, $my_xref2, $owner_xref);
    else  write_citn2($rectype, $type, $name, $level, $my_xref2, $owner_xref);
}//End function

function write_citn2($rectype, $type, $name, $level, $my_xref2, $owner_xref) {
  global $fields, $extras, $rootspressdb, $rp_prefix;
//  static $item_count;
//  $my_xref2 = 'C@' . ++$item_count;  //assign an internal xref
       $extras[$name . 'REF_'] =  rp_getref($extras, $name . 'REF_') . $my_xref2 . ';' ; //place citation id into list for owner write
        $line = '';
	$line .= "'" .$my_xref2. "',";
	$line .= "'" .$owner_xref;     //owner id
	$line .= "'". "',";                  //source xref
        $line .= "'" .trim(rp_getfield($fields,$name. 'YAUX_'), '@'). "',";   //description
        $line .= "'". "',";                   //page
        $line .= "'" .rtrim(rp_getfield($extras, $name . 'NOTE_REF_', $rootspressdb), ';'). "',";
        $line .= "'". "',";                   //event
        $line .= "'". "',";                    //role
	$line .= "'" .rp_getfield($fields, $name . 'TEXT_', $rootspressdb). "',";
	$line .= "'" .rtrim(rp_getfield($extras, $name . 'OBJE_REF_'), ';'). "',";
	$line .= "'" .rp_getfield($fields, $name . 'QUAY_'). "'";
        data_write('citation', null, $line);
        return;
} // End function


function write_citn1($rectype, $type, $name, $level, $my_xref2, $owner_xref) {
  global $fields, $extras, $rootspressdb, $rp_prefix;
  //  citn_source_xref=  '". trim($fields[$name], '@').  "',
        $extras[$name . 'REF_'] =  rp_getref($extras, $name . 'REF_') . $my_xref2 . ';' ; //place citation id into list for owner write
        $line = '';
	$line .= "'" . $my_xref2. "',";
	$line .= "'" . $owner_xref. "',";   //owner id
	$line .= "'" . trim(rp_getfield($fields, $name. 'YAUX_'), '@'). "',";   //source
        $line .= "'" . "',";                                             // description
	$line .= "'" . rp_getfield($fields, $name . 'PAGE_', $rootspressdb). "',";         //page
	$line .= "'" . rtrim(rp_getfield($extras, $name . 'NOTE_REF_', $rootspressdb), ';'). "',";         //note ref
	$line .= "'" . rp_getfield($fields, $name . 'EVEN_'). "',";              //event from
	$line .= "'" . rp_getfield($fields, $name . 'EVEN_ROLE_'). "',";        //event role
	$line .= "'" . rp_getfield($fields, $name . 'DATA_TEXT_', $rootspressdb). "',";      //text
	$line .= "'" . rtrim(rp_getfield($extras, $name . '_OBJE_REF_'), ';'). "',";        //media ref
	$line .= "'" . rp_getfield($fields, $name . 'QUAY_'). "'";                         //quality
        data_write('citation', null, $line);
        return;
}  //function end

function write_place($rectype, $type, $name, $level, $my_xref, $owner_xref) {
  global $fields, $extras, $rootspressdb, $rp_prefix, $system_id;
  static $item_count;
//If this is a RootsMagic file, the places table is only populated by _PLAC tags
if ((strtolower($system_id) == 'rootsmagic') && ($name != 'ZPLAC_')) {
  return;  //RM extension
}

  $my_xref2 = 'P@' . ++$item_count;  //assign an internal xref
//NOTE: place field is NOT cleared here so that it can be used in INDI record
        $line = '';
	$line .= "'" .  $my_xref2. "',";
	$line .= "'" . $owner_xref. "',";
	$line .= "'" . mysql_real_escape_string(rp_getref($fields, $name), $rootspressdb). "',";
	$line .= "'" . rp_getfield($fields, $name . 'MAP_LATI_'). "',";
	$line .= "'" . rp_getfield($fields, $name . 'MAP_LONG_'). "',";
        $line .= "'". "'";      // note text
        data_write('places', null, $line);
        return;
}

function write_repo($rectype, $type, $name, $level) {
  global $fields, $extras, $rootspressdb, $rp_prefix;
  $retn = $fields['ZREPO_XREF_'];
	$line='';
	$line .= "'" . rp_getfield($fields, 'ZREPO_XREF_'). "',";
	$line .= "'" . rp_getfield($fields, 'ZREPO_NAME_', $rootspressdb). "',";
	$line .= "'". "',";      //address
	$line .= "'" . rtrim(rp_getfield($extras, 'ZREPO_NOTE_REF_', $rootspressdb), ';'). "',";
	$line .= "'" . rtrim(rp_getfield($extras, 'ZREPO_REFN_LIST_'), ';'). "'";
        data_write('repo', null, $line);
        return $retn;
}

//Writes NOTE record
function write_znote($rectype, $type, $name, $level) {
  global $fields, $extras, $rootspressdb, $rp_prefix;
  $retn = $fields['ZNOTE_XREF_'];
        $line = '';
        $line .= "'" . rp_getfield($fields, 'ZNOTE_XREF_'). "',";
	$line .= "'" . rp_getfield($fields, 'ZNOTE_YAUX_', $rootspressdb). "',";    //note text
        $line .= "'" . rtrim(rp_getfield($extras, 'ZNOTE_REFN_LIST_'), ';'). "',";
        $line .= "'" . rtrim(rp_getfield($extras, 'ZNOTE_SOUR_REF_'), ';'). "'";
        data_write('note', null, $line);
        return $retn;
}

function write_zobje($rectype, $type, $name, $level) {
  global $fields, $extras, $rootspressdb, $rp_prefix;
  $retn = $fields['ZOBJE_XREF_'];
	$line = '';
        $line .= "'" . rp_getfield($fields, 'ZOBJE_XREF_'). "',";
        $line .= "'". "',";
        $line .= "'" . rtrim(rp_getfield($extras, 'ZOBJE_FILE_REF_'), ';'). "',";
        $line .= "'" . rp_getfield($fields, $name . 'FILE_TITL_', $rootspressdb). "',";
        $line .= "'" . rp_getfield($fields, $name . 'FILE_FORM_TYPE_', $rootspressdb). "',";
        $line .= "'" . rtrim(rp_getfield($extras, 'ZOBJE_REFN_LIST_'), ';'). "',";
        $line .= "'" . rtrim(rp_getfield($extras, 'ZOBJE_NOTE_REF_', $rootspressdb), ';'). "',";
        $line .= "'" .  rtrim(rp_getfield($extras, 'ZOBJE_SOUR_REF_'), ';'). "'";
        data_write('obje', null, $line);
        return $retn;
}

function sql_query($sql, $tab) {
  global $xfileout, $rootspressdb;
  if (MY_SQL_WRITE != 'YES') return true;

$time_start = microtime_float();

//    	mysql_select_db($_SESSION['database'], $rootspressdb);
        $result=mysql_query($sql, $rootspressdb);
fwrite($xfileout, $sql . "\r\n");
$result = true;
        
$time_end = microtime_float();
$_SESSION['sqltime'] =  $_SESSION['sqltime']+ $time_end - $time_start;

	if ($result === false) {
          echo 'Error writing to ' . $tab . ' table: ' .mysql_error();
          echo ' : query=' . $sql . '<br/>';
          }
}

function line_end($line) {
   if(strpos($line, "\r\n") !== false) return "\r\n";
   if(strpos($line, "\n\r") !== false) return "\n\r";
   if(strpos($line, "\r") !== false) return "\r";  //its not \r\n or \n\r so must be \r alone
   if(strpos($line, "\n") !== false) return "\n";  //its not \r\n or \n\r so must be \n alone
   return false; //error
}

function data_write($type, $mod=null, $data) {
  global $t_handle;
  $xtype = $type.$mod;
  if(strlen($t_handle[$xtype]) >0) $pref = ','; //add , to end prior values set
     else $pref = '';
  $t_handle[$xtype] .=  $pref . '('.$data . ')';  //add values to type string
//echo 'writing ' . $data .  ' to type='.$type.' mod='.$mod.'<br/>';
  return true;
}

function data_manage($command, $rp_prefix)  {
  global $rootspressdb, $rp_database;
  require('preliminary.php');
  global $t_handle;
  static $t_size;
$types = array('attrib', 'citation', 'event', 'family', 'individual', 'media', 'note', 'obje',  'places', 'repo',  'source');   //order is not important
$t_size =  sizeof($types);

if($command == 'open') {
    $t_handle = array();  //reset array
    for($i=0; $i<$t_size; $i++) {
      $type = $types[$i];
      $t_handle[$type] = '';
    }
return;
}

if($command == 'close') {
//  $rootspressdb = mysql_connect($_SESSION['dbhost'], $_SESSION['dbuser'], $_SESSION['dbpwd']);
//  mysql_select_db($_SESSION['database'], $rootspressdb);
  $return = 0; //default return code to rootsPress
  $memsize=0;
//  echo 'Memory usage: ';
for($i=0; $i<$t_size; $i++) {
    $type = $types[$i];
     if(strlen($t_handle[$type]) > 0) {
//      echo $type.' '.strlen($t_handle[$type]).', ';
    $memsize +=  strlen($t_handle[$type]);
    $sql = $prelim[$type] . ' VALUES ' . $t_handle[$type]  ;       //note terminating semi-colon    . ';'
    $result = mysql_query($sql, $rootspressdb);
    if(!$result) {
      echo 'mysql error for type '.  $type . "<br/>";
      echo mysql_errno($rootspressdb).' ' . mysql_error($rootspressdb). "<br/>";
//      echo $sql.'<br/>';
      $return = -2;
      }
    }

    }
//    echo 'Total '.($memsize/1000000).'mb<br/>';
    return $return;
  }   //end close
} //end function

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

function my_stream_get_line($handle, $length, $end)
{
$line = fgets($handle, $length);
//$lline = strlen($line);
//echo 'in my_stream_get_line '. ' line read, length=' . $lline . ' end preset=' . strToHex($end) . ' last 2 characters=' . strToHex(substr($line, -2)) . '<br/>';
return  $line;
}

function rp_load_ged($prefix, $gedfile) {
  global $rootspressdb, $rp_prefix, $extras, $fields, $system_id;
  $system_id = null;
  static $line_count, $note_save;
  $rp_prefix = $prefix;

//Used by accumulator function for saving text for concatenation
     $saved = array();
     
//Holds active xref if's for each level, indexed by level (level 0 is null)
     $owner_xref = array();
     $owner_xref[0] = '';

     $fields = array();
     $extras = array();

$handle = fopen ($gedfile, "r");
if (!$handle) {
echo 'File ' . $gedfile . ' not found';
die();
}
$tag_array = array();
$tag_index = 0;
$level = '0';
$old_level = '0';
$text = null;
$name = null;
$old_name = null;

//  $rootspressdb = mysql_connect($_SESSION['dbhost'],$_SESSION['dbuser'],$_SESSION['dbpwd'], true);
echo '<div id="stopload" style="display:inline;">';
echo __('Please wait while the gedcom load completes. If the load appears to be taking too long you can stop it using the button below. You will then need to remove the partially loaded tree.', 'rpress_main') . '<br/>';
if(ini_get('safe_mode'))
       echo __('NOTE: this site has php configured in safe mode. The allotted maximum execution time may not be sufficient for large trees and may cause php errors.', 'rpress_main') . '<br/>';
echo '<p id="elapsed" style="font-style: italic;">0% complete</p>';
echo '<form><input class="button-primary" type="button" onclick="confirmation(' . "'" . $_SERVER['REQUEST_URI'] .  "'" . ');" value="Stop load process"></form>';
echo '</div>';
//echo str_repeat(' ', ini_get('output_buffering'));
//ob_flush();
//flush();
$progress = 0;
$finish = false;
$max_exec_time = ini_get('max_execution_time');  //save max execution time for restoration later

if( !ini_get('safe_mode') ) set_time_limit (10*60); //set time limit to 10 minutes for safety
$localmax = ini_get('max_execution_time')-2; //set local max to prevent time out errors
  ?>
<script type="text/javascript">
function offFunction()
{
    document.getElementById('stopload').style.display = 'none';
}

function showFunction(done)
{
    document.getElementById('elapsed').innerHTML = done;
}

</script>
<?php
if ($handle) {
    $start_time = microtime_float();
    data_manage('open', $prefix);  //OPEN TEMPORARY FILES TO HOLD TEXT OUTPUT
    $temp = fread($handle, 16); //read first 16 characters from file to determine line termination
    rewind($handle);
    $end = line_end($temp);
    $fullsize = filesize($gedfile);
    $thismax = $fullsize/10;
    while (($line = my_stream_get_line($handle, 1024, $end) ) !== false && $finish == false) {
       $current = ftell($handle);
       if($current>$thismax) {
         $done = round(($thismax/$fullsize), 2)*100 . '% complete';
       ?><script type="text/javascript">showFunction(' <?php echo $done ?>');</script>
       <?php
       ob_flush();
       flush();
         $thismax = $thismax+($fullsize/10);
       }
       $timer = microtime_float()-$start_time;
       if($timer>$localmax) {
          if( !ini_get('safe_mode')  && $max_exec_time != null ) set_time_limit ($max_exec_time);  //restore max execution time if changed prior
          ?><script type="text/javascript">offFunction();</script>
          <?php
          return -1;
       }

      $line_count++;
      if ($line_count == 1) ltrim($line, "\0");   //remove strange characters from front (maybe RM only)
      if (substr($line,0 ,3)!= '***')  { //Ignore comments line

      $parsed = array();  //to receive parsed content
      parse2($line, $line_count, $parsed);
      $old_level = $level; //save current level
      $level = $parsed['level'];
      $tag = $parsed['tag'];
      $xref = $parsed['xref'];
      $xref = trim($xref,'@');  // remove @ at start and end

      if ($level == 0) {
      if ($tag == 'TRLR') $finish = true;
      if($tag == 'NOTE') $tag = 'ZNOTE';

//      if ($tag != '_PLAC') {

        if($tag == '_PLAC') $tag = 'ZPLAC';
        if($tag == 'SOUR') $tag = 'ZOUR';
        if($tag == 'OBJE') $tag = 'ZOBJE'; 
        if($tag == 'REPO') $tag = 'ZREPO';

        $fields[$tag . '_XREF_'] = $xref; //store new level 0 tag for output

      }  //end if level 0
      
//form composite name
      $tag_index = (int)$level;
      $tag_array[$tag_index] = $tag;
      $old_name = $name;
      $name = '';
      $max =  $tag_index+1;
      for ($i = 0; $i<$max; $i++) {
            $name = $name . $tag_array[$i] . '_';
      }
//place in bucket
//For those items that have a value following the lead tag, we need to adjust the data ( eg SOUR tag)
//replace the line n SOUR value with:
// n SOUR internal xref
// n+1 YSOUR value
//assume that any following CONT/CONC are at level n+1
$output = array();
   if($tag == 'SOUR' || $tag == 'ZNOTE' || $tag == 'NOTE' || $tag == 'OBJE' && $parsed['content'] != '') {
/** These elements can have a data value following the tag with possible continuation
** The value is placed in a specially created YAUX tag subordinate to the NOTE
**/
     $res = accumulator($tag, $name,'', $output);
     bucketizer($xref, $tag, $level, $name);
     $res = accumulator('YAUX', $name . 'YAUX_',$parsed['content'], $output);
     bucketizer($xref, 'YAUX', ($level+1), $name . 'YAUX_');
     } else {
     $res = accumulator($tag, $name,$parsed['content'], $output);
     bucketizer($xref, $tag, $level, $name);
  }

 } //End if comment check

}

    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($handle);
    $retcode = data_manage('close', $prefix);  //close temporary data files and load data to mysql
    if( !ini_get('safe_mode')  && $max_exec_time != null ) set_time_limit ($max_exec_time);  //restore max execution time if changed prior
?><script type="text/javascript">offFunction();</script>
 <?php
}
   return $retcode;
    }
?>