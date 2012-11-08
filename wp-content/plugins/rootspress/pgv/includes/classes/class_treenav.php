<?php
/**
* Class file for the tree navigator
*
* Code heavily based on phpgedview
* (@version $Id: class_treenav.php 6879 2010-01-30 11:35:46Z fisharebest $)
*/
require_once PGV_ROOT.'includes/classes/class_person.php';
require_once PGV_ROOT.'includes/classes/class_pressdb.php';
require_once PGV_ROOT.'includes/functions/functions_rp.php';
require_once PGV_ROOT.'includes/functions/functions_UTF8.php';
require_once PGV_ROOT.'includes/functions/functions_tn.php';
global $locale;

class TreeNav {
	var $rootPerson = null;
	var $ged_id = null;
	var $bwidth = 170; //minimum box width
	var $zoomLevel = 0;
	var $name = 'nav';
	var $generations = 4;
	var $allSpouses = true;
	var $images = true;
	var $xlocale = null;

	/**
	* Tree Navigator Constructor
	* @param string $rootid the rootid of the person
	* @param int $zoom The starting zoom level
	*/
	function __construct($rootid='', $ged_id, $name='nav', $zoom=0, $locale = '', $tn_options) {
          global $GEDCOM, $PGV_MIN_VIEW_HEIGHT, $tn_options, $locale;
          $_SESSION['locale'] = $locale;
          $this->xlocale = $locale;

//SET UP LOCALIZATION
/*$lang = $locale;
if($lang != null) {
$ret=putenv("LANG=$lang");
$ret=setlocale(LC_ALL, $lang);
if(!$ret) {
//   echo '<strong>Failure to set help locale for language ' . $lang . '. Default settings will be used.</strong><br/>';
    }
}
$domain = 'rpress_ext';
$fullpath=bindtextdomain($domain, "../localization/ext/");
//echo $fullpath;
bind_textdomain_codeset($domain, 'UTF-8');
$ret=textdomain($domain);
if(!$ret) echo 'textdomain failed';
 */


		if ($rootid!='none') {
			$zoom=2;    //INITIAL ZOOM VALUE
			$this->zoomLevel = $zoom;
		//	rp_connect(); //connect to rootsPress database
			$tmp=array();
                        $tmp['ged_id'] =  $GEDCOM;
			$tmp['xref'] = $rootid;
                        $this->rootPerson = Person::getInstance('i', $tmp);

			if (is_null($this->rootPerson)) $this->rootPerson = new Person('');
		}
		$this->name = $name;
		//-- handle AJAX requests
		if (!empty($_REQUEST['navAjax'])) {
			//-- embedded tree for mashups
			if ($_REQUEST['navAjax']=='embed') {
				global $SERVER_URL;
				global $stylesheet;
				?>
				document.writeln('<link rel="stylesheet" href="<?php print $SERVER_URL.$stylesheet; ?>" type="text/css" media="all" />');
                                document.writeln('<script type="text/javascript" src="<?php print $SERVER_URL; ?>/js/phpgedview2.js"></script>');
				<?php
				ob_start();
				$w = $_GET['width'];
				$h = $_GET['height'];
//				if($h<$PGV_MIN_VIEW_HEIGHT) $h=$PGV_MIN_VIEW_HEIGHT;
				if (!empty($w)) $w.="px";
				if (!empty($h)) $h.="px";
				$this->drawViewport($rootid, $w, $h);
				$output = ob_get_clean();
				$lines = preg_split("/\r?\n/", $output);
				foreach($lines as $line)
					print "document.writeln('".str_replace("'", "\\'", $line)."');\n";
				exit;
			}
			if (isset($_REQUEST['allSpouses'])) {
				if ($_REQUEST['allSpouses']=='false' || $_REQUEST['allSpouses']==false) $this->allSpouses = false;
				else $this->allSpouses = true;
			}
			if (!empty($_REQUEST['details'])) {
				$this->getDetails($this->rootPerson);
			}
			else if (!empty($_REQUEST['newroot'])) {
				if (!empty($_REQUEST['drawport'])) $this->drawViewport('', "", "150px");
				else {
					$fam = null;
					if ($this->allSpouses) $this->drawMain($this->rootPerson, 4, 0);
					else $this->drawAncestor($this->rootPerson, 4, 0, $fam);
				}
			}
			else if (!empty($_REQUEST['parent'])) {
				$person = $this->rootPerson;
				if ($_REQUEST['parent']=='f') {
					$cfamily = $person->getPrimaryChildFamily();
					if (!empty($cfamily)) {
						$father = $cfamily->getHusband();
						if (!empty($father)) {
							$fam = null;
							$this->drawAncestor($father, 2, 1, $fam);
						}
						else print "<br />\n";
					}
					else print "<br />\n";
				}
				else {
					$spouse = $person->getCurrentSpouse();
					if (!empty($spouse)) {
						$cfamily = $spouse->getPrimaryChildFamily();
						if (!empty($cfamily)) {
							$mother = $cfamily->getHusband();
							if (!empty($mother)) {
								$fam = null;
								$this->drawAncestor($mother, 2, 1, $fam);
							}
							else print "<br />\n";
						}
						else print "<br />\n";
					}
					else print "<br />\n";
				}
			}
			else {
				$fams = $this->rootPerson->getSpouseFamilies();
				$family = end($fams);
				if (!$this->allSpouses) $this->drawChildren($family, 2);
				else $this->drawChildren($this->rootPerson, 2);
			}
			exit;
		}
	}

