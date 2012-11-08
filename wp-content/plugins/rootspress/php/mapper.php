<?php
function addMapLayer(&$Layer, $person) {
  global $prefix;

$Layer .= '<script type = "text/javascript">';
$Layer .= '    var side_bar_html = "";  ';
$Layer .= '    var gmarkers = []; ';
$Layer .= '    var bLatLng = []; ';
$Layer .= '    var cLatLng = []; ';
$Layer .= '    var GmarkersArray = [];';
$Layer .= 'function mapload(){   ';
$Layer .= 'var myOptions = {';
$Layer .= '    zoom: 10,  ';
$Layer .= '    center: new google.maps.LatLng(51.5, 0.0), ';
if ($_SESSION['typech']) $Layer .= '    mapTypeControl: true, ';
  else  $Layer .= '    mapTypeControl: false, ';
$Layer .= '    mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},  ';
$Layer .= '    navigationControl: true, ';
switch ($_SESSION['maptype']) {
  case 'gstreet':
    $Layer .= '    mapTypeId: google.maps.MapTypeId.ROADMAP  ';
    break;
  case 'gterr':
    $Layer .= '    mapTypeId: google.maps.MapTypeId.TERRAIN  ';
    break;
  case 'ghyb':
    $Layer .= '    mapTypeId: google.maps.MapTypeId.HYBRID  ';
    break;
  case 'gsat':
    $Layer .= '    mapTypeId: google.maps.MapTypeId.SATELLITE  ';
    break;
}

$Layer .= '  };  ';
//$Layer .= '  var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions); ';
$Layer .= '  map = new google.maps.Map(document.getElementById("map_canvas"), myOptions); ';
$Layer .= "  google.maps.event.addListener(map, 'click', function() {infowindow.close(); geogrmv(); });  ";

//$Layer .= "google.maps.event.trigger(map, 'resize'); ";
//Add markers
addEventMarker($Layer, $person);
//Zoom to fit markers
$Layer .= 'var bounds = new google.maps.LatLngBounds ();' ;
$Layer .= 'for (var i = 0, LtLgLen = bLatLng.length; i < LtLgLen; i++) { bounds.extend (bLatLng[i]); }  ';
$Layer .= 'map.fitBounds (bounds); ';
$Layer .= 'google.maps.event.addListenerOnce(map, "idle", function() {
  if (map.getZoom() > 9) map.setZoom(9);
}); ';
$Layer .= '  document.getElementById("side_bar").innerHTML = side_bar_html; ';
$Layer .= '  infowindow = new google.maps.InfoWindow( ); ';
//$Layer .= '  infowindow.setContent("<div id=window1 style=\"border: 2px solid red;\" >ABC</div>"); ';

$Layer .= 'return;}';
$Layer .= '</script>';
}

function addEventMarker(&$Layer, $person) {
  global $prefix, $rp_database;
     $ref_list = explode(';', $person['indi_event_ref']);
     foreach ($ref_list as $value) {
        $row = rp_get_event($value);
        $ev_type = long_event($row['event_tagtype']);
        if ($row['event_tagtype'] == 'EVEN') $ev_type = $row['event_type'];
        $place =  $row['event_place'];
        $sql = 'SELECT * FROM '. $rp_database . '.' . $prefix . '_places' . ' WHERE places_place = ' . '"' . $place . '"';
        $row2 = rp_sql($sql, 'places');
//convert lat with N, S prefix to +/- and lon with E, W to +/-
        $lat_srch = array('N', 'S');
        $lat_rep  = array('', '-');
        $lon_srch = array('E', 'W');
        $lon_rep  = array('', '-');

        if ($row2['places_lati'] != null &&  $row2['places_lati'] != null) {
           $lati = str_replace($lat_srch, $lat_rep, $row2['places_lati']);
           $long = str_replace($lon_srch, $lon_rep, $row2['places_long']);
//CHOOSE ICON INDEX BASED ON EVENT
        switch ($row['event_tagtype']) {
           case 'BIRT':
             $icon = '1';
             break;
           case 'BAPM':
             $icon = '2';
             break;
           case 'CENS':
             $icon = '3';
             break;
           case 'MARR':
             $icon = '5';
             break;
           case 'DEAT':
             $icon = '8';
             break;
           case 'BURI':
             $icon = '7';
             break;
           default:
             $icon = '0';
         }
//createMarker(map, lat, lng, label, html, index)
$Layer .= 'createMarker(map, ' . $lati . ',' . $long . ',"' . $ev_type . ': ' . $row['event_date'] . '","' . $row['event_place'] . '",' . $icon . ');' ;
        }

      }

} //End function

?>