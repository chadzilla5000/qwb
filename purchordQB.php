<?php
include_once('inc/_init.php');
include_once('inc/functions/general.php');
//include_once('../wp-content/inc/functions/general.php');
require_once('inc/functions/qbw_fs.php');
require_once('inc/dblcheck.php');
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




//$Queue = new QuickBooks_WebConnector_Queue($dsn, $qbwc_user);
//$newid = rand();
///////$Queue->enqueue(QUICKBOOKS_QUERY_SALESORDER_W_ITEMS, $newid);
//$Queue->enqueue(QUICKBOOKS_IMPORT_PURCHASEORDER, $newid);
//exit;


$li=0;
$r0 = NULL;


	$xml = simplexml_load_file("data/sinchro/purchord.xml");
	
	
	




//exit;


//echo '<pre>';print_r($r0);echo '</pre>'; exit;








/////////////////////////////////////////////////////////////////////////////////////////////////////////////

$mfactor=(isset($_POST['mfactor']))?$_POST['mfactor']:NULL;

$oid = NULL; // (isset($_POST['srchiorder']))?$_POST['srchiorder']:NULL;

$pcnt .= '
<style>
.TList{
	width: 100%; background: #999;
	}
.TList th{
	background: #999;
	font-size: 13px;
	color: #ccc;
	}
.TList td{
	font-size: 11px;
	padding: 5px;
	}

