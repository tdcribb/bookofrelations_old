<?php
/**
* Class file for a person
*
* phpGedView: Genealogy Viewer
* Copyright (C) 2002 to 2009  PGV Development Team.  All rights reserved.
*
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*
* @package PhpGedView
* @subpackage DataModel
* @version $Id: class_person.php 6914 2010-02-04 12:21:25Z volschin $
*/

/*if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}    */

require_once 'includes/classes/class_pressdb.php';

class Person extends Pressdb {
	var $indifacts = array();
	var $otherfacts = array();
	var $globalfacts = array();
	var $mediafacts = array();
	var $facts_parsed = false;
	var $birthdate = '';
	var $birthplace = '';
	var $birthshort = '';
	var $deathdate = '';
 	var $deathplace = '';
	var $deathshort = '';
	var $fams = null;
	var $famc = null;
	var $spouseFamilies = null;
	var $childFamilies = null;
	var $label = '';
	var $highlightedimage = null;
	var $file = '';
	var $age = null;
	var $isdead = -1;   //************not used in rp
	var $sex=null;
	var $fullname=null;
	var $generation; // used in some lists to keep track of this Person's generation in that list

	// Cached results from various functions.
	private $_getBirthDate=null;
	private $_getBirthPlace=null;
	private $_getAllBirthDates=null;
	private $_getAllBirthPlaces=null;
	private $_getEstimatedBirthDate=null;
	private $_getDeathDate=null;
	private $_getDeathPlace=null;
	private $_getAllDeathDates=null;
	private $_getAllDeathPlaces=null;
	private $_getEstimatedDeathDate=null;

	// Create a Person object from either raw GEDCOM data or a database row
	function __construct($data, $simple=true) {
          global $RP_PORTRAITS, $GEDCOM;
		if (is_array($data)) {
			// Construct from a row from the database

			if (array_key_exists('indi_sex', $data)) $this->sex   =$data['indi_sex'];
                        $this->fullname = str_replace('/', '', $data['indi_fullname']);
                        $tmp = rp_getone_event($data['indi_gedcomnumber'], $GEDCOM, 'BIRT');
                        $this->birthdate = $tmp['event_date'];
                        $this->birthplace = $tmp['event_place'];
                        $this->birthshort = ltrim(substr($this->birthdate, strrpos($this->birthdate, ' ')), ' ');
                        $tmp = rp_getone_event($data['indi_gedcomnumber'], $GEDCOM, 'DEAT');
                        $this->deathdate = $tmp['event_date'];
                        $this->deathplace = $tmp['event_place'];
                        $this->deathshort = ltrim(substr($this->deathdate, strrpos($this->deathdate, ' ')), ' ');
                        if (array_key_exists('indi_fams', $data)) $this->fams = $data['indi_fams'];

		}

		parent::__construct($data, $simple);

		$this->dispname=$this->disp || showLivingNameById($this->xref);
	}
	
	function getFullName() {
		return $this->fullname;
	}

	function getBirthDate() {
	    return $this->birthdate;
	}

	function getDeathDate() {
	    return $this->deathdate;
	}
	
	/**
	* get the birth place
	* @return string
	*/
	function getBirthPlace() {
	     return $this->birthplace;
	}
	/**
	* get the death place
	* @return string
	*/
	function getDeathPlace() {
	     return $this->deathplace;
	}
	
	function getBirthShort() {
	     return $this->birthshort;
	}
	function getDeathShort() {
	     return $this->deathshort;
	}

	/**
	* get the sex
	* @return string  return M, F, or U
	*/
	function getSex() {
		if (is_null($this->sex)) {
			if (preg_match('/\n1 SEX ([MF])/', $this->gedrec, $match)) {
				$this->sex=$match[1];
			} else {
				$this->sex='U';
			}
		}
		return $this->sex;
	}

	/**
	* get the person's sex image
	* NOTE: It would have been nice if we'd called the images sexM, sexF and sexU
	* @return string  <img ... />
	*/
	function getSexImage($size='small', $style='', $title='') {
		return self::sexImage($this->getSex(), $size, $style, $title);
	}

	static function sexImage($sex, $size='small', $style='', $title='') {
		global $PGV_IMAGE_DIR, $PGV_IMAGES, $SERVER_URL;
		switch ($sex) {
		case 'M':
			if (isset($PGV_IMAGES['sex'][$size])) {
				return "<img src=\"{$SERVER_URL}{$PGV_IMAGE_DIR}/{$PGV_IMAGES['sexm'][$size]}\" class=\"gender_image\" style=\"{$style}\" alt=\"{$title}\" title=\"{$title}\" />";
			} else {
				return '<span style="size:'.$size.'">'.PGV_UTF8_MALE.'</span>';
			}
		case 'F':
			if (isset($PGV_IMAGES['sex'][$size])) {
				return "<img src=\"{$SERVER_URL}{$PGV_IMAGE_DIR}/{$PGV_IMAGES['sexf'][$size]}\" class=\"gender_image\" style=\"{$style}\" alt=\"{$title}\" title=\"{$title}\" />";
			} else {
				return '<span style="size:'.$size.'">'.PGV_UTF8_FEMALE.'</span>';
			}
		default:
			if (isset($PGV_IMAGES['sex'][$size])) {
				return "<img src=\"{$SERVER_URL}{$PGV_IMAGE_DIR}/{$PGV_IMAGES['sexn'][$size]}\" class=\"gender_image\" style=\"{$style}\" alt=\"{$title}\" title=\"{$title}\" />";
			} else {
				return '<span style="size:'.$size.'">?</span>';
			}
		}
	}

