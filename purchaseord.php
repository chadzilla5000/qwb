<?php
include_once('inc/_init.php');
include_once('inc/functions/general.php');
//include_once('../wp-content/inc/functions/general.php');
require_once('inc/functions/qbw_fs.php');
require_once('inc/dblcheck_f.php');
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



</style>
<script type="text/javascript" src="files/js/dblchk.js">
var clr;
var stp = 0;

</script>
<div>
<form action="#" method="post" name="upurchord" enctype="multipart/form-data" style="margin: 20px;">
<input type="file" name="csv" id="csv" />
<!-- input type="file" name="csv[]" multiple / -->
<input type="submit" name="upfile" id="upfile" value="Open" style="padding: 1px 5px;" />&nbsp; &nbsp;

<br><br>
<input type="text" name="srchiorder" id="srchiorder" value="" OnKeyUp="tordsrch(this)" placeholder="Type Name or Order #" /> <br>
<div id="ddtarget"></div>



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






/////////////////////////////////////////////////////////////////////////////////////////////////////////
$vi=$bi=0;
$soldto = $shipto = NULL;
$bln = $sold2arr = array();
$aln = $ship2arr = array();




$vnd = getvendor($csvarr);

echo $vnd ;
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

$crd = getcoords($csvarr, 'Sold To:');
while($bi<15){
	$bln[$bi] = lk4cl($csvarr, $crd, 0, $bi+1);
	if($bln[$bi]=='Confirm To:'){break;}
	$soldto .= $bln[$bi].'<br>'; array_push($sold2arr, $bln[$bi]);
	$bi++;
	}

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


//$oid = getordn($csvarr);  //////////////////////////////////////////////////////////////////////
$order = ($oid) ? new WC_Order($oid) : NULL; //////////////////////////////////////////////////

$sku1=$sku2=NULL;
$qty1=$qty2=$sprice=$pcost=$sttl_1=$sttl_2=0;
//$brand = NULL;
$stsbg = 'transparent';
$itemsfrm = $purchfrm = '';

$soarr = array();
$i=0;

		$r0 = orscan($order);

// echo '<pre>'; print_r($r0); echo '</pre>';

$user_id = ($order)?$order->get_user_id():NULL;
$user_phone = get_user_meta( $user_id, 'billing_phone', true );

$bill_phone = get_user_meta( $user_id, 'billing_phone', true );
$ship_phone = get_user_meta( $user_id, 'shipping_phone', true );

$shipping_total = ($order)?$order->get_shipping_total():NULL;


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

$lrows1 = $lrows2 = $qtit1 = $qtit2 = 0;
$io_ttl_0 = $io_ttl_1 = $io_ttl_2 = $oc_ttl = 0;

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
	
	$sttl = $oit['ppr'] * $oit['qty']; 
	$io_ttl_0 += floatval($oit['lpr']) * $oit['qty'];
	$io_ttl_1 += $sttl;
	$io_ttl_2 += floatval($oit['amt']);
	
	
	$itemsfrm .= '
<tr id="C1_'.$sku.'" OnMouseOver="look4sim(this);" OnMouseOut="lookout(this);"><td style="text-align: center; background: #'.$style1.';">'.$oit['qty'].'</td>
	<td style="background: #'.$style2.';">'.$oit['sku'].'</td>
	<td>'.$oit['ttl'].' <input type="checkbox" name="lsku" value="1" style="float: right;" /></td>
	<td style="padding: 0px;width:50px;"><input type="text" style="width: 50px; height: 23px; padding: 2px; border: none; text-align: right; margin: 0px; background: #ffd; font-size: 12px;" name="f_'.$sku.'" value="'.$factor.'" OnChange="recalcost(this, \''. $oit['lpr'] .'\');" OnKeyUp="putbttn(this);" />
	</td>
	<td style="padding: 0px 2px; vertical-align: middle; text-align: right; background: #'.$style3.';" id="CRC_'.$sku.'">$'.number_format($oit['ppr'], 2).'</td>
	<td style="padding: 0px 2px; vertical-align: middle; text-align: right;" id="CRC_'.$sku.'">$'.number_format($sttl, 2).'</td>
	<td style="text-align: right;">$'.number_format($oit['lpr'], 2).'</td>
	<td style="text-align: right;">$'.number_format($oit['spr'], 2).'</td>
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
	
	$lrows2++;
	$qtit2 += $cit['qty'];
	$cit['amt']=($cit['amt'])?$cit['amt']:$cit['ppr']*$cit['qty'];
	$oc_ttl += floatval($cit['amt']);

	$purchfrm .= '
