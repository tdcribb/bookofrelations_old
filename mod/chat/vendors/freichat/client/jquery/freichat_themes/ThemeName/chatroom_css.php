.frei_roomcontainer {
width:500px;
content:overflow;
}

#chatroom_branding {
bottom:65px;
position:absolute;
display:block;
}

#chatroom_branding a {
color:blue;
text-decoration:none;
}

#chatroom_branding a:link,
#chatroom_branding a:visited, 
#chatroom_branding a:hover, 
#chatroom_branding a:active {
text-decoration:none;
color:blue;
background-color:none;
}

.frei_roomtitle {
font-weight:bold;
color:white;
font-size:14px;
padding:4px;
border:0px;
text-align:center;
background-image:url('<?php echo $freichat_theme;  ?>/<?php echo $chatroom_head; ?>');
text-transform:uppercse;
background:#800000;
height:22px;;
}

.frei_chatroompanel {
height:364px;
border-right:solid grey 1px;
border-bottom:solid grey 1px;
background-image:url('<?php echo $freichat_theme; ?>/<?php echo $chatroomimg; ?>');
}

.frei_chatroomleftpanel {
float:left;
background-image:url('<?php echo $freichat_theme;  ?>/<?php echo $chatroom_leftpanel; ?>');
width:348px;
}

.frei_chatroomrightpanel {
float:left;
width:200px;
border-left:solid grey 1px;
}

.chatroommessagearea {
resize:none;
height:100%;
font-size:11px;
outline:0;
width:100%;
padding:0px;
font-style:helvetica;
}

.frei_chatroomtextarea {
width:345px;
height:52px;
}

.frei_roompanel {
border-top:grey solid 1px;
width:100%;
height:182px;
overflow:auto;
}

.frei_userpanel {
width:100%;
height:182px;
overflow:auto;
}

.frei_chatroommsgcnt {
text-align:justify;
width:100%;
height:310px;
overflow:auto;
font-size:11px;
font-style:arial, helvetica, sans-serif;
}

.frei_chatroom_message {
padding-left:5px;
}

.frei_room_close {
position:absolute;
right:10px;
font-size:18px;
top:5px;
}

.frei_lobby_room {
border-bottom:1px solid #CCC;
border-bottom-width:1px;
border-bottom-style:solid;
border-bottom-color:#CCC;
color:#000000;
display:block;
font-family:"Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif;
font-size:11px;
padding-bottom:7px;
padding-top:7px;
width:100%;
cursor:pointer;
height:15px;
}

.frei_selected_room {
border-bottom:1px solid #CCC;
border-bottom-width:1px;
border-bottom-style:solid;
border-bottom-color:#CCC;
color:#FFFFFF;
background-image:url('<?php echo $freichat_theme;  ?>/<?php echo $chatroom_selected; ?>');
display:block;
font-family:"Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif;
font-size:11px;
padding-bottom:7px;
padding-top:7px;
width:100%;
cursor:pointer;
height:15px;
font-weight:bold;
z-index:500;
}

.frei_chat_userlist_hover {
background-color:#B2C9F1;
color:#000000;
}

.frei_chat_chatroomselected {
background-color:#333333;
font-weight:bold;
}

.frei_lobby_room_1 {
float:left;
padding-left:5px;
}

.frei_lobby_room_2 {
float:right;
padding-right:5px;
width:70px;
text-align:right;
}

.frei_lobby_room_3 {
float:right;
padding-right:0px;
}

.frei_lobby_room_4 {
float:right;
padding-right:0px;
}

.frei_chatroom {
width:550px;
padding:0px;
font-family:"Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif;
height:400px;
font-size:11px;
background-repeat:no-repeat;
}

.frei_chatroom .frei_chatroom-content ul {
list-style:square;
margin:0 0 0 25px;
line-height:1.frei_3em;
}

.frei_chatroom .frei_chatroom-content ul li {
font-size:10px;
}

.frei_chatroom .frei_chatroom-content ul li a {
color:#fff;
font-size:13px;
}

.frei_chatroom .frei_chatroom-content ul li a:hover {
color:#000;
}

.frei_chatroom.top {
top:0;
}

.frei_chatroom.right {
right:0;
}

.frei_chatroom.bottom {
bottom:0;
}

.frei_chatroom.left {
left:0;
}

.frei_chatroom .frei_tab {
cursor:pointer;
font-size:12px;
line-height:12px;
background:#333;
padding:6px;
color:#fff;
font-weight:bold;
border-top-right-radius:5px;
border-bottom-right-radius:5px;
}

.frei_chatroom, .frei_chatroom .frei_tab {
border-left:0px solid #ccc;
}

.frei_chatroom.right .frei_tab, .frei_chatroom.left .frei_tab {
top:3px;
}

.frei_chatroom.top .frei_tab {
bottom:3px;
background-position:0 100%;
}

.frei_chatroom.top, .frei_chatroom.top .frei_tab {
border-top:none;
}

.frei_chatroom.right .frei_tab {
left:3px;
margin-top:-3px;
}

.frei_chatroom.right, .frei_chatroom.right .frei_tab {
border-right:none;
}

.frei_chatroom.bottom .frei_tab {
top:3px;
background-position:0 0;
}

.frei_chatroom.bottom, .frei_chatroom.bottom .frei_tab {
border-bottom:none;
}

.frei_chatroom.left .frei_tab {
right:3px;
margin-top:-3px;
background-position:100% 0;
}

.frei_chatroom.left, .frei_chatroom.left .frei_tab {
border-left:none;
width:550px;
}

.frei_chatroom.align-right .frei_tab {
margin-right:-3px;
}

.frei_chatroom.align-left .frei_tab {
margin-left:-3px;
width:64px;
}

.frei_chatroom-content p {
margin-bottom:1em;
line-height:1.5em;
}

.frei_chatroom-content a {
color:#fff;
}

.frei_userlist_onhover {
background-color:#B2C9F1;
color:#000000;
text-align:left;
}

.frei_userlist {
cursor:pointer;
height:20px;
line-height:100%;
text-align:left;
padding-top:6px;
padding-bottom:1px;
padding-left:4px;
border-bottom:1px solid #CCC;
border-bottom-width:1px;
border-bottom-style:solid;
border-bottom-color:#CCC;
}

.chatroom_messagefrom_left {
text-align:left;
padding-left:30px;
font-weight:bold;
color:#000000;
}

.chatroom_messagefrom_right {
text-align:right;
padding-left:240px;
font-weight:bold;
color:#000000;
}

.frei_chatroom_msgcontent {
background:transparent;
color:white;
font-weight:bold;
font-size:8pt;
opacity:0.97;
z-index:10000;
}