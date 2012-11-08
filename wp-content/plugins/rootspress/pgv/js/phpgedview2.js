function onPortraitErrorPGV(source, type)
{
mynode= source.parentNode.parentNode;
mynode.innerHTML='<img class="portrait" src="wp-content/plugins/rootspress/pgv/images/silhouette_' + type +'.gif">';

// disable onerror to prevent endless loop
source.onerror = "";
return true;
}

  function findPosX(obj)
  {
    var curleft = 0;
    if(obj.offsetParent)
        while(1)
        {
          curleft += obj.offsetLeft;
          if(!obj.offsetParent)
            break;
          obj = obj.offsetParent;
        }
    else if(obj.x)
        curleft += obj.x;
    return curleft;
  }

  function findPosY(obj)
  {
    var curtop = 0;
    if(obj.offsetParent)
        while(1)
        {
        	if (obj.style.position=="relative") break;
          curtop += obj.offsetTop;
          if(!obj.offsetParent)
            break;
          obj = obj.offsetParent;
        }
    else if(obj.y)
        curtop += obj.y;
    return curtop;
  }

  function createXMLHttp()
{
	if (typeof XMLHttpRequest != "undefined")
	{
		return new XMLHttpRequest();
	}
	else if (window.ActiveXObject)
	{
		var ARR_XMLHTTP_VERS=["MSXML2.XmlHttp.5.0","MSXML2.XmlHttp.4.0",
			"MSXML2.XmlHttp.3.0","MSXML2.XmlHttp","Microsoft.XmlHttp"];

		for (var i = 0; i < ARR_XMLHTTP_VERS.length; i++)
		{
			try
			{
				var oXmlHttp = new ActiveXObject(ARR_XMLHTTP_VERS[i]);
				return oXmlHttp;
			}
			catch (oError) {;}
		}
	}
	throw new Error("XMLHttp object could not be created.");
};

/**
 * function to extract JS code from a text string.  Useful to call when loading
 * content dynamically through AJAX which contains a mix of HTML and JavaScript.
 * retrieves all of the JS code between <script></script> tags and adds it as a <script> node
 * @param string text   the text that contains a mix of html and inline javascript
 * @param DOMElement parentElement	the element that the text and JavaScript will added to
 */
function evalAjaxJavascript(text, parentElement) {
	parentElement.innerHTML = "";
	/* -- uncomment for debugging
	debugelement = document.createElement("pre");
	debugelement.appendChild(document.createTextNode(text));
	parentElement.appendChild(debugelement);
	*/
	pos2 = -1;
	//-- find the first occurrence of <script>
	pos1 = text.indexOf("<script", pos2+1);
	while(pos1>-1) {
		//-- append the text up to the <script tag to the content of the parent element
		parentElement.innerHTML += text.substring(0, pos1);

		//-- find the close of the <script> tag
		pos2 = text.indexOf(">",pos1+5);
		if (pos2==-1) {
			parentElement.innerHTML += "Error: incomplete text";
			return;
		}
		//-- create a new <script> element to add to the parentElement
		jselement = document.createElement("script");
		jselement.type = "text/javascript";
		//-- look for any src attributes
		scripttag = text.substring(pos1, pos2);
		regex = new RegExp("\\ssrc=\".*\"", "gi");
		results = scripttag.match(regex);
		if (results) {
			for(i=0; i<results.length; i++) {
				src = results[i].substring(results[i].indexOf("\"")+1, results[i].indexOf("\"", 6));
				src = src.replace(/&amp;/gi, "&");
				jselement.src = src;
			}
		}
		opos1 = pos1;
		pos1 = pos2;
		//-- find the closing </script> tag
		pos2 = text.indexOf("</script",pos1+1);
		if (pos2==-1) {
			parentElement.innerHTML += "Error: incomplete text";
			return;
		}
		//-- get the JS code between the <script></script> tags
		if (!results || results.length==0) {
			jscode = text.substring(pos1+1, pos2);
			if (jscode.length>0) {
				ttext = document.createTextNode(jscode);
				//-- add the JS code to the <script> element as a text node
				jscode=jscode.replace(/<!--/g, ''); // remove html comment [ 1737256 ]
				jscode=jscode.replace(/function ([^( ]*)/g,'window.$1 = function');
				eval(jscode);
			}
		}
		//-- add the javascript element to the parent element
		parentElement.appendChild(jselement);
		//-- shrink the text for the next iteration
		text = text.substring(pos2+9, text.length);
		//-- look for the next <script> tag
		pos1 = text.indexOf("<script");
	}
	//-- make sure any HTML/text after the last </script> gets added
	parentElement.innerHTML += text;
}

function info(page){
var theWin = null;
var theURL = rp_siteURL.siteurl+"/wp-content/plugins/rootspress/pgv/help/help.php?id=" + page;
theWin = window.open(''+theURL,'mywin', 'left=20,top=20,width=500,height=500,toolbar=0,location=0,resizable=0, scrollbars=1');

}
