<?php
// *** Generate new tables ***
function rp_build_tables($prefix, $rp_database) {
  global $rootspressdb;
//  $rootspressdb = mysql_connect($_SESSION['dbhost'], $_SESSION['dbuser'], $_SESSION['dbpwd']);
//  mysql_select_db($_SESSION['database'], $rootspressdb);
$t_prefix = $prefix . '_';
//Individual table
$sql = "CREATE TABLE ".$rp_database . '.' . $t_prefix."individual (
	indi_id mediumint(6) unsigned NOT NULL auto_increment,
	indi_gedcomnumber varchar(25) CHARACTER SET utf8,
	indi_famc varchar(25) CHARACTER SET utf8,
	indi_fams varchar(25) CHARACTER SET utf8,
	indi_givnname varchar(120) CHARACTER SET utf8,
	indi_surname varchar(120) CHARACTER SET utf8,
	indi_prefix varchar(30) CHARACTER SET utf8,
        indi_suffix varchar(30) CHARACTER SET utf8,
	indi_fullname varchar(50) CHARACTER SET utf8,
	indi_nickname varchar(50) CHARACTER SET utf8,
	indi_sex varchar(1) CHARACTER SET utf8,
	indi_event_ref varchar(255) CHARACTER SET utf8,
	indi_attrib_ref varchar(255) CHARACTER SET utf8,
	indi_refn varchar(255) CHARACTER SET utf8,
	indi_note_ref varchar(255) CHARACTER SET utf8,
	indi_source_ref varchar(255) CHARACTER SET utf8,
	indi_media_ref varchar(255) CHARACTER SET utf8,
	PRIMARY KEY (`indi_id`),
	KEY (indi_surname),
	KEY (indi_gedcomnumber)
	)";
	$result = @mysql_query($sql, $rootspressdb);
	if ($result === FALSE) echo "FAILURE IN TABLE CREATION " . $rp_database . '.' . $t_prefix . ' ' . mysql_error();
        if ($result === FALSE) return false;
        
//Family table
$sql = "CREATE TABLE ".$rp_database . '.' . $t_prefix."family (
	fam_id mediumint(6) unsigned NOT NULL auto_increment,
	fam_gedcomnumber varchar(25) CHARACTER SET utf8,
	fam_spouse1 varchar(25) CHARACTER SET utf8,
	fam_spouse2 varchar(25) CHARACTER SET utf8,
        fam_child_ref varchar(255) CHARACTER SET utf8,
	fam_event_ref varchar(255) CHARACTER SET utf8,
	fam_refn varchar(255) CHARACTER SET utf8,
	fam_note_ref varchar(255) CHARACTER SET utf8,
	fam_source_ref varchar(255) CHARACTER SET utf8,
	fam_media_ref varchar(255) CHARACTER SET utf8,
	PRIMARY KEY (`fam_id`),
	KEY (fam_gedcomnumber)
	)";
	$result = @mysql_query($sql, $rootspressdb);
	if ($result === FALSE) echo "FAILURE IN TABLE CREATION " . $rp_database . '.' . $t_prefix . ' ' . mysql_error();
        if ($result === FALSE) return false;

        
//Event table
$sql = "CREATE TABLE ".$rp_database . '.' . $t_prefix."event (
	event_id mediumint(6) unsigned NOT NULL auto_increment,
	event_gedcomnumber varchar(25) CHARACTER SET utf8,
	event_owner_id  varchar(25) CHARACTER SET utf8,
	event_tagtype varchar(6) CHARACTER SET utf8,
	event_content varchar(255) CHARACTER SET utf8,
	event_type varchar(15) CHARACTER SET utf8,
	event_date varchar(50) CHARACTER SET utf8,
	event_place varchar(120) CHARACTER SET utf8,
	event_cause varchar(90) CHARACTER SET utf8,
	event_note_ref varchar(255) CHARACTER SET utf8,
	event_source_ref varchar(255) CHARACTER SET utf8,
	event_media_ref varchar(255) CHARACTER SET utf8,
	PRIMARY KEY (`event_id`),
	KEY (event_owner_id),
	KEY (event_gedcomnumber)
	)";
	$result = @mysql_query($sql, $rootspressdb);
	if ($result === FALSE) echo "FAILURE IN TABLE CREATION " . $rp_database . '.' . $t_prefix . ' ' . mysql_error();
        if ($result === FALSE) return false;
        
        
