<?php
include_once('inc/_init.php');
include_once('inc/functions/general.php');
include_once 'wconfig.php';
//include_once('../wp-content/inc/functions/general.php');
require_once('inc/functions/qbw_fs.php');
require_once('inc/dblcheck_f.php');
//error_reporting(E_ALL | E_STRICT);
// require_once('PHPMailer-master/src/PHPMailer.php');
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

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

error_reporting(E_ERROR | E_WARNING | E_PARSE);






if(0){

$email = new PHPMailer();
$email->SetFrom('custserv@waverlycabinets.com', 'Zazzzzzz'); //Name is optional
$email->Subject   = 'It should be HTML attachement here';
$email->Body      = 'No body now';
$email->AddAddress( 'zaza@waverlycabinets.com' );

$file_to_attach = 'sdk.html';

$email->AddAttachment( $file_to_attach , 'sdk.html' );

return $email->Send();
	
	
	exit;
}



//require('html2pdf.php');


/////////////////////////////////////////////////////////////////////////////////////////////////////////////

$mfactor=(isset($_POST['mfactor']))?$_POST['mfactor']:NULL;

$oid = (isset($_POST['srchiorder']))?$_POST['srchiorder']:NULL;

$oopts = NULL;

/*
$args = array(
    'limit' => 10000,
    'orderby' => 'date',
    'order'   => 'DESC'
);

$query = new WC_Order_Query( $args );
$orders = $query->get_orders();

foreach($orders as $o){


	$om = get_post_meta($o->id);

$com4sr = strtolower($om['_billing_company'][0]);
$ln4sr = strtolower($om['_billing_last_name'][0]);
$fn4sr = strtolower($om['_billing_first_name'][0]);
$em4sr = strtolower($om['_billing_email'][0]);
$ph4sr = strtolower($om['_billing_phone'][0]);

$oopts .= '<option value="'.$o->id.'">'.$o->id.':'.$om['_billing_company'][0]. ' - '.$om['_billing_last_name'][0]. ' - '.$om['_billing_first_name'][0].'</option>';

//	$od = new WC_Order($oid);


}
*/

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


.GP{
	width: 100%; background: #999;
	}
.GP th{
	background: #999;
	font-size: 13px;
	color: #ccc;
	}
.GP td{
	background: #fff;
	font-size: 11px;
	padding: 5px;
	}
.GP input{width: 80px;	}


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

.dbcro{
	border: none;
}

.NFborder {
width: 100%;
margin: 0px !important;
padding: 0px !important;
border: none;
outline-offset: 0px !important;
outline: none !important;
}

</style>
<script src="files/js/html2canvas.js"></script> 

<script type="text/javascript" src="files/js/dblchk.js">
var clr;
var stp = 0;

</script>
<div>
<form action="#" method="post" name="upurchord" enctype="multipart/form-data" style="margin: 20px;">
<input type="file" name="csv" id="csv" />
<!-- input type="file" name="csv[]" multiple / -->
<input type="submit" name="upfile" id="upfile" value="Open" style="padding: 1px 5px;" />&nbsp; &nbsp;

<!-- br><br>
<input type="text" name="srchiorder" id="srchiorder" value="" OnKeyUp="tordsrch(this)" placeholder="Search Initial Order" /> <br>
<div id="ddtarget"></div -->



<!-- select name="ords">
<option value="">Select initial order</option>
'.$oopts.'
</select -->

</div>
<div style="clear: both;"></div>

<script>
document.getElementById("upfile").onclick = function(e) {
  if (document.getElementById("csv").value == "") {
    e.preventDefault();
    alert("Please select a \".csv\" file to upload!");
  }
}
</script>

';


