<?php 
if(isset($_POST['gcsv'])){
	$bcsv = 'sku,qty
';
	$arxr = $_POST['order2020items'];
	
//echo '<pre>';print_r($arxr);echo '</pre>'; exit;	
foreach($arxr as $rp){
	$itq = explode(':::', $rp);
	if($itq[0]){ 
		$csku = preg_replace('/^U-/',     '',$itq[0]);
		$csku = preg_replace('/-LEFT$/',  '',$csku);
		$csku = preg_replace('/-RIGHT$/', '',$csku);
//		$csku = preg_replace('/-TypeA$/', '',$csku);
		$bcsv .= $csku.','.$itq[1].'
';	
		}
	}

$csvfname = "uscd_purchase_order.csv";
$file = fopen($csvfname, "w") or die("Unable to open file!");
fwrite($file, $bcsv);
fclose($file);

header_remove();
header("Content-Disposition: attachment; filename=\"" . $csvfname . "\"");
header("Content-Type: application/force-download");
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header("Content-Type: text/plain");
echo $bcsv;
exit;
}

include_once('inc/_init.php');
include_once('inc/functions/general.php');
//include_once('../wp-content/inc/functions/general.php');
require_once('inc/functions/qbw_fs.php');
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

include_once 'wconfig.php';

error_reporting(E_ERROR | E_WARNING | E_PARSE);








/*

global $dbh;

	$query = mysqli_query($dbh, "SELECT * FROM wc_consolisku WHERE Vendor='GHI'");
	while($row = mysqli_fetch_assoc($query)){
		$stl = NULL;
//		if(preg_match('/^(GWEP)(\w+)(-)([\w]{3})/',$row['Sku_WC'], $m)){
		if($row['Sku_2020']=='' OR $row['Style_2020']==''){
			
// $stt = $m[1].$m[2];
// $stl = $m[4];
		// echo $row['Sku_WC'].' - - - - '.$stt.' - '.$stl.'<br>';

			$qwi="UPDATE wc_consolisku SET
				Sku_2020    = '$stt', 
				Style_2020  = '$stl'
			WHERE id  = '$row[id]'"; 
//			mysqli_query($dbh, $qwi); echo mysqli_error($dbh);
//		echo $row['Sku_WC'].' - - - - '.$m[1].$m[2].' --- '.$m[4].'<br>';
		echo $row['Sku_WC'].' - - - - '.$row['Sku_2020'].' - '.$row['Style_2020'].'<br>';
			}
		
		
		
		}







exit;


*/









/////////////////////////////////////////////////////////////////////////////////////////////////////////////
//$bn = (preg_match('/(T-)([\d]{1,10})$/', getoidmax('T-'), $m)) ? $m[2] : 0;
//$bn = intval($bn);

$bn = getoidmax('T-');
//$lbn = str_pad(++$bn, 6, '0', STR_PAD_LEFT);
$oid = 'T-'.str_pad(++$bn, 6, '0', STR_PAD_LEFT);
///////////////////////////////////////////////////////
$dt = NULL;

$chkassembled=($_POST['assembled'])?'checked':NULL;

$pcnt .= '
<style>
#TRght, #TBase, #TList{
	width: 100%; background: #999;
	}
#TRght td, #TBase td, #TList td{
	 background: #fff;
	 padding: 5px;
	}

#TBase{ margin-top: 50px;}
	
.LRB{
	width: 45%;
	height: 200px;
	border: solid 1px #333;
	padding: 9px 15px;
	background: #fff;
	font-size: 13px;
	color: #999;
}

.LRB input{
	width: 250px;
	border: none;
	border-left: dotted 1px #ccc;
	border-bottom: dotted 1px #ccc;
	margin-top: 1px;
	padding: 1px 5px;
	background: #fff;
	font-size: 12px;
}
.TSP01{
	width: 70%;
	text-align: right;
	font-size: 15px;
	color: #999;
}

#cstsrchresults{
	position: absolute;
	display: none;
	width: 562px;
	padding: 3px 7px;
	background: #ffc;
	border: dotted 1px #fc0;
	border-radius: 0px 0px 9px 9px;
	z-index: 470;
}
#cstsrchresults a{ cursor: pointer; }
#cstsrchresults a:hover{ font-weight: bold; }
</style>

<div>
<form action="#" method="post" name="up2020ord" enctype="multipart/form-data" style="margin: 20px;">
<input type="file" name="csv" value="" />
<input type="submit" name="upfile" value="Upload" style="padding: 1px 5px;" />&nbsp; &nbsp;
<input type="checkbox" name="assembled" value="1" '.$chkassembled.' /> Assembled &nbsp;
</form>

</div>
<div style="clear: both; height: 50px;"></div>';


if(isset($_POST['gxml'])){
	
	$bxml = '<?xml version="1.0"?>
<items>';
	$arxr = $_POST['order2020items'];
foreach($arxr as $rp){
	$itq = explode(':::', $rp);
	
	if($itq[0]){
		
	$bxml .= '
	<item>
		<sku>'.preg_replace('/^U-/','',$itq[0]).'</sku>
		<qty>'.$itq[1].'</qty>
	</item>
';	
		
//	echo $itq[0] . '<br>';
	}
}

$bxml .= '</items>';

$namefile = "uscd_purchase_order.xml";

//save file
$file = fopen($namefile, "w") or die("Unable to open file!");
fwrite($file, $bxml);
fclose($file);
//header download
header("Content-Disposition: attachment; filename=\"" . $namefile . "\"");
header("Content-Type: application/force-download");
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header("Content-Type: text/plain");

echo $bxml;
//echo '<pre>';print_r($arxr);echo '</pre>';
exit;
}



