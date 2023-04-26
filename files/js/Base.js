
var rfr=0;


function showhide22(o){
	alert(o);
		return;
}


function query_srv( url, trgt ) { //alert(url); return false;
	var x = typeof trgt === 'object' ? trgt : document.getElementById(trgt);
	var req = new XMLHttpRequest();
	req.onreadystatechange = function() {
		if( req.readyState == 4 ) {
			if( req.status>=200 && req.status<300 ) { x.innerHTML = req.responseText; }
		} else x.innerHTML = '  . . . Retrieving...';
	}
	req.open( "GET", url, true );
	req.setRequestHeader( "content-type", "application/x-www-form-urlencoded" );
	req.send( null );
	return true;
}


function perform( perfURL ) {
	var XHR = new XMLHttpRequest();
	XHR.open( 'GET', perfURL, false );
	XHR.send();
	return XHR.responseText;
}

function rmv(p){
if(confirm('Are you sure you want to remove this document?')){
var url = '/inc/updb.php?ds=rmvsavedoc&pth='+p;
var prt = perform(url); 
	if(prt){alert(prt);}
	else{window.location.reload();}
	}
return false;
}

function passtep(obj){
if(!(obj.value>0)){ return false; }
	alert(obj.value);
}

function removestep(o){ 
var url = 'updb.php?ds=removestep&stid='+o.name;
//var wr = window.open(url, "wName", "resizable,scrollbars,status"); return;
var prt = perform(url); 
	if(prt){alert(prt);}
	else{window.location.reload();}
return false;
}

function opup(obj){
//alert(obj.name); return;	
shade('Добавить процесс');
var url = 'updb.php?ds=dispsteps&o='+obj.name;
	query_srv( url, 'pupcnt');
	return false;
}


function dynsrchproc(o){
if(o.value.length>=3){
var odt = document.getElementById('odt').value;	
var url = 'updb.php?ds=dispsteps&o='+odt+'&val='+o.value;
	query_srv( url, 'pupcnt');
	}
return false;
}

function whpup(obj){
shade('Авторизация');
f4wh.bcwh.value='';
f4wh.bcwh.focus();
	return false;
}


function cpup(){
	document.getElementById('Shed').style.display  = 'none';	
	document.getElementById('popup').style.display = 'none';
	if(rfr){window.location.reload();}
	return false;
}

function addrmvproc(o){
	rfr=1;
var url = '';
if(o.checked){ url = '/lstat/updb.php?ds=addrmvproc&on='+o.name+'&d=1'; }
else         { url = '/lstat/updb.php?ds=addrmvproc&on='+o.name+'&d=0'; }
var prt = perform(url); 
	if(prt){alert(prt);}
	return;
}

function assignbrg(o){
var url = '/lstat/updb.php?ds=assignbrg&on='+o.name+'&v='+o.value;
var prt = perform(url); 
	if(prt){alert(prt);}
	return;
}

function addstg(itd){
var url = 'updb.php?ds=addstg&it='+itd;
var prt = perform(url); 
	if(prt){alert(prt);}
	else{window.location.reload();}
return false;
}

function removestage(o){ 
if(confirm('Уверены что хотите удалить этап?')){
var url = 'updb.php?ds=removestage&stg='+o.name;
//var wr = window.open(url, "wName", "resizable,scrollbars,status"); return;
var prt = perform(url); 
	if(prt){alert(prt);}
	else{window.location.reload();}
	}
return false;
}

function svstgttl(o){
var url = '/lstat/updb.php?ds=svstgttl&on2='+o.name+'&v2='+o.value;
var prt = perform(url); 
	if(prt){alert(prt);}
	return;
}


function movestg(o){
var url = '/lstat/updb.php?ds=movestg&onm='+o.name;
var prt = perform(url); 
if( prt==='scs' ){window.location.reload();}
else { alert(prt); }
	return;
}


function movestep(o){
var url = '/lstat/updb.php?ds=movestep&on3='+o.name;
var prt = perform(url); 
if( prt==='scs' ){window.location.reload();}
else { alert(prt); }
	return;
}