//////////// Form handler ////////////////////////////////////////////////////
if(isset($_FILES['csv']) AND $_FILES['csv']['name']){

	$dt = NULL;
	$incnt = NULL;
	$gttl = 0;

	$csvarr = array();
	if($_FILES['csv']['error'] == 0){ // check there are no errors
//if(1){


/*
$countfiles = count($_FILES['csv']['name']);
for($i=0;$i<$countfiles;$i++){
	$csvfname = $_FILES['csv']['name'][$i];
 
 echo $csvfname . '<br>';

//  move_uploaded_file($_FILES['file']['tmp_name'][$i],'upload/'.$csvfname);
 }
exit;

*/

	$xml1 = simplexml_load_file("data/sinchro/purchord.xml");
	$xml2 = simplexml_load_file("data/sinchro/qbordersdd.xml");






		$name = $_FILES['csv']['name'];
		$ext = strtolower(end(explode('.', $_FILES['csv']['name'])));
		$type = $_FILES['csv']['type'];
		$tmpName = $_FILES['csv']['tmp_name'];
		if($ext === 'html'){
			$fupcnt = file_get_contents($tmpName);
			
			// if(($handle = fopen($tmpName, 'r')) !== FALSE) {
				// // necessary if a large csv file
				// set_time_limit(0);
				// $row = 0;
				// while(($data = file_get_contents($tmpName)) !== FALSE) {
					// $col_count = count($data);
					// for($col=0; $col<$col_count; $col++){
						// $csvarr[$row][$col]=$data[$col];
						// }
					// $row++;
					// }
				// fclose($handle);
				// }
			}



/////////////////////////////////////////////////////////////////////////////////////////////////////////
$vi=$bi=0;
$soldto = $shipto = NULL;
$bln = $sold2arr = array();
$aln = $ship2arr = array();

$bill2 = getbillingaddr($fupcnt);
$shp2w = getshippingaddr($fupcnt);

$html = str_get_html($fupcnt); // Parse the HTML, stored as a string in $string
$rows = ($html)?$html->find('tr'):array(); // Find all rows in the table

$df=$fi=$fii=0;
$r0=array();
$r1=array();
$tt=array();


foreach ($rows as $row) {
//	echo $row->children()[1]->plaintext . '<br>';
	
	if(preg_match('/(.*)(Order #&nbsp;)([\d]{7,9})(.*)/', $row->children()[1]->plaintext, $m)){ $r1[0]['ocn']=$m[3]; }
	if(preg_match('/(.*)(PO[&nbsp;\s]*#[&nbsp;\s]*)([\w\s]*)(Shipping Method)(.*)/', $row->children()[1]->plaintext, $m)){ $r1[0]['pon']= preg_replace('/\s/','',$m[3]); }
	if($row->children()[0]->plaintext=='Subtotal')						{ $tt['onet'] = floatval(preg_replace('/[\$,]*/','',$row->children()[1]->plaintext)); $df = 0; }
	if(	$row->children()[0]->plaintext=='Shipping Cost (Freight - MF)' OR
		$row->children()[0]->plaintext=='Shipping Cost (Carrier)'	)	{ $tt['frgt'] = floatval(preg_replace('/[\$,]*/','',$row->children()[1]->plaintext)); }
	if($row->children()[0]->plaintext=='Total')							{ $tt['ottl'] = floatval(preg_replace('/[\$,]*/','',$row->children()[1]->plaintext)); }
	if($df){	
		$r1[$fi]['qty'] = $row->children()[1]->plaintext;
		$r1[$fi]['sku'] = $row->children()[3]->plaintext;
		$r1[$fi]['rmk'] = $row->children()[4]->plaintext;
		$r1[$fi]['ttl'] = $row->children()[5]->plaintext;
		$r1[$fi]['ppr'] = $row->children()[6]->plaintext;
		$r1[$fi]['amt'] = $row->children()[7]->plaintext;

		// foreach ($row->children() as $cell) {
			// $r1[$fi][$fii] = $cell->plaintext;
			// $fii++;
			// }
		$fi++;
		}
	if($row->children()[0]->plaintext=='B/O ETA 1'){ $df = 1; }
	}

$insn = NULL;
$ship0=array();
$custoname = NULL;
$grttl_1=0;

	foreach($xml1->QBXMLMsgsRs->PurchaseOrderQueryRs->PurchaseOrderRet as $d){
		$inpn =($pordname)?	$pordname : $r1[0]['pon'];
		if(strtolower($d->RefNumber)== strtolower($inpn)){
//			$flg_01 = 0;
			if(!$custoname){ $custoname = $d->PurchaseOrderLineRet->CustomerRef->FullName; }
			$grttl_1=$d->TotalAmount;
			$ship0=array(
			$d->PurchaseOrderLineRet->CustomerRef->FullName,
			$d->ShipAddress->Addr1,
			$d->ShipAddress->Addr2,
			$d->ShipAddress->City,
			$d->ShipAddress->State,
			$d->ShipAddress->PostalCode,
			$d->ShipAddress->Note);
			$r0 = group_sims(poscan($d));
//			echo $d->Memo;
			if(preg_match('/(Sales Order )([\w-]*)(:)(.*)/', $d->Memo, $m)){ $insn = $m[2]; }
			}
		}
 

	foreach($xml2->QBXMLMsgsRs->SalesOrderQueryRs->SalesOrderRet as $d){
//		$insn =($sordname)?	$sordname : $r1[0]['son'];
		if($d->RefNumber==$insn){
//			$flg_01 = 0;
			$bll2s=array(
			$d->CustomerRef->FullName,
			$d->BillAddress->Addr1,
			$d->BillAddress->Addr2,
			$d->BillAddress->City,
			$d->BillAddress->State,
			$d->BillAddress->PostalCode,
			$d->BillAddress->Note);

			$shp2s=array(
			$d->CustomerRef->FullName,
			$d->ShipAddress->Addr1,
			$d->ShipAddress->Addr2,
			$d->ShipAddress->Addr3,
			$d->ShipAddress->Addr4,
			$d->ShipAddress->City);
//			$r2 = group_sims(soscan($d));
			$r2 = soscan($d);

			// if(	preg_match('/Subtot/', $d->SalesOrderLineRet->ItemRef->FullName) OR 
				// preg_match('/Discou/', $d->SalesOrderLineRet->ItemRef->FullName)){}
			// else{ $r2 = soscan($d); }
			}
	//		echo $d->RefNumber . ' - ' . $insn . '<br>';
		}







// echo $stot.'<br>';
// echo $ship.'<br>';
// echo $ftot.'<br>';


// echo '<table>';
// foreach($r1 as $fr){
	// echo '<tr>';
	// foreach($fr as $fc){
		// echo '<td>' . $fc . '</td>';
		// }
	// echo '</tr>';
	// }
// echo '</table>';




// $xpathParser = new DOMXPath($fupcnt);
// $tableDataNodes = $xpathParser->evaluate("//table/tr/td");
// for ($x=0;$x<$tableDataNodes.length;$x++) {
    // echo $tableDataNodes[$x];
// }


//exit;


$cc = NULL;

if(preg_match('/(<tr bgcolor="#777777">)([\w\W\r\n]*)(<tr bgcolor="#FFFFFF"> <\/tr><\/table>)/', $fupcnt, $m)){
	
	$tb_0 = $m[2];
	$tb_0 = preg_replace('/^(<th nowrap)([\w\W\r\n\s]*)(<\/th><\/tr>)/', '', $tb_0);
	$tb_0 = preg_replace('/^(<tr><td valign=top class="lined" >)([\W\r\n\s]*nbsp;)/', '', $tb_0);
	
	
	$rows = preg_split('(</td>[\W]*</tr><tr><td valign=top class="lined" >[\W]*nbsp;)', $tb_0);

	
	foreach($rows as $row){
		$tds = preg_split('(</td>[\W]*<td valign=top class="lined"[\w="\s]*>)', $row);
		foreach($tds as $cell){
			$cc .= $cell . '
';
			}
		}
		
	$tb_0 = preg_replace('/^(<\/tr><tr>)([\w\W\r\n\s]*nbsp;)(Subtotal)/', '', $tb_0);
	
	}


//echo '<textarea style="width: 100%; height: 800px;">'.$cc.'</textarea>';


//exit;























$vnd = 'fm';//getvendor($csvarr);
//$r1 = NULL;
$crd = $addrstyle = $shphonestyle = $lrowstyle = $qtitstyle = NULL;
$vendor = NULL;

if($vnd=='fm'){       ////////////////////////////////////////////     Forevermark //////////////
//$r1 = ttscan_fm($csvarr);
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


//$oid = getordn($csvarr);  //////////////////////////////////////////////////////////////////////
$order = ($oid) ? new WC_Order($oid) : NULL; //////////////////////////////////////////////////

$sku1=$sku2=NULL;
$qty1=$qty2=$sprice=$pcost=$sttl_1=$sttl_2=0;
//$brand = NULL;
$stsbg = 'transparent';
$salesfrm = $itemsfrm = $purchfrm = '';

$soarr = array();
$i=0;

//		$r0 = orscan($order);

// echo '<pre>'; print_r($r0); echo '</pre>';

$user_id = ($order)?$order->get_user_id():NULL;
$user_phone = get_user_meta( $user_id, 'billing_phone', true );

$bill_phone = get_user_meta( $user_id, 'billing_phone', true );
$ship_phone = get_user_meta( $user_id, 'shipping_phone', true );

$shipping_total = 0; //($order)?$order->get_shipping_total():NULL;

/*
$bill1=array(
$order->billing_first_name,
$order->billing_last_name,
$order->billing_company,
$order->billing_address_1,
$order->billing_address_2,
$order->billing_city,
$order->billing_state,
$order->billing_postcode,
$order->billing_country
);

$ship1=array(
$order->shipping_first_name,
$order->shipping_last_name,
$order->shipping_company,
$order->shipping_address_1,
$order->shipping_address_2,
$order->shipping_city,
$order->shipping_state,
$order->shipping_postcode,
$order->shipping_country
);

*/

$lrows0 = $lrows1 = $lrows2 = $qtit0 = $qtit1 = $qtit2 = 0;
$io_ttl_0 = $io_ttl_1 = $io_ttl_2 = $oc_ttl = 0;
$ttlength1 = 60;
$ttlength2 = 50;

foreach($r0 as $oit){
	$sku = skutrim0($oit['sku']);
	$rr01 = look2sku($r1, $sku);
	$rr11 = look4sku($r1, $sku);
	$style1 = ($oit['qty']==$rr11['qty'])?'fff':'fc9';
	$style2 = ($rr01)?'fff':(($rr11)?'ffc':'fc9');
	$style3 = ($oit['ppr']==$rr11['ppr'])?'fff':'fc9';


	$lrows0++;
	$qtit0 += $oit['qty'];
	$factor = (0)?0:$oit['fct'];
	$itemsfrm .= '
<tr id="C1_'.$sku.'" OnMouseOver="look4sim(this);" OnMouseOut="lookout(this);"><td style="text-align: center; background: #'.$style1.';">'.$oit['qty'].'</td>
	<td style="background: #'.$style2.';">'.$oit['sku'].'</td>
	<td title="'.dclean($oit['ttl']).'">'.substr($oit['ttl'], 0, $ttlength1).' <input type="checkbox" name="lsku" value="1" style="float: right;" /></td>
	<!-- td style="padding: 0px;width:50px;"><input type="text" style="width: 50px; height: 23px; padding: 2px; border: none; text-align: right; margin: 0px; background: #ffd; font-size: 12px;" name="f_'.$sku.'" value="'.$factor.'" OnChange="recalcost(this, \''. $oit['lpr'] .'\');" OnKeyUp="putbttn(this);" />
	</td -->
	<td style="padding: 0px 2px; vertical-align: middle; text-align: right; background: #'.$style3.';">$'.number_format($oit['ppr'], 2).'</td>
	<td style="text-align: right;">$'.number_format($oit['amt'], 2).'</td>
</tr>
   ';
}

foreach($r1 as $cit){
	$sku = skutrim1($cit['sku']);
	$rr02 = look2sku($r0, $sku);
	$rr12 = look4sku($r0, $sku);
	$style1 = ($cit['qty']==$rr12['qty'])?'fff':'fc9';
	$style2 = ($rr02)?'fff':(($rr12)?'ffc':'fc9');
	$style3 = ($cit['ppr']==$rr12['ppr'])?'fff':'fc9';
	
	$lrows1++;
	$qtit1 += $cit['qty'];
	$cit['amt']=($cit['amt'])?$cit['amt']:$cit['ppr']*$cit['qty'];
	$oc_ttl += ($cit['sku']!='Subtotal' AND $cit['sku']!='Freight')?floatval($cit['amt']):0; //// dont really used

	$purchfrm .= '
<tr id="C2_'.$sku.'" OnMouseOver="look4sim(this);" OnMouseOut="lookout(this);"><td id="cll_'.$sku.'_1" style="width: 40px;background: #'.$style1.';">'.$cit['qty'].'</td>
	<td id="cll_'.$sku.'_2" style="width: 220px; background: #'.$style2.';">'.$cit['sku'].'</td>
	<td style="padding: 0px;">
	<!-- input class="NFborder" style="height: 22px;" type="text" name="remark_" value="'.$cit['rmk'].'" size="23" / -->
	<textarea style="width: 100%; height: 19px; word-break: break-all; text-wrap: unrestricted;" class="NFborder" name="remark_">'.$cit['rmk'].'</textarea>
	</td>
	<td title="'.dclean($cit['ttl']).'" style="width: 400px;">'.substr($cit['ttl'], 0, $ttlength1).' <input type="checkbox" name="chb_'.$sku.'" value="1" style="float: right;" OnChange="chbhndl(this);" /></td>
	<td id="cll_'.$sku.'_3" style="width: 70px; background: #'.$style3.';">$'.number_format(floatval($cit['ppr']), 2).'</td>
	<td style="width: 70px;">$'.number_format(floatval($cit['amt']), 2).'</td>
</tr>
';
	
}

$qsubttl = $qdscnt = 0;
foreach($r2 as $iit){
	if($iit['sbttl']){ $qsubttl = $iit['sbttl']; continue; }
	if($iit['dscnt']){ $qdscnt  = $iit['dscnt']; continue; }
	if($iit['frght']){ $shipping_total = $iit['frght']; continue; }
	$sku = skutrim0($iit['sku']);
	// $rr01 = look2sku($r1, $sku);
	// $rr11 = look4sku($r1, $sku);
	// $style1 = ($iit['qty']==$rr11['qty'])?'fff':'fc9';
	// $style2 = ($rr01)?'fff':(($rr11)?'ffc':'fc9');
	//$style3 = ($iit['ppr']==$rr11['ppr'])?'fff':'fc9';

	$lrows2++;
	$qtit2 += $iit['qty'];
	$factor = (0)?0:$iit['fct'];
	
	$sttl = $iit['ppr'] * $iit['qty']; 
	$io_ttl_0 += floatval($iit['lpr']) * $iit['qty'];
	$io_ttl_1 += $sttl;
	$io_ttl_2 += floatval($iit['amt']);
	
	$salesfrm .= '
<tr id="C3_'.$sku.'" OnMouseOver="look4sim(this);" OnMouseOut="lookout(this);"><td style="text-align: center;" id="QTY_'.$sku.'">'.$iit['qty'].'</td>
	<td>'.$iit['sku'].'</td>
	<td title="'.dclean($iit['ttl']).'">'.substr($iit['ttl'], 0, $ttlength2).' <input type="checkbox" name="ssku" value="1" style="float: right;" /></td>
	<td style="padding: 0px;width:50px;"><input type="text" style="width: 50px; height: 23px; padding: 2px; border: none; text-align: right; margin: 0px; background: #ffd; font-size: 12px;" name="f_'.$sku.'" value="'.$factor.'" title="'.$iit['lpr'].'" OnKeyUp="recalcost(this); recalcostall();" dOnKeyUp="putbttn(this);" />
	</td>
	<td style="padding: 0px 2px; vertical-align: middle; text-align: right;" id="CRC_'.$sku.'">$'.number_format($iit['ppr'], 2).'</td>
	<td style="padding: 0px 2px; vertical-align: middle; text-align: right;" id="CRT_'.$sku.'">$'.number_format($sttl, 2).'</td>
	<td style="text-align: right;" id="LPR_'.$sku.'">$'.number_format($iit['lpr'], 2).'</td>
	<td style="text-align: right;">$'.number_format($iit['spr'], 2).'</td>
	<td style="text-align: right;">$'.number_format($iit['amt'], 2).'</td>
</tr>
   ';
}

//$tt = scantotals($csvarr, $vnd);

$ttbl = '<table>';
$ttbl.= ( $tt['onet']!='' ) ? '<tr><td>Net Order:</td><td>'     . $tt['onet'].'</td></tr>' : ''; 
$ttbl.= ( $tt['dcnt']!='' ) ? '<tr><td>Less Discount:</td><td>' . $tt['dcnt'].'</td></tr>' : ''; 
$ttbl.= ( $tt['frgt']!='' ) ? '<tr><td>Freight:</td><td>'       . $tt['frgt'].'</td></tr>' : ''; 
$ttbl.= ( $tt['sltx']!='' ) ? '<tr><td>Sales Tax:</td><td>'     . $tt['sltx'].'</td></tr>' : ''; 
$ttbl.= ( $tt['ottl']!='' ) ? '<tr><td>Order Total:</td><td>'   . $tt['ottl'].'</td></tr>' : ''; 
$ttbl.= '</table>';


		$pcnt .= '
<div style="height: 93%; background: #ffe;">
	<div style="float: left; width:45%; min-height: 95%; padding: 7px;">
		<table class="TList" cellspacing="1">
			<tr><th colspan="5" style="height: 30px; vertical-align: middle; font-size: 17px;">Purchase Order: '.$inpn.'</th></tr>
<tr><td colspan="5" style="padding: 0px;">


<table style="width: 100%;">
			<tr><th style="width: 30%; vertical-align: top;">Ship to address</th>
				<td style="width: 40%; height: 200px; vertical-align: top;">
				<input type="checkbox" name="" value="1" style="float: right;" />
'.$ship0[0].'
'.$ship0[1].'<br>
'.$ship0[2].'<br>
'.$ship0[3].'<br>
'.$ship0[4].'<br>
'.$ship0[5].'<br>
'.$ship0[6].'<br>
'.$ship0[7].'<br><br>
'.$user_phone.'<br>
				</td>
				<th style="width: 30%; vertical-align: top;"></th>
			</tr>
</table>

</td></tr>
			<tr><th style="width: 5%; height: 30px;">Qty</th>
				<th>Item Code</th>
				<th>Description</th>
				<th>Cost</th>
				<th>Total</th>
			</tr>
'.$itemsfrm.'
			<tr><td colspan="3" style="height: 50px; vertical-align: middle;"><span style="border-radius: 50%; padding: 9px; '.$lrowstyle.'">Rows - '.$lrows0.'</span>&nbsp;&nbsp;&nbsp; <span style="border-radius: 50%; padding: 9px; '.$qtitstyle.'">Items - '.$qtit0.'</span></td>
				<td colspan="2" style="text-align: right; vertical-align: middle; font-weight: bold;">Total: '.number_format(floatval($grttl_1), 2).'</td>
			</tr>
		</table>
	</div>

	<div id="ocfm" style="float: right; width:53%; min-height: 95%; padding: 7px;">
		<table class="TList" cellspacing="1">
		
			<tr><th colspan="2" style="height: 30px; vertical-align: middle; font-size: 17px;">'.$vendor.'</th><th colspan="4" style="height: 30px; vertical-align: middle; font-size: 17px;">Confirmation # '.$r1[0]['ocn'].'</th></tr>

			<tr><td colspan="6" style="padding: 0px;">
				<table style="width: 100%;">
				<tr><th style="width: 20%; vertical-align: top;">Bill to address</th>
					<td style="width: 30%; height: 200px; vertical-align: top; '.$addrstyle.'"><input type="checkbox" name="" value="1" style="float: right;" />
'.$bill2.'<div style="float: left; width: 90px; height: 5px; '.$shphonestyle.'"></div></td>
					<th style="width: 20%; vertical-align: top;">Ship to address</th>
					<td style="width: 30%; height: 200px; vertical-align: top; '.$addrstyle.'"><input type="checkbox" name="" value="1" style="float: right;" />
'.$shp2w.'<div style="float: left; width: 90px; height: 5px; '.$shphonestyle.'"></div></td>
				</tr>
				</table>
			</td></tr>

			<tr><th style="width: 5%; height: 30px;">Qty</th>
				<th>Item Code</th>
				<th>Remark</th>
				<th>Description</th>
				<th>Cost</th>
				<th>Total</th>
			</tr>
'.$purchfrm.'
			<tr><td colspan="4" style="height: 50px; vertical-align: middle;"><span style="border-radius: 50%; padding: 9px; '.$lrowstyle.'">Rows - '.$lrows1.'</span>&nbsp;&nbsp;&nbsp; <span style="border-radius: 50%; padding: 9px; '.$qtitstyle.'">Items - '.$qtit1.'</span></td>
				<td colspan="2" style="vertical-align: middle; text-align: right; font-weight: bold;">'.$ttbl.'</td>
			</tr>
		</table>
	</div>


	<input type="button" onclick="takeshot();" name="scrbtn" value="SShot" style="position: absolute; top: 22px; right: 13px;" />
	<div id="dput" style="display: none; position: absolute; z-index: 537; top: 43px; width: 85%; background: rgb(0,0,0,0.5); text-align: center; padding-top: 15px; padding-bottom: 20px;">
	<img class="zclose" src="files/img/btnclosehover.png" OnClick="ConsCls();" style="float: right; cursor: pointer; margin: 10px;" title="Close Console" />
		<div id="output" style="width: auto;"></div>
	</div>
	<div style="clear: both; padding: 0px;"></div>';

$io_ttl_2s = $io_ttl_2;
$io_ttl_2 += $qdscnt;
$io_ttl_2 += $shipping_total;

$ttbl2 = '<table>';
$ttbl2.= '<tr><td>Net Order:</td><td>' . number_format($io_ttl_2s, 2) .'</td></tr>'; 
$ttbl2.= '<tr><td>Discount:</td><td>'  . number_format($qdscnt, 2) .'</td></tr>'; 
$ttbl2.= ($shipping_total) ? '<tr><td>Freight:</td><td>' . number_format($shipping_total, 2).'</td></tr>' : ''; 
//$ttbl2.= ( $tt['sltx']!='' ) ? '<tr><td>Sales Tax:</td><td>'     . $tt['sltx'].'</td></tr>' : ''; 
$ttbl2.= '<tr><td>Order Total:</td><td>'   . number_format($io_ttl_2, 2) .'</td></tr>'; 
$ttbl2.= '</table>';





$diff_01 = $io_ttl_2 - $shipping_total;
$diff_02 = $tt['ottl'] - $tt['frgt'];
$gprofit = $io_ttl_2 - $tt['ottl'];
$gpercent = $gprofit/$io_ttl_2*100; //$tt['ottl']/$io_ttl_2;
$rfrprcnt = $shipping_total/$io_ttl_2*100;
$wfrprcnt = $tt['frgt']/$tt['ottl']*100;
$rfrmat = $shipping_total/$diff_01 *100;
$wfrmat = $tt['frgt']/$diff_02 *100;
$wfrtts = $tt['frgt']/$io_ttl_2 *100;
$wfrmts = $tt['frgt']/$diff_01 *100;


$fln = explode(' ', $custoname);
$cnamerev = $custoname; //$fln[1] . '_' . $fln[0];

$filename = preg_replace('/\s/','_',$cnamerev.'_TSG_SO-'.$r1[0]['ocn'].'_PO-'.$inpn.'_ADCR_'.$lghsh['Name'].'_Date-'. date('Y-m-d').'.pdf');
//$filename = '\\\server2\Share\From NAS\D Drive NAS\Projects\Jobs Quoted and Sold\\'.$custoname.'\\'.$filename;

		$pcnt .= '
	<div style="float: left; width:60%; min-height: 95%; padding: 7px;">
		<table class="TList" cellspacing="1">
			<tr><th colspan="8" style="height: 30px; vertical-align: middle; font-size: 17px;">Sales Order - '.$insn.'</th></tr>

<tr><td colspan="9" style="padding: 0px;">

<table style="width: 100%;">

			<tr><th style="width: 20%; vertical-align: top;">Bill to address</th>
				<td style="width: 30%; height: 200px; vertical-align: top; '.$addrstyle.'">
				<input type="checkbox" name="" value="1" style="float: right;" />
'.$bll2s[0].'<br>
'.$bll2s[1].'<br>
'.$bll2s[2].'<br>
'.$bll2s[3].'<br>
'.$bll2s[4].'<br>
'.$bll2s[5].'<br>
'.$bll2s[6].'<br>
'.$bll2s[7].'<br><br>			
				</td>
				<th style="width: 20%; vertical-align: top;">Ship to address</th>
				<td style="width: 30%; height: 200px; vertical-align: top; '.$addrstyle.'">
				<input type="checkbox" name="" value="1" style="float: right;" />
'.$shp2s[0].'<br>
'.$shp2s[1].'<br>
'.$shp2s[2].'<br>
'.$shp2s[3].'<br>
'.$shp2s[4].'<br>
'.$shp2s[5].'<br>
'.$shp2s[6].'<br>
'.$shp2s[7].'<br><br>			
				</td>
			</tr>
</table>

</td></tr>
			
			<tr><th style="width: 5%; height: 30px;">Qty</th>
				<th>Item Code</th>
				<th>Description</th>
				<th><input type="text" name="factoramt" id="factoramt" value="" placeholder="Factor" OnKeyUp="refillfactors(this); recalcostall();" style="width: 50px;" /></th>
				<th>Cost</th>
				<th>Total</th>
				<th>List Price</th>
				<th>Std. Price</th>
				<th>Total</th>
			</tr>
'.$salesfrm.'
			<tr><td colspan="5" style="height: 50px; vertical-align: middle;"><span style="border-radius: 50%; padding: 9px; '.$lrowstyle.'">Rows - '.$lrows2.'</span>&nbsp;&nbsp;&nbsp; <span style="border-radius: 50%; padding: 9px; '.$qtitstyle.'">Items - '.$qtit2.'</span></td>
				<td style="vertical-align: middle; text-align: right; font-weight: bold;" id="TTLCost">'.number_format($io_ttl_1, 2).'</td>
				<td style="vertical-align: middle; text-align: right; font-weight: bold;">'.number_format($io_ttl_0, 2).'</td>
				<td colspan="2" style="vertical-align: middle; text-align: right; font-weight: bold;">'.$ttbl2.'</td>
			</tr>
		</table>
		<div style="margin: 30px 7px; font-size: 15px; color: #399;">		
To save this page as ".pdf" follow steps below:
After you open "Print dialog" by 
<br>clicking button - <input type="button" name="b14" value="Open print" OnClick="window.print();" style="padding: 1px 5px; color: #339;" />&nbsp;&nbsp;
or by right click and choose "Print", select: 
<br>Destination - "Save as PDF"
<br>Layout - "Landscape"
<br>More settings:
<br>Paper size - "A3"
<br>Options - uncheck "Headers and footers" checkbox, 
<br>check "Background graphics" checkbox
<br>Hit "Save" and choose appropriate subfolder in "Jobs Quoted and Sold" folder
<br>Review the filename "file...name.pdf" and Click "Save"
		</div>	
	</div>


	<div style="float: right; width:38%; padding: 7px;">
	
	<input type="reset" name="reset" value="Reset" />
	<input type="button" name="recalc" value="Recalculate" style="float: right;" OnClick="recalculategp();" />
		<table class="GP" cellspacing="1">
			<tr><th style="height: 30px;">Retail Sell</th>
				<th>Amount</th>
				<th>Purchase Cost</th>
				<th>PO Cost</th>
			</tr>
			<tr><td>Retail Sale (No Tax)</td>
				<td>$ <input type="text" name="dbc_rs" id="dbc_rs" value="'.number_format($io_ttl_2, 2).'" /></td>
				<td>Purchase Order (No Tax)</td>
				<td>$ <input type="text" name="dbc_po" id="dbc_po" value="'.number_format($tt['ottl'], 2).'" /></td>
			</tr>
			<tr><td>Retail Freight</td>
				<td>$ <input type="text" name="dbc_rf" id="dbc_rf" value="'.number_format($shipping_total, 2).'" /></td>
				<td>Wholesale Freight</td>
				<td>$ <input type="text" name="dbc_wf" id="dbc_wf" value="'.number_format($tt['frgt'], 2).'" /></td>
			</tr>
			<tr><td>Sell without Freight</td>
				<td>$ <input type="text" name="dbc_swf" id="dbc_swf" class="dbcro" value="'.number_format($diff_01, 2).'" readonly="readonly" /></td>
				<td>Cost without Freight</td>
				<td>$ <input type="text" name="dbc_cwf" id="dbc_cwf" class="dbcro" value="'.number_format($diff_02, 2).'" readonly="readonly" /></td>
			</tr>
			<tr><td colspan="4" style="height: 50px;"></td>
			</tr>

			<tr><td>Gross Profit</td>
				<td></td>
				<td>Gross Profit</td>
				<td>$ <input type="text" name="dbc_gpr" id="dbc_gpr" class="dbcro" value="'.number_format($gprofit, 2).'" readonly="readonly" /></td>
			</tr>
			<tr><td>GP %</td>
				<td></td>
				<td>GP %</td>
				<td>%<input type="text" name="dbc_prs" id="dbc_prs" class="dbcro" value="'.number_format($gpercent, 2).'" readonly="readonly" /></td>
			</tr>
			<tr><td colspan="4" style="height: 20px;"></td>
			</tr>
			<tr><td>Freight</td>
				<td></td>
				<td>Freight</td>
				<td></td>
			</tr>
			<tr><td>Retail Freight/Total Sell</td>
				<td>%<input type="text" name="dbc_rfts" id="dbc_rfts" class="dbcro" value="'.number_format($rfrprcnt, 2).'" readonly="readonly" /></td>
				<td>Wholesale Freight/Total Cost</td>
				<td>%<input type="text" name="dbc_wftc" id="dbc_wftc" class="dbcro" value="'.number_format($wfrprcnt, 2).'" readonly="readonly" /></td>
			</tr>
			<tr><td>Freight</td>
				<td></td>
				<td>Freight</td>
				<td></td>
			</tr>
			<tr><td>Retail Freight/Material Sell</td>
				<td>%<input type="text" name="dbc_rfms" id="dbc_rfms" class="dbcro" value="'.number_format($rfrmat, 2).'" readonly="readonly" /></td>
				<td>Wholesale Freight/Material Cost</td>
				<td>%<input type="text" name="dbc_wfmc" id="dbc_wfmc" class="dbcro" value="'.number_format($wfrmat, 2).'" readonly="readonly" /></td>
			</tr>
			<tr><td colspan="4" style="height: 20px;"></td>
			</tr>
			<tr><td></td>
				<td></td>
				<td>Freight</td>
				<td></td>
			</tr>
			<tr><td></td>
				<td></td>
				<td>Wholesale Freight/Total Sell</td>
				<td>%<input type="text" name="dbc_wfts" id="dbc_wfts" class="dbcro" value="'.number_format($wfrtts, 2).'" readonly="readonly" /></td>
			</tr>
			<tr><td></td>
				<td></td>
				<td>Freight</td>
				<td></td>
			</tr>
			<tr><td></td>
				<td></td>
				<td>Wholesale Freight/Material Sell</td>
				<td>%<input type="text" name="dbc_wfms" id="dbc_wfms" class="dbcro" value="'.number_format($wfrmts, 2).'" readonly="readonly" /></td>
			</tr>


		</table>



<input class="NFborder" type="button" name="stmpbttn" id="stmpbttn" value="Approve" OnClick="showDT();" style="width: 50px; margin: 40px 0px 0px 0px; padding: 1px 7px; background: #fff; color: #999; border: dotted 1px #999;" />


	<div id="stmp" style="display: none; width: 250px;
	height: 70px;
	margin: 0px -4px;
	padding: 7px;
	background: #396;
	border: double 13px #fff;
	fborder-radius: 50%;
	color: #fff;">
	'.$lghsh['Name'].'
	<div style="text-align: center; font-size: 27px;">APPROVED</div>
<div id="CrntDT"></div>
	</div>
	
	<div style="margin-top: 30px; font-weight: bold; color: #f00;">Notes:</div>
	<textarea class="NFborder" style="width: 100%; height: 300px; border: none; font-size: 17px; font-style: italic; color: #336;"></textarea>
	
</div>
	
</div>
<div style="clear: both;"></div>

<div style="text-align: right;">'.$filename.'</div>
<input type="button" name="shbttn" value="Show HTML Content" OnClick="shhdcsvcnt();" style="float: right;" />
<div id="CSVCnt" style="display: none;">'. $fupcnt . '<textarea style="width: 100%; height: 700px;">'. $fupcnt . '</textarea></div>

</form>
<script>
document.title = "'.$filename.'";

var c1 = new Array();
var c2 = new Array();
var c3 = new Array();

function chbhndl(o){
var sku = o.name.replace(/^chb_/, "");
var d1n = "cll_"+sku+"_1";
var d2n = "cll_"+sku+"_2";
var d3n = "cll_"+sku+"_3";
var d1  = document.getElementById(d1n);	
var d2  = document.getElementById(d2n);	
var d3  = document.getElementById(d3n);	

	if (typeof c1[sku] == "undefined"){c1[sku] = d1.style.backgroundColor;}
	if (typeof c2[sku] == "undefined"){c2[sku] = d2.style.backgroundColor;}
	if (typeof c3[sku] == "undefined"){c3[sku] = d3.style.backgroundColor;}

if(o.checked){
	d1.style.backgroundColor = "#cfc";
	d2.style.backgroundColor = "#cfc";
	d3.style.backgroundColor = "#cfc";
	}
else{
	d1.style.backgroundColor = c1[sku];
	d2.style.backgroundColor = c2[sku];
	d3.style.backgroundColor = c3[sku];
	}
	
}

function ConsCls(){
var pv = document.getElementById("dput");
	pv.style.display = "none";
return;	
}


function takeshot() { 

var pv = document.getElementById("dput");
	pv.style.display = "block";

//	var xt = document.getElementsByClassName("NFborder");
//	div.style.width = "99%";
//	for(i=0;i<xt.length;i++){
//		xt[i].style.width = "98%";
//	}
//lert(xt.name);
//	return;
//	div.style.width = "99%";
//	xt.style.width = "98%";

var x = new XMLHttpRequest();
var url = "https://www.waverlycabinets.com/qbw/mailcnv.php";

	x.open("POST", url, true);
	x.setRequestHeader("Content-type", "application/x-www-form-urlencoded");



//var canvas = document.getElementById("canvas");
var dataURL; // = canvas.toDataURL();

	let op = document.getElementById("output");
		op.innerHTML = "";

	let div = document.getElementById("ocfm");
	html2canvas(div).then( 
		function (canvas) { 

//		document.getElementById("output").removeChild(canvas); 
//		document.getElementById("output").appendChild(canvas); 
		op.appendChild(canvas);


//   dataURL = canvas.toDataURL("image/png");

//var doc = new jsPDF("p", "mm");
//doc.addImage(dataURL, "PNG", 10, 10);
//doc.save("sample-file.pdf");

//	x.send("data="+dataURL);

//dataURL = canvas.toDataURL("image/jpeg", 1.0);

			}
		) 


//	x.send();


//	div.style.width = "53%";
//	xt.style.width = "100%";
	} 



</script>


<!-- script>

var clr1;
var clr2;
var stp = 0;
		
function look4sim(o){
var bN = o.id; alert(bN);
var oN;
var qN;
	if(bN.match(/^C1_\w+/g)){ 
		oN=bN.replace(/^C1_/, "C2_"); 
		qN=bN.replace(/^C1_/, "C3_"); 
		}
	if(bN.match(/^C2_\w+/g)){ 
		oN=bN.replace(/^C2_/, "C1_"); 
		qN=bN.replace(/^C2_/, "C3_"); 
		}
	if(bN.match(/^C3_\w+/g)){ alert(bN);
		oN=bN.replace(/^C3_/, "C1_"); 
		qN=bN.replace(/^C3_/, "C2_"); 
		}
var d = document.getElementById(oN);
var b = document.getElementById(qN);
	clr1 = d.style.backgroundColor;
	clr1 = b.style.backgroundColor;
	d.style.backgroundColor = "ddd";
	b.style.backgroundColor = "ddd";
return;
}

function lookout(o){
var bN = o.id;
var oN;
var qN;
	if(bN.match(/^C1_\w+/g)){ 
	oN=bN.replace(/^C1_/, "C2_"); 
	qN=bN.replace(/^C1_/, "C3_"); 
	}
	if(bN.match(/^C2_\w+/g)){ 
	oN=bN.replace(/^C2_/, "C1_"); 
	qN=bN.replace(/^C2_/, "C3_"); 
	}
	if(bN.match(/^C3_\w+/g)){ 
	oN=bN.replace(/^C3_/, "C1_"); 
	qN=bN.replace(/^C3_/, "C2_"); 
	// if(bN.match(/^C1_\w+/g)){ 
	// oN=bN.replace(/^C1_/, "C2_"); 
	// }
	// if(bN.match(/^C2_\w+/g)){ 
	// oN=bN.replace(/^C2_/, "C1_"); 
	// }
var d = document.getElementById(oN);
var b = document.getElementById(qN);
	d.style.backgroundColor = clr1;
	b.style.backgroundColor = clr2;
return;
}

function recalcost(o, lpr){
	sku = o.name.replace(/^f_/, "");
	url = "recalcost.php?lp="+lpr+"&f="+o.value;
	query_srv( url, "CRC_"+sku );
}

function putbttn(o){
	trg = o.name.replace(/^f_/, "CRC_");
var d = document.getElementById(trg);
	d.innerHTML = "";
	
//const container = document.getElementById(trg);
const button = document.createElement("button");
	button.innerText = "Recalc";
	d.appendChild(button);	
}

</script -->










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

// require('WriteHTML.php');

// $pdf=new PDF_HTML();
// $pdf->AddPage();
// $pdf->SetFont('Arial');
// $pdf->WriteHTML($pgcontent);
// $pdf->Output();

//exit;


    // $pdf = new createPDF(
        // $pgcontent,   // html text to publish
        // $_POST['title'],  // article title
        // $_POST['url'],    // article URL
        // $_POST['author'], // author name
        // time()
    // );
    // $pdf->run();




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
/*
function getcoords($arr, $trm, $stv=0, $sth=0){
	foreach($arr as $vk=>$vv){
		foreach($vv as $hk=>$hv){
			if($vk>=$stv AND $hk>=$sth AND $hv==$trm){ return array($vk, $hk); }
			}
		}
	return NULL;
	}


function getVcoord($arr, $trm, $rshift=0, $stv=0){
	if($rshift){ return $arr[getVcoord($arr, $trm, 0, $stv)][$rshift]; }	
	else{
		foreach($arr as $rk=>$rv){
			if($rk>=$stv AND $rv[0]==$trm){ return $rk; }
			}
		return NULL;
		}
}

function lk4cl($arr, $coords, $rshift=0, $dshift=0){
	return $arr[$coords[0]+$dshift][$coords[1]+$rshift];
}

function getWCSku($sku, $stl, $a=NULL){ global $dbh;
//	$acl=($a)?'Asm=\''.$a.'\'':'1';
//	return mysqli_fetch_assoc(mysqli_query($dbh, "SELECT * FROM wc_consolisku WHERE Sku_2020='$sku' AND Style_2020='$stl' AND ".$acl))['Sku_WC'];
	return mysqli_fetch_assoc(mysqli_query($dbh, "SELECT * FROM wc_consolisku WHERE Sku_2020='$sku' AND Style_2020='$stl' AND Asm='$a'"))['Sku_WC'];
}











function orscan($o){
	$i = $ii = 0;
	$nr = array();
	if($o){
	foreach ( $o->get_items() as $iid => $io ) { //global $mfactor;
		$pm = $io->get_product();
		
		$fctxtn = 'f_'.$pm->sku;

		$nr[$i]['sku'] = $pm->sku;
		$nr[$i]['fct'] = getfactor($pm->sku);
		$nr[$i]['fct'] = (isset($_POST[$fctxtn]))?$_POST[$fctxtn]:$nr[$i]['fct'];
		$nr[$i]['id']  = getidbysku($pm->sku);;
		$nr[$i]['qty'] = $io->get_quantity();
		$nr[$i]['ttl'] = $io->get_name();
		$nr[$i]['spr'] = $pm->price;
//		$nr[$i]['lpr'] = getlistpricebyid($nr[$i]['id']);;
		$nr[$i]['lpr'] = calclistpricebysku($pm->sku);;
//		$nr[$i]['ppr'] = calcostbyid($nr[$i]['id']);
//		$nr[$i]['ppr'] = ($factor)?round($nr[$i]['lpr']*$factor, 2):calcostbysku($pm->sku);
		$nr[$i]['ppr'] = round($nr[$i]['lpr']*$nr[$i]['fct'], 2);
//		$nr[$i]['ppr'] = calcostbysku($pm->sku);
		$nr[$i]['amt'] = $pm->price * $nr[$i]['qty'];
		$i++;
		
//		echo $iid .' - '.$iobj->get_product_id() .' - ' .$pm->sku . '<br>';
		
		}
	}
	return $nr;
}


function ttscan_fm($r){
	$nr = array();
	
	$poc = getcoords($r, 'P.O. Number');
	$nr[0]['pon']  = $r[$poc[0]+2][$poc[1]];
	$occ = getcoords($r, 'Order No.');
	$nr[0]['ocn']  = $r[$occ[0]+2][$occ[1]+1];
	
	$i = $ii = 0;
	$qty_index = $sku_index = $dsc_index = $prc_index = $amt_index = NULL;

	while(	$r[$ii][0]  !='' OR 
			$r[$ii+1][0]!='' OR 
			$r[$ii+2][0]!='' OR 
			$r[$ii+3][0]!='' OR 
			$r[$ii][4]  !='' OR 
			$r[$ii+1][4]!='' OR 
			$r[$ii+2][4]!='' OR 
			$r[$ii+3][4]!=''){
		
		if($r[$ii][0]=='B/O ETA 1'){
			for($in=0; $in<15; $in++){
				if($r[$ii][$in]=='Qty')			{ $qty_index = $in; }
				if($r[$ii][$in]=='Item')		{ $sku_index = $in; }
				if($r[$ii][$in]=='Description')	{ $dsc_index = $in; }
				if($r[$ii][$in]=='Unit $')		{ $prc_index = $in; }
				if($r[$ii][$in]=='Amount')		{ $amt_index = $in; }
				}
			}
		if($r[$ii][0]=='#' AND $r[$ii][1]=='Qty'){
			for($in=0; $in<15; $in++){
				if($r[$ii][$in]=='Qty')			{ $qty_index = $in; }
				if($r[$ii][$in]=='User code')	{ $sku_index = $in; }
				if($r[$ii][$in]=='Manuf. code')	{ $dsc_index = $in; }
				if($r[$ii][$in]=='Description')	{ $prc_index = $in; }
				}
			}
		
		if(	$r[$ii][$qty_index]>0   AND 
			$r[$ii][$sku_index]!='' AND $r[$ii][$sku_index]!='Residential/Jobsite' AND 
			$r[$ii][$dsc_index]!='' AND 
			$r[$ii][$prc_index]!='' ){
			$nr[$i]['sku'] = $r[$ii][$sku_index];
			$nr[$i]['qty'] = $r[$ii][$qty_index];
			$nr[$i]['ttl'] = $r[$ii][$dsc_index];
			$nr[$i]['ppr'] = $r[$ii][$prc_index];
			$nr[$i]['amt'] =($r[$ii][$amt_index])?$r[$ii][$amt_index]:$r[$ii][$prc_index]*$r[$ii][$qty_index];
			$i++;
			}
		$ii++;
		}
	return $nr;
}


function ttscan_ghi($r){
	$i = $ii = 0;
	$nr = array();
	$strght=NULL;

	$poc = getcoords($r, 'P.O. Number');
	$nr[0]['pon']  = $r[$poc[0]+2][$poc[1]];
	$occ = getcoords($r, 'Order Number:');
	$nr[0]['ocn']  = $r[$occ[0]][$occ[1]+1];
	
	$qty_index = $sku_index = $dsc_index = $prc_index = $amt_index = NULL;
	
	
	while(	$r[$ii][0]  !='' OR 
			$r[$ii+1][0]!='' OR 
			$r[$ii+2][0]!='' OR 
			$r[$ii+3][0]!='' OR 
			$r[$ii+4][0]!='' OR 
			$r[$ii+5][0]!='' OR 
			$r[$ii+6][0]!='' OR 
			$r[$ii+7][0]!='' OR 
			$r[$ii+8][0]!='' OR 
			$r[$ii+9][0]!=''){

		if($r[$ii][0]=='Line Number'){ $sku_index = 0; $dsc_index = 0;
			for($in=0; $in<15; $in++){
				if($r[$ii][$in]=='Ordered')	{ $qty_index = $in; }
				if($r[$ii][$in]=='Price')	{ $prc_index = $in; }
				if($r[$ii][$in]=='Amount')	{ $amt_index = $in; }
				}
			}
				
//		if( preg_match('/GHI/', $r[$ii][0]) AND $r[$ii][4]>0 AND ($r[$ii][7]>0 AND $r[$ii][9]>0) ){
		if( preg_match('/^(GHI\s)(\w+)(\s)(\w.*)/', $r[$ii][$dsc_index], $m) ){
			$nr[$i]['sku'] = 'G'.$m[2];
			if(preg_match('/MULL DR/', $m[4])){$nr[$i]['sku'] .='MD';}
			if(preg_match('/NANTUCKET LIN/', $m[4])){$nr[$i]['sku'] .='-NTL';}
			if(preg_match('/STONE HARBOR GRAY/', $m[4])){$nr[$i]['sku'] .='-SHG';}
			
			$nr[$i]['ttl'] = $r[$ii][$dsc_index];
			$nr[$i]['qty'] = $r[$ii][$qty_index];
			$nr[$i]['ppr'] = $r[$ii][$prc_index];
			$nr[$i]['amt'] = $r[$ii][$amt_index]; if(!$nr[$i]['amt']>0){ $nr[$i]['amt'] = $r[$ii][$amt_index+1]; }
			$i++;
			}

		if( preg_match('/^(FL\s)(\w+)(\s)(\w.*)/', $r[$ii][$dsc_index], $m) ){
			$nr[$i]['sku'] = 'F'.$m[2];
			if(preg_match('/NEWPORT WHITE/', $m[4])){$nr[$i]['sku'] .='-NPW';}
			if(preg_match('/GRAND RESERVE CHERRY/', $m[4])){$nr[$i]['sku'] .='-GRC';}
			
			$nr[$i]['ttl'] = $r[$ii][$dsc_index];
			$nr[$i]['qty'] = $r[$ii][$qty_index];
			$nr[$i]['ppr'] = $r[$ii][$prc_index];
			$nr[$i]['amt'] = $r[$ii][$amt_index]; if(!$nr[$i]['amt']>0){ $nr[$i]['amt'] = $r[$ii][$amt_index+1]; }
			$i++;
			}
		if( preg_match('/^(JIFFY KIT - )(\w+)( - # )(\w.*)/', $r[$ii][0], $m) ){
		
			$nr[$i]['sku'] = 'JK'.$m[2];
			$nr[$i]['ttl'] = $r[$ii][$dsc_index];
			$nr[$i]['qty'] = $r[$ii][$qty_index];
			$nr[$i]['ppr'] = $r[$ii][$prc_index];
			$nr[$i]['amt'] = $r[$ii][$amt_index]; if(!$nr[$i]['amt']>0){ $nr[$i]['amt'] = $r[$ii][$amt_index+1]; }
			$i++;
			}
		$ii++;
		}
	return $nr;
}

function ttscan_uscd($r){
	$i = $ii = 0;
	$nr = array();

	$qty_index = $sku_index = $dsc_index = $prc_index = $amt_index = NULL;

	while($r[$ii][0]!='' OR $r[$ii+1][0]!='' OR $r[$ii+2][0]!='' OR $r[$ii+3][0]!='' OR $r[$ii+4][0]!='' OR $r[$ii+5][0]!=''){
		if($r[$ii][0]=='Quantity'){
			for($in=0; $in<15; $in++){ // $qty_index = 0;
				if($r[$ii][$in]=='Quantity')		{ $qty_index = $in; }
				if($r[$ii][$in]=='Item')			{ $sku_index = $in; }
				if($r[$ii][$in]=='Description')		{ $dsc_index = $in; }
				if($r[$ii][$in]=='Price Each')	{ $prc_index = $in; }
				if($r[$ii][$in]=='Amount')			{ $amt_index = $in; }
				}
			}
		if($r[$ii][0]=='Product Name'){
			for($in=0; $in<15; $in++){
				if($r[$ii][$in]=='Product Name')	{ $sku_index = $in; }
				if($r[$ii][$in]=='Price')			{ $prc_index = $in; }
				if($r[$ii][$in]=='Qty')				{ $qty_index = $in; }
				if($r[$ii][$in]=='Subtotal')		{ $amt_index = $in; }
				}
			}

		$r[$ii][$qty_index] = preg_replace('/Ordered: /','',$r[$ii][$qty_index]);
		$r[$ii][$prc_index] = preg_replace('/\$/','',$r[$ii][$prc_index]);
		$r[$ii][$amt_index] = preg_replace('/\$/','',$r[$ii][$amt_index]);

		if( $r[$ii][$qty_index]!='' AND $r[$ii][$sku_index]!='' AND is_numeric($r[$ii][$prc_index]) AND is_numeric($r[$ii][$amt_index])){
			$nr[$i]['sku'] = $r[$ii][$sku_index];
			$nr[$i]['qty'] = $r[$ii][$qty_index];
			$nr[$i]['ttl'] = $r[$ii][$dsc_index];
			$nr[$i]['ppr'] = $r[$ii][$prc_index];
			$nr[$i]['amt'] = $r[$ii][$amt_index];
			$i++;
			}


		$ii++;
		}
	return $nr;
}

function ttscan_ctc($r){
	$i = $ii = 0;
	$nr = array();

	$qty_index = $sku_index = $dsc_index = $prc_index = $amt_index = NULL;

	while($r[$ii][0]!='' OR $r[$ii+1][0]!='' OR $r[$ii+2][0]!='' OR $r[$ii+3][0]!='' OR $r[$ii+4][0]!='' OR $r[$ii+5][0]!=''){
		
		if($r[$ii][0]=='Order Qty'){
			for($in=0; $in<15; $in++){ // $qty_index = 0;
				if($r[$ii][$in]=='Apr. Qty')		{ $qty_index = $in; }
				if($r[$ii][$in]=='Item Number')		{ $sku_index = $in; }
//				if($r[$ii][$in]=='Description')		{ $dsc_index = $in; }
				if($r[$ii][$in]=='Unit Price')		{ $prc_index = $in; }
				if($r[$ii][$in]=='Extended Price')	{ $amt_index = $in; }
				}
			}

	
//		if( preg_match('/[\d]{1,4}/', $r[$ii][1]) AND $r[$ii][2]!='' AND ($r[$ii][3]!='' OR $r[$ii][4]!='') AND ($r[$ii][7]!='' OR $r[$ii][8]!='' OR  $r[$ii][9]!='' OR $r[$ii][10]!='') ){
		if( $r[$ii][$qty_index]!='' AND $r[$ii][$sku_index]!='' AND is_numeric($r[$ii][$prc_index]) AND is_numeric($r[$ii][$amt_index])){
			$nr[$i]['sku'] = $r[$ii][$sku_index];
			$nr[$i]['qty'] = $r[$ii][$qty_index];
			$nr[$i]['ttl'] = $r[$ii][$dsc_index];
			$nr[$i]['ppr'] = $r[$ii][$prc_index];
			$nr[$i]['amt'] = $r[$ii][$amt_index];
			// $nr[$i]['sku'] = $r[$ii][2];
			// $nr[$i]['qty'] = $r[$ii][1];
			// $nr[$i]['ttl'] = $r[$ii][4];
			// $nr[$i]['ppr'] = $r[$ii][9];
			// $nr[$i]['amt'] = $r[$ii][10];
			$i++;
			}
		$ii++;
		}
	return $nr;
}




function getordn($arr){
	foreach($arr as $vk=>$vv){
		foreach($vv as $hk=>$hv){
			if(preg_match('/(OC-)([\d]{4,7})/', $hv)){ return preg_replace('/OC-/','',$hv); }
			}
		}
	return NULL;
}



function look2sku($r, $sku){
	foreach ($r as $v){
		if(preg_match("/^$sku$/", $v['sku'])){ return $v; }
		}
	return 0;
}

function look4sku($r, $sku){
	foreach ($r as $v){
		if(preg_match("/$sku/", $v['sku'])){ return $v; }
		}
	return 0;
}

function skutrim0($sku){
	$tsku = preg_replace('/-RTA/', '', $sku);
	$tsku = preg_replace('/\//', '\/', $tsku);
	$tsku = preg_replace('/\(/', '\(', $tsku);
	$tsku = preg_replace('/\)/', '\)', $tsku);
return $tsku;	
}

function skutrim1($sku){
	$tsku = preg_replace('/-SOL.*$/', '', $sku);
	$tsku = preg_replace('/\“.*$/',   '', $tsku);
//	$tsku = preg_replace('/\“/',      '', $tsku);
	$tsku = preg_replace('/\‘.*$/',   '', $tsku);
//	$tsku = preg_replace('/\‘/',      '', $tsku);
	$tsku = preg_replace('/-SP$/',    '', $tsku);
	$tsku = preg_replace('/\s\(2\)/', '', $tsku);
	$tsku = preg_replace('/\//',    '\/', $tsku);
	$tsku = preg_replace('/\(/',    '\(', $tsku);
	$tsku = preg_replace('/\)/',    '\)', $tsku);
return $tsku;	
}




function getqw_item($sku){ global $dbh;
	return mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM qw_item WHERE Sku = '$sku'", MYSQLI_ASSOC));
}


function getoidmax($px){ global $dbh;
	return mysqli_fetch_array(mysqli_query($dbh, "SELECT MAX(IID) FROM qw_xt WHERE (oname = 'Order' OR oname = 'Estimate') AND IID LIKE '$px%'", MYSQLI_ASSOC))[0];
}




function getpfx($r){
	$px = NULL;
	$sn = getVcoord($r, 'Supplier');
if($r[$sn][1] == 'US Cabinets'){
	$vc = getVcoord($r, 'Door style', 1);
	if(preg_match('/^(.*)(\()([\w]{2})(\))/', $vc, $m)){ $px = 'U-'.$m[3]; }
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


function getvendor($r){
	foreach($r as $rk=>$rv){
		foreach($rv as $ik=>$iv){
			if(preg_match('/TSG/', $iv) OR preg_match('/FOREVERMARK/', $iv)){ return 'fm'; }
			if(preg_match('/Horning/', $iv)){ return 'ghi'; }
			if(preg_match('/cubitac/', $iv)){ return 'ctc'; }
			if(preg_match('/US Cabinet Depot/', $iv) OR preg_match('/(Order #)([\d]{7,10})/', $iv)){ return 'uscd'; }
			}
		}
	return NULL;
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
			  ($r[$vc][0] != 'Email:')  AND
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


function chkcustqb($name, $em, $ph){ global $dbh;
	
//	return mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM qw_customer WHERE (lname = 'Order' OR oname = 'Estimate') AND IID LIKE '$px%'", MYSQLI_ASSOC))[0];
	
	return;
}
*/


function getbillingaddr($dt){
	if(preg_match('/(<b>Bill To<\/b><br>)(.*)(<\/TD>)/', $dt, $m)){
		return $m[2];
		}
	return NULL;
}

function getshippingaddr($dt){
	if(preg_match('/(<b>Ship To<\/b><br>)(.*)(<\/TD>)/', $dt, $m)){
		return $m[2];
		}
	return NULL;
}


function getitemtable($dt){


	$DOM = new DOMDocument();
	$DOM->loadHTML($dt);
	
	$Header = $DOM->getElementsByTagName('th');
	$Detail = $DOM->getElementsByTagName('td');

    //#Get header name of the table
	foreach($Header as $NodeHeader) 
	{
		$aDataTableHeaderHTML[] = trim($NodeHeader->textContent);
	}
	//print_r($aDataTableHeaderHTML); die();

	//#Get row data/detail table without header name as key
	$i = 0;
	$j = 0;
	foreach($Detail as $sNodeDetail) 
	{
		$aDataTableDetailHTML[$j][] = trim($sNodeDetail->textContent);
		$i = $i + 1;
		$j = $i % count($aDataTableHeaderHTML) == 0 ? $j + 1 : $j;
	}
//	echo '<pre>';	print_r($aDataTableDetailHTML);	echo '</pre>';

		
//echo '<br><br><br><br><br><br>';		
	return NULL;
}





/*
</table>
<P>
<table
*/


function parseTable($html)
{
  // Find the table
  preg_match("/<table.*?>.*?<\/[\s]*table>/s", $html, $table_html);

  // Get title for each row
  preg_match_all("/<th.*?>(.*?)<\/[\s]*th>/", $table_html[0], $matches);
  $row_headers = $matches[1];

  // Iterate each row
  preg_match_all("/<tr.*?>(.*?)<\/[\s]*tr>/s", $table_html[0], $matches);

  $table = array();

  foreach($matches[1] as $row_html)
  {
    preg_match_all("/<td.*?>(.*?)<\/[\s]*td>/", $row_html, $td_matches);
    $row = array();
    for($i=0; $i<count($td_matches[1]); $i++)
    {
      $td = strip_tags(html_entity_decode($td_matches[1][$i]));
      $row[$row_headers[$i]] = $td;
    }

    if(count($row) > 0)
      $table[] = $row;
  }
  return $table;
}


function dclean($str){
	return preg_replace('/"/', '\'\'', $str);
}







?>