	/**
	* Draw the view port which creates the draggable/zoomable framework
	* @param string $id an id to use for the starting HTML elements
	* @param string $width the width parameter for the outer style
	* @param string $height the height parameter for the outer style
	*/
	function drawViewport($id='', $width='', $height='') {
                global $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM, $SERVER_URL, $pgv_lang, $RP_TREE_NAME, $RP_ROOT_PAGE, $PGV_BASE, $PGV_BG, $locale;
		if (empty($id)) $id = $this->rootPerson->getXref();
		$widthS = "";
		$heightS = "";
		if (!empty($width)) $widthS = "width: $width; ";
		if (!empty($height)) $heightS = "height: $height; ";
		?>
		<?php $this->setupJS(); ?>
		<div id="out_<?php print $this->name; ?>" dir="ltr" style="position: relative;  background-color: inherit; <?php print $widthS.$heightS; ?>text-align: center; overflow: hidden;" title="<?php echo $RP_TREE_NAME. " tree: ". $this->rootPerson->getFullName(); ?>">
                        <h3 id="headertxt" style="position: absolute; left: 0px; top: 5px; z-index: 50;"><?php echo $this->rootPerson->getFullName() ?></h3>
                        <div id="in_<?php print $this->name; ?>" style="position: relative; background-color: inherit; left: -20px; width: auto; cursor: move;" onmousedown="dragStart(event, 'in_<?php print $this->name; ?>', <?php print $this->name; ?>);" onmouseup="dragStop(event);">
			<?php $parent=null;

			if (!$this->allSpouses) $this->drawAncestor($this->rootPerson, $this->generations, 0, $parent);
			else $this->drawMain($this->rootPerson, $this->generations, 0);?>
			</div>
			<div id="controls" style="position: absolute; left: 0px; top: 0px; z-index: 100; display:inline;">
                        <ul style="list-style-type: none; padding:0; margin:0; border: none">
<li><a href="#" onclick="<?php print $this->name; ?>.zoomIn(); return false;"><img src="<?php print $SERVER_URL.$PGV_IMAGE_DIR."/".$PGV_IMAGES['zoomin']['other'];?>" class="rp_icons_tn"  style="background-color: transparent; border: none;"  <?php echo 'title="' . _( 'Zoom in'  ) . '"'; ?> alt="zoomin" /></a></li>
<li><a href="#" onclick="<?php print $this->name; ?>.zoomOut(); return false;"><img src="<?php print $SERVER_URL.$PGV_IMAGE_DIR."/".$PGV_IMAGES['zoomout']['other'];?>" class="rp_icons_tn"  style="background-color: transparent;  border: none;" <?php echo 'title="' . _( 'Zoom out'  ) . '"'; ?> alt="zoomout" /></a></li>
<li><a href= <?php print $PGV_BASE . "?page_id=" . $RP_ROOT_PAGE . "&ged=" . $GEDCOM . "&mode=indx ";?> ><img src="<?php print $SERVER_URL.$PGV_IMAGE_DIR."/".$PGV_IMAGES['index']['small'];?>" class="rp_icons_tn"  style="background-color: transparent;  border: none;" <?php echo 'title="' . _( 'Index'  ) . '"'; ?> alt="" /></a></li>
<li><a href= <?php print $PGV_BASE . "?page_id=" . $RP_ROOT_PAGE . "&ged=" . $GEDCOM . "&mode=home ";?> ><img src="<?php print $SERVER_URL.$PGV_IMAGE_DIR."/".$PGV_IMAGES['home']['small'];?>"class="rp_icons_tn"  style="background-color: transparent;  border: none;" <?php echo 'title="' . _( 'Home'  ) . '"'; ?> alt="" /></a></li>
<li><a href='#' onclick="<?php print $this->name; ?>.eCenter(); return false;"><img id="c_nav" src="<?php print $SERVER_URL.$PGV_IMAGE_DIR."/".$PGV_IMAGES['red_circle']['other'];?>" class="rp_icons_tn"   style="background-color: transparent ;  border: none;" alt="<?php echo 'box_'.$this->rootPerson->getXref(); ?>"  title="<?php printf(_('Center on %s'), $this->rootPerson->getFullName()); ?>" /></a></li>
<li><a href="#" onclick="TINY.box.show({url:'../wp-content/plugins/rootspress/pgv/help/help.php?id=201&amp;locale=<?php echo $locale; ?>',width:650,height:650,opacity:50,topsplit:2, close:true})" >
     <img src="<?php print $SERVER_URL.$PGV_IMAGE_DIR."/".$PGV_IMAGES['help']['small'];?>" class="rp_icons_tn"   style="background-color: transparent;  border: none;" <?php echo 'title="' . _( 'Help' ) . '"'; ?> alt="help" /</a></li>
<li><img id="<?php print $this->name; ?>_loading" src="<?php print $SERVER_URL; ?>images/loading.gif" style="display: none;" alt="Loading..." /></li>
                        </ul>
			</div>
		</div>
		<script type="text/javascript">
		<!--
		var <?php print $this->name; ?> = new NavTree("out_<?php print $this->name; ?>","in_<?php print $this->name; ?>", '<?php print $this->name; ?>', '<?php print $id; ?>', '<?php print $GEDCOM; ?>' );
		<?php print $this->name; ?>.zoom = <?php print $this->zoomLevel; ?>;
		<?php print $this->name; ?>.center();

                 window.onload = <?php print $this->name; ?>.eCenter('root');
		//-->
		</script>
		<?php
	}

