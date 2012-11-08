<?php
function rp_selector ($passed_ged, $passed_pid, $passed_mode, $row) {
/* The mode to be used may come from either the user clicking an icon to go to the required page
** or may be passed by the shortcode handler
** The user request takes precedence. It would be null if the shortcode handler passes the mode */

$locale = get_locale();

  $pid = null;
  $mode = null;
  $ged = null;

       if (isset($_GET['pid'])) $pid=$_GET['pid'];
       if (isset($_GET['ged'])) $ged=$_GET['ged'];
       if (isset($_GET['mode'])) $mode=$_GET['mode'];

   if($ged==null) $ged = $passed_ged;
   if($mode==null) $mode = $passed_mode;

   if($pid==null) $pid = $row['default_id'];
     $mainpage = $row['wp_root_id'];
     $permalink = page_link($ged);

   if($mode=='base') {
        if (secure_page($ged, $content)) return $content;

     $block = rp_viewer($ged, $pid);
     return $block;
   }
   if($mode=='tree') {
       if (secure_page($ged, $content)) return $content;
       
       $options = get_option('rootspress_options');
       $content = '<script type="text/javascript" src="' . $_SESSION['wp_site_url'] . '/wp-content/plugins/rootspress/pgv/treenav.php?navAjax=embed&width='.$options['twidth'].'&height='.$options['theight'].'&rootid=';
       $content .= $pid;
       $content .= '&gedid=';
       $content .= $ged;
       $content .= '&allSpouses&generations=0&zoom=2';
       $content .= '&locale=' . $locale;
       $content .= '&tnoptions=' . $options['tnprev1'].$options['tnprev2'].$options['tnprev3'].$options['tnprev4'];
       $content .= '"></script>';
       return $content;
   }
   if($mode=='indx') {
       if (secure_page($ged, $content)) return $content;
//       $content = '<h2 class="titleline">' . $_SESSION['tree_name'] . ' Index of people alphabetically by surname' ; 
       $content = '<h2 class="titleline">' . __('Index of people alphabetically by surname', 'rpress_main') ;
       $content .= "<a href='" . $permalink . "&pid=" . '' . "&ged=" . $ged . "&mode=home" . "'>";
       $content .= '<img src="'. WP_PLUGIN_URL . '/rootspress/images/home.gif"' . ' title="' . __( 'Home', 'rpress_main' ). '" class="rp_icons" '  . '>' . '</a>';
       $help_link =  WP_PLUGIN_URL . '/rootspress/pgv/help/help.php';
       $content .=  '<a href="#"  onclick="TINY.box.show({url:' . "'$help_link?id=203&amp;locale=$locale',width:400,height:600,opacity:50,topsplit:2, close:true})" . '" >';
       $content .= '<img title="' . __( 'Help', 'rpress_main' ) . '" class="rp_icons"  src=' . WP_PLUGIN_URL . '/rootspress/images/help.gif' . '>';
       $content .= '</a>';

       $content .= '</h2>' ;
       rootsPress::build_index($ged, $content, null);
       return $content;
   }
   if($mode=='home') {

//       $content = '<h2 class="titleline">' . $_SESSION['tree_name'] . ' family tree';
       $content = '<h2 class="titleline">' . __('About', 'rpress_main');
//       $page_data = get_page($row['wp_home_id']); // You must pass in a variable to the get_page function. If you pass in a value (e.g. get_page ( 123 ); ), WordPress will generate an error.
//       $content .= apply_filters('the_content', $page_data->post_content); // Get Content and retain Wordpress filters such as paragraph tags. Origin from: http://wordpress.org/support/topic/get_pagepost-and-no-paragraphs-problem

       $content .= "<a href='" . $permalink . "&pid=" . '' . "&ged=" . $ged . "&mode=indx" . "'>";
//       $content .= '<img src="'. WP_PLUGIN_URL . '/rootspress/images/index.gif"' . ' title="Index" width="20px" align="right" '  . '>' . '</a>';
       $content .= '<img src="'. WP_PLUGIN_URL . '/rootspress/images/index.gif"' . ' title="' . __( 'Index', 'rpress_main' ). '" class="rp_icons" '  . '>' . '</a>';
       $content .= "<a href='" . $permalink . "&pid=" . '' . "&ged=" . $ged . "&mode=tree" . "'>";
       $content .= '<img src="'. WP_PLUGIN_URL . '/rootspress/images/gedcom.gif"' . ' title="' . __( 'Interactive tree', 'rpress_main' ). '" class="rp_icons" '  . '>' . '</a>';

       $content .= "<a href='" . $permalink . "&pid=" . $pid . "&ged=" . $ged . "&mode=base" . "'>";
       $content .= '<img src="'. WP_PLUGIN_URL . '/rootspress/images/indis.gif"' . ' title="' . __( 'Individual page', 'rpress_main' ). '" class="rp_icons" '  . '>' . '</a>';
       $help_link =  WP_PLUGIN_URL . '/rootspress/pgv/help/help.php';
       $content .=  '<a href="#"  onclick="TINY.box.show({url:' . "'$help_link?id=204&amp;locale=$locale',width:400,height:600,opacity:50,topsplit:2, close:true})" . '" >';
       $content .= '<img title="' . __( 'Help', 'rpress_main' ) . '" class="rp_icons"  src=' . WP_PLUGIN_URL . '/rootspress/images/help.gif' . '>';
       $content .= '</a>';

       $content .=  '</h2>';
       
//       $page_data = get_page($row['wp_home_id']); // You must pass in a variable to the get_page function. If you pass in a value (e.g. get_page ( 123 ); ), WordPress will generate an error.
//       $content .= apply_filters('the_content', $page_data->post_content); // Get Content and retain Wordpress filters such as paragraph tags. Origin from: http://wordpress.org/support/topic/get_pagepost-and-no-paragraphs-problem
        $content .= apply_filters('the_content', $row['home_html']); // Get Content and retain Wordpress filters such as paragraph tags. Origin from: http://wordpress.org/support/topic/get_pagepost-and-no-paragraphs-problem

       return $content;   //Add to any exiting content
   }
} //End function

function secure_page ($ged, &$content) {
        $permalink = page_link($ged);
        $args = array(
        'echo' => false,   //return string
        'redirect' =>  $permalink,   //ie current page
        'value_remember' => false );
       if(security($ged) && !is_user_logged_in() ) {
       $content .= '<h2 class="titleline">' . $_SESSION['tree_name'] . ' family tree' . '</h2>'  ;
       $content .= "<a href='" . $permalink . "&pid=" . '' . "&ged=" . $ged . "&mode=home" . "'>";
       $content .= '<img src="'. WP_PLUGIN_URL . '/rootspress/images/home.gif"' . ' title="' . __( 'Home', 'rpress_main' ). '" class="rp_icons" '  . '>' . '</a>';

         $content .= '<br/><h4>' .  __( 'You must login to view this tree. Contact the administrator if you wish to register. Click the icon at right to return to the home page . ', 'rpress_main' ) . '</h4>';
         $content .= wp_login_form($args);
       return true;
       }  else {
         return false;
       }

} //End function
?>