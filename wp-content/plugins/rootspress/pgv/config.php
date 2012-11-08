<?php
$SERVER_URL= '//' . $_SERVER['SERVER_NAME'] . pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME) . '/';
$RP_PORTRAITS = null;  //set from rootsPress database for this tree

$USE_SILHOUETTE = false;    //Set to true to always use silhouettes in place of portrait images.
$SHOW_ID_NUMBERS = false;   //Set to true to show id numbers in expanded box otherwise false.

$PGV_IMAGE_DIR='images';
$PGV_IMAGES = array();
$PGV_IMAGES['zoomin']['other'] =  'zoomin.gif';
$PGV_IMAGES['zoomout']['other'] =  'zoomout.gif';
$PGV_IMAGES['gedcom']['small'] =  'small/gedcom.gif';
$PGV_IMAGES["default_image_U"]["other"] = 'silhouette_unknown.gif';
$PGV_IMAGES["default_image_M"]["other"] = 'silhouette_M.gif';
$PGV_IMAGES["default_image_F"]["other"] = 'silhouette_F.gif';
$PGV_IMAGES["default_image_M"]["small"] = 'small/silhouette_M.gif';
$PGV_IMAGES["default_image_F"]["small"] = 'small/silhouette_F.gif';
$PGV_IMAGES['red_circle']['other'] = 'red_circle.png';
$PGV_IMAGES['index']['small'] = 'small/index.gif';
$PGV_IMAGES['home']['small']  = 'small/home.gif';
$PGV_IMAGES['help']['small'] =  'small/help.gif';

/*
** In-line style settings DO NOT CHANGE
** Designed to override css inheritance from Wordpress themes
*/
$tn_table   = "background-color: $PGV_BG; padding-bottom: 5px; margin: 0px 0px 1px auto; width: 1px; border: none; border-collapse: collapse; vertical-align:middle; -moz-box-shadow: none;" ;
$tn_table2  = "background-color: $PGV_BG; padding-bottom: 5px; margin: 0px 0px 1px 0px; width: 1px; border: none; border-collapse: collapse; vertical-align:middle; -moz-box-shadow: none;" ;
$tn_vline   = "padding: 0px; vertical-align:middle; width: 3px;" ;
$tn_hline   = "padding: 0px; margin: 0px 0px 0px auto; height: 3px; border: none; border-collapse: collapse; vertical-align: middle;" ;
$tn_box     = "padding: 0px; margin: 0px 0px 0px auto; width: 1px; border: none; border-collapse: collapse; vertical-align: middle;" ;
$tn_bigbox  = "background-color: $PGV_BG; padding: 0px; margin: 0px 0px 0px auto; width: 1px; border: none; border-collapse: collapse;  vertical-align: middle;" ;
//min height in person box set because a narrrow box doesnt format hlines well
//margins are used to separate boxes but note DOM offsetHeight does not include margins so must be built into treenav.js
$tn_persbox0 = "background-color: $PGV_BOXBKGND; border: $PGV_BOXBORDER; padding:1px 2px 1px 2px; margin: 6px 0px 6px 0px; min-height:25px; text-align: left; cursor: pointer; border-radius: 10px; -moz-border-radius: 10px; font-size: ";

$PGV_HLENGTH = '20';
$PGV_BOXSPACING = '5';
$TEXT_DIRECTION = 'ltr';

$zoom = 0;
$rootid = '';
$name = 'nav';
?>