	/**
	* Setup the JavaScript for the tree navigator
	*/
	function setupJS() {
		global $SERVER_URL;

		?>
	<script type="text/javascript" src="<?php print $SERVER_URL; ?>js/treenav.js"></script>
	<script type="text/javascript">
	<!--
		var myrules = {
		'#out_<?php print $this->name; ?> .person_box' : function(element) {
			element.onmouseout = function() {
				if (<?php print $this->name; ?>.zoom>=-2) return false;
				return nd(); // hide helptext
			}
			element.onmouseover = function() { // show helptext
				if (<?php print $this->name; ?>.zoom>=-2) return false;
				bid = element.id.split("_");
				if (<?php print $this->name; ?>.opennedBox[bid[1]]) return false;
				helptext = this.title;
				if (helptext=='') helptext = this.value;
				if (helptext=='' || helptext==undefined) helptext = element.innerHTML;
				this.title = helptext; if (document.all) return; // IE = title
				this.value = helptext; this.title = ''; // Firefox = value
				// show images
				helptext=helptext.replace(/display: none;/gi, "display: inline;");
				return overlib(helptext, BGCOLOR, "#000000", FGCOLOR, "#FFFFE0");
			}
		},
		'.draggable' : function(element) {
			new Draggable(element.id, {revert:true});
		}
		}
		Behaviour.register(myrules);
		/* not used yet
		function dragObserver() {
			this.parent = null;
			this.onEnd = function(eventName, draggable, event) {
				this.parent.appendChild(draggable.element);
				<?php print $this->name; ?>.collapseBox = false;
			}
			this.onStart = function(eventName, draggable, event) {
				this.parent = draggable.element.parentNode;
				document.body.appendChild(draggable.element);
			}
		}
		Draggables.addObserver(new dragObserver());
		*/
	//-->
	</script>
		<?php
	}

