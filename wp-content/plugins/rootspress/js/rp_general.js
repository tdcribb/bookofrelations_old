function onImgErrorSmall(source)
{

source.src = "/images/no-image-100px.gif";

// disable onerror to prevent endless loop
source.onerror = "";
return true;
}

function onImgErrorLarge(source)
{
source.src = "/images/no-image-200px.gif";
// disable onerror to prevent endless loop
source.onerror = "";
return true;
}

function onPortraitError(source, type)
{
source.src = "wp-content/plugins/rootspress/images/silhouette_" + type +".gif";

// disable onerror to prevent endless loop
source.onerror = "";
return true;
}

 function rp_toggle(showHide, switchImg) {
        var ele = document.getElementById(showHide);
        var imageEle = document.getElementById(switchImg);
        ihtml = rp_siteURL.siteurl+'/wp-content/plugins/rootspress/images/';
        if(ele.style.display == "block") {
                ele.style.display = "none";
		imageEle.innerHTML = '<img src="' + ihtml+ 'plus.png"/><strong>'+rpress_main.source+'</strong>';
        }
        else {
                ele.style.display = "block";
          //      imageEle.src = "/wordpress/wp-content/plugins/rootspress/images/minus.png";

         //       imageEle.innerHTML = '<img src="/wordpress/wp-content/plugins/rootspress/images/minus.png"/>';
	  imageEle.innerHTML = '<img src="' + ihtml+ 'minus.png"/><strong>'+rpress_main.source+'</strong>';

        }
}

function confirmation(retpage) {
	var answer = confirm(rpress_main.confirm)
	if (answer){
           window.location = retpage;
	}
}

function tPreview(formname) {
var myvar = document.rpress_form3.elements["tnopt[]"];
document.getElementById("prv_hr1").style.borderColor='#'+myvar[1].value;
document.getElementById("prv_hr1").style.borderStyle='#'+myvar[1].value;
document.getElementById("prv_hr1").style.backgroundColor='#'+myvar[1].value;

document.getElementById("prv_hr2").style.borderColor='#'+myvar[1].value;
document.getElementById("prv_hr2").style.borderStyle='#'+myvar[1].value;
document.getElementById("prv_hr2").style.backgroundColor='#'+myvar[1].value;

document.getElementById("prv_bg").style.backgroundColor='#'+myvar[0].value;

document.getElementById("prv_box").style.backgroundColor='#'+myvar[2].value;
document.getElementById("prv_box").style.border='2px solid '+'#'+myvar[3].value;
}