.TList tr{ background: #fff; }
.TList tr:hover{ background: #ddd; }

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

#pordsrchresults{
	position: absolute;
	left: 20px;
	display: none;
	width: auto;
	padding: 3px 7px;
	background: #ffc;
	border: dotted 1px #fc0;
	border-radius: 0px 0px 9px 9px;
	z-index: 470;
}
#pordsrchresults a{ cursor: pointer; }
#pordsrchresults a:hover{ font-weight: bold; }
</style>

<script type="text/javascript" src="files/js/dblchk.js">

var clr;
var stp = 0;

</script>

<div>
<form action="#" method="post" name="upurchord" enctype="multipart/form-data" style="margin: 20px;">
<input type="file" name="csv" id="csv" title="" />
<input type="submit" name="upfile" id="upfile" value="Open" style="padding: 1px 5px;" />&nbsp; &nbsp;
<div id="ddtarget" style="height: 30px;"></div>


<input type="text" name="srch_purchord" id="srch_purchord" value="" placeholder="Search purchase order" OnKeyUp="srchpord(this);" style="width: 150px; padding: 3px;" />
<div id="pordsrchresults">&nbsp;</div>

<script>
document.getElementById("upfile").onclick = function(e) {
  if (document.getElementById("csv").value == "") {
    e.preventDefault();
    alert("Please select a \".csv\" file to upload!");
  }
}
</script>



</div>
<div style="clear: both;"></div>

';

//////////// Form handler ////////////////////////////////////////////////////
if(isset($_FILES['csv']) AND $_FILES['csv']['name']){

	$dt = NULL;
	$incnt = NULL;
	$gttl = 0;

	$csvarr = array();
	if($_FILES['csv']['error']==0){ // check there are no errors
//if(1){

//print_r( $_FILES['csv']['error']); echo '<br>';

//$countfiles = count($_FILES['csv']['name']);
//$csv0 = getcsvarr(0);
$csvarr = getcsvarr(1);




/////////////////////////////////////////////////////////////////////////////////////////////////////////
$vi=0;
$shipto = NULL;
$aln = $ship2arr = array();




$vnd = getvendor($csvarr);
$r1 = NULL;
$crd = $addrstyle = $shphonestyle = $lrowstyle = $qtitstyle = NULL;
$vendor = NULL;

if($vnd=='ctc'){       ////////////////////////////////////////////    Cubitac //////////////
$r1 = ttscan_ctc($csvarr);
$vendor = 'Cubitac';
}

if($vnd=='fm'){       ////////////////////////////////////////////     Forevermark //////////////
$r1 = ttscan_fm($csvarr);
$vendor = 'Forevermark';

$crd = getcoords($csvarr, 'Ship To');
	
while($vi<15){
	$aln[$vi] = lk4cl($csvarr, $crd, 0, $vi+1);
	if($aln[$vi]=='Door Style'){break;}
	if($aln[$vi]=='Waverly Cabinets *'){}
	else{$shipto .= $aln[$vi].'<br>'; array_push($ship2arr, $aln[$vi]); }
	$vi++;
	}

$addrstyle = (addrchck($order, $ship2arr))?'color: #090;':'color: #f00;';
$shphonestyle = (ship2phonehck($user_phone, $ship2arr))?'background: transparent;':'background: #f00;';

$lrowstyle = ($lrows1==$lrows2)?'bacground: transparent;':'background: #fc9;';
$qtitstyle = ($qtit1==$qtit2)?'bacground: transparent;':'background: #fc9;';
}

if($vnd=='ghi'){       ////////////////////////////////////////////     Horning //////////////
$r1 = ttscan_ghi($csvarr);
$vendor = 'Horning';
$crd = getcoords($csvarr, 'Ship To:');
while($vi<5){
	$aln[$vi] = lk4cl($csvarr, $crd, 0, $vi+1);
	$shipto .= $aln[$vi].'<br>'; array_push($ship2arr, $aln[$vi]);
	$vi++;
	}

$addrstyle = (addrchck($order, $ship2arr))?'color: #090;':'color: #f00;';
$shphonestyle = (ship2phonehck($user_phone, $ship2arr))?'background: transparent;':'background: #f00;';

$lrowstyle = ($lrows1==$lrows2)?'bacground: transparent;':'background: #fc9;';
$qtitstyle = ($qtit1==$qtit2)?'bacground: transparent;':'background: #fc9;';
}

if($vnd=='uscd'){       ////////////////////////////////////////////    USCD //////////////
$r1 = ttscan_uscd($csvarr);
$vendor = 'USCD';
$crd = getcoords($csvarr, 'Shipping Address');
while($vi<5){
	$aln[$vi] = lk4cl($csvarr, $crd, 0, $vi+1);
	$shipto .= $aln[$vi].'<br>'; array_push($ship2arr, $aln[$vi]);
	$vi++;
	}

$addrstyle = (addrchck($order, $ship2arr))?'color: #090;':'color: #f00;';
$shphonestyle = (ship2phonehck($user_phone, $ship2arr))?'background: transparent;':'background: #f00;';

$lrowstyle = ($lrows1==$lrows2)?'bacground: transparent;':'background: #fc9;';
$qtitstyle = ($qtit1==$qtit2)?'bacground: transparent;':'background: #fc9;';
}




/*
if($vnd=='ghi'){       ////////////////////////////////////////////     Forevermark //////////////
$r1 = ttscan_ghi($csvarr);


$vi=0;
$shipto = NULL;
$aln = $ship2arr = array();
$crd = getcoords($csvarr, 'Ship To');
//	$aln[0] = lk4cl($csvarr, $crd, 0, $vi+3);
//	$shipto .= $aln[0].'<br>';
	
while($vi<15){
	$aln[$vi] = lk4cl($csvarr, $crd, 0, $vi+1);
	if($aln[$vi]=='Door Style'){break;}
	if($aln[$vi]=='Waverly Cabinets *'){}
	else{$shipto .= $aln[$vi].'<br>'; array_push($ship2arr, $aln[$vi]); }
	$vi++;
	}
	

$addrstyle = (addrchck($order, $ship2arr))?'color: #090;':'color: #f00;';
$shphonestyle = (ship2phonehck($user_phone, $ship2arr))?'background: transparent;':'background: #f00;';

$lrowstyle = ($lrows1==$lrows2)?'bacground: transparent;':'background: #fc9;';
$qtitstyle = ($qtit1==$qtit2)?'bacground: transparent;':'background: #fc9;';

}

if($vnd=='uscd'){       ////////////////////////////////////////////     Forevermark //////////////
$r1 = ttscan_uscd($csvarr);

$vi=0;
$shipto = NULL;
$aln = $ship2arr = array();
$crd = getcoords($csvarr, 'Ship To');
//	$aln[0] = lk4cl($csvarr, $crd, 0, $vi+3);
//	$shipto .= $aln[0].'<br>';
	
while($vi<15){
	$aln[$vi] = lk4cl($csvarr, $crd, 0, $vi+1);
	if($aln[$vi]=='Door Style'){break;}
	if($aln[$vi]=='Waverly Cabinets *'){}
	else{$shipto .= $aln[$vi].'<br>'; array_push($ship2arr, $aln[$vi]); }
	$vi++;
	}
	

$addrstyle = (addrchck($order, $ship2arr))?'color: #090;':'color: #f00;';
$shphonestyle = (ship2phonehck($user_phone, $ship2arr))?'background: transparent;':'background: #f00;';

$lrowstyle = ($lrows1==$lrows2)?'bacground: transparent;':'background: #fc9;';
$qtitstyle = ($qtit1==$qtit2)?'bacground: transparent;':'background: #fc9;';

}

*/

$flg_01 = 1;
$popts = NULL;
$parr = array();
$ship1=array();

$pordname = (isset($_POST['srch_purchord']))?$_POST['srch_purchord']:NULL;


	foreach($xml->QBXMLMsgsRs->PurchaseOrderQueryRs->PurchaseOrderRet as $d){

//		array_push($parr,$d->RefNumber);

//echo array_search('green', $array);
//if($d->RefNumber=='PerWal1123'){

$inpn =($pordname)?	$pordname : $r1[0]['pon'];
	
if(strtolower($d->RefNumber)== strtolower($inpn)){
$flg_01 = 0;

$ship1=array(
$d->CustomerRef->FullName,
$d->ShipAddress->Addr1,
$d->ShipAddress->Addr2,
$d->ShipAddress->Addr3,
$d->ShipAddress->Addr4,
$d->ShipAddress->City,
$d->ShipAddress->State,
$d->ShipAddress->PostalCode,
$d->ShipAddress->Note
);


$r0 = group_sims(poscan($d));



//foreach



//echo strtolower($r1[0]['pon']) . ' --- ' . $d->RefNumber;


//echo $d->RefNumber . ' - <a href="purchas2020?rf='.$d->RefNumber.'">' . $d->ShipToEntityRef->FullName . '</a> - '.$d->TxnDate.'<br>';

}
//echo ++$li.'. '.$d->RefNumber . ' - <a href="purchas2020?rf='.$d->RefNumber.'">' . $d->CustomerRef->FullName . '</a> - '.$d->TxnDate.'<br>';


		}


//asort($parr);

// foreach ($parr as $v){
// echo $v . '<br>';

	// $popts .='
// <option value="'.$v.'">'.$v.'</option>
// '; 

// }

//if($flg_01){
if(0){
	$pcnt .= '

</form>

';

$page_title = 'Vendor Confirmation Double Check';

$pgcontent = <<<EOD__
<!-- Page content start -->
$pcnt
<!-- Page content end -->
EOD__;

require_once('inc/_shell.php'); ///

exit;	
}

//$oid = getordn($csvarr);  //////////////////////////////////////////////////////////////////////
//$order = ($oid) ? new WC_Order($oid) : NULL; //////////////////////////////////////////////////

$sku1=$sku2=NULL;
$qty1=$qty2=$sprice=$pcost=$sttl_1=$sttl_2=0;
//$brand = NULL;
$stsbg = 'transparent';
$itemsfrm = $purchfrm = '';

$soarr = array();
$i=0;


// echo '<pre>'; print_r($r0); echo '</pre>';

$user_id = ($order)?$order->get_user_id():NULL;
$user_phone = get_user_meta( $user_id, 'billing_phone', true );

$lrows1 = $lrows2 = $qtit1 = $qtit2 = 0;

if($flg_01){
	$itemsfrm .= '<tr><td colspan="5" style="text-align: center; font-size: 23px; color: #f00;">Purchase order not found. Select it manually.</td></tr>';
}
else{
foreach($r0 as $oit){
	$sku = skutrim0($oit['sku']);
	$rr01 = look2sku($r1, $sku);
	$rr11 = look4sku($r1, $sku);
	$style1 = ($oit['qty']==$rr11['qty'])?'fff':'fc9';
	$style2 = ($rr01)?'fff':(($rr11)?'ffc':'fc9');
	$style3 = ($oit['ppr']==$rr11['ppr'])?'fff':'fc9';


	$lrows1++;
	$qtit1 += $oit['qty'];
	$factor = (0)?0:$oit['fct'];
	$itemsfrm .= '
<tr id="C1_'.$sku.'" OnMouseOver="look4sim(this);" OnMouseOut="lookout(this);"><td style="text-align: center; background: #'.$style1.';">'.$oit['qty'].'</td>
	<td style="background: #'.$style2.';">'.$oit['sku'].'</td>
	<td>'.$oit['ttl'].' <input type="checkbox" name="lsku" value="1" style="float: right;" /></td>
	<!-- td style="padding: 0px;width:50px;"><input type="text" style="width: 50px; height: 23px; padding: 2px; border: none; text-align: right; margin: 0px; background: #ffd; font-size: 12px;" name="f_'.$sku.'" value="'.$factor.'" OnChange="recalcost(this, \''. $oit['lpr'] .'\');" OnKeyUp="putbttn(this);" />
	</td -->
	<td style="padding: 0px 2px; vertical-align: middle; text-align: right; background: #'.$style3.';" id="CRC_'.$sku.'">$'.number_format($oit['ppr'], 2).'</td>
	<td style="text-align: right;">$'.number_format($oit['amt'], 2).'</td>
</tr>
   ';
}

}

foreach($r1 as $cit){
	$sku = skutrim1($cit['sku']);
	$rr02 = ($r0)?look2sku($r0, $sku):NULL;
	$rr12 = ($r0)?look4sku($r0, $sku):NULL;
	$style1 = ($cit['qty']==$rr12['qty'])?'fff':'fc9';
	$style2 = ($rr02)?'fff':(($rr12)?'ffc':'fc9');
	$style3 = ($cit['ppr']==$rr12['ppr'])?'fff':'fc9';
	
	$lrows2++;
	$qtit2 += $cit['qty'];

	$purchfrm .= '
<tr id="C2_'.$sku.'" OnMouseOver="look4sim(this);" OnMouseOut="lookout(this);"><td style="background: #'.$style1.';">'.$cit['qty'].'</td>
	<td style="background: #'.$style2.';">'.$cit['sku'].'</td>
	<td>'.$cit['rmk'].'</td>
	<td>'.$cit['ttl'].'</td>
	<td style="background: #'.$style3.';">$'.number_format(floatval($cit['ppr']), 2).'</td>
	<td style="">$'.number_format(floatval($cit['amt']), 2).'</td>
</tr>
';
	
}



		$pcnt .= '
<div style="height: 90%; background: #ffe;">
	<div style="float: left; width:49%; min-height: 95%; padding: 7px;">
		<table class="TList" cellspacing="1">
			<tr><th colspan="5" style="height: 30px; vertical-align: middle; font-size: 17px;">Purchase Order: '.$inpn.'</th></tr>
			<tr><th colspan="2" style="vertical-align: top;">Ship to address</th>
				<td colspan="3" style="height: 200px; vertical-align: top;">
'.$ship1[0].'
'.$ship1[1].'<br>
'.$ship1[2].'<br>
'.$ship1[3].'<br>
'.$ship1[4].'<br>
'.$ship1[5].'<br>
'.$ship1[6].'<br>
'.$ship1[7].'<br><br>
'.$user_phone.'<br>
				</td>
			</tr>
			<tr><th style="width: 5%; height: 30px;">Qtty</th>
				<th>Item Code</th>
				<th>Description</th>
				<th>Cost</th>
				<th>Total</th>
			</tr>
'.$itemsfrm.'
			<tr><td colspan="3" style="height: 50px; vertical-align: middle;"><span style="border-radius: 50%; padding: 9px; '.$lrowstyle.'">Rows - '.$lrows1.'</span>&nbsp;&nbsp;&nbsp; <span style="border-radius: 50%; padding: 9px; '.$qtitstyle.'">Items - '.$qtit1.'</span></td>
				<td colspan="2" style="vertical-align: middle;">&nbsp;</td>
			</tr>
		</table>
	</div>
	<div style="float: right; width:49%; min-height: 95%; padding: 7px;">
		<table class="TList" cellspacing="1">
		
			<tr><th colspan="2" style="height: 30px; vertical-align: middle; font-size: 17px;">'.$vendor.'</th><th colspan="2" style="height: 30px; vertical-align: middle; font-size: 17px;">Confirmation # '.$r1[0]['ocn'].'</th></tr>
			<tr><th colspan="2" style="vertical-align: top;">Ship to address</th>
				<td colspan="4" style="height: 200px; vertical-align: top; '.$addrstyle.'">'.$shipto.'<div style="float: left; width: 90px; height: 5px; '.$shphonestyle.'"></div></td>
			</tr>
			<tr><th style="width: 5%; height: 30px;">Qtty</th>
				<th>Item Code</th>
				<th>Remarks</th>
				<th>Description</th>
				<th>Cost</th>
				<th>Total</th>
			</tr>
'.$purchfrm.'
			<tr><td colspan="4" style="height: 50px; vertical-align: middle;"><span style="border-radius: 50%; padding: 9px; '.$lrowstyle.'">Rows - '.$lrows2.'</span>&nbsp;&nbsp;&nbsp; <span style="border-radius: 50%; padding: 9px; '.$qtitstyle.'">Items - '.$qtit2.'</span></td>
				<td colspan="2" style="vertical-align: middle;">&nbsp;</td>
			</tr>
		</table>
	</div>
</div>
<div style="clear: both; padding: 30px 0px;">
'. displaycsvarr($csvarr) . '
</div>

</form>

';
		}
	}
else{}

$page_title = 'Vendor Confirmation Double Check';

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



?>