	/**
	* Get the details for a person and their spouse
	* @param Person $person the person to print the details for
	*/
	function getDetails(&$person) {
		global $pgv_lang, $factarray, $tn_persbox0, $SHOW_ID_NUMBERS, $USE_SILHOUETTE, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM, $SERVER_URL;
		global $TEXT_DIRECTION, $RP_TREE_NAME, $RP_ROOT_PAGE, $tn_options;
                $this->_local();
                $tn_persbox = $tn_persbox0 . (10 + $this->zoomLevel) . "px;" . " width: " . ($this->bwidth+($this->zoomLevel*18)) . "px; direction: " . $TEXT_DIRECTION . "; " ;
		if (empty($person)) $person = $this->rootPerson;
                $famid = 'none';
		if (!empty($_REQUEST['famid'])) $famid = $_REQUEST['famid']; 
		$name = $person->getFullName();
		if ($SHOW_ID_NUMBERS)
		$name .=" (".$person->getXref().")";

		?>
		<span class="name1">
		<?php $thumb = $this->getThumbnail($person); 
		if (!empty($thumb)) {
			echo $thumb;
		} else if ($USE_SILHOUETTE && isset($PGV_IMAGES["default_image_U"]["other"])) {
                        $class = "portrait";
			if ($TEXT_DIRECTION == "rtl") $class .= "_rtl";
			$sex = $person->getSex();
			$thumbnail = "<img src=\"";
			if ($sex == 'F') {
				$thumbnail .= $SERVER_URL.$PGV_IMAGE_DIR."/".$PGV_IMAGES["default_image_F"]["small"];
			}
			else if ($sex == 'M') {
				$thumbnail .= $SERVER_URL.$PGV_IMAGE_DIR."/".$PGV_IMAGES["default_image_M"]["small"];
			}
			else {
				$thumbnail .= $SERVER_URL.$PGV_IMAGE_DIR."/".$PGV_IMAGES["default_image_U"]["small"];
			} 
			$thumbnail .="\" class=\"".$class."\" border=\"none\" alt=\"\" />";
			echo $thumbnail;
		} ?>

		<a href="<?php print $person->getLinkUrl(); ?>" onclick="if (!<?php print $this->name;?>.collapseBox) return false;"><?php print PrintReady($name); ?></a>
                <img src="<?php print $SERVER_URL.$PGV_IMAGE_DIR."/".$PGV_IMAGES["gedcom"]["small"];?>" class="gedicon" onclick="<?php print $this->name;?>.newRoot('<?php print $person->getXref();?>', '<?php print $person->getFullName();?>' , <?php print $this->name;?>.innerPort, '<?php print htmlentities($GEDCOM,ENT_COMPAT,'UTF-8'); ?>' , '<?php print $tn_options ?>'); " />
                </span><br />
		<div style="$tn_persbox">
				<?php rp_print_fact(_('Born'), $person->getBirthDate(), $person->getBirthPlace()); ?>
				<br />
				<?php if(isset($famid)  && $famid != 'none') {
                                  $family = Family::getInstance('f', $famid);
                                rp_print_fact(_('Married'), $family->getMarriageDate(), $family->getMarriagePlace());
                                ?>
				<br />
				<?php
				}
                                rp_print_fact(_('Died'), $person->getDeathDate(), $person->getDeathPlace()); ?>
				<br />

		</div>

		<?php
	}

	/**
	* Draw all of the children for a person
	* @param Person $person The person to draw the children for
	* @param int $gen The number of generations of descendents to draw
	*/
	function drawChildren(&$person, $gen=2, $family) {
          		global $PGV_IMAGE_DIR, $PGV_IMAGES, $SERVER_URL;
          		if($family == null) return;
              $gen=2;    //force draw of all children
		if (!empty($person) && $gen>0) {
			$children = $family->getChildren();
			foreach($children as $ci=>$child) {
		           $dup = is_dup($child->getXref());
		           $famx = $child->getSpouseFamilies();
		           $multi = 0;
			foreach((array)$famx as $famid=>$family) {
                          $this->drawMain($child, ($gen-1), -1, $multi, $dup, $family);
                          $multi++;
                          }
                        if (empty($famx)) $this->drawMain($child, ($gen-1), -1, null, $dup, null);
			}
		}
	}
	