<tr id="C2_'.$sku.'" OnMouseOver="look4sim(this);" OnMouseOut="lookout(this);"><td style="background: #'.$style1.';">'.$cit['qty'].'</td>
	<td style="background: #'.$style2.';">'.$cit['sku'].'</td>
	<td>'.$cit['ttl'].' <input type="checkbox" name="lsku" value="1" style="float: right;" /></td>
	<td style="background: #'.$style3.';">$'.number_format(floatval($cit['ppr']), 2).'</td>
	<td style="">$'.number_format(floatval($cit['amt']), 2).'</td>
</tr>
';
	
}

$tt = scantotals($csvarr, $vnd);

$ttbl = '<table>';
$ttbl.= ( $tt['onet']!='' ) ? '<tr><td>Net Order:</td><td>'     . $tt['onet'].'</td></tr>' : ''; 
$ttbl.= ( $tt['dcnt']!='' ) ? '<tr><td>Less Discount:</td><td>' . $tt['dcnt'].'</td></tr>' : ''; 
$ttbl.= ( $tt['frgt']!='' ) ? '<tr><td>Freight:</td><td>'       . $tt['frgt'].'</td></tr>' : ''; 
$ttbl.= ( $tt['sltx']!='' ) ? '<tr><td>Sales Tax:</td><td>'     . $tt['sltx'].'</td></tr>' : ''; 
$ttbl.= ( $tt['ottl']!='' ) ? '<tr><td>Order Total:</td><td>'   . $tt['ottl'].'</td></tr>' : ''; 
$ttbl.= '</table>';


		$pcnt .= '
<div style="height: 90%; background: #ffe;">
	<div style="float: left; width:49%; min-height: 95%; padding: 7px;">
		<table class="TList" cellspacing="1">
			<tr><th colspan="8" style="height: 30px; vertical-align: middle; font-size: 17px;">Sales Order - '.$oid.'</th></tr>
			<tr><th colspan="2" style="vertical-align: top;">Bill to address</th>
				<td style="height: 200px; vertical-align: top;">
'.$bill1[0].'
'.$bill1[1].'<br>
'.$bill1[2].'<br>
'.$bill1[3].'<br>
'.$bill1[4].'<br>
'.$bill1[5].'<br>
'.$bill1[6].'<br>
'.$bill1[7].'<br><br>
'.$bill_phone.'<br>
				</td>
				<th colspan="3" style="vertical-align: top;">Ship to address</th>
				<td colspan="3" style="height: 200px; vertical-align: top;">
'.$ship1[0].'
'.$ship1[1].'<br>
'.$ship1[2].'<br>
'.$ship1[3].'<br>
'.$ship1[4].'<br>
'.$ship1[5].'<br>
'.$ship1[6].'<br>
'.$ship1[7].'<br><br>
'.$ship_phone.'<br>
				</td>
			</tr>
			<tr><th style="width: 5%; height: 30px;">Qtty</th>
				<th>Item Code</th>
				<th>Description</th>
				<th>Factor</th>
				<th>Cost</th>
				<th>Total</th>
				<th>List Price</th>
				<th>Std. Price</th>
				<th>Total</th>
			</tr>
