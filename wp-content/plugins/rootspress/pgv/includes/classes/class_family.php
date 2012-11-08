<?php
/**
 * Class file for a Family
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
 * @version $Id: class_family.php 6815 2010-01-23 14:42:56Z fisharebest $
 */

/*if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

define('PGV_CLASS_FAMILY_PHP', '');     */

//require_once PGV_ROOT.'includes/classes/class_gedcomrecord.php';
require_once PGV_ROOT.'includes/classes/class_pressdb.php';

class Family extends Pressdb {
	private $husb = null;
	private $wife = null;
	private $children = array();
	private $childrenIds = array();
	private $marriage = null;
	private $marrdate = 'Unknown';
	private $marrplace = '';
	private $children_loaded = false;
	private $numChildren   = false;
	private $_isDivorced   = null;
	private $_isNotMarried = null;

	// Create a Family object from either raw GEDCOM data or a database row
	function __construct($data, $simple=true) {
          global $GEDCOM;
		if (is_array($data)) {
                        if(array_key_exists('fam_gedcomnumber', $data)) {
                        $tmp = rp_getone_event($data['fam_gedcomnumber'], $GEDCOM, 'MARR');
                        $this->marrdate = $tmp['event_date'];
                        $this->marrplace = $tmp['event_place'];
                        }

//                	if ($data['fam_spouse1']) {
                        if(array_key_exists('fam_spouse1', $data) && $data['fam_spouse1'] != null) {
				$this->husb=Person::getInstance('i', $data['fam_spouse1']);
			}
//			if ($data['fam_spouse2']) {
                        if(array_key_exists('fam_spouse2', $data) && $data['fam_spouse2'] != null) {
				$this->wife=Person::getInstance('i', $data['fam_spouse2']);
			}
//In the (rare) case that the father is not given, set the 'wife' as the husband to ensure the tree works
//			if($this->husb == null) {
//                          $this->husb =  $this->wife;
//                          $this->wife = null;
//                         }

                        if(array_key_exists('fam_child_ref', $data)) {
			if (strpos($data['fam_child_ref'], ';')) {
				$this->childrenIds=explode(';', trim($data['fam_child_ref'], ';'));
			}
                        if ($data['fam_child_ref'] == null) $this->numChildren = 0;
                          else  $this->numChildren = 1+substr_count ( $data['fam_child_ref'] , ';' ) ;
                        }
                          


		} 

		// Make sure husb/wife are the right way round.
//		if ($this->husb && $this->husb->getSex()=='F' || $this->wife && $this->wife->getSex()=='M') {
//			list($this->husb, $this->wife)=array($this->wife, $this->husb);
//		}

		parent::__construct($data);
	}

	/**
	 * get the husbands ID
	 * @return string
	 */
	function getHusbId() {
		if (!is_null($this->husb)) return $this->husb->getXref();
		else return '';
	}

	/**
	 * get the wife ID
	 * @return string
	 */
	function getWifeId() {
		if (!is_null($this->wife)) return $this->wife->getXref();
		else return '';
	}

	/**
	 * get the husband's person object
	 * @return Person
	 */
	function &getHusband() {
		return $this->husb;
	}
	/**
	 * get the wife's person object
	 * @return Person
	 */
	function &getWife() {
		return $this->wife;
	}

	/**
	 * return the spouse of the given person
	 * @param Person $person
	 * @return Person
	 */
	function getSpouse($person) {
		if (is_null($this->wife) or is_null($this->husb)) return null;
		if ($this->wife->equals($person)) return $this->husb;
		if ($this->husb->equals($person)) return $this->wife;
		return null;
	}

	/**
	 * return the spouse id of the given person id
	 * @param string $pid
	 * @return string
	 */
	function &getSpouseId($pid) {
		if (is_null($this->wife) or is_null($this->husb)) return null;
		if ($this->wife->getXref()==$pid) return $this->husb->getXref();
		if ($this->husb->getXref()==$pid) return $this->wife->getXref();
		return null;
	}

	/**
	 * get the children
	 * @return array 	array of children Persons
	 */
	function getChildren() {
		if (!$this->children_loaded) $this->loadChildren();
		return $this->children;
	}

	/**
	 * get the children ids
	 * @return array 	array of children ids
	 */
	function getChildrenIds() {
		if (!$this->children_loaded) $this->loadChildren();
		return $this->childrenIds;
	}


	/**
	 * Load the children from the database
	 * We used to load the children when the family was created, but that has performance issues
	 * because we often don't need all the children
	 * now, children are only loaded as needed
	 */
	function loadChildren() {
		if ($this->children_loaded) return;
		if ($this->numChildren <= 0) return;
		$this->childrenIds = array();
                $this->childrenIds = explode(';', $this->gedrec['fam_child_ref']);
		foreach($this->childrenIds as $t=>$chil) {
			$child=Person::getInstance('i', $chil);
			if (!is_null($child)) $this->children[] = $child;
		}
		$this->children_loaded = true;
	}

	function getNumberOfChildren() {
		if ($this->numChildren!==false) return $this->numChildren;
		   else return null;
	}


	/**
	 * get marriage date
	 * @return string
	 */
	function getMarriageDate() {
		global $pgv_lang;
		return $this->marrdate;
		if (!$this->canDisplayDetails()) {
			return new GedcomDate('');
		}
		if (is_null($this->marriage)) {
			$this->_parseMarriageRecord();
		}
		return $this->marriage->getDate();
	}

	/**
	 * get the marriage year
	 * @return string
	 */
	function getMarriageYear($est = true, $cal = ''){
		// TODO - change the design to use julian days, not gregorian years.
		$mdate = $this->getMarriageDate();
		$mdate = $mdate->MinDate();
		if ($cal) $mdate = $mdate->convert_to_cal($cal);
		return $mdate->y;
	}


	/**
	 * get the type for this marriage
	 * @return string
	 */
	function getMarriageType() {
		if (is_null($this->marriage)) $this->_parseMarriageRecord();
		return $this->marriage->getType();
	}

	/**
	 * get the marriage place
	 * @return string
	 */
	function getMarriagePlace() {
                return $this->marrplace;
		$marriage = $this->getMarriage();
		return $marriage->getPlace();
	}


	// Generate a URL that links to this record
	public function getLinkUrl() {
		return parent::_getLinkUrl('family.php?famid=');
	}

}
?>