	function drawRoot(&$person, $gen=2) {
          		global $PGV_IMAGE_DIR, $PGV_IMAGES, $SERVER_URL;
          		if($person == null) return;
          		$families=$person->getSpouseFamilies();
          		if($families==null)  $this->drawMain($person, ($gen-1), -9, 0, null, null);
          		$m=0;
               		$temp = array();
          		foreach($families as $fam) {
                        $this->drawMain($person, ($gen-1), -8, $m, null, $fam);
                          if($m==0) $sfx2=null;
                            else $sfx2='@'.$m;
                        $temp[] = $person->getXref().$sfx2;
                        $m++;
                        }
			if($temp == null)  return;
			
			$cfamily = $person->getPrimaryChildFamily();  //get family of which this person is a child
			if (!empty($cfamily)) {
				$father = $cfamily->getHusband();
				if (empty($father)) $father = $cfamily->getWife();
			}
                        
                        if ($temp[0] !=  end($temp)){
                             $lineid = 'pline_' . $temp[0] . '_' . end($temp);
                             $this->draw_vline($lineid);
                             if(sizeof($temp) >1)  $this->draw_hline(null, $lineid);
                          } else {
                            if (empty($cfamily))return;
                     //        $lineid = 'gline_' . $temp[0] . '_' . $father->getXref();
                     //        $lineid = 'gline_' . $father->getXref(). '_' . $temp[0];
                             $lineid = 'pline_' . $temp[0]. '_' .$father->getXref();
                             $this->draw_vline($lineid, 'bvert');   //appears to cause a redundant line between child and parent
                             if(sizeof($temp) >1)  $this->draw_hline(null, $lineid);
                          }
            return;
		}

	/**
	* Get the thumbnail image for the given person
	*
	* @param Person $person
	* @return string
	*/
	function getThumbnail(&$person) {
                global $USE_SILHOUETTE;
                if ($USE_SILHOUETTE) return null;
                $pfile = $person->getXref() . '.jpg';
                $pname = $person->getFullName();
                $sex =   $person->getSex();
                $thumbnail = rp_portrait_tree($pfile, $pname, $sex, true);
                return $thumbnail;
	}
	
function _local() {
  //SET UP LOCALIZATION
$lang = $this->xlocale;
if($lang != null) {
$ret=putenv("LANG=$lang");
$ret=setlocale(LC_ALL, $lang);
if(!$ret) {
//   echo '<strong>Failure to set help locale for language ' . $lang . '. Default settings will be used.</strong><br/>';
    }
}
$domain = 'rpress_ext';
//$fullpath=bindtextdomain($domain, "E:/xampp/htdocs/wordpress/wp-content/plugins/rootspress/localization/ext/");
$fullpath=bindtextdomain($domain, "../wp-content/plugins/rootspress/localization/ext/");
//echo $fullpath;
bind_textdomain_codeset($domain, 'UTF-8');
$ret=textdomain($domain);
return;
}