	function getBoxStyle() {
		$tmp=array('M'=>'','F'=>'F', 'U'=>'NN');
		return 'person_box'.$tmp[$this->getSex()];
	}

	/**
	* get family with spouse ids
	* @return array array of the FAMS ids
	*/
	function getSpouseFamilyIds() {
                    $tmp =  explode(';', $this->gedrec['indi_fams']);
                    echo 'in getspousefamilyids ' . $this->gedrec['indi_gedcomnumber'];
                    return $tmp;
	}

	/**
	* get the families with spouses
	* @return array array of Family objects
	*/
	function getSpouseFamilies() {
		global $pgv_lang, $SHOW_LIVING_NAMES;
    //            if($this->gedrec['indi_fams']==null)  return (array)null;
                if(!array_key_exists('indi_fams', $this->gedrec)) return (array)null;
                $tmp =  explode(';', $this->gedrec['indi_fams']);
		foreach ($tmp as $famid) {
			$family=Family::getInstance('f', $famid);

				if (is_null($family)) {
				//	echo '<span class="warning">', $pgv_lang['unable_to_find_family'], ' |', $famid, '|</span>';
					return (array)$family;
				} else {
						$this->spouseFamilies[$famid] = $family;
				}
		//	}
		}   //end foreach
		return array_reverse($this->spouseFamilies);
	}  //End function

	/**
	* get the current spouse of this person
	* The current spouse is defined as the spouse from the latest family.
	* The latest family is defined as the last family in the GEDCOM record
	* @return Person  this person's spouse
	*/
	function getCurrentSpouse() {
		$tmp=$this->getSpouseFamilies();
		$family = end($tmp);
		if ($family) {
			return $family->getSpouse($this);
		} else {
			return null;
		}
	}

	// Get a count of all of the children for this individual with ALL spouses
	function getNumberOfChildren() {
          global $GEDCOM;
          $count = 0;
          if ($this->gedrec['indi_fams'] == null) return 0;
                $tmp =  explode(';', $this->gedrec['indi_fams']);
		foreach ($tmp as $famid) {
                  $result = rp_fetch_family_record($famid, $GEDCOM);
                  if ($result['fam_child_ref'] != null)
                      $count = $count+1+substr_count($result['fam_child_ref'], ';');
		}   //end foreach
		return $count;
	}
	/**
	* get family with child ids
	* @return array array of the FAMC ids
	*/
	function getChildFamilyIds() {
          $tmp[] =  $this->gedrec['indi_famc'];     //NOTE THIS COULD BE NULL
          return $tmp;
	}
	/**
	* get an array of families with parents
	* @return array array of Family objects indexed by family id
	*/
	function getChildFamilies() {
		global $pgv_lang, $SHOW_LIVING_NAMES;
       //
		if (is_null($this->childFamilies)) {
			$this->childFamilies=array();
		//	if ($this->getChildFamilyIds() == null) 'echo NO CHILD FAMILY IDS';
			foreach ($this->getChildFamilyIds() as $famid) {
            //              echo 'in getchildfamilies family id=|' . $famid . '|<br/>';
				$family=Family::getInstance('f', $famid);
				if (is_null($family)) {
					echo '<span class="warning">', $pgv_lang['unable_to_find_family'], ' ', $famid, '</span>';
				    }  else {
                                      $this->childFamilies[$famid]=$family;
                                    }
				}
		//	}
		}
		return $this->childFamilies;
	}
	
	/**
	* [get primary family with parents]
	* Since rootsPress assumes a child can only have on set of parents, this just returns a single family
	* @return Family object
	*/
	function getPrimaryChildFamily() {
          if( $this->gedrec == null) return null;
          if(array_key_exists('indi_famc', $this->gedrec)) $famid =  $this->gedrec['indi_famc']; 
          else return null;
          if ($famid == null) return null;
          $family=Family::getInstance('f', $famid);
          return $family;

/*		$families=$this->getChildFamilies();
		switch (count($families)) {
		case 0:
			return null;
		case 1:
			return reset($families);
		}         */
	}

	// Generate a URL that links to the main person page
	public function getLinkUrl() {
          global $RP_ROOT_PAGE;
//If this component is no longer called by treenav.php, the code below will need to be changed
          $t = str_replace('wp-content/plugins/rootspress/pgv/treenav.php', null, $_SERVER['SCRIPT_NAME']);
          return '//'.$_SERVER['SERVER_NAME'].$t. '?page_id=' . $RP_ROOT_PAGE . '&pid=' . $this->xref . '&ged=' . $this->ged_id . '&mode=base';
	}

}
?>