'.$itemsfrm.'
			<tr><td colspan="5" style="height: 50px; vertical-align: middle;"><span style="border-radius: 50%; padding: 9px; '.$lrowstyle.'">Rows - '.$lrows1.'</span>&nbsp;&nbsp;&nbsp; <span style="border-radius: 50%; padding: 9px; '.$qtitstyle.'">Items - '.$qtit1.'</span></td>
				<td style="vertical-align: middle; text-align: right; font-weight: bold;">'.number_format($io_ttl_1, 2).'</td>
				<td style="vertical-align: middle; text-align: right; font-weight: bold;">'.number_format($io_ttl_0, 2).'</td>
				<td style="vertical-align: middle;">&nbsp;</td>
				<td style="vertical-align: middle; text-align: right; font-weight: bold;">'.number_format($io_ttl_2, 2).'</td>
			</tr>
		</table>
	</div>
	<div style="float: right; width:49%; min-height: 95%; padding: 7px;">
		<table class="TList" cellspacing="1">
		
			<tr><th colspan="2" style="height: 30px; vertical-align: middle; font-size: 17px;">'.$vendor.'</th><th colspan="2" style="height: 30px; vertical-align: middle; font-size: 17px;">Purchase Order # '.$r1[0]['pon'].'&nbsp;&nbsp;&nbsp;&nbsp; Confirmation # '.$r1[0]['ocn'].'</th></tr>
			<tr><th colspan="2" style="vertical-align: top;">Bill to address</th>
				<td style="height: 200px; vertical-align: top; '.$addrstyle.'">'.$soldto.'<div style="float: left; width: 90px; height: 5px; '.$shphonestyle.'"></div></td>
				<th style="vertical-align: top;">Ship to address</th>
				<td style="height: 200px; vertical-align: top; '.$addrstyle.'">'.$shipto.'<div style="float: left; width: 90px; height: 5px; '.$shphonestyle.'"></div></td>
			</tr>
			<tr><th style="width: 5%; height: 30px;">Qtty</th>
				<th>Item Code</th>
				<th>Description</th>
				<th>Cost</th>
				<th>Total</th>
			</tr>
'.$purchfrm.'
			<tr><td colspan="3" style="height: 50px; vertical-align: middle;"><span style="border-radius: 50%; padding: 9px; '.$lrowstyle.'">Rows - '.$lrows2.'</span>&nbsp;&nbsp;&nbsp; <span style="border-radius: 50%; padding: 9px; '.$qtitstyle.'">Items - '.$qtit2.'</span></td>
				<td></td>
				<td style="vertical-align: middle; text-align: right; font-weight: bold;">'.$ttbl.'</td>
			</tr>
		</table>
	</div>
</div>
<div style="clear: both; padding: 0px;">';

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

		$pcnt .= '
	<div style="width:49%; padding: 7px;">
	
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



<input type="button" name="stmpbttn" id="stmpbttn" value="Approve" OnClick="showDT();" style="margin-top: 40px;" />


	<div id="stmp" style="display: none; width: 250px;
	height: 70px;
	margin: 40px 0px;
	padding: 7px;
	background: #396;
	border: double 13px #fff;
	fborder-radius: 50%;
	color: #fff;">
	'.$lghsh['Name'].'
	<div style="text-align: center; font-size: 27px;">APPROVED</div>
<div id="CrntDT"></div>

	</div>

	</div>
	
	
</div>

<input type="button" name="shbttn" value="Show CSV Content" OnClick="shhdcsvcnt();" style="float: right;" />
<div id="CSVCnt" style="display: none;">'. displaycsvarr($csvarr) . '</div>

</form>


<!-- script>

var clr;
var stp = 0;
	
	
function look4sim(o){
var bN = o.id;
var oN;
	if(bN.match(/^C1_\w+/g)){ 
	oN=bN.replace(/^C1_/, "C2_"); 
	}
	if(bN.match(/^C2_\w+/g)){ 
	oN=bN.replace(/^C2_/, "C1_"); 
	}
var d = document.getElementById(oN);
	clr = d.style.backgroundColor;
	d.style.backgroundColor = "ddd";
return;
}

function lookout(o){
var bN = o.id;
var oN;
	if(bN.match(/^C1_\w+/g)){ 
	oN=bN.replace(/^C1_/, "C2_"); 
	}
	if(bN.match(/^C2_\w+/g)){ 
	oN=bN.replace(/^C2_/, "C1_"); 
	}
var d = document.getElementById(oN);
	d.style.backgroundColor = clr;
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

?>