	/**
	* Draw a person for the chart but include all of their spouses instead of just one
	* @param Person $person The Person object to draw the box for
	* @param int $gen The number of generations up or down to print
	* @param int $state Whether we are going up or down the tree, -1 for descendents +1 for ancestors
	*/
	function drawMain(&$person, $gen, $state, $multi='', $dupch = false, $family= null) {
		global $SHOW_ID_NUMBERS, $PGV_IMAGE_DIR, $PGV_IMAGES, $TEXT_DIRECTION, $SERVER_URL, $PGV_BOXSPACING, $PGV_HLENGTH;
                global $tn_table, $tn_table2, $tn_vline, $tn_hline, $tn_box, $tn_bigbox, $tn_persbox0;
          $this->_local();
          $newfamily = $family;
          $cspouse=null;          
          if($family != null) $cspouse =  $family->getSpouse($person);

                if($multi == '0' || $family == null) $sfx = null;
                   else $sfx = '@'.$multi;

                $tn_persbox = $tn_persbox0 . (10 + $this->zoomLevel) . "px;" . " width: " . ($this->bwidth+($this->zoomLevel*18)) . "px; direction: " . $TEXT_DIRECTION . "; " ;
                if ($gen<0) {
			return;
		}
		if ($this->zoomLevel < -2) $style = "display: none;";
		else $style = "width: ".(10+$this->zoomLevel)."; height: ".(10+$this->zoomLevel).";";
		if (empty($person)) $person = $this->rootPerson;
		if (empty($person)) return;

		$mother = null;
		$father = null;

		if ($state==0) {
			$cfamily = $person->getPrimaryChildFamily();  //get family of which this person is a child
			if (!empty($cfamily)) {
				$father = $cfamily->getHusband();
				if (empty($father)) $father = $cfamily->getWife();
			}

		}

		?>
		<table class="tn_table" style="<?php print $tn_table; ?>" >
			<tbody>
				<tr>
					<?php /* print the children */
					if ($state<=0) {
					?>
					<td class="tn_bigbox" id="ch_<?php print uniqid();?>"  style="<?php print $tn_bigbox; ?>"  >
                                        <?php if($state==0) $this->drawRoot($person, $gen);
                                          else
                                          if ($dupch !== true) $this->drawChildren($person, $gen, $family);   
                                          if ($dupch === true) {
                                            _drawNote($person, '<i>' .  _( 'Click here to see this family continued elsewhere on the chart'  ) . '</i>', $this->zoomLevel, $this->bwidth);
                                            $this->draw_hline();
                                          }
                                            ?>
					</td>
					<?php

                              if ($dupch !== true) {

//This is the case where there is one child but that child has multiple spouses
$famx = null;
if($newfamily != null)
if(_getNumberChildrenFam($newfamily) ==1) {
$children = $family->getChildren();
$child=$children[0];
$famx = $child->getSpouseFamilies();
}
$num =  _getNumberChildrenFam($family);

                                        if ($num >0 || sizeof($famx)>1) {
//* Build the vline on the right joining first and last children
                              if($state<0)   {
                                     $lineid=draw_vertline($person, $sfx);
                                     if($num >0) $this->draw_hline(null, 'hline_' . $lineid);  //Build the hline connecting a child to the parent vertical line
                                          }
                                   }
			}
                }  //end if dupch !true

/* print the person */
if($state == -8 && $family == null ) {
          $cspouse = Person::getInstance('x', uniqid());
}

                       if($state==0 && $family != null)  {
                          $cspouse =  $family->getSpouse($person);
//* Build the id for the vline on the right joining first and last children
          		$families=$person->getSpouseFamilies();
          		$m=0;
               		$temp = array();
          		foreach($families as $fam) {
                          if($m==0) $sfx2=null;
                            else $sfx2='@'.$m;
                        $temp[] = $person->getXref().$sfx2;
                        $m++;
                        }
//* Build the vline on the right joining first and last children
			if($temp!= null)
 $lineid = 'cline_' . $temp[0] . '_' . end($temp);
 echo $this->draw_vline($lineid, 'cvertline');

$num =  _getNumberChildrenFam($family);
 }


if($state<0) {

                                           $tmp2 = '';
                                           if($dupch === true) $tmp2='x';
                                           $line_id = null;
                                           if (!empty($cspouse)) {
                                            $line_id = 'pline_'.$person->getXref().$sfx.$tmp2;
                                            $tmp2a = '';
                                            if(is_dup($cspouse->getXref(), false)===true) $tmp2a='x';
                                            $line_id .= '_'.$cspouse->getXref().$tmp2a;
                                            $this->draw_vline($line_id, 'pvertline');
                                             }

 if ($state < 0)  {
  ?>
					<td class="tn_box" style="<?php print $tn_box; ?>">
                                 <table style="<?php print $tn_table; ?>"><tbody><tr>

                              <?php  /* This code adds a pseudo spouse if the person is root, with no parents and no existing spouse */
                                     if ($state == -8 && $cspouse == null) {
                                                if ($person->getPrimaryChildFamily() == null) {
                                                  $cspouse = Person::getInstance('x', uniqid());
                                                  $line_id = 'pline_'.$person->getXref();
                                                  $line_id .= '_'.$cspouse->getXref();
                                                  $this->draw_vline($line_id, 'pvertline');
                                                            }
                                                        }        ?>


                                 <?php if ($cspouse != null) $this->draw_hline();
                                       if($multi>0) $opacity = .8;
                                         else $opacity = 1;
                                       ?>

                                 <?php  _drawPerson($person, $family, $dupch, $multi, $this->zoomLevel, $this->bwidth);    ?>

                                                <?php 

                                                $myfam = $person->getPrimaryChildFamily();
                                                if($multi>0) $this->draw_hline('dotted');
                                                      else {
                                                        $lineid = 'zhline_' . $person->getXref() . '_' .   $person->getXref();
                                                        if (!empty($myfam)) $this->draw_hline(null, null);      //draws hline from single child to parent vline
                                                             else $this->draw_hline('blank');
                                                      }

                                                      ?>
                                                </tr></tbody></table>

                                                <?php  /* Draw spouse */
                                                if ($cspouse != null) {
                                                    $tmp2 = '';
                                                $dupsp = is_dup($cspouse->getXref());
                                                  ?>
                                                <table style="<?php print $tn_table; ?>"><tbody><tr><?php $this->draw_hline(); ?>

                                               <?php  _drawPerson($cspouse, $family, $dupsp, null, $this->zoomLevel, $this->bwidth);
                                                       $this->draw_hline('blank'); ?>
                                                                                              
                                                </tr></tbody></table>
                                                <?php } ?>

					</td>
					<?php  
                                                }
                                         }

					/* print the father */
				        if(!empty($father)) $mother = $cfamily->getSpouse($father);
					if ($state==0 && (!empty($father) || !empty($mother))) {
//THIS SECTION ONLY USED WHEN STATE==0 SINCE STATE>0 PROCESSED BY drawAncestor

if(empty($father)) $father = Person::getInstance('x', uniqid());
if(empty($mother)) $mother = Person::getInstance('x', uniqid());
$xfamily = $person->getPrimaryChildFamily(); //pass this to drawAncestor for both parents
						$lineid = "pline_";
						if (!empty($father)) $lineid.=$father->getXref();
						$lineid.="_";
						if (!empty($mother)) $lineid.=$mother->getXref();
                                                if (!empty($father) && (!empty($mother))) {
                                                  $this->draw_vline($lineid);
                                                }
                                        ?><td class="tn_bigbox" style="<?php print $tn_bigbox; ?>" >
						<table class="tn_table"  style="<?php print $tn_table; ?>" >
							<tbody>
								<tr>
									<?php /* there is a IE JavaScript bug where the "id" has to be the same as the "name" in order to use the document.getElementsByName() function */ ?>
									<td class="tn_bigbox" style="<?php print $tn_bigbox; ?>" >
										<?php if (!empty($father)) $this->drawAncestor($father, $gen-1, 1, $xfamily); else print "<br />\n";?>
									</td>
								</tr>
								<?php
								{
								
									if (!is_null($mother)) {
								?>
								<tr>
									<td class="tn_bigbox" style="<?php print $tn_bigbox; ?>" >
                                                                                <?php if (!empty($mother)) $this->drawAncestor($mother, $gen-1, 1, $xfamily); else print"<br />\n"; ?>
                                                                        </td>
								</tr>
								<?php } } ?>
							</tbody>
						</table>
					</td>
					<?php } ?>
				</tr>

			</tbody>
		</table>
		<?php
	}

function draw_hline($line=null, $id=null)  {
  		global $PGV_IMAGE_DIR, $PGV_IMAGES, $SERVER_URL, $PGV_HLENGTH, $PGV_LINE;
                global $tn_hline;
                $bwidth = 1;
//                $height = 2;
                $color = $PGV_LINE;
                $bkgnd = $PGV_LINE;
                $opacity = '1';

echo '<td class="tn_hline" '. '" style="' . $tn_hline. '" >';
                if($line==null) $line='solid';
//blank line fills in space for spouses
                if ($line == 'blank') {
                    $bwidth = 0;
                    $color = 'transparent';
                  }
                 if ($line == 'dotted') {
                    $opacity = '.5';
                 }

if ($id == null) $idfield = null;
  else $idfield = 'id="' . $id . '"';
//echo '<hr ' . $idfield . ' style="height: ' . $height . 'px; padding: 0px; margin: 0px; border-width: ' . $bwidth . 'px; border-color: ' . $color . ' ; border-style: ' . $color . ' ; opacity: ' . $opacity . '; background-color: ' . $color . ';"  width="' . $PGV_HLENGTH . '">';
echo '<hr ' . $idfield . ' style="height: 2px; width: ' . $PGV_HLENGTH . 'px; padding: 0px; margin: 0px; border-width: ' . $bwidth . 'px; border-color: ' . $color . ' ; border-style: ' . $color . ' ; opacity: ' . $opacity . '; background-color: ' . $color . ';"  >';

echo '</td>';
}

function draw_vline($lineid, $name='pvertline')  {
  if($lineid == null) echo 'NULL LINE ID VLINE<br />';
  		global $SHOW_ID_NUMBERS, $PGV_IMAGE_DIR, $PGV_IMAGES, $TEXT_DIRECTION, $SERVER_URL, $PGV_BOXSPACING, $PGV_HLENGTH, $PGV_LINE;
                global $tn_table, $tn_table2, $tn_vline, $tn_hline, $tn_box, $tn_bigbox, $tn_persbox;
echo '<td class="tn_vline" style="' . $tn_vline. ';">';
$style = "position: absolute; padding: 0px; vertical-align: middle; width: 0px; border-width: 1px; border-style: solid; border-color: " . $PGV_LINE . " ; top: 0px; height: 0px;";
echo '<div' . ' id="' . $lineid. '" name="' . $name. '"' . ' style=" ' . $style . '"></div>';
echo '</td>';
}