//Attribute table
$sql = "CREATE TABLE ".$rp_database . '.' . $t_prefix."attrib (
	attrib_id mediumint(6) unsigned NOT NULL auto_increment,
	attrib_gedcomnumber varchar(25) CHARACTER SET utf8,
	attrib_owner_id  varchar(25) CHARACTER SET utf8,
	attrib_tagtype varchar(6) CHARACTER SET utf8,
	attrib_content varchar(255) CHARACTER SET utf8,
	attrib_type varchar(15) CHARACTER SET utf8,
	attrib_date varchar(50) CHARACTER SET utf8,
	attrib_place varchar(120) CHARACTER SET utf8,
	attrib_note_ref varchar(255) CHARACTER SET utf8,
	attrib_source_ref varchar(255) CHARACTER SET utf8,
	attrib_media_ref varchar(255) CHARACTER SET utf8,
	PRIMARY KEY (`attrib_id`),
	KEY (attrib_owner_id),
	KEY (attrib_gedcomnumber)
	)";
	$result = @mysql_query($sql, $rootspressdb);
	if ($result === FALSE) echo "FAILURE IN TABLE CREATION " . $rp_database . '.' . $t_prefix . ' ' . mysql_error();
        if ($result === FALSE) return false;

//Media file table  
$sql = "CREATE TABLE ".$rp_database . '.' . $t_prefix."media (
	media_id mediumint(6) unsigned NOT NULL auto_increment,
	media_gedcomnumber varchar(25) CHARACTER SET utf8,
	media_owner_id  varchar(25) CHARACTER SET utf8,
	media_file varchar(90) CHARACTER SET utf8,
	media_form varchar(6) CHARACTER SET utf8,
	media_type varchar(15) CHARACTER SET utf8,
	media_title varchar(255) CHARACTER SET utf8,
	PRIMARY KEY (`media_id`),
	KEY (media_owner_id),
	KEY (media_gedcomnumber)
	)";
	$result = @mysql_query($sql, $rootspressdb);
	if ($result === FALSE) echo "FAILURE IN TABLE CREATION " . $rp_database . '.' . $t_prefix . ' ' . mysql_error();
        if ($result === FALSE) return false;

//Media object table  (Shared by OBJE tag and OBJE record)
$sql = "CREATE TABLE ".$rp_database . '.' . $t_prefix."obje (
	obje_id mediumint(6) unsigned NOT NULL auto_increment,
	obje_gedcomnumber varchar(25) CHARACTER SET utf8,
	obje_owner_id  varchar(25) CHARACTER SET utf8,
	obje_file_ref varchar(255) CHARACTER SET utf8,
	obje_title varchar(255) CHARACTER SET utf8,
	obje_type varchar(15) CHARACTER SET utf8,
	obje_refn varchar(255) CHARACTER SET utf8,
	obje_note_ref varchar(255) CHARACTER SET utf8,
	obje_source_ref varchar(255) CHARACTER SET utf8,
	PRIMARY KEY (`obje_id`),
	KEY (obje_owner_id),
	KEY (obje_gedcomnumber)
	)";
	$result = @mysql_query($sql, $rootspressdb);
	if ($result === FALSE) echo "FAILURE IN TABLE CREATION " . $rp_database . '.' . $t_prefix . ' ' . mysql_error();
        if ($result === FALSE) return false;

//Source citation table
$sql = "CREATE TABLE ".$rp_database . '.' . $t_prefix."citation (
	citn_id mediumint(6) unsigned NOT NULL auto_increment,
	citn_gedcomnumber varchar(25) CHARACTER SET utf8,
	citn_owner_id  varchar(25) CHARACTER SET utf8,
	citn_source_xref varchar(25) CHARACTER SET utf8,
	citn_description text CHARACTER SET utf8,
	citn_page varchar(255) CHARACTER SET utf8,
	citn_note_ref text CHARACTER SET utf8,
	citn_eventfrom varchar(15) CHARACTER SET utf8,
	citn_role varchar(15) CHARACTER SET utf8,
	citn_text text CHARACTER SET utf8,
	citn_media_ref varchar(255) CHARACTER SET utf8,
	citn_qual char(1) CHARACTER SET utf8,
	PRIMARY KEY (`citn_id`),
	KEY (citn_gedcomnumber)
	)";
	$result = @mysql_query($sql, $rootspressdb);
	if ($result === FALSE) echo "FAILURE IN TABLE CREATION " . $rp_database . '.' . $t_prefix . ' ' . mysql_error();
        if ($result === FALSE) return false;

