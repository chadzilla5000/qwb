
function load_console(url, wd, ht){ alert(url); return false;
var	obj = document.getElementById("CConsole");
var	pln = document.getElementById("consolink");
	pln.href = url+"&cns=pf";
	obj.style.display = "block";
	obj.style.width = wd;
	obj.style.height = ht;
	query_srv(url, \'InConsole\');
	return false;
}
function ClsConsole(){
	document.getElementById("CConsole").style.display = "none";
	return;
}