	/**
	* Draw a person for the chart
	* @param Person $person The Person object to draw the box for
	* @param int $gen The number of generations up or down to print
	* @param int $state Whether we are going up or down the tree, -1 for descendents +1 for ancestors
	* @param Family $pfamily
	*/
	function drawAncestor(&$person, $gen, $state, $fam=null) {
		global $SHOW_ID_NUMBERS, $PGV_IMAGE_DIR, $PGV_IMAGES, $TEXT_DIRECTION, $SERVER_URL, $PGV_BOXSPACING, $PGV_HLENGTH;
                global $tn_table, $tn_table2, $tn_vline, $tn_hline, $tn_box, $tn_bigbox, $tn_persbox0;
                $tn_persbox = $tn_persbox0 . (10 + $this->zoomLevel) . "px;" . " width: " . ($this->bwidth+($this->zoomLevel*18)) . "px; direction: " . $TEXT_DIRECTION . "; " ;
//ASSUME THIS FUNCTION ONLY CALLED WHEN STATE >0
		$gen++;
		if ($gen<0) {
			return;
		}
		if ($this->zoomLevel < -2) $style = "display: none;";
		else $style = "width: ".(10+$this->zoomLevel)."; height: ".(10+$this->zoomLevel).";";
		if (empty($person)) $person = $this->rootPerson;
		if (empty($person)) return;
		$mother = null;
		$father = null;

		if ($state>=0) {
                  $myfam = $person->getPrimaryChildFamily();
                  if(!empty($myfam)) $father = $myfam->getHusband();
                  if(!empty($myfam)) $mother = $myfam->getWife();
		}

		?>
		<table class="tn_table" style="<?php print $tn_table2; ?>" >
			<tbody>
				<tr>
					<?php
					if ($state>0) {
                                           $this->draw_hline();	  //hline joining parents to vline in ancsetor section
                                           }

                                        /* print the person */ ?>
                                       <?php  echo '<td class="tn_bigbox"'.' style="'.$tn_bigbox.';">';
                                        $dup = is_dup($person->getXref(), true);
                                       _drawPerson($person, $fam, $dup, null, $this->zoomLevel, $this->bwidth);
                                       echo '</td>';
                                       if($dup ===true) {
                                          
               echo '<td class="tn_hline" '. '" style="' . $tn_hline. '" >';

                                      $this->draw_hline();
                                      _drawNote($person, '<i>' .  _( 'Click here to see this family continued elsewhere on the chart'  ) . '</i>', $this->zoomLevel, $this->bwidth);
                                       }

                                         if($dup !==true) {
                                         /* print the father */
					if ($state>=0 && (!empty($father) || !empty($mother))) {
if($father == null) $father = Person::getInstance('x', uniqid());
if($mother == null) $mother = Person::getInstance('x', uniqid());

						$lineid = "pline_";
						if (!empty($father)) {
                                                  $lineid.=$father->getXref();
                                                  $dupch =  is_dup($father->getXref(), false);
                                                  if ($dupch) {
                                                    $lineid.= 'x';
                                                        }
                                                 }
						$lineid.="_";
						if (!empty($mother)) $lineid.=$mother->getXref();
						$dupsp =  is_dup($mother->getXref(), false);
                                                  if ($dupsp) $lineid.= 'x';
					        if (!empty($father) && (!empty($mother))) {
                                                    $this->draw_hline(null ,$lineid);  //hline to join child to parent vline in Ancestor section
                                                    $this->draw_vline($lineid);
                                                }
				     ?><td class="tn_bigbox"  style="<?php print $tn_bigbox; ?>">
						<table class="tn_table"  style="<?php print $tn_table; ?>" >
							<tbody>
								<tr>
									<?php /* there is a IE JavaScript bug where the "id" has to be the same as the "name" in order to use the document.getElementsByName() function */ ?>

                                          <td class="tn_bigbox"  style="<?php print $tn_bigbox; ?>" >

                                                                        	<?php if (!empty($father)) $this->drawAncestor($father, $gen-1, 1, $myfam); else print "<br />\n";?>
									</td>
								</tr>
								<tr>
								<?php /* print the mother */ ?>
									<td class="tn_bigbox"  style="<?php print $tn_bigbox; ?>" >
										<?php 
                                                                                if (!empty($mother)) $this->drawAncestor($mother, $gen-1, 1, $myfam); else print"<br />\n";?>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
<?php } //end of bypass if duplicate id ?>
					<?php } ?>
				</tr>
			</tbody>
		</table>
		<?php
	}
}

?>
