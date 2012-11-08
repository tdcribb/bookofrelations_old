<?php
function _drawPerson($person, $pfam, $dup, $multi, $thiszoom, $thisbwidth) {
  global $tn_persbox0, $tn_box, $tn_options;
     $tn_persbox = $tn_persbox0 . (10 + $thiszoom) . "px;" . " width: " . ($thisbwidth+($thiszoom*18)) . "px;" ;
     if($multi>0) {
       $opacity = .8;
       $sfx = '@'. $multi;
     } else {
       $opacity = 1;
       $sfx = null;
     }
     echo '<td class="tn_box" '. ' style="'.$tn_box  . '">';
     $tmp = '';
    if($dup === true) $tmp = 'x';
    if(isset($pfam)) $famid = $pfam->getXref();
              else $famid = 'none';
     if ($person->getType() == 'x') {
          echo '<div class="person_box" id="box_'. $person->getXref().$tmp.$sfx. '" style="'. $tn_persbox . '">';
     $name = '<i>Unknown</i>';
     } else {
          echo '<div class="person_box" id="box_'. $person->getXref().$tmp.$sfx. '" style="'. $tn_persbox . ' opacity: ' . $opacity  . ';" onclick="' . 'nav' . ".expandBox(this, '" . $person->getXref() . "','"  . $famid . "','"  . $tn_options .  "');" . '">';
     $name = $person->getFullName().' ('.$person->getBirthShort(). '-' . $person->getDeathShort().')';
     }
     print PrintReady($name);
     echo '</div>';
     echo '</td>';
      }
      


function _drawNote($person, $note, $thiszoom, $thisbwidth) {
  global $tn_persbox0, $tn_box;
     $tn_persbox = $tn_persbox0 . (10 + $thiszoom) . "px;" . " width: " . ($thisbwidth+($thiszoom*18)) . "px;" ;
     echo '<td class="tn_box" '. ' style="'.$tn_box  . '">';
     echo '<div class="person_box" style="'. $tn_persbox . '" onclick="' . 'nav' . ".dupId('" . 'box_'. $person->getXref() . "','c');" . '">';

     echo $note;
     echo '<br />';
     echo '</div>';
     echo '</td>';
     }

function _getNumberChildrenFam($family) {
if(!empty($family)) return  $family->getNumberOfChildren();
  else return 0;
}

function draw_vertline($person, $sfx) {
  		global $PGV_IMAGE_DIR, $PGV_IMAGES,$SERVER_URL, $PGV_LINE, $tn_vimage, $tn_vline;
			$fams = $person->getSpouseFamilies();
                        $k=0;
			foreach((array)$fams as $famid=>$family) {
                          if($k==0) $fsfx=null;
                             else $fsfx='@'.$k;
                             if($fsfx==$sfx) {
              			if(_getNumberChildrenFam($family) <1) return null;
              			$children = $family->getChildren();
               			$temp = array();
               			$m=0;
				foreach($children as $ci=>$child) {
                                   $famx = $child->getSpouseFamilies();
                                   if(sizeof($famx)>1) {
                                   $temp[] = $child->getXref();
                                   $temp[] = $child->getXref().'@'.(sizeof($famx)-1);
                                     }
                                   else $temp[] = $child->getXref();
                                   $m++;
				}
                             }
			  $k++;
			}
			if($temp!= null)
  $lineid = $temp[0] . '_' . end($temp);

  if($lineid == null) echo 'NULL LINE ID VLINE<br />';

echo '<td class="tn_vline" style="' . $tn_vline. ';">';
$name = 'cvertline';
$style = "position: absolute; padding: 0px; vertical-align: middle; width: 0px; border: 1px solid " . $PGV_LINE . "; top: 0px; height: 0px;";
echo '<div id="' . 'cline_' . $lineid. '" name="' . $name. '"' . ' style="' . $style . '"></div>';
echo '</td>';

  return $lineid;
}

?>