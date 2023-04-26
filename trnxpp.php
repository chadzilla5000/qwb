<?php namespace Sample;

require_once('inc/_init.php');
require_once('inc/functions/general.php');
require_once('inc/functions/paypal.php');
error_reporting(E_ALL | E_STRICT);

include_once 'inc/functions.php';
setlocale(LC_MONETARY, 'en_US');

$pcnt = $msg = NULL;
require_once 'wconfig.php';
/////////////////////////////////

$trid=(isset($_GET['trid']))?$_GET['trid']:NULL;

if($trid){
	$rsp = get_transaction_details($trid);
//	echo "<pre>"; print_r($rsp); echo "</pre>"; exit;

error_reporting(E_ERROR | E_WARNING | E_PARSE);




$dtl = substr($rsp['TIMESTAMP'], 0, 19);
$dto = substr($rsp['ORDERTIME'], 0, 19);

$billtoname = $bladdress = $blcity = $blstate = $blzip = $phone = $email = NULL;
$shiptoname = $shaddress = $shcity = $shstate = $shzip = NULL;

$optlist = getqbcustomers($rsp['FIRSTNAME'].' '.$rsp['LASTNAME']);






$pcnt = '
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
	font-size: 17px;
}

.TSP01{
	width: 70%;
	text-align: right;
	font-size: 15px;
	color: #999;
}
</style>


<div style="width: 80%; margin: 0 auto; margin-top: 10px; padding: 5px;">

<div style="float: left; width: 500px;">
<h2>Transaction details</h2><br>
<table id="TRght" cellspacing="1">
<tr><td style="width: 50%;">Date / Time</td>
	<td>Transaction ID</td>
	<td>Payer ID</td>
</tr>
<tr><td>'.$dtl.' Pay<br>'.$dto.' Ord.</td>
	<td>'.$rsp['TRANSACTIONID'].'</td>
	<td>'.$rsp['PAYERID'].'</td>
</tr>
</table>
</div>

<div style="float: right;"><b>List payments</b><br>
<a title="Authnet transactions" href="trnxau.php" OnClick="return load_console(this.href, \'70%\', \'90%\');">Authorize.net</a><br>
<a title="Paypal transactions" href="trnxpp.php" OnClick="return load_console(this.href, \'70%\', \'90%\');">PayPal</a><br><br>

<form action="qbw.php" method="post" name="psendform">
<input type="hidden" name="fsbm" value="1" />
<input type="hidden" name="payment2send[]" value="'.$_GET['oid'].'" />
<input type="submit" name="submit" value="Send payment" />
<select name="manucustomer">
<option value="">Select customer</option>
'.$optlist.'
</select>
</form>
</div>

<div style="clear: both; height: 35px;"></div>

<div class="LRB" style="float: left;">
<h2>Name:</h2>
'.$rsp['FIRSTNAME'].' '.$rsp['LASTNAME'].'<br>
<br>
Email: '. $rsp['EMAIL'] .'<br>
Phone: '. $rsp['SHIPTOPHONENUM'] .'<br>
</div>
<div class="LRB" style="float: right;">
<h2>Address:</h2>
'.$rsp['SHIPTONAME'].'<br>
'.$rsp['SHIPTOSTREET'].'<br>
'.$rsp['SHIPTOCITY'].', 
'.$rsp['SHIPTOSTATE'].'<br>
'.$rsp['SHIPTOZIP'].'<br>



</div>


<div style="clear: both;"></div>

<table id="TBase" cellspacing="1">
<tr><td style="width: 30%; text-align: right;">Payment Method</td>
	<td>PayPal</td>
</tr>
<tr><td style=" text-align: right;">Status</td>
	<td>'.$rsp['PAYMENTSTATUS'].'</td>
</tr>
<tr><td style=" text-align: right;">Correlation ID</td>
	<td>'.$rsp['CORRELATIONID'].'</td>
</tr>
<tr><td style=" text-align: right;">Fee</td>
	<td>'.$rsp['FEEAMT'].'</td>
</tr>
<tr><td style=" text-align: right;">Tax</td>
	<td>'.$rsp['TAXAMT'].'</td>
</tr>
<tr><td style=" text-align: right;">Shipping</td>
	<td>'.$rsp['SHIPPINGAMT'].'</td>
</tr>
<tr><td style=" text-align: right;">Handling</td>
	<td>'.$rsp['HANDLINGAMT'].'</td>
</tr>
<tr><td style=" text-align: right;">Payd Total (USD)</td>
	<td>'.$rsp['AMT'].'</td>
</tr>
</table>

</div>';


	}