//////////// Form handler ////////////////////////////////////////////////////
if(isset($_POST['fsbm'])){

	$Queue = new QuickBooks_WebConnector_Queue($dsn, $qbwc_user); 

	if(isset($_POST['manualsend2020order'])){
		if(isset($_POST['manualcustomerselect2020'])){
			$newid = insert2020order();
			if($newid){
				if($_POST['ordest_2020']=='Est') { $Queue->enqueue(QUICKBOOKS_ADD_ESTIMATE, $newid); }
				else							 { $Queue->enqueue(QUICKBOOKS_ADD_SALESORDER, $newid); }
				}
			}
		}


	if(isset($_POST['crcustomer'])){
		
		
		
		// $mnh = preg_replace('/\'/', ' ', $_POST['trnW']);
		// $mnh = preg_replace('/\\ /', '', $mnh);
//		echo $mnh;
		$newid = CrCustomer();
		$Queue->enqueue(QUICKBOOKS_ADD_CUSTOMER, $newid);
		}


	if(isset($_POST['CreateCart'])){
		$pcnt .= '<div style="text-align: center;">'.customwoocart().'</div>';
		}



/*
	if($_POST['customer2send']){ ///////////   Preparing customer(s) to send ///////
		foreach($_POST['customer2send'] as $sv){
			$newid = insertCustomer($sv);
			$Queue->enqueue(QUICKBOOKS_ADD_CUSTOMER, $newid);
			}
		$msg .= 'Customer(s) checked';
		}
	*/
	
	



/*
	foreach ($_POST['addresslines'] as $ts){
		echo $ts . '<br>';
		}


	foreach ($_POST['shaddresslines'] as $ts){
		echo $ts . '<br>';
		}


	foreach ($_POST['order2020items'] as $ts){
		echo $ts . '<br>';
		}
*/


//	$msg .=($msg)?'':'No data to send to QB';
	}

