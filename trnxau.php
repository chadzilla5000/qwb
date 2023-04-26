<?php
require_once('inc/_init.php');
require_once('inc/functions/general.php');
require_once('inc/functions/authnet.php');
error_reporting(E_ALL | E_STRICT);

$page_title       = 'Unified WC Terminal';
$page_description = '';
$keywords         = '';
$head_ext         = '';

if($lghsh['Id'] == 0){
	require_once('inc/functions/authorization.php'); 
	$pgcontent = logform(); 
	require_once('inc/_shell.php'); ///
	exit;
	}

include_once 'inc/functions.php';
setlocale(LC_MONETARY, 'en_US');

$pcnt  = $msg = NULL;
$trows = '';
$t = 0;

require_once 'wconfig.php';
/////////////////////////////////






//$cdt = date("Y-m-d");
//$dto = (isset($_GET['dateto'])) ? $_GET['dateto'] : date('Y-m-d', strtotime($cdt. ' + 1 days'));


      
if(!defined('DONT_RUN_SAMPLES')){
	if(isset($_GET['trid'])){
		$tid = $_GET['trid'];
		$rs = getTransactionDetails($tid);

//echo "<pre>"; print_r($rs);	echo "</pre>"; //exit;
if($rs->getmessages()->getresultCode() != 'Ok'){
	echo '<div style="font-weight: bold ; color: #f00;">Error code: '.$rs->getmessages()->getmessage()[0]->getcode().' - '.$rs->getmessages()->getmessage()[0]->gettext().'</div>';
	exit;
}

$vu = (array) $rs->getTransaction()->getsubmitTimeUTC();
$vl = (array) $rs->getTransaction()->getsubmitTimeLocal();
$dtu = substr($vu['date'], 0, 19);
$dtl = substr($vl['date'], 0, 19);

$billtoname = $bladdress = $blcity = $blstate = $blzip = $phone = $email = NULL;
$shiptoname = $shaddress = $shcity = $shstate = $shzip = NULL;

if($rs->getTransaction()->getbillto())	{ 
	if($rs->getTransaction()->getbillto()->getemail())       { $email = $rs->getTransaction()->getbillto()->getemail();   }
	if($rs->getTransaction()->getbillto()->getphoneNumber()) { $phone = $rs->getTransaction()->getbillto()->getphoneNumber();   }
	}

if((!$email) AND $rs->getTransaction()->getcustomer())	{ 
	if($rs->getTransaction()->getcustomer()->getemail())       { $email = $rs->getTransaction()->getcustomer()->getemail(); }
	}
if((!$phone) AND $rs->getTransaction()->getcustomer())	{ 
	if(null !== $rs->getTransaction()->getcustomer()->getphoneNumber()) { $phone = $rs->getTransaction()->getcustomer()->getphoneNumber(); }
	}

if($rs->getTransaction()->getbillTo()){
	$billtoname = ($rs->getTransaction()->getbillTo()->getcompany())?
	$rs->getTransaction()->getbillTo()->getcompany().' ('.$rs->getTransaction()->getbillTo()->getfirstName().' '.$rs->getTransaction()->getbillTo()->getlastName().')':
	$rs->getTransaction()->getbillTo()->getfirstName().' '.$rs->getTransaction()->getbillTo()->getlastName();
	$bladdress  = $rs->getTransaction()->getbillTo()->getaddress();
	$blcity     = $rs->getTransaction()->getbillTo()->getcity(); 
	$blstate    = $rs->getTransaction()->getbillTo()->getstate();
	$blzip      = $rs->getTransaction()->getbillTo()->getzip();
}

if($rs->getTransaction()->getshipTo()){
	$shiptoname = ($rs->getTransaction()->getshipTo()->getcompany())?
	$rs->getTransaction()->getshipTo()->getcompany().' ('.$rs->getTransaction()->getshipTo()->getfirstName().' '.$rs->getTransaction()->getshipTo()->getlastName().')':
	$rs->getTransaction()->getshipTo()->getfirstName().' '.$rs->getTransaction()->getshipTo()->getlastName();
	$shaddress  = $rs->getTransaction()->getshipTo()->getaddress();
	$shcity     = $rs->getTransaction()->getshipTo()->getcity(); 
	$shstate    = $rs->getTransaction()->getshipTo()->getstate();
	$shzip      = $rs->getTransaction()->getshipTo()->getzip();
}

$pm = $_GET['pm'];

$optlist = getqbcustomers($billtoname);
$btchid = ($rs->getTransaction()->getbatch())? $rs->getTransaction()->getbatch()->getbatchId(): NULL;

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
	<td>Batch ID</td>
</tr>
<tr><td>'.$dtu.' UTC<br>'.$dtl.' Local</td>
	<td>'.$rs->getTransaction()->gettransId().'</td>
	<td>'.$btchid.'</td>
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
<h2>Billing address:</h2>
'.$billtoname.'<br>
'.$bladdress.'<br>
'.$blcity.',
'.$blstate.'&nbsp;&nbsp;
'.$blzip.'<br>
<br>
Email: '. $email .'<br>
Phone: '. $phone .'<br>

</div>
<div class="LRB" style="float: right;">
<h2>Shiping address:</h2>
'.$shiptoname.'<br>
'.$shaddress.'<br>
'.$shcity.', 
'.$shstate.'
'.$shzip.'<br>

<br>Phone: '. $phone .'<br>

</div>


<div style="clear: both;"></div>

<table id="TBase" cellspacing="1">
<tr><td style="width: 30%; text-align: right;">Payment Method</td>
	<td>'.$pm.'</td>
</tr>
<tr><td style=" text-align: right;">Status</td>
	<td>'.$rs->getTransaction()->gettransactionStatus().'</td>
</tr>
<tr><td style=" text-align: right;">Auth Code</td>
	<td>'.$rs->getTransaction()->getauthCode().'</td>
</tr>
<tr><td style=" text-align: right;">AVS Responce</td>
	<td>'.$rs->getTransaction()->getaVSResponse().'</td>
</tr>
<tr><td style=" text-align: right;">CCode</td>
	<td>'.$rs->getTransaction()->getcardCodeResponse().'</td>
</tr>
<tr><td style=" text-align: right;">Description</td>
	<td>'.$rs->getTransaction()->getorder()->getdescription().'</td>
</tr>
<tr><td style=" text-align: right;">Order #</td>
	<td><a href="dispcns.php?sbj=order&oid='.$rs->getTransaction()->getorder()->getinvoiceNumber().'" OnClick="return load_console(this.href, \'80%\', \'80%\');">'.$rs->getTransaction()->getorder()->getinvoiceNumber().'</a></td>
</tr>
<tr><td style=" text-align: right;">Card number</td>
	<td>'.$rs->getTransaction()->getpayment()->getcreditCard()->getcardNumber().'</td>
</tr>
<tr><td style=" text-align: right;">Card Type</td>
	<td>'.$rs->getTransaction()->getpayment()->getcreditCard()->getcardType().'</td>
</tr>
<tr><td style=" text-align: right;">IP</td>
	<td>'.$rs->getTransaction()->getcustomerIP().'</td>
</tr>
<tr><td style=" text-align: right;">Result Code</td>
	<td>'.$rs->getmessages()->getresultCode().'</td>
</tr>
<tr><td style=" text-align: right;">Payd Total (USD)</td>
	<td>'.number_format($rs->getTransaction()->getauthAmount(), 2).'</td>
</tr>
<tr><td style=" text-align: right;">Total Settle (USD)</td>
	<td>'.number_format($rs->getTransaction()->getsettleAmount(), 2).'</td>
</tr>
</table>

</div>';
		}
	else{
		$sortstatus = (isset($_GET['st']))? $_GET['st']:NULL;

		
$dto = (isset($_GET['dateto'])) ? $_GET['dateto'] : date('Y-m-d');
$dfr = (isset($_GET['datefr'])) ? $_GET['datefr'] : date('Y-m-d', strtotime($dto. ' - 31 days'));
$dto1 = date('Y-m-d', strtotime($dto. ' + 1 days'));
$dfr1 = date('Y-m-d', strtotime($dfr. ' + 1 days'));
$d = explode('-',$dto1);

// both the first and last dates must be in the same time zone
// a date constructed from an ISO8601 format date string
$firstSettlementDate = new DateTime($dfr1."T00:00:00Z");
// a date constructed manually
$lastSettlementDate  = new DateTime();
$lastSettlementDate->setDate($d[0],$d[1],$d[2]);
$lastSettlementDate->setTime(23,59,59);
//$lastSettlementDate->setTimezone(new DateTimeZone('UTC'));

		$res0 = getSettledBatchList($firstSettlementDate, $lastSettlementDate);
		foreach($res0->getBatchList() as $bt){
			$btid = $bt->getBatchId();
			$res1 = getTransactionList($btid);
	//		$res2 = getCustomerPaymentProfile();

			foreach ($res1->getTransactions() as $trxs) {
				$tid = $trxs->getTransId();

if($sortstatus AND $sortstatus != 'all' AND ($sortstatus != $trxs->getTransactionStatus())){continue;}

//echo "<pre>"; print_r($trxs); echo "</pre>"; echo '<br><br><br><br><br><br><br><br>';

		
				$trows .= '
	<tr><td>'.++$t.'. </td><td style="width: 50px;">
		<a title="Authorize.net transaction details" href="trnxau.php?pm=authnet&trid='.$tid.'" OnClick="return load_console(this.href, \'70%\', \'90%\');">'.$tid.'</a></td>
		<td>'.$btid.'</td>
		<td>'.date_format($trxs->getSubmitTimeLocal(), 'Y-m-d H:i:s').'</td>
		<td>'.$trxs->getTransactionStatus().'</td>
		<td>'.$trxs->getfirstName().' '.$trxs->getlastName().'</td>
		<td>'.number_format($trxs->getSettleAmount(), 2, '.', '').'</td>
	</tr>';
				}
			}
		$pcnt = '
<div style="width: 90%; margin: 0 auto;">

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
<tr><th colspan="7" style="background: #ccc; color: #000; text-align: left; padding-left: 35px; font-size: 15px;">Authorize.Net
		<div style="float: right; font-size: 11px;">
			<a  href="trnxau.php?st=all" OnClick="return load_console(this.href, \'70%\', \'90%\');">All payments</a>&nbsp;&nbsp;
			<a  href="trnxau.php?st=settledSuccessfully" OnClick="return load_console(this.href, \'70%\', \'90%\');">Settled</a>&nbsp;&nbsp;
			<a  href="trnxau.php?st=refundSettledSuccessfully" OnClick="return load_console(this.href, \'70%\', \'90%\');">Refunded</a>&nbsp;&nbsp;
			<a  href="trnxau.php?st=declined" OnClick="return load_console(this.href, \'70%\', \'90%\');">Declined</a>&nbsp;&nbsp;
		</div>	
	</th>
</tr>
<tr><th></th>
	<th style="width: 130px;">Transaction ID</th>
	<th style="width: 130px;">Batch ID</th>
	<th style="width: 130px;">Date/Time</th>
	<th style="width: 130px;">Status</th>
	<th style="width: 130px;">Name</th>
	<th style="width: 130px;">Amount</th>
</tr>
'.$trows.'
</table>

</form></div>';
		}
	}

$pgtitle = '<h4>Customer list</h4>';
$pgcontent = <<<EOD__
<!-- Page content start -->
$pcnt
<!-- Page content end -->
EOD__;
echo $pgcontent; exit;

//	}
	
require_once('inc/_shell.php'); ///
//////////////////////////////////
/////////////////////////////////
////////////////////////////////
///////////////////////////////



?>

