<?php
// *** Remove all tables***
function rp_remove_tables($prefix, $rp_database) {
  global $rootspressdb;
//  $rootspressdb = mysql_connect($_SESSION['dbhost'], $_SESSION['dbuser'], $_SESSION['dbpwd']);
//  mysql_select_db($_SESSION['database'], $rootspressdb);
  $t_prefix = $rp_database . '.' . $prefix . '_';
        $sql = "DROP TABLE " .
        $t_prefix . "individual" . ', ' .
        $t_prefix . "family" . ', ' .
        $t_prefix . "event" . ', ' .
        $t_prefix . "attrib" . ', ' .
        $t_prefix . "media" . ', ' .
        $t_prefix . "citation" . ', ' .
        $t_prefix . "source" . ', ' .
        $t_prefix . "repo" . ', ' .
        $t_prefix . "note" . ', ' .
        $t_prefix . "obje" . ', ' .
        $t_prefix . "places";
	$result = mysql_query($sql, $rootspressdb);
	if ($result === FALSE) {
          echo mysql_errno() . ' ' . mysql_error($rootspressdb) . '<br/>';
//          echo "FAILED TO DELETE ALL THE TABLES ". $t_prefix . ' ' . mysql_error();
          return false;
 }

return TRUE;
}
?>