//Source table
$sql = "CREATE TABLE ".$rp_database . '.' . $t_prefix."source (
	srce_id mediumint(6) unsigned NOT NULL auto_increment,
	srce_gedcomnumber varchar(25) CHARACTER SET utf8,
	srce_abbrev  varchar(60) CHARACTER SET utf8,
        srce_auth text CHARACTER SET utf8,
	srce_title text CHARACTER SET utf8,
	srce_pub_facts text CHARACTER SET utf8,
	srce_text text CHARACTER SET utf8,
        srce_repos varchar(255) CHARACTER SET utf8,
	srce_refn varchar(255) CHARACTER SET utf8,
	srce_note_ref varchar(255) CHARACTER SET utf8,
	srce_media_ref varchar(255) CHARACTER SET utf8,
	PRIMARY KEY (`srce_id`),
	KEY (srce_gedcomnumber)
	)";
	$result = @mysql_query($sql, $rootspressdb);
	if ($result === FALSE) echo "FAILURE IN TABLE CREATION " . $rp_database . '.' . $t_prefix . ' ' . mysql_error();
        if ($result === FALSE) return false;

//Repository table
$sql = "CREATE TABLE ".$rp_database . '.' . $t_prefix."repo (
	repo_id mediumint(6) unsigned NOT NULL auto_increment,
	repo_gedcomnumber varchar(20) CHARACTER SET utf8,
	repo_name varchar(90) CHARACTER SET utf8,
	repo_address text CHARACTER SET utf8,
	repo_note_ref text CHARACTER SET utf8,
	repo_refn varchar(255) CHARACTER SET utf8,
	PRIMARY KEY (`repo_id`),
	KEY (repo_gedcomnumber)
	)";
	$result = @mysql_query($sql, $rootspressdb);
	if ($result === FALSE) echo "FAILURE IN TABLE CREATION " . $rp_database . '.' . $t_prefix . ' ' . mysql_error();
        if ($result === FALSE) return false;
        
//Note table
$sql = "CREATE TABLE ".$rp_database . '.' . $t_prefix."note (
	note_id mediumint(6) unsigned NOT NULL auto_increment,
	note_gedcomnumber varchar(20) CHARACTER SET utf8,
	note_note text CHARACTER SET utf8,
	note_refn varchar(20) CHARACTER SET utf8,
	note_source_ref varchar(255) CHARACTER SET utf8,
	PRIMARY KEY (`note_id`),
	KEY (note_gedcomnumber)
	)";
	$result = @mysql_query($sql, $rootspressdb);
	if ($result === FALSE) echo "FAILURE IN TABLE CREATION " . $rp_database . '.' . $t_prefix . ' ' . mysql_error();
        if ($result === FALSE) return false;

//Places table
$sql = "CREATE TABLE ".$rp_database . '.' . $t_prefix."places (
	places_id mediumint(6) unsigned NOT NULL auto_increment,
	places_gedcomnumber varchar(20) CHARACTER SET utf8,
	places_owner_id  varchar(25) CHARACTER SET utf8,
	places_place  varchar(255) CHARACTER SET utf8,
        places_lati varchar(8) CHARACTER SET utf8,
        places_long varchar(8) CHARACTER SET utf8,
	places_note text CHARACTER SET utf8,
	PRIMARY KEY (`places_id`),
	KEY (places_gedcomnumber)
	)";
	$result = @mysql_query($sql, $rootspressdb);
	if ($result === FALSE) echo "FAILURE IN TABLE CREATION " . $rp_database . '.' . $t_prefix . ' ' . mysql_error();
        if ($result === FALSE) return false;

return TRUE;
}

?>