function spreadstg(o){
var str = o.id;	
var vrp = str.replace('StgLst_','TrgSpr_');
var t1 =o.src; 
if(o.src.match('spread')){ 
	o.src=o.src.replace('spread','shrink'); 
	o.title='Свернуть';
	}
else{ 
	o.src=o.src.replace('shrink','spread'); 
	o.title='Раскрыть';
	document.getElementById(vrp).innerHTML  = ''; 
	return false; 
	}
var url = 'updb.php?ds=spreadstg&o='+str;
	query_srv(url, vrp);
	return false;
	
}

function spreadproc(o){
var str = o.id;	
var vrp = str.replace('PrLst_','PrTrg_');
var t1 =o.src; 
if(o.src.match('spread')){ 
	o.src=o.src.replace('spread','shrink'); 
	o.title='Свернуть';
	}
else{ 
	o.src=o.src.replace('shrink','spread'); 
	o.title='Раскрыть';
	document.getElementById(vrp).innerHTML  = ''; 
	return false; 
	}
var url = 'updb.php?ds=spreadproc&o='+str;
	query_srv(url, vrp);
	return false;
	
}
function StgType(o){
//	alert(o.name);return;
if(o.value==0){alert('Выберите тип');return;}
var url = '/lstat/updb.php?ds=StgType&on4='+o.name+'&ov4='+o.value;
var prt = perform(url); 
if (prt){alert(prt);}
else{window.location.reload();}

return false;
}
function SubType(o){
//	alert(o.name);return;
if(o.value==0){alert('Выберите подтип');return;}
var url = '/lstat/updb.php?ds=SubType&on5='+o.name+'&ov5='+o.value;
var prt = perform(url); 
if (prt){alert(prt);}
else{window.location.reload();}

return false;
}
function SubStg(o){
//	alert(o.name);return;
if(o.value==0){alert('Выберите подэтап');return;}
var url = '/lstat/updb.php?ds=SubStg&on6='+o.name+'&ov6='+o.value;
var prt = perform(url); 
if (prt){alert(prt);}
else{window.location.reload();}

return false;
}



function preprnt(obj){
//shade('Печать штрих-кодов');
var url = 'updb.php?ds=preprnt&o='+obj.name;
window.open(url, '_blank');

//	query_srv( url, 'pupcnt');
	return false;
}

////////////////////////////////////////////////////////////////////
function preprntMBC(id){
var url = 'updb.php?ds=preprntMBC&id='+id;
window.open(url, '_blank');
	return false;
}

function printMsBC(v){
var url = 'updb.php?ds=getMstr&v='+v;
var arrstr = perform(url);
 
if (arrstr){ run_printer(arrstr, 0); }
return false;
}

function printAllPrBC(o){
//var url = 'updb.php?ds=getfarrstr&o='+o.name;
var url = 'updb.php?ds=pr_fpreview&o='+o.name;
window.open(url, '_blank');

// var arrstr = perform(url);
// if (arrstr){ run_printer(arrstr, 1); }
return false;
}

function printPrBC(o){
//var url = 'updb.php?ds=getarrstr&o='+o.name;
var url = 'updb.php?ds=pr_preview&o='+o.name;
window.open(url, '_blank');

//var arrstr = perform(url);
//alert(arrstr); return false;
//if (arrstr){ run_printer(arrstr, 1); }
//if (arrstr){ pr_preview(arrstr); }
return false;
}

function chooseprint(id){
	preprntMBC(id); return false;
	
    if(document.getElementById("RegPrint").checked){ preprntMBC(id); }
	else                                           { printMsBC(id); }
	return false;
}
//////////////////////////////////////////////////////////////////////

function pr_preview(x) {
	
	alert(x); return;
return;
}



function run_printer(x, v) {
	
//	alert(x); return;
var arr=x.split("-:-");
for (i=0; i<arr.length; i++) {
//	RegisterCheck(Device, 0, true, arr[i]);
	if(v){ PrintSlip(Device, true, arr[i]); }
	else{ PrintPrsBC(Device, true, arr[i]); }
	}
return;
}









