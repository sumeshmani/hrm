<script>
//Ajax to get User Serch View in adduser.php form starts here
function GetXmlHttpObject2()
    { 
        var objXMLHttp=null;
        if (window.XMLHttpRequest)
            {
				// browser has native support for XMLHttpRequest object
                objXMLHttp=new XMLHttpRequest();
            }
        else if (window.ActiveXObject)
            {
				// try XMLHTTP ActiveX (Internet Explorer) version
                objXMLHttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
        return objXMLHttp;
    }

function getuserserchview() 
    {
xmlHttp=GetXmlHttpObject2()
if (xmlHttp==null) {
	alert ("Your browser does not support HTTP Request");
	return
                   } 
var user = document.getElementById("searchu").value;
if ((user == null) || (user == "")) return;
var url = "usersearchview.php?user=" + escape(user);
xmlHttp.open("GET", url, true);
xmlHttp.onreadystatechange = updateProject2;
xmlHttp.send(null);
//check();
    }

function updateProject2() {
  if (xmlHttp.readyState == 4 || xmlHttp.readyState=="complete") { 
	document.getElementById("usersearch").innerHTML =xmlHttp.responseText;
  }
}
//Ajax to get usersearchview in adduser.php  form ends here
//Ajax for pending search in ncrreport.php starts here
function GetXmlHttpObject3()
    { 
        var objXMLHttp=null;
        if (window.XMLHttpRequest)
            {
				// browser has native support for XMLHttpRequest object
                objXMLHttp=new XMLHttpRequest();
            }
        else if (window.ActiveXObject)
            {
				// try XMLHTTP ActiveX (Internet Explorer) version
                objXMLHttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
        return objXMLHttp;
    }

function getpendingview() {
xmlHttp=GetXmlHttpObject3()
if (xmlHttp==null) {
	alert ("Your browser does not support HTTP Request");
	return
} 
//var pending = document.getElementByName(pend).value;
  for (var i = 0; i < document.getElementsByName('pend').length; i++)
    {
    	if (document.getElementsByName('pend')[i].checked)
    	{
    		var pending = document.getElementsByName('pend')[i].value;
    	}
    }
//alert("pending" +pending);
if ((pending == null) || (pending == "")){alert("no data selected");return}
var url = "ncrpendingview.php?pending=" + escape(pending);
xmlHttp.open("GET", url, true);
xmlHttp.onreadystatechange = updateProject3;
xmlHttp.send(null);
//check();
}

function updateProject3() {
  if (xmlHttp.readyState == 4 || xmlHttp.readyState=="complete") { 
	document.getElementById("ncrpendingsearch").innerHTML =xmlHttp.responseText;
  }
}
//Ajax for pending search in ncrreport.php Ends here
function GetXmlHttpObject4()
    { 
        var objXMLHttp=null;
        if (window.XMLHttpRequest)
            {
				// browser has native support for XMLHttpRequest object
                objXMLHttp=new XMLHttpRequest();
            }
        else if (window.ActiveXObject)
            {
				// try XMLHTTP ActiveX (Internet Explorer) version
                objXMLHttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
        return objXMLHttp;
    }

function kpigroupselect() {
xmlHttp=GetXmlHttpObject4()
if (xmlHttp==null) {
	alert ("Your browser does not support HTTP Request");
	return
} 
//var pending = document.getElementByName(pend).value;
var groupid= document.getElementById("groupid").value;
var kpiParentId= document.getElementById("Kpi_ParentId").value;
//alert("parent id is "+kpi_ParentId);
var url = "kpidetailsaddajax.php?groupidajax="+escape(groupid)+"&kpiParentId="+escape(kpiParentId);
xmlHttp.open("GET", url, true);
xmlHttp.onreadystatechange = updateProject4;
xmlHttp.send(null);
//check();
}

function updateProject4() {
  if (xmlHttp.readyState == 4 || xmlHttp.readyState=="complete") { 
	document.getElementById("kpiadddetails").innerHTML =xmlHttp.responseText;
  }
}
//to get arabic calender 
function GetXmlHttpObject6()
    { 
        var objXMLHttp=null;
        if (window.XMLHttpRequest)
            {
				// browser has native support for XMLHttpRequest object
                objXMLHttp=new XMLHttpRequest();
            }
        else if (window.ActiveXObject)
            {
				// try XMLHTTP ActiveX (Internet Explorer) version
                objXMLHttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
        return objXMLHttp;
    }

function getentoardate() {
xmlHttp=GetXmlHttpObject6()
if (xmlHttp==null) {
	alert ("Your browser does not support HTTP Request");
	return
} 
//var pending = document.getElementByName(pend).value;
var entoar= document.getElementById("entoar").value;
//var kpiParentId= document.getElementById("Kpi_ParentId").value;
//alert("parent id is "+kpi_ParentId);
var url = "dateaddajax.php?entoar="+escape(entoar);
xmlHttp.open("GET", url, true);
xmlHttp.onreadystatechange = updateProject6;
xmlHttp.send(null);
//check();
}

function updateProject6() {
  if (xmlHttp.readyState == 4 || xmlHttp.readyState=="complete") { 
	document.getElementById("arabicdate").innerHTML =xmlHttp.responseText;
  }
}
//vacation planning to see allowed vacation days


</script>		  