else{
	$sortstatus = (isset($_GET['st']))? $_GET['st']:NULL;
	
	
	$t=0;
	$dto = (isset($_GET['dateto'])) ? $_GET['dateto'] : date('Y-m-d');
$dfr = (isset($_GET['datefr'])) ? $_GET['datefr'] : date('Y-m-d', strtotime($dto. ' - 31 days'));
$dto1 = date('Y-m-d', strtotime($dto. ' + 1 days'));
$dfr1 = date('Y-m-d', strtotime($dfr. ' + 1 days'));
$d = explode('-',$dto1);

	$res = get_pplist();
	foreach ($res as $trxs) {
		$tid = $trxs['transaction_id'];

//echo $_GET['st']; exit;

if($sortstatus AND $sortstatus != 'all' AND ($sortstatus != $trxs['status'])){continue;}


//$dt = substr(0, 19, $trxs['timestamp']);
//echo "<pre>"; print_r($trxs); echo "</pre>"; echo '<br><br><br><br><br><br><br><br>';

		$dtl = substr($trxs['timestamp'], 0, 19);
		$dtl = preg_replace('/T/',' ',$dtl);

		$trows .= ($tid)?'
<tr><td>'.++$t.'. </td><td style="width: 50px;">
	<a title="PayPal transaction details" href="trnxpp.php?pm=paypal&trid='.$tid.'" OnClick="return load_console(this.href, \'70%\', \'90%\');">'.$tid.'</a></td>
	<td>'.$trxs['email'].'</td>
	<td>'.$dtl.' '.$trxs['timezone'].'</td>
	<td>'.$trxs['status'].'</td>
	<td>'.$trxs['name'].'</td>
	<td>'.number_format(floatval($trxs['amt']), 2, '.', '').'</td>
	<td>'.number_format(floatval($trxs['fee_amount']), 2, '.', '').'</td>
	<td>'.number_format(floatval($trxs['net_amount']), 2, '.', '').'</td>
</tr>':'';
		}

	$pcnt = '
<!-- form action="#" method="get" name="audform">
List dated From - 
<input type="date" name="datefr" value="'.$dfr.'" />&nbsp; &nbsp;To - 
<input type="date" name="dateto" value="'.$dto.'" />&nbsp; &nbsp;
<input type="submit" name="submitdt" value="List Transactions" style="padding: 1px 5px;" OnChange="dateformsubmit();" />
</form -->

<div style="float: right;"><b>List payments</b>&nbsp;&nbsp;&nbsp;
<a title="Authnet transactions" href="trnxau.php" OnClick="return load_console(this.href, \'70%\', \'90%\');">Authorize.net</a>&nbsp;&nbsp;&nbsp;
<a title="Paypal transactions" href="trnxpp.php" OnClick="return load_console(this.href, \'70%\', \'90%\');">PayPal</a><br><br>
</div>
<form action="#" method="post" name="aupform">
<table class="TBL" width="100%" cellspacing="1">
<tr><th colspan="9" style="background: #ccc; color: #000; text-align: left; padding-left: 35px; font-size: 15px;">PayPal
<div style="float: right; font-size: 11px;">
	<a  href="trnxpp.php?st=all" OnClick="return load_console(this.href, \'70%\', \'90%\');">All payments</a>&nbsp;&nbsp;
	<a  href="trnxpp.php?st=Completed" OnClick="return load_console(this.href, \'70%\', \'90%\');">Completed</a>&nbsp;&nbsp;
	<a  href="trnxpp.php?st=Refunded" OnClick="return load_console(this.href, \'70%\', \'90%\');">Refunded</a>&nbsp;&nbsp;
	<a  href="trnxpp.php?st=Partially Refunded" OnClick="return load_console(this.href, \'70%\', \'90%\');">Partially Refunded</a>&nbsp;&nbsp;
</div>	
	</th>
</tr>
<tr><th></th>
	<th style="width: 130px;">Transaction ID</th>
	<th style="width: 130px;">Email</th>
	<th style="width: 130px;">Date/Time</th>
	<th style="width: 100px;">Status</th>
	<th style="width: 130px;">Name</th>
	<th style="width: 50px;">Amount</th>
	<th style="width: 50px;">Fee</th>
	<th style="width: 50px;">Net</th>
</tr>
'.$trows.'
</table>

</form>';

}




$pgtitle = '<h4>Sku edit</h4>';
$pgcontent = <<<EOD__
<!-- Page content start -->
$pcnt
<!-- Page content end -->
EOD__;

//	}
$page_title       = 'Unified WC Terminal';
$page_description = '';
$keywords         = '';
$head_ext         = '';
	
require_once('inc/_pupshell.php'); ///
//////////////////////////////////
/////////////////////////////////
////////////////////////////////
///////////////////////////////






?>

