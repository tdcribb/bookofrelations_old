  var iconArray = new Array(
    rp_siteURL.siteurl+'/wp-content/plugins/rootspress/images/orange.png',
    rp_siteURL.siteurl+'/wp-content/plugins/rootspress/images/red.png',
    rp_siteURL.siteurl+'/wp-content/plugins/rootspress/images/pink.png',
    rp_siteURL.siteurl+'/wp-content/plugins/rootspress/images/blue.png',
    rp_siteURL.siteurl+'/wp-content/plugins/rootspress/images/ltblue.png',
    rp_siteURL.siteurl+'/wp-content/plugins/rootspress/images/green.png',
    rp_siteURL.siteurl+'/wp-content/plugins/rootspress/images/yellow.png',
    rp_siteURL.siteurl+'/wp-content/plugins/rootspress/images/purple.png',
    rp_siteURL.siteurl+'/wp-content/plugins/rootspress/images/black.png'
      );
      
 function geogrcv(obj) {
//Remove previous markers if any
for (i=0; i<GmarkersArray.length;i++) {
      GmarkersArray[i].setMap(null);
      }
   if (GmarkersArray.length>0) {
      GmarkersArray.length = 0;
     }

   var i=0;
   while (obj[i] != undefined) {
     label=obj[i].title;
     lng=obj[i].wgs84_long;
     lat=obj[i].wgs84_lat;

var imagefile = obj[i].image;
var thumb = obj[i].thumbnail;
var caption = obj[i].title;
caption=caption.replace(/'/g, "`");   //replace apostrephe by pseudo
if(obj[i].comment != undefined) {
//detail="<b>"+obj[i].comment+"</b><br/>";
  detail=obj[i].comment+"<br/>";
}  else detail = '';
profile='<i>by </i><a href="http://www.geograph.org.uk'+obj[i].profile_link+'" target="_blank">';
name='<i>\"'+obj[i].realname+'\"</i>';
var c1 = '<a href="#" onclick="jQuery.slimbox(' +"'"+imagefile + "','" + caption + "'); return false;" + '">';
var c2= '<img style="float: left; margin: 0 5px 5px 0; line-height: 70%" src=\"'+thumb+'\"></a>';
var content = c1+c2+'<p style="font-size: smaller; text-align: left">'+detail+profile+name+'</p>';

createGGraph(map, lat, lng, label, content);
i++;
   }
if(i==0) {
  alert(rpress_main.noGeog);
  return;
}
//Centre on icons
var bounds = new google.maps.LatLngBounds ();
for (var i = 0, LtLgLen = cLatLng.length; i < LtLgLen; i++) { bounds.extend (cLatLng[i]); }
map.fitBounds (bounds);

}

 function geogrmv() {
//Remove previous markers if any
for (i=0; i<GmarkersArray.length;i++) {
      GmarkersArray[i].setMap(null);
      }
   if (GmarkersArray.length>0) {
      GmarkersArray.length = 0;
     }
 }

 function panorcv(obj) {
//Remove previous markers if any
for (i=0; i<GmarkersArray.length;i++) {
      GmarkersArray[i].setMap(null);
      }
   if (GmarkersArray.length>0) {
      GmarkersArray.length = 0;
     }

   var setlen = obj.photos.length;
   if(setlen == 0) {
     alert(rpress_main.noPana);
     return;   //if no images
   }
   var i=0;
   for (i=0; i<setlen; i++) {
     label=obj.photos[i].photo_title;
     lng=obj.photos[i].longitude;
     lat=obj.photos[i].latitude;

var thumb = obj.photos[i].photo_file_url;
var imagefile = thumb.replace("thumbnail", "medium")
var caption = obj.photos[i].photo_title;
caption=caption.replace(/'/g, "`");   //replace apostrephe by pseudo
var detail = '';
profile='<i>by </i><a href=\"'+obj.photos[i].owner_url+'\" target="_blank">';
name='<i>\"'+obj.photos[i].owner_name+'\"</i>';
var c1 = '<a href="#" onclick="jQuery.slimbox(' +"'"+imagefile + "','" + caption + "'); return false;" + '">';
var c2= '<img style="float: left; margin: 0 5px 5px 0; line-height: 70%" src=\"'+thumb+'\"></a>';
var content = c1+c2+'<p style="font-size: smaller; text-align: left">'+detail+profile+name+'</p>';

createGGraph(map, lat, lng, label, content);
i++;
   }
//Centre on icons
var bounds = new google.maps.LatLngBounds ();
for (var i = 0, LtLgLen = cLatLng.length; i < LtLgLen; i++) { bounds.extend (cLatLng[i]); }
map.fitBounds (bounds);
/*google.maps.event.addListenerOnce(map, "idle", function() {
  if (map.getZoom() > 11) map.setZoom(11);
  alert(map.getZoom);
});  */
 }

// Call geograph by building script elements
       function geogcall(lat, lon)
            {   var headID = document.getElementsByTagName("head")[0];
                var newScript = document.createElement('script');
                newScript.type = 'text/javascript';
                newScript.src = 'http://api.geograph.org.uk/api/Latlong/0.5km/'+lat+','+lon+'/?output=json&callback=geogrcv';
                headID.appendChild(newScript);
             }

// Call panoramio by building script elements
       function panocall(lat, lon)
            {   var headID = document.getElementsByTagName("head")[0];
                var newScript = document.createElement('script');
                newScript.type = 'text/javascript';
//Compute bounding box
var range = 1; //1km range
dlat=range*0.5/111.12
dlon= Math.cos(lat/57.3)*range*0.5/111.12
minx=lon-dlon;
maxx=lon+dlon;
miny=lat-dlat;
maxy=lat+dlat;
newScript.src = 'http://www.panoramio.com/map/get_panoramas.php?set=full&from=0&to=10&minx='+minx+'&miny='+miny+'&maxx='+maxx+'&maxy='+maxy+'&size=thumbnail&mapfilter=true&output=json&callback=panorcv';

                headID.appendChild(newScript);
             }


function getMarkerImage(iconIndex) {
     myicon = new google.maps.MarkerImage(iconArray[iconIndex],
//      new google.maps.Size(12, 20),
      new google.maps.Size(32, 32),
      new google.maps.Point(0,0),
      new google.maps.Point(6, 20));
   return myicon;
}

function getMarkerShadow() {
  var iconShadow = new google.maps.MarkerImage( rp_siteURL.siteurl+'/wp-content/plugins/rootspress/images/shadow50.png',
      new google.maps.Size(37, 34),
      new google.maps.Point(0,0),
      new google.maps.Point(0, 20)
      );
  return iconShadow;
}

//MarkerImage(url:string, size?:Size, origin?:Point, anchor?:Point, scaledSize?:Size)

function getMarkerShape() {
  var iconShape = {
      coord: [4,0,0,4,0,7,3,11,4,19,7,19,8,11,11,7,11,4,7,0],
      type: 'poly'
  };
  return iconShape;
}
// This function picks up the click and opens the corresponding info window
function myclick(i) {
  google.maps.event.trigger(gmarkers[i], "click");
}

function createMarker(map, lat, lng, label, html, index) {
var ggLink = '<a href="#" onclick="geogcall(' +lat+','+lng+');return false;"><img src='+rp_siteURL.siteurl+'/wp-content/plugins/rootspress/images/geograph_marker.png style="width: 18px; height: 18px;" align=left></a>';
var pnLink = '<a href="#" onclick="panocall(' +lat+','+lng+');return false;"><img src='+rp_siteURL.siteurl+'/wp-content/plugins/rootspress/images/panoramio_marker.png style="width: 18px; height: 18px;" align=left></a>';
    var contentString = '<div style="height:100px; " ><b>'+label+'</b><br>'+html+'<br>'+ggLink+'&nbsp; &nbsp; &nbsp;'+pnLink+'</div>';
    var myLatLng = new google.maps.LatLng( lat, lng);
        bLatLng.push(myLatLng);
    var marker = new google.maps.Marker({
        position: myLatLng,
        map: map,
        shadow: getMarkerShadow(),
        icon: getMarkerImage(index),
//        size: (75, 32),
        size: (32, 32),
//        shape: getMarkerShape(),
        title: label,
        zIndex: Math.round(myLatLng.lat()*-100000)<<5
        });

    google.maps.event.addListener(marker, 'click', function() {
        infowindow.setContent(contentString); 
        infowindow.open(map,marker);
        geogrmv();
        });
    // save the info we need to use later for the side_bar
    gmarkers.push(marker);
    // add a line to the side_bar html
side_bar_html += '<a href="javascript:myclick(' + (gmarkers.length-1) + ')">' + '<img src="'+iconArray[index]+'" width="24px" height="24px" />' + label + '<\/a><br>';
}


function createGGraph(map, lat, lng, label, html) {
    var contentString = '<div><b>'+label+'</b><br>'+html+'<br>'+'</div>';

    var image =  rp_siteURL.siteurl+'/wp-content/plugins/rootspress/images/camera.png';
    var myLatLng = new google.maps.LatLng( lat, lng);
    cLatLng.push(myLatLng);
    var marker = new google.maps.Marker({
        position: myLatLng,
        map: map,
//        shadow: getMarkerShadow(),
        icon: image,
        title: label
//        zIndex: Math.round(myLatLng.lat()*-100000)<<5
        });
        
    GmarkersArray.push(marker);

    google.maps.event.addListener(marker, 'click', function() {
        infowindow.setContent(contentString);
        infowindow.open(map,marker);
        });
}