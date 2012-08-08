<?php
/**
 * Walled garden CSS
 */

$url = elgg_get_site_url();

?>
.AG-70 {font:normal 70px 'Academy Engraved LET';}   
.AG-60 {font:normal 60px 'Academy Engraved LET';}     
.AG-38 {font:normal 38px 'Academy Engraved LET';}

#coming-soon-overlay {position:absolute; top:75px; left:0px; width:100%; height:475px; background:#fff; z-index:999; display:none;}   
#coming-soon-img {margin-top:50px !important; width:250px; margin:auto;}    
#coming-soon-mssg {width:100%; text-align:center; font-size:30px; margin-top:20px;}   
#coming-soon-mssg span {font:normal 34px 'Academy Engraved LET';}
        
#elgg-walledgarden-login {background:none !important;}

.elgg-body-walledgarden {margin: 100px auto 0 auto; position: relative; width: 530px;}
.elgg-module-walledgarden {position: absolute; top: 0; left: 0;}
.elgg-module-walledgarden > .elgg-head {height: 17px;}
.elgg-module-walledgarden > .elgg-body {padding: 0 10px;}
.elgg-module-walledgarden > .elgg-foot {height: 17px;} 

.elgg-walledgarden-double > .elgg-head {background: url(<?php echo $url; ?>_graphics/walled_garden/two_column_top.png) no-repeat left top;}
.elgg-walledgarden-double > .elgg-body {background: url(<?php echo $url; ?>_graphics/walled_garden/two_column_middle.png) repeat-y left top;}
.elgg-walledgarden-double > .elgg-foot {background: url(<?php echo $url; ?>_graphics/walled_garden/two_column_bottom.png) no-repeat left top;}
.elgg-walledgarden-single > .elgg-head {background: url(<?php echo $url; ?>_graphics/walled_garden/one_column_top.png) no-repeat left top;}
.elgg-walledgarden-single > .elgg-body {background: url(<?php echo $url; ?>_graphics/walled_garden/one_column_middle.png) repeat-y left top;}
.elgg-walledgarden-single > .elgg-foot {background: url(<?php echo $url; ?>_graphics/walled_garden/one_column_bottom.png) no-repeat left top;}

.elgg-col > .elgg-inner {margin: 0 0 0 5px;}
.elgg-col:first-child > .elgg-inner {margin: 0 5px 0 0;}
.elgg-col > .elgg-inner {padding: 0 8px;}

.elgg-walledgarden-single > .elgg-body {padding: 0 18px;}

.elgg-module-walledgarden-login {margin: 0; margin-top:10px;}
.elgg-body-walledgarden h3 {font-size:18px; color:#888; text-transform:uppercase; margin-bottom:10px;}

.elgg-heading-walledgarden {margin-top: 30px; line-height: 1.1em; font-size:40px; height:185px; margin-left:20px;}   
.elgg-heading-walledgarden img, .elgg-heading-walledgarden div {position:relative; float:left;}   
.title-ook {margin-top:25px; margin-left:5px; height:60px;}  
#login-footer-links {text-align:center;}  
#login-footer-links a {color:#444;}   

.book-login .elgg-button-submit, .book-login .elgg-button {background: url(<?php echo $url; ?>images/buttons/login-button.png) no-repeat; width:136px; height:43px; border:none !important; text-shadow:none !important; text-decoration:none; border-radius:0 !important; box-shadow:none !important;}  
.book-login label {font-size:10px;}

h1, h2, h3, h4, h5, h6 {color: #666;}

a {color: #999;}    

.fb_edge_widget_with_comment {width:300px;}     
.twitter-share-button {margin-left:20px;}