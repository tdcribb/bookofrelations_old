<?php
/**
* Base class for all gedcom records
*
* phpGedView: Genealogy Viewer
* Copyright (C) 2002 to 2009 PGV Development Team.  All rights reserved.
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
* @version $Id: class_gedcomrecord.php 6808 2010-01-22 16:49:43Z fisharebest $
*/

require_once 'includes/classes/class_person.php';
require_once 'includes/classes/class_family.php';

class Pressdb {
	var $xref       =null;  // The record identifier
	var $type       =null;  // INDI, FAM, etc.
	var $ged_id     =null;  // The gedcom file, only set if this record comes from the database
	var $gedrec     =null;  // Raw gedcom text (privatised)
	var $rfn        =null;
	var $facts      =null;
	var $disp       =true;  // Can we display details of this object
	var $dispname   =true;  // Can we display the name of this object

	// Cached results from various functions.
	protected $_getAllNames=null;
	protected $_getPrimaryName=null;
	protected $_getSecondaryName=null;

	// Create a GedcomRecord object from either raw GEDCOM data or a database row
	function __construct($data, $simple=false) {
          	if (is_array($data)) {
			// Construct from a row from the database
			$this->gedrec=$data;
			if (array_key_exists('indi_gedcomnumber', $data)) $this->xref = $data['indi_gedcomnumber'];
			if (array_key_exists('fam_gedcomnumber', $data)) $this->xref = $data['fam_gedcomnumber'];
                        if (array_key_exists('type', $data)) $this->type = $data['type'];
                          else  $this->type = 'Z';                        
		}

	}
	
	// Get an instance of a database record.  We either specify
	// an XREF (in the current gedcom), or we can provide a row
	// from the database
	static function &getInstance($type, $data) {
          global $GEDCOM;
		if (is_array($data)) {
			$ged_id=$data['ged_id'];
			$pid   =$data['xref'];
		}  else {
			$ged_id=$GEDCOM;
			$pid   =$data;
		}

//could use get_called_class in PHP_VERSION, '5.3', '>='  and subsequently  $object=new $class_name($data);
                        switch ($type) {

				case 'i':
					$data=rp_fetch_person_record($pid, $ged_id);
		                        $object=new Person($data);
                                        $data['type'] = 'i';
					break;
				case 'f':
					$data=rp_fetch_family_record($pid, $ged_id);
                                        $data['type'] = 'f';
                                        $object=new Family($data);
					break;
				case 'x':
					$data = array();
                                        $data['indi_gedcomnumber']= $pid;
                                        $data['indi_fullname'] = '<i>Unknown</i>';
                                        $data['type'] = 'x';
                                        $object=new Person($data);
					break;
				}

// Indicate which gedcom it comes from.
		$object->ged_id=$ged_id;
		return $object;
	}

	/**
	* get the xref
	* @return string returns the person ID
	*/
	function getXref() {
		return $this->xref;
	}

	function getType() {
		return $this->type;
	}
	
	function getGed_id() {
		return $this->ged_id;
	}
	
	/**
	* check if this object is equal to the given object
	* @param GedcomRecord $obj
	*/
	public function equals(&$obj) {
		return !is_null($obj) && $this->xref==$obj->getXref();
	}
	
}
?>