elseif(isset($_FILES['csv']) AND $_FILES['csv']){

	$dt = NULL;
	$incnt = NULL;
	$gttl = 0;
	$optlist = allqbcustomers();
	$optitlist = allqbitems();

	$csvarr = array();
	if($_FILES['csv']['error'] == 0){ // check there are no errors
		$name = $_FILES['csv']['name'];
		$ext = strtolower(end(explode('.', $_FILES['csv']['name'])));
		$type = $_FILES['csv']['type'];
		$tmpName = $_FILES['csv']['tmp_name'];
		if($ext === 'csv'){
			if(($handle = fopen($tmpName, 'r')) !== FALSE) {
				// necessary if a large csv file
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
			}

		$pfx = getpfx($csvarr);	

		$crd = getcoords($csvarr, 'Email:');
		$eml = lk4cl($csvarr, $crd, 1);

		$crd = getcoords($csvarr, 'Home:');
		$phone = lk4cl($csvarr, $crd, 1);
		$crd = getcoords($csvarr, 'Work:');
		$phone .= ($phone)?' '.lk4cl($csvarr, $crd, 1):lk4cl($csvarr, $crd, 1);

		$crd = getcoords($csvarr, 'Note');
		$note = lk4cl($csvarr, $crd, 0, 1);

		$pd = getVcoord($csvarr, 'Print date:', 1);
		$dt = date("Y-m-d", strtotime($pd));

		$itemsfrm = $itemshidform = NULL;
		$ii=0;

		$arrd = tfscan($csvarr);
		$groupr = $arrd; //group_sims($arrd);

		$darr = array(); 
		$sflg = $di = 0;

		foreach ($groupr as $gv){
			$asm = $ds = NULL;
			$pfx = $gv['stl'];
		
				if(preg_match('/\-L$/', $gv['sku']))				{ $ds = preg_replace('/\-L$/', '', $gv['sku']); $asm = 'LEFT'; }
			elseif(preg_match('/\sL$/', $gv['sku']))				{ $ds = preg_replace('/\sL$/', '', $gv['sku']); $asm = 'LEFT'; }
			elseif(preg_match('/(.*)([\d]{1,6})(L)$/', $gv['sku']))	{ $ds = preg_replace('/L$/',   '', $gv['sku']); $asm = 'LEFT'; }
			elseif(preg_match('/-R$/', $gv['sku']))					{ $ds = preg_replace('/-R$/',  '', $gv['sku']); $asm = 'RIGHT'; }
			elseif(preg_match('/\sR$/', $gv['sku']))				{ $ds = preg_replace('/\sR$/', '', $gv['sku']); $asm = 'RIGHT'; }
			elseif(preg_match('/(.*)([\d]{1,6})(R)$/', $gv['sku']))	{ $ds = preg_replace('/R$/',   '', $gv['sku']); $asm = 'RIGHT'; }
			else                                    				{ $ds = $gv['sku']; }

///////////////  if we need left or right ///////////////
				// if(preg_match('/\-L$/', $gv['sku']))				{ $ds = preg_replace('/\-L$/', '-LEFT', $gv['sku']); }
			// elseif(preg_match('/\sL$/', $gv['sku']))				{ $ds = preg_replace('/\sL$/', '-LEFT', $gv['sku']); }
			// elseif(preg_match('/(.*)([\d]{1,6})(L)$/', $gv['sku']))	{ $ds = preg_replace('/L$/',   '-LEFT', $gv['sku']); }
			// elseif(preg_match('/-R$/', $gv['sku']))					{ $ds = preg_replace('/-R$/',  '-RIGHT', $gv['sku']); }
			// elseif(preg_match('/\sR$/', $gv['sku']))				{ $ds = preg_replace('/\sR$/', '-RIGHT', $gv['sku']); }
			// elseif(preg_match('/(.*)([\d]{1,6})(R)$/', $gv['sku']))	{ $ds = preg_replace('/R$/',   '-RIGHT', $gv['sku']); }
			// else                                    				{ $ds = $gv['sku']; }


//echo $ds . ' - '.$pfx.'<br>';
//			$asm=($asm AND $chkassembled)?$asm:'RTA';
			$ds = preg_replace('/"/','',$ds);

			$asm=($chkassembled)?(($asm)?$asm:'ASM'):'RTA';
			$wcsku = getWCSku($ds, $pfx, $asm);
			if(!$wcsku){ $wcsku = getWCSku('G'.$ds, $pfx); }
			if(!$wcsku){ $wcsku = getWCSku($ds, $pfx, 'SR'); }
			if(!$wcsku){ $wcsku = getWCSku($ds, $pfx, ''); }
			
			$row = getqw_item($wcsku);

			$vttl = $row['Title'].' <a style="float: right; font-size: 11px;" id="ssgn_'.$ds.'" href="#" OnClick="return reassign(this);">ReAssign <b>'.$gv['sku'].'</b></a>';

			if($wcsku){	
				$bsku = $wcsku;	
				$darr[$di]['Sku'] = $wcsku; 
				$darr[$di]['Qty'] = $gv['qty']; 
				$di++; 
				}
			else{ $sflg = 1;
				$bsku = '<span style="font-size: 12px; color: #f90;">'.$gv['sku'].'</span>' ;				
				if(1){
					$vttl = '
<select id="massign2020_'.$ds.'" name="massign2020_'.$ds.'"><option value="">Select WC Sku</option>
'.$optitlist.'
</select>
<input type="button" name="massignbttn_'.$ds.'" title="'.$ds.'" value="Assign" OnClick="assign2020(this, \''.$pfx.'\');" />';
					}
				}	

			$itemshidform .= '
<input type="hidden" name="order2020items[]" value="'.$wcsku.':::'.$gv['qty'].'" />';

			$subttl = $row['SPrice'] * $gv['qty'];
			$gttl += $subttl;
			$itemsfrm .= '
<tr><td style="text-align: center;">'.$gv['qty'].'</td>
	<td nowrap>'.$bsku.'</td>
	<td></td>
	<td id="ttldivssgn_'.$ds.'">'.$vttl.'</td>
	<!-- td style="text-align: right;">$'.number_format($row['RPrice'], 2).'</td -->
	<td style="text-align: right;">$'.number_format($row['SPrice'], 2).'</td>
	<td style="text-align: right;">$'.number_format($subttl, 2).'</td>
</tr>';
			} 


//customwoocart($darr);


		$caddr = $shaddr = NULL;

		$aar = getaddress($csvarr, 0);
		foreach($aar as $av){ 
			$itemshidform .= '
<input type="hidden" name="addresslines[]" value="'.$av.'" />';
			$caddr .= $av . '<br>'; 
			}

		$fln = explode(' ', $aar[0]);

		$shaddr .= $aar[0] . '<br>';
		$itemshidform .= '
<input type="hidden" name="shaddresslines[]" value="'.$aar[0].'" />';

		$sar = getaddress($csvarr, 1);
		foreach($sar as $av){  
			$itemshidform .= '
<input type="hidden" name="shaddresslines[]" value="'.$av.'" />';
			$shaddr .= $av . '<br>';
			}

		if($phone){
			$shaddr .= '<br>Phone: '.$phone;
			}


///////////////////////////////////////////////////////
$cstxst = (chkcustqb($aar[0], $eml, $phone))?'Y':'N';
//echo $cstxst;





		$incnt .= displaycsvarr($csvarr);

		$pcnt .= '
<div style="width: 80%; margin: 0 auto; margin-top: 10px; padding: 5px;">

<div style="float: left; width: 300px; font: normal 15px \'Arial\'">
<h3>Waverly Cabinets</h3>
345 Enterprise Way<br>
Pittston, PA 18640<br>
570-693-0285<br>
sales@waverlycabinets.com<br>
</div>

<div style="float: right; width: 370px;">
<h2>Sales Receipt</h2><br>
<table id="TRght" cellspacing="1">
<tr><td style="width: 70%;">Date / Time</td>
	<td>Order #</td>
</tr>
<tr><td>'.$dt.'</td>
	<td>'.$oid.'</td>
</tr>
</table>
</div>

<div id="manualsel" style="margin-top: 20px; float: left;">
<form action="p20202ord.php" method="post" name="order2020sendman">

<input type="hidden" name="sndprevent" value="'.$sflg. '" />
<input type="hidden" name="cdate" value="'. $dt . '" />
<input type="hidden" name="fsbm" value="1" />
<input type="hidden" name="order2020" value="'.$oid.'" />

'.$itemshidform.'

<input type="hidden" name="custnote" value="'.$note.'" />
<input type="hidden" name="gtotal" value="'.$gttl.'" />

<select name="ordest_2020" style="font-size: 12px;" title="Send to QB as">
	<option value="Est">Estimate</option>
	<option value="Ord" selected>Order</option>
</select>
<input type="submit" name="manualsend2020order" value="Send to " OnClick="return chcstsel(this);" />
<select name="manualcustomerselect2020" id="manualcustomerselect2020">
<option value="">Select customer</option>
'.$optlist.'
</select>
<input type="text" name="srchcstm" value="" placeholder="Search customer" OnKeyUp="srchc(this);" style="width: 120px;" />
<div id="cstsrchresults">&nbsp;</div>
</div>


<div style="clear: both; height: 35px;">
<input type="submit" name="crcustomer" value="Create customer" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="submit" name="CreateCart" value="Create WOO Cart" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="submit" name="gcsv" value="Generate CSV" />

</div>

<div class="LRB" style="float: left;">
<h2>Address</h2>

<input type="text" name="cname" value="'.$aar[0].'" /> Name &nbsp;&nbsp;&nbsp; 
<input type="text" name="fname" value="'.$fln[1].'" style="width: 80px;" /> First &nbsp;&nbsp;&nbsp; 
<input type="text" name="lname" value="'.$fln[0].'" style="width: 80px;" /> Last<br>
<input type="text" name="addr1" value="'.$aar[1].'" /> Street<br>
<input type="text" name="addr2" value="'.$aar[2].'" /> City<br>
<input type="text" name="addr3" value="'.$aar[3].'" /> State<br>
<input type="text" name="addr4" value="'.$aar[4].'" /> Zip<br>
<input type="text" name="addr5" value="'.$aar[5].'" /><br>
<br>
<input type="text" name="email" value="'.$eml.'" /> Email<br>
<input type="text" name="phone" value="'.$phone.'" /> Phone<br>
</div>
<div class="LRB" style="float: right;">
<h2>Ship To</h2> 


<input type="text" name="shcname" value="'.$aar[0].'" /> Name<br>
<input type="text" name="shaddr1" value="'.$sar[0].'" /> Street<br>
<input type="text" name="shaddr2" value="'.$sar[1].'" /> City<br>
<input type="text" name="shaddr3" value="'.$sar[2].'" /> State<br>
<input type="text" name="shaddr4" value="'.$sar[3].'" /> Zip<br>
<input type="text" name="shaddr5" value="'.$sar[4].'" /><br>
<br>
<input type="text" name="shphone" value="'.$phone.'" /> Phone<br>

<div style="clear: both;"></div>
</div>
<div style="clear: both; padding: 30px 0px;">

<table id="TList" cellspacing="1">
<tr><td style="width: 5%;">Qtty</td>
	<td>Item Code</td>
	<td>Assembly</td>
	<td>Description</td>
	<!-- td>List Price</td -->
	<td>Std. Price</td>
	<td>Total</td>
</tr>
'.$itemsfrm.'
<tr><td colspan="4" rowspan="2" style="vertical-align: middle;"><b>Note:</b> '.$note.'</td>
	<!-- td rowspan="2" style="vertical-align: middle;">List Total: </td -->
	<td colspan="2" style="text-align: right;">
	
	<select name="dscntselect" id="dscntselect">
	<option value="0">Select discount</option>
	<option value="1">Contractor discount 1 (5.0%)</option>
	</select>
	
	</td>
</tr>
<tr><td colspan="2" style="text-align: right;">
		<table style="width: 100%">
			<tr><td class="TSP01"><b>Total Cost:</b></td>
				<td><b>'.$gttl.'</b></td>
			</tr>
		</table>
	</td>
</tr>
</table>

'. $incnt . '

</form>
</div>
<script>

function chcstsel(o){
	if(o.form.sndprevent.value>0){
		alert("One or more items is not recognized/assigned");
		return true; //false;
		}
	if(order2020sendman.manualcustomerselect2020.value)  { return true; }
	alert("Select customer to send order");	return false;
	}

function assign2020(o, pfx){
	
	var vm = o.name.replace(/massignbttn_/i, "");
//	vm.replace(/^[\w]{2,4}-/, "");
	var sm = "massign2020_"+vm;
	var d = document.getElementById(sm);

	var url = "consquery.php?id="+d.value+"&v="+vm+"&pf="+pfx;
	
	var prt = perform(url); 
		if(prt){alert(prt);}
	
	
}

function srchc(o){
	if(o.value.length<3){return;}
	var d = document.getElementById("cstsrchresults");
		d.style.display = "block";
//		d.innerHTML = o.value;
		
	var url = "srchqbcustomer.php?n="+o.value;
		
		query_srv( url, d );
		
}

function selsbx(o){
	document.getElementById("manualcustomerselect2020").value = o.name;
	document.getElementById("cstsrchresults").style.display = "none";
	return false;
}


function reassign(o){
var dvs = o.id.replace(/ssgn_/i, "");
var url = "rssgn.php?dv="+dvs;
var d = document.getElementById("ttldiv"+o.id);

// d.innerHTML = o.id;
	query_srv( url, d );

	return false;
}
</script>

';
		}
	}

else{
	
	}

$page_title = 'Waverly2020design_';

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
/////////////////////////////
////////////////////////////
///////////////////////////
//////////////////////////
/////////////////////////
////////////////////////
///////////////////////
//////////////////////
/////////////////////
////////////////////
///////////////////
//////////////////
/////////////////
////////////////
///////////////
//////////////
/////////////
////////////
///////////
//////////
/////////
////////
///////
//////
/////
////
///
//

function getcoords($arr, $trm, $stv=0, $sth=0){
	foreach($arr as $vk=>$vv){
		foreach($vv as $hk=>$hv){
			if($vk>=$stv AND $hk>=$sth AND $hv==$trm){ return array($vk, $hk); }
			}
		}
	return NULL;
	}


function getVcoord($arr, $trm, $rshift=0, $stv=0){
	$rown=NULL;
	if($rshift){ return $arr[getVcoord($arr, $trm, 0, $stv)][$rshift]; }	
	else{
		foreach($arr as $rk=>$rv){
			if($rk>=$stv AND $rv[0]==$trm){ return $rk; }
			}
		return $rown;
		}
}

function lk4cl($arr, $coords, $rshift=0, $dshift=0){
	return $arr[$coords[0]+$dshift][$coords[1]+$rshift];
}

function getWCSku($sku, $stl, $a=NULL){ global $dbh;
//	$acl=($a)?'Asm=\''.$a.'\'':'1';
//	$sku = preg_replace('/"/','\"',$sku);
//echo $stl;
	
	// preg_match('/^([\w-]{2,4})(-)(\w.*)/', $sku, $m);
	// $pfx = $m[1];
	// $s2020 = $m[3];
	
//	return mysqli_fetch_assoc(mysqli_query($dbh, "SELECT * FROM wc_consolisku WHERE Sku_2020='$sku' AND Style_2020='$stl' AND ".$acl))['Sku_WC'];
	return mysqli_fetch_assoc(mysqli_query($dbh, "SELECT * FROM wc_consolisku WHERE Sku_2020='$sku' AND Style_2020='$stl' AND Asm='$a'"))['Sku_WC'];
//	return mysqli_fetch_assoc(mysqli_query($dbh, "SELECT * FROM wc_consolisku WHERE Sku_2020='$s2020' AND Style_2020='$pfx' AND Asm='$a'"))['Sku_WC'];
//	return mysqli_fetch_assoc(mysqli_query($dbh, "SELECT * FROM wc_consolisku WHERE Sku_WC='$sku' AND Asm='$a'"))['Sku_WC'];
}

function ttscan($r){
	$i = $ii = 0;
	$nr = array();
	while($r[$ii][0]!='' OR $r[$ii+1][0]!='' OR $r[$ii+2][0]!=''){
		if( preg_match('/[\d]{1,4}/', $r[$ii][1]) AND ($r[$ii][4]>0 OR $r[$ii][5]>0)){
			$nr[$i]['sku'] = $r[$ii][2];
			$nr[$i]['qty'] = $r[$ii][1];
			$i++;
			}
		$ii++;
		}
	return $nr;
}

function tfscan($r){
	$i = $ii = 0;
	$nr = array();
	$qty_index = $sku_index = $dsc_index = $prc_index = $amt_index = NULL;
	$spl = $dst = $pfx = NULL;
	while($r[$ii][0]!='' OR $r[$ii+1][0]!='' OR $r[$ii+2][0]!='' OR $r[$ii+3][0]!='' OR $r[$ii+4][0]!=''){
		getstyle($r[$ii], $spl, $dst, $pfx);


/*  works for USCD
		if($r[$ii][1]>0 AND $r[$ii][2]!='' AND $r[$ii][3]!='' AND $r[$ii][4]!='' AND ($r[$ii][5]>0 OR $r[$ii][6]>0)){
			$qty_index = 1;
			$sku_index = 2;
			$dsc_index = 4;
			}
*/

////////////// For Forevermark //////////////
		if($r[$ii][1]>0 AND $r[$ii][2]!='' AND $r[$ii][3]!='' AND $r[$ii][4]!=''){
			$qty_index = 1;
			$sku_index = 2;
			$dsc_index = 4;
			}


/*
		if($r[$ii][0]=='#' AND $r[$ii][1]=='Qty'){
			for($in=0; $in<15; $in++){
				if($r[$ii][$in]=='Qty')			{ $qty_index = $in; }
				if($r[$ii][$in]=='User code')	{ $sku_index = $in; }
				if($r[$ii][$in]=='Description')	{ $dsc_index = $in; }
//				if($r[$ii][$in]=='Unit $')		{ $prc_index = $in; }
//				if($r[$ii][$in]=='Amount')		{ $amt_index = $in; }
				}
			}
*/

//		if( preg_match('/[\d]{1,4}/', $r[$ii][1]) AND ($r[$ii][4]>0 OR $r[$ii][5]>0)){
//			$nr[$i]['sku'] = $px.'-'.$r[$ii][2];

		if( $r[$ii][$qty_index]>0 AND $r[$ii][$sku_index]!='' AND $r[$ii][$dsc_index]!=''){
			$nr[$i]['stl'] = $pfx;
			$nr[$i]['sku'] = $r[$ii][$sku_index];
			$nr[$i]['qty'] = $r[$ii][$qty_index];
			$i++;
			}
		$ii++;
		}
	return $nr;
}


function getstyle($ir, &$spl, &$dst, &$px){

	if($ir[0]=='Supplier'){
		$spl = $ir[1];
		}
	if($spl == 'US Cabinets'){ 
		if($ir[0]=='Door style'){
			$dst = $ir[1];
			if(preg_match('/^(.*)(\()([\w]{2})(\))/', $dst, $m)){ $px = 'U-'.$m[3]; }
			}
		}
	if(preg_match('/Horning/', $spl)){
		if($ir[0]=='Door style'){
			$dst = $ir[1];
			if($dst == 'Arcadia White Shaker'){ $px = 'ACW'; }
			if($dst == 'Stone Harbor Gray'){ $px = 'SHG'; }
			}
		}
	if( ($spl == 'FOREVERMARK CABINETRY (TSG)') OR
		($spl == '20-20 Technologies')){

		if($ir[0]=='Wall Door'){
			$dst = $ir[1];
			if(preg_match('/^([\w]{1,4})(\s\-)(.*)/', $dst, $m)){ $px = $m[1]; }
			}
		if(!$px){ 
			if($ir[0]=='Door style'){
				$dst = $ir[1];
				if(preg_match('/^([\w]{1,4})(\s\-)(.*)/', $dst, $m)){ $px = $m[1]; }
				}
			}	
		}
	if($spl == 'Cubitac Cabinetry'){
		if($ir[0]=='Door style'){
			$dst = $ir[1];
			if(preg_match('/^(Belmont Cafe Glaze)(.*)/', $dst)){ $px = 'CBG'; }
			if(preg_match('/^(Bergen Latte)(.*)/', $dst))      { $px = 'ILB'; }
			if(preg_match('/^(Bergen Shale)(.*)/', $dst))      { $px = 'ISB'; }
			if(preg_match('/^(Dover Cafe)(.*)/', $dst))        { $px = 'CD'; }
			if(preg_match('/^(Dover Espresso)(.*)/', $dst))    { $px = 'ED'; }
			if(preg_match('/^(Dover Latte)(.*)/', $dst))       { $px = 'LD'; }
			if(preg_match('/^(Dover Shale)(.*)/', $dst))       { $px = 'SD'; }
			if(preg_match('/^(Milan Latte)(.*)/', $dst))       { $px = 'LM'; }
			if(preg_match('/^(Milan Shale)(.*)/', $dst))       { $px = 'SM'; }
			if(preg_match('/^(Newport Cafe)(.*)/', $dst))      { $px = 'CN'; }
			if(preg_match('/^(Newport Latte)(.*)/', $dst))     { $px = 'LN'; }
			if(preg_match('/^(Oxford Latte)(.*)/', $dst))      { $px = 'BLO'; }
			if(preg_match('/^(Oxford Pastel)(.*)/', $dst))     { $px = 'BPO'; }
			if(preg_match('/^(Ridgefield Latte)(.*)/', $dst))  { $px = 'BLR'; }
			if(preg_match('/^(Ridgefield Pastel)(.*)/', $dst)) { $px = 'BPL'; }
			if(preg_match('/^(Sofia Caramel)(.*)/', $dst))     { $px = 'LNCG'; }
			if(preg_match('/^(Sofia Pewter)(.*)/', $dst))      { $px = 'LNPG'; }
			if(preg_match('/^(Sofia Sable)(.*)/', $dst))       { $px = 'LNSG'; }
			}
		}
	return $px;	
}

function group_sims($r){
//return $r;
$nr = array();
$i = 0;
foreach ($r as $k=>$v){
	$itex = true;
	$qty = $r[$k]['qty'];
	$sku = $r[$k]['sku'];
	$stl = $r[$k]['stl'];
	
	foreach($nr as $nk=>$nv){
		if( $nv['sku'] == $sku AND $nv['stl'] == $stl){ 
			$itex = false;
			$nr[$nk]['qty'] += $qty;
			}
		}	
	if($itex){ 
		$nr[$i]['stl'] = $stl;
		$nr[$i]['qty'] = $qty;
		$nr[$i]['sku'] = $sku;
		$i++;
		}
	}
return $nr;
}


function create2020customer(){
	
	
}


function insert2020order(){ global $dbh; 

	$sv = $_POST['order2020'];
	$lid = $_POST['manualcustomerselect2020'];
	$iarr = $_POST['order2020items'];

//	$csh = getqbcustomerTblHsh($lid);
//	$csh['name']  = spcharhndl($csh['name']);
//	$shname = spcharhndl($omd['_shipping_company'][0]);

	$country   = ($_POST['addr5'])?$_POST['addr5']:'US';
	$shcountry = ($_POST['shaddr5'])?$_POST['shaddr5']:'US';
	$shphone   = $_POST['phone']; ///// >>>>>>>>>> /????????????????????
// echo $omd['_shipping_company'][0] . '<br>' . $shname . '<br>'; return NULL;	

//	$qbcust_listid = chkcustomer($omd);
	$date = $_POST['cdate']; //'2020/08/18';
	$que = "INSERT INTO	wc_order	(	
			OrderID, OrderDT, QBCustListID, CName, FName, LName, Phone, EMail, Street, Line2, City, State, Zip, Country,
			CNameAlt, FNameAlt,	LNameAlt, StreetAlt, Line2Alt, CityAlt, StateAlt, ZipAlt, CountryAlt, ShPhone, OrderTT, TaxPrc, Notes
		) VALUES (
			'" . mysqli_escape_string($dbh, $sv )   . "',
			'" . mysqli_escape_string($dbh, $date ) . "',

			'" . mysqli_escape_string($dbh, $lid )            . "',
			'" . mysqli_escape_string($dbh, $_POST['cname'] ) . "',
			'" . mysqli_escape_string($dbh, $_POST['fname'] ) . "',
			'" . mysqli_escape_string($dbh, $_POST['lname'] ) . "',
			'" . mysqli_escape_string($dbh, $_POST['phone'] ) . "',
			'" . mysqli_escape_string($dbh, $_POST['email'] ) . "',

			'" . mysqli_escape_string($dbh, $_POST['addr1'] )     . "',
			'" . mysqli_escape_string($dbh, '' )                  . "',
			'" . mysqli_escape_string($dbh, $_POST['addr2'] )     . "',
			'" . mysqli_escape_string($dbh, $_POST['addr3'] )     . "',
			'" . mysqli_escape_string($dbh, $_POST['addr4'] )     . "',
			'" . mysqli_escape_string($dbh, $country )            . "',

			'" . mysqli_escape_string($dbh, $_POST['shcname'] )   . "',
			'" . mysqli_escape_string($dbh, $_POST['fname'] )     . "',
			'" . mysqli_escape_string($dbh, $_POST['lname'] )     . "',
			'" . mysqli_escape_string($dbh, $_POST['shaddr1'] )   . "',
			'" . mysqli_escape_string($dbh, '' )                  . "',
			'" . mysqli_escape_string($dbh, $_POST['shaddr2'] )   . "',
			'" . mysqli_escape_string($dbh, $_POST['shaddr3'] )   . "',
			'" . mysqli_escape_string($dbh, $_POST['shaddr4'] )   . "',
			'" . mysqli_escape_string($dbh, $shcountry )          . "',
			'" . mysqli_escape_string($dbh, $shphone )            . "',

			'" . mysqli_escape_string($dbh, $_POST['gtotal'] )    . "',
			'" . mysqli_escape_string($dbh, '' )                  . "',
			'" . mysqli_escape_string($dbh, $_POST['custnote'] )  . "'
		)";
	mysqli_query($dbh, $que);
	$newID = mysqli_insert_id($dbh);
	insertOrder2020Items($sv, $iarr);
	if($_POST['dscntselect']){ insertOrderDiscount($sv, 1); }

//	insertOrder2020Shipping($sv);
	return $newID;
}


