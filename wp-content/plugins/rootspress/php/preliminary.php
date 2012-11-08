<?php
$prelim = array();
//Individual
$prelim['individual'] = "INSERT INTO ". $rp_database . '.' . $rp_prefix . '_'."individual(
	indi_gedcomnumber,
	indi_famc,
        indi_fams,
	indi_givnname,
        indi_surname,
	indi_prefix,
	indi_suffix,
	indi_fullname,
        indi_nickname,
        indi_sex,
        indi_event_ref,
        indi_attrib_ref,
        indi_refn,
 	indi_note_ref,
        indi_source_ref,
	indi_media_ref) ";
	
//Family
$prelim['family'] = "INSERT INTO ". $rp_database . '.' . $rp_prefix . '_'."family(
	fam_gedcomnumber,
        fam_spouse1,
        fam_spouse2,
        fam_child_ref,
        fam_refn,
 	fam_note_ref,
        fam_event_ref,
        fam_source_ref,
	fam_media_ref) ";
	
//Source
$prelim['source'] = "INSERT INTO ". $rp_database . '.' . $rp_prefix . '_'."source(
	srce_gedcomnumber,
	srce_abbrev,
	srce_pub_facts,
        srce_auth,
	srce_note_ref,
        srce_title,
        srce_text,
        srce_repos,
        srce_refn,
	srce_media_ref) ";
	
//Event
$prelim['event'] = "INSERT INTO ". $rp_database . '.' . $rp_prefix . '_'."event(
	event_gedcomnumber,
	event_owner_id,
        event_tagtype,
        event_content,
        event_type,
        event_date,
	event_place,
	event_cause,
	event_note_ref,
	event_source_ref,
	event_media_ref) ";
	
//Attributes
$prelim['attrib'] = "INSERT INTO ". $rp_database . '.' . $rp_prefix . '_'."attrib(
	attrib_gedcomnumber,
	attrib_owner_id,
        attrib_tagtype,
        attrib_content,
        attrib_type,
        attrib_date,
	attrib_place,
	attrib_note_ref,
	attrib_source_ref,
	attrib_media_ref) ";
	
//Media object 1
$prelim['obje'] = "INSERT INTO ". $rp_database . '.' . $rp_prefix . '_'."obje(
	obje_gedcomnumber,
	obje_owner_id,
	obje_file_ref,
        obje_title,
        obje_type,
        obje_refn,
	obje_note_ref,
	obje_source_ref) ";
        
//Media
$prelim['media'] = "INSERT INTO ". $rp_database . '.' . $rp_prefix . '_'."media(
	media_gedcomnumber,
	media_owner_id,
	media_file,
	media_form,
	media_type,
	media_title) ";

//Note 1
$prelim['note'] = "INSERT INTO ". $rp_database . '.' . $rp_prefix . '_'."note(
	note_gedcomnumber,
	note_note,
        note_refn,
        note_source_ref) ";

//Citation 1
$prelim['citation'] = "INSERT INTO ". $rp_database . '.' . $rp_prefix . '_'."citation(
        citn_gedcomnumber,
	citn_owner_id,
	citn_source_xref,
        citn_description,
        citn_page,
        citn_note_ref,
	citn_eventfrom,
	citn_role,
	citn_text,
	citn_media_ref,
	citn_qual) ";

//Citation 2
/*$prelim['citation'] = "INSERT INTO ". $rp_database . '.' . $rp_prefix . '_'."citation(
	citn_gedcomnumber,
	citn_owner_id,
	citn_source_xref,
        citn_description,
        citn_page,
        citn_note_ref,
	citn_eventfrom,
	citn_role,
	citn_text,
	citn_media_ref,
	citn_qual) ";     */
	
//Places
$prelim['places'] = "INSERT INTO ". $rp_database . '.' . $rp_prefix . '_'."places(
	places_gedcomnumber,
	places_owner_id,
	places_place,
        places_lati,
        places_long,
        places_note) ";

//Repository
$prelim['repo'] = "INSERT INTO ". $rp_database . '.' . $rp_prefix . '_'."repo(
	repo_gedcomnumber,
	repo_name,
        repo_address,
        repo_refn,
	repo_note_ref) ";

//Note 2
/*$prelim['note'] = "INSERT INTO ". $rp_database . '.' . $rp_prefix . '_'."note(
	note_gedcomnumber,
	note_note,
        note_refn,
        note_source_ref) ";       */

//Media object 2
/*$prelim['obje'] = "INSERT INTO ". $rp_database . '.' . $rp_prefix . '_'."obje(
	obje_gedcomnumber,
	obje_owner_id,
	obje_file_ref,
        obje_title,
        obje_type,
        obje_refn,
	obje_note_ref,
	obje_source_ref) ";       */
?>