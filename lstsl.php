<?php
include_once('inc/_init.php');
include_once('inc/functions/general.php');
error_reporting(E_ALL | E_STRICT);

$page_description = '';
$keywords         = '';
$head_ext         = '';
$page_title       = 'Unified WC Terminal';

if($lghsh['Id'] == 0){
	require_once('inc/functions/authorization.php'); 
	$pgcontent = logform(); 
	$page_title = 'WC Terminal LogIn';
	require_once('inc/_shell.php'); ///
	exit;
	}

include_once 'inc/functions.php';
setlocale(LC_MONETARY, 'en_US');

$ords = $msg = NULL;
include_once 'wconfig.php';




$csvarr = array();
//$tmpName = 'data/csv/uscd.csv';
$tmpName = 'data/csv/uscdfrlscab.csv';
if(($handle = fopen($tmpName, 'r')) !== FALSE) {
	set_time_limit(0);
	$row = 0;
	while(($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
		$col_count = count($data);
		for($col=0; $col<$col_count; $col++){
			$csvarr[$row][$col]=$data[$col];
			}
		$row++;
		}
	fclose($handle);
	}


//echo displaycsvarr($csvarr);
// echo '<pre>';
// print_r($csvarr);
// echo '</pre>';
//exit;

//global $dbh;
$mdbh = mysqli_connect('localhost', 'waverly', 'ZxcAsdqwe123!', 'waverly');

$trows = NULL;
$ii = 0;

	$mquery = "SELECT * FROM wp_posts WHERE (post_type='product' OR post_type='product_variation') AND post_status='publish' AND post_title LIKE 'US Cabinet Depot%'"; 
	$result = mysqli_query($mdbh, $mquery) or die (mysqli_error($mdbh));
	while ($row = mysqli_fetch_array($result)) {
//		$mdh = get_md($row['ID']);
		$m1 = mysqli_fetch_array(mysqli_query($mdbh, "SELECT * FROM wp_postmeta WHERE post_id='$row[ID]' AND meta_key='_sku'"));
		$m2 = mysqli_fetch_array(mysqli_query($mdbh, "SELECT * FROM wp_postmeta WHERE post_id='$row[ID]' AND meta_key='_regular_price'"));
		$m3 = mysqli_fetch_array(mysqli_query($mdbh, "SELECT * FROM wp_postmeta WHERE post_id='$row[ID]' AND meta_key='_price'"));
//		$m4 = mysqli_fetch_array(mysqli_query($mdbh, "SELECT * FROM wp_postmeta WHERE post_id='$row[ID]' AND meta_key='_sale_price'"));


$px = NULL;
$mp = NULL;
$sx = NULL;

$wcsku = $m1['meta_value'];
//if($wcsku!='TGW-WFD301224'){continue;}
$wcsku = preg_replace('/\s{1,3}$/', '', $wcsku);

if(preg_match('/([-\w]{2,5})(-)(.*)/',$wcsku,$m)){
$px = $m[1];	
$mp = $m[3];
}

$mp = preg_replace('/-LEFT/','',$mp);
$mp = preg_replace('/-RIGHT/','',$mp);


$rshft = 0;


//////////////   Framed cabinets shift ///////// 
/*
if( $px=='U-SW'){ $rshft = 2; }
if( $px=='U-SG' OR
	$px=='U-SD' OR
	$px=='U-SA'){ $rshft = 3; }

if( $px=='U-SC' OR
	$px=='U-CS' OR
	$px=='U-CW' OR
	$px=='U-TW' OR
	$px=='U-TD' OR
	$px=='U-YW'){ $rshft = 4; }
*/

if(!preg_match('/^(U-)(\w.*)/',$px)){
	if( $px=='PGW'){ $rshft = 3; }
	if( $px=='RCS' OR
		$px=='ROS' OR
		$px=='TDW' OR
		$px=='TGW' OR
		$px=='MGW' OR
		$px=='TWP' ){ $rshft = 2; }
}


$vc = getVcoord($csvarr, $mp);

$bv = ($rshft) ? $csvarr[$vc][$rshft] : NULL;

$bv = intval(preg_replace('/\$/','',$bv));

if($bv>0){
//	mysqli_query($mdbh, "UPDATE wp_postmeta SET meta_value = '$bv' WHERE post_id='$row[ID]' AND meta_key='_regular_price'");
}
//echo $px . ' - ' . $mp . '<br>';

$mv = ($m2['meta_value'])?$m2['meta_value']:$m3['meta_value'];
$st2=($bv>$mv)?'#f00':(($bv<$mv)?'#090':'#000');

//$spr = $m3['meta_value']*0.4;

//$hh = wc_get_product( $row['ID'] );
$dstyle = ($bv)?'style="color: '.$st2.'"':'style="background: #f90;"';

//if(preg_match('/^U-/', $wcsku) AND !($bv>0)){


		$trows .= '
<tr><td>'.++$ii.'</td>
	<td>'.$row['ID'].'</td>
	<td>'.$wcsku.'</td>
	<td>'.$row['post_title'].'</td>
	<td>'.$row['post_date'].'</td>
	<td>'.$row['post_modified'].'</td>
	<td>'.$px.'</td>
	<td>'.$mp.'</td>
	<td>'.$m2['meta_value'].'::'.$m3['meta_value'].'</td>
	<td '.$dstyle.'>'.$bv.'</td>
</tr>';
//}

		}





//		$incnt .= displaycsvarr($csvarr);



$pcnt = '
<div style="float: left; margin-top: 30px;">

<table class="TBL" width="100%" cellspacing="1">
<tr><th></th>
	<th style="font-size: 13px;" colspan="3">Product</th>
	<th style="font-size: 13px;" colspan="2">Date / Time</th>
	<th style="font-size: 13px;"></th>
	<th style="font-size: 13px;" colspan="3"></th>
</tr>
<tr><th></th><th><input type="checkbox" name="item2sendchkcnt" title="Check/Empty all" value="1" OnChange="chkbxchng(this);" '.$dchk.' /></th>
	<th style="width: 180px;">Sku</th>
	<th style="width: 200px;">Title</th>
	<th style="width: 120px;">Posted</th>
	<th style="width: 120px;">Modified</th>
	<th style="width: 70px;">Prefix</th>
	<th style="width: 70px;">USCD Sku</th>
	<th style="width: 70px;">Reg.</th>
	<th style="width: 70px;">New price</th>
	<!-- th style="width: 150px;">List ID</th>
	<th style="width: 70px;">Cost</th>
	<th style="width: 70px;">Sale</th -->
</tr>
'.$trows.'
</table>

<script>
function pupnote(o){
var id = o.id.replace(/cnotd_/, "cnotediv_");
	document.getElementById(id).style.display = "block";
	return;
	}

function pclsnote(o){
var id = o.id.replace(/cnotd_/, "cnotediv_");
	document.getElementById(id).style.display = "none";
	return;
	}

function bgtrput(o){
//	document.getElementById(o.id).style.backgroundColor = "rgba(255, 255, 255, 0.7)";
	o.style.backgroundColor = "rgba(255, 255, 255, 0.7)";
	o.style.boxShadow = "5px 5px 5px #555";
	o.style.border = "solid 1px #555";
	return;
}
function bgtrrmv(o){
//	alert(o.id);
//	document.getElementById(o.id).style.backgroundColor = "rgba(255, 255, 255, 1)";
	o.style.backgroundColor = "rgba(255, 255, 255, 1)";
	o.style.boxShadow = "10px 15px 15px #555";
	o.style.border = "solid 1px #000";
	return;
}

function chkbxchng(o){
	var items = document.getElementsByName("item2send[]");
	if(o.checked) {
        for (var i = 0; i < items.length; i++) {
            if (items[i].type == "checkbox" && items[i].disabled == false) { items[i].checked = true; }
			}
		}
	else { 
		for (var i = 0; i < items.length; i++) {
			if (items[i].type == "checkbox" && items[i].disabled == false) { items[i].checked = false; }
			}
		}
return;
}


function slcustomer(o){
if(o.value.length >= 3){
var oid = o.id.replace("ptxbx_", "");
var url = "dispcns.php?sbj=pmt&oid="+oid;
	load_console(url, \'40%\', \'90%\');
	}
	return false;
}

function load_console(url, wd, ht){ //alert(url); return false;
var	obj = document.getElementById("CConsole");
var	pln = document.getElementById("consolink");
	pln.href = url;
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

</script>

';


$pgtitle = '<h4>List</h4>';
$pgcontent = <<<EOD__
<!-- Page content start -->
$pcnt
<!-- Page content end -->
EOD__;

require_once('inc/_shell.php'); ///
//////////////////////////////////
/////////////////////////////////
////////////////////////////////
///////////////////////////////
//////////////////////////////

function get_md($id){ global $mdbh;
	$mh = array();
//	foreach(mysqli_fetch_array(mysqli_query($mdbh, "SELECT * FROM wp_postmeta WHERE post_id='$id'")) as )
	
}
function displaycsvarr($r){
	$cnt='<table class="TBL" width="100%" cellspacing="1" style="margin-top: 25px;">
<tr><th colspan="10"></th></tr>
	';
	foreach($r as $trow) {
		$cnt .= '<tr>';
		foreach($trow as $tcoldt){
			$cnt .= '<td>'.$tcoldt.'</td>';
			}
		$cnt .= '</tr>';
		}
	$cnt .= '</table>';
	return $cnt;
}


function getVcoord_________($arr, $trm, $rshift=0, $stv=0){
	$rown=NULL;
	if($rshift){ return $arr[getVcoord($arr, $trm, 0, $stv)][$rshift]; }	
	else{
		foreach($arr as $rk=>$rv){
			$prv = preg_replace('/(\*\s[\w].*)$/','',$rv[0]);
			if($rk>=$stv AND $prv==$trm){ return $rk; }
			}
		return $rown;
		}
}

function getVcoord($arr, $trm){
	foreach($arr as $rk=>$rv){
		$prv = preg_replace('/(\*\s[\w].*)$/','',$rv[0]);
		if($prv==$trm){ return $rk; }
		}
	return NULL;
}


?>