///////////////////////////////////////////////////////////////////////////////////////////////////
function insertOrder2020Items($sv, $itarr){ global $dbh;
//	$ord = new WC_Order($sv);

foreach($itarr as $rp){
	$itq = explode(':::', $rp);	
	if(!$itq[0]){continue;}
	echo $itq[0] . ' - ' . $itq[1] . '<br>';
	$row = getqw_item($itq[0]);
	$subtotal = $row['SPrice'] * $itq[1];
	$que1 = NULL;
	$res = mysqli_query($dbh, "SELECT * FROM wc_order_items WHERE OrderID='$sv' AND Sku='$itq[0]'");
	if(mysqli_fetch_assoc($res)['id']){
		$que1 = "UPDATE wc_order_items SET 
			Title  =  '" . mysqli_escape_string($dbh, $row['Title'] ) . "',
			Qtty   = '" . mysqli_escape_string($dbh, $itq[1] ) . "',
			STtl   = '" . mysqli_escape_string($dbh, $subtotal ) . "'
		WHERE OrderID='$sv' AND Sku='$itq[0]'";
		}
	else{
		$que1 = "INSERT INTO wc_order_items ( OrderID, Sku, Title, Qtty, STtl ) 
			VALUES (
				'" . mysqli_escape_string($dbh, $sv ) . "',
				'" . mysqli_escape_string($dbh, $itq[0] ) . "',
				'" . mysqli_escape_string($dbh, $row['Title'] ) . "',
				'" . mysqli_escape_string($dbh, $itq[1] ) . "',
				'" . mysqli_escape_string($dbh, $subtotal ) . "'
				)";
		}
	mysqli_query($dbh, $que1);
	}
	return NULL;
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////////////////////////
function insertOrder2020Shipping($sv){ global $dbh;
	$ord = new WC_Order($sv);
	$shh = $ord->get_shipping_total();

	$que1 = NULL;
	$res = mysqli_query($dbh, "SELECT * FROM wc_order_items WHERE OrderID='$sv' AND Sku='Freight'");
	if(mysqli_fetch_assoc($res)['id']){
		$que1 = "UPDATE wc_order_items SET 
			Title  = 'Shipping Charges', 
			Qtty   = 1,
			SPrice = '" . mysqli_escape_string($dbh, $shh ) . "',
			STtl   = '" . mysqli_escape_string($dbh, $shh ) . "'
		WHERE OrderID='$sv' AND Sku='Freight'";
		}
	else{
		$que1 = "INSERT INTO wc_order_items ( OrderID, Sku, Title, Qtty, SPrice, STtl ) 
			VALUES (
			'" . mysqli_escape_string($dbh, $sv ) . "',
			'Freight',
			'Shipping Charges', 
			1,
			'" . mysqli_escape_string($dbh, $shh ) . "',
			'" . mysqli_escape_string($dbh, $shh ) . "'
			)";
		}
	mysqli_query($dbh, $que1);
	return NULL;
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////


function getqw_item($sku){ global $dbh;
	return mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM qw_item WHERE Sku = '$sku'", MYSQLI_ASSOC));
}



function getoidmax($px){ global $dbh;
	$topn = array(); $topd = 0;
	$xml2 = simplexml_load_file("data/sinchro/sorder_inc.xml");
	foreach($xml2->QBXMLMsgsRs->SalesOrderQueryRs->SalesOrderRet as $d){
		if(preg_match("/^($px)(\w.*)/", $d->RefNumber, $m)){ array_push($topn, intval($m[2])); }
		}
	$dxml = max($topn);
	$dtbl = mysqli_fetch_array(mysqli_query($dbh, "SELECT MAX(IID) FROM qw_xt WHERE (oname = 'Order' OR oname = 'Estimate') AND IID LIKE '$px%'", MYSQLI_ASSOC))[0];
	if(preg_match("/^($px)(\w.*)/", $dtbl, $m)){ $topd = intval($m[2]); }

	if($dxml > $topd)	{ return $dxml; }
	else 				{ return $topd; }
}



function getpfx($r){
	$px = NULL;
	$sn = getVcoord($r, 'Supplier');
if($r[$sn][1] == 'US Cabinets'){
	$vc = getVcoord($r, 'Door style', 1);
	if(preg_match('/^(.*)(\()([\w]{2})(\))/', $vc, $m)){ $px = 'U-'.$m[3]; }
	}

if(preg_match('/Horning/', $r[$sn][1])){
//if($r[$sn][1] == "Horning's Supply Inc."){
	$vc = getVcoord($r, 'Door style', 1);
	if($vc == 'Arcadia White Shaker'){ $px = 'ACW'; }
	if($vc == 'Stone Harbor Gray'){ $px = 'SHG'; }
//	if(preg_match('/^(.*)(\()([\w]{2})(\))/', $vc, $m)){ $px = 'U-'.$m[3]; }
	}

if( ($r[$sn][1] == 'FOREVERMARK CABINETRY (TSG)') OR
	($r[$sn][1] == '20-20 Technologies')
	){
	$vc = getVcoord($r, 'Wall Door', 1);
	if(preg_match('/^([\w]{1,4})(\s\-)(.*)/', $vc, $m)){ $px = $m[1]; }
	if(!$px){ $vc = getVcoord($r, 'Door style', 1);
		if(preg_match('/^([\w]{1,4})(\s\-)(.*)/', $vc, $m)){ $px = $m[1]; }
		}	
	}

if($r[$sn][1] == 'Cubitac Cabinetry'){
	$vc = getVcoord($r, 'Door style', 1);
	if(preg_match('/^(Milan Shale)(.*)/', $vc)){ $px = 'SM'; }
	}

	return $px;
}








function getaddress($r, $s){
//$stend = array('Customer','Ship to address','DESIGN DETAILS');
$addr = array();
$i = 0;
if($s){
	$vc = getVcoord($r, 'Ship to address');
	if($vc){
		while(($r[++$vc][0] != 'DESIGN DETAILS')  AND 
			  ($r[$vc][0] != '')){
			$addr[$i++] = $r[$vc][0];
			if($r[$vc][1]){ $addr[$i++] = preg_replace('/^\s/', '', $r[$vc][1]); }
			}
		}
	}
else{
	$vc = getVcoord($r, 'Customer');
	if($vc){
		while(($r[++$vc][0] != 'Ship to address')  AND 
			  ($r[$vc][0] != 'Note')  AND
			  ($r[$vc][0] != 'Fax:')  AND
			  ($r[$vc][0] != 'Home:')  AND
			  ($r[$vc][0] != 'Email:') AND
			  ($r[$vc][0] != 'DESIGN DETAILS')  AND
			  ($r[$vc][0] != '')){
			$addr[$i++] = $r[$vc][0];
			if($r[$vc][1]){ $addr[$i++] = preg_replace('/^\s/', '', $r[$vc][1]); }
			}
		}
	}
	
	
//$addr[3] = preg_replace('/\s/', '', $addr[3]);	
return $addr;
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



function CrCustomer(){ global $dbh;
//	$omd = get_post_meta($sv);
//	$cname = preg_replace("/\’/", '', $omd['_billing_company'][0]);
//	$cname  = spcharhndl($omd['_billing_company'][0]); // preg_replace("/\W/", ' ', $omd['_billing_company'][0]);
//	$shname = spcharhndl($omd['_shipping_company'][0]); //preg_replace("/\W/", ' ', $omd['_shipping_company'][0]);
$cname = str_replace("\'", "'", $_POST['cname']);
$lname = str_replace("\'", "'", $_POST['lname']);


$country   = ($_POST['addr5'])?$_POST['addr5']:'US';
$shcountry = ($_POST['shaddr5'])?$_POST['shaddr5']:'US';

	$que = "INSERT INTO	wc_customer	(	
			OrderID, CName,	FName, LName, Phone, EMail,	Street,	Line2, City, State,	Zip, Country,
			CNameAlt, FNameAlt,	LNameAlt, StreetAlt, Line2Alt, CityAlt, StateAlt, ZipAlt, CountryAlt
		) VALUES (
			'" . mysqli_escape_string($dbh, $_POST['order2020'] ) . "',
			'" . mysqli_escape_string($dbh, $cname ) . "',
			'" . mysqli_escape_string($dbh, $_POST['fname'] )     . "',
			'" . mysqli_escape_string($dbh, $lname ) . "',
			'" . mysqli_escape_string($dbh, $_POST['phone'] )     . "',
			'" . mysqli_escape_string($dbh, $_POST['email'] )     . "',
			'" . mysqli_escape_string($dbh, $_POST['addr1'] )     . "',
			'" . mysqli_escape_string($dbh, '' )                  . "',
			'" . mysqli_escape_string($dbh, $_POST['addr2'] )     . "',
			'" . mysqli_escape_string($dbh, $_POST['addr3'] )     . "',
			'" . mysqli_escape_string($dbh, $_POST['addr4'] )     . "',
			'" . mysqli_escape_string($dbh, $country )     . "',
			'" . mysqli_escape_string($dbh, $_POST['shcname'] )   . "',
			'" . mysqli_escape_string($dbh, $_POST['fname'] )     . "',
			'" . mysqli_escape_string($dbh, $_POST['lname'] )     . "',
			'" . mysqli_escape_string($dbh, $_POST['shaddr1'] )   . "',
			'" . mysqli_escape_string($dbh, '' )                  . "',
			'" . mysqli_escape_string($dbh, $_POST['shaddr2'] )   . "',
			'" . mysqli_escape_string($dbh, $_POST['shaddr3'] )   . "',
			'" . mysqli_escape_string($dbh, $_POST['shaddr4'] )   . "',
			'" . mysqli_escape_string($dbh, $shcountry )   . "'
		)";
	mysqli_query($dbh, $que);
	$insid = mysqli_insert_id($dbh);

	$que1 = "INSERT INTO qw_customer	(	
			name, fname, lname, phone, email) VALUES (
			'" . mysqli_escape_string($dbh, $cname ) . "',
			'" . mysqli_escape_string($dbh, $_POST['fname'] ) . "',
			'" . mysqli_escape_string($dbh, $lname ) . "',
			'" . mysqli_escape_string($dbh, $_POST['phone'] ) . "',
			'" . mysqli_escape_string($dbh, $_POST['email'] ) . "'
		)";
	mysqli_query($dbh, $que1);
	return $insid; 
}

function chkcustqb($name, $em, $ph){ global $dbh;
	
//	return mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM qw_customer WHERE (lname = 'Order' OR oname = 'Estimate') AND IID LIKE '$px%'", MYSQLI_ASSOC))[0];
	
	return;
}


function customwoocart() { $wc = WC();

// $rp  = $_POST['order2020items'][0];
// $it = explode(':::', $rp);

// $chsh = $wc->cart->generate_cart_id( $it[0] );

		// return '<a style="font-size: 17px; color: #009;" href="'. get_permalink( woocommerce_get_page_id('cart') ) . '?cid='.$chsh.'" target="_blank">WooCommerce Cart</a> created successfully';



// $chsh = WC()->cart->get_cart(); //NULL;
// echo '<pre>';
// print_r ($chsh);
// echo '</pre>';
// return;

	$chsh = $wc->cart->generate_cart_id( rand() );

	foreach($_POST['order2020items'] as $rp){
		$itq = explode(':::', $rp);
		$Pr_ID  = wc_get_product_id_by_sku($itq[0]);
//	$chsh = $wc->cart->generate_cart_id( $Pr_ID );
//echo $chsh . '<br>';
		$Pr = wc_get_product( $Pr_ID );
		if( $pr instanceof WC_Product && $Pr->is_type('variable') ) {
			$p_ID = wp_get_post_parent_id( $Pr_ID );
			if( $wc->cart ){ $wc->cart->add_to_cart( $Pr_ID, $itq[1], $p_ID ); }
			}
		else{ 
			if( $wc->cart ){ $wc->cart->add_to_cart( $Pr_ID, $itq[1] );	}
			}
		}


// $crtcnt = $wc->cart->find_product_in_cart( $chsh );
   // if ( $crtcnt ) {
      // echo 'YOUR CONTENT HERE';
   // }

// echo '<pre>';
// print_r ($crtcnt);
// echo '</pre>';

	if($chsh){
		return '<a style="font-size: 17px; color: #009;" href="'. get_permalink( woocommerce_get_page_id('cart') ) . '?cid='.$chsh.'" target="_blank">WooCommerce Cart</a> created successfully';
		}	
	else{ return 'Cart has not been created'; }
	}



?>