function shade(ttl){
var y1=document.getElementById('Shed');	
var y2=document.getElementById('popup');
var y3=document.getElementById('pupttl');	
	y1.style.display = 'block';	
	y2.style.display = 'block';	
	y3.innerHTML = ttl;
return;	
}

function ScanItm(o){
var trg = document.getElementById('jxcont1');
if(o.value.length<2){ trg.innerHTML=''; return; }
var url = 'updb.php?ds=scanitm&v='+o.value;
//window.open(url, '_blank');
	query_srv( url, trg);
	return false;
}

function Put2F1(o){
//var f1=document.getElementById('ItemID');	
var f2=document.getElementById('Article');	
//f1.value = o.id;
f2.value = o.innerHTML;
return;
}

function scanbc(o){
	alert(o.value);
	return false;
}


function PauseProc(o){
var url = 'updb.php?ds=PauseProc&pd='+o.name;
var prt = perform(url); 
if (prt){alert(prt);}
else{window.location.reload();}
	return false;
}

function EndProc(o){
if(confirm('Хотите завершить процесс?')){
var url = 'updb.php?ds=EndProc&pd='+o.name;
var prt = perform(url); 
if (prt){alert(prt);}
else{window.location.reload();}
}
	return false;
}

function PauseOrder(o){
//alert(o.name); return;	
var url = 'updb.php?ds=PauseOrder&ord='+o.name;
var prt = perform(url); 
if (prt){alert(prt);}
else{window.location.reload();}
	return false;
}


function chngbrg(o){
var url = 'updb.php?ds=chbrg&brg='+o.name+'&brgid='+o.value;
var prt = perform(url); 
if (prt){alert(prt);}
	return false;
}

function chkttl(fel){
//	alert(fel.value);	return false;
if(fel.value.length>0){ return true; }	
alert('Не заполнено обязательное поле');
fel.focus();
	return false;
}

function dynoptsstg(o){
	
var url = 'updb.php?ds=dynoptsstg&ov='+o.value;
	query_srv( url, 'dynoptsstg');

	return false;
}


function updatePrvlttl(o){
var url = 'updb.php?ds=updatePrvlttl&on='+o.name+'&ov='+o.value;
var prt = perform(url); 
if (prt){alert(prt);}
	return false;
	
}

function updatePrvaccs(o){
//	alert(o.name+' - '+o.value);
var url = 'updb.php?ds=updatePrvaccs&on='+o.name+'&ov='+o.value;
var prt = perform(url); 
if (prt){alert(prt);}
	return false;
	
}

function HelpConsole(n){

var pn = n+"_Prt";
var cn = n+"_Chd";
var p=document.getElementById(pn);	
var c=document.getElementById(cn);
var url = 'helpconsole.php?hs='+n;
//	query_srv( url, c);
	if(p.style.display == "none")	{ p.style.display = "block"; query_srv( url, c); }
	else							{ c.innerHTML = ""; p.style.display = "none"; }
return false;
}


function PrintMConsole(n){

var did = n+"_Prt";
var printContents = document.getElementById(did).innerHTML;
var originalContents = document.body.innerHTML;
	document.body.innerHTML = printContents;
	window.print();
	document.body.innerHTML = originalContents;

return false;
}

function printDiv(divName) {
         var printContents = document.getElementById(divName).innerHTML;
         var originalContents = document.body.innerHTML;
         document.body.innerHTML = printContents;
         window.print();
         document.body.innerHTML = originalContents;
    }

function srchc(o){
	if(o.value.length<3){return false;}
	var d = document.getElementById("cstsrchresults");
		d.style.display = "block";
		
	var url = "srchqbcustomer.php?n="+o.value;
		query_srv( url, d );
	return false;
}

function selsbx(o){
	document.getElementById("manualcustomerselect").value = o.name;
	document.getElementById("cstsrchresults").style.display = "none";
	return false;
}
