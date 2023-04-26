<?php 
ini_set("display_errors", 1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

include_once('inc/functions/basics.php');

$lghsh = logged();

if(isset($_GET['cmd']) AND $_GET['cmd']=='logout'){ 
require_once('inc/functions/authorization.php'); 
	logout();
	$lghsh = NULL;
	}
if($lghsh['Prvlg']){
switch ($lghsh['Prvlg']){
	case 'A': 
	$tmenu = '
<li style="margin-left: 30px;" class="dropdown"><a href="qbw.php">WCT</a>
	<div class="dropdown-content">
		<a href="p20202ord.php">2020</a>
		<a href="p2020XML.php">2020 XML</a>
	</div>
</li>
<li class="dropdown"><span style="color: #999;">Payments</span>
	<div class="dropdown-content">
		<a href="tx_au.php?pm=authnet" onclick="return load_console(this.href, \'70%\', \'90%\');">Authorize.net</a>
		<a href="tx_pp.php?pm=paypal" onclick="return load_console(this.href, \'70%\', \'90%\');">Paypal</a>
	</div>
</li>
<li class="dropdown"><a href="dblqbchck.php">DBL-Check</a>
	<div class="dropdown-content">
		<a href="uscdgnrder.php">USCD Purch. Order</a>
	</div>
	<!-- div class="dropdown-content">
		<a href="porder_fm.php">Forevermark</a>
		<a href="purchaseord.php">Purchase Order</a>
		<a href="purchordQB.php">QuickBooks</a>
	</div -->
</li>
<li class="dropdown"><span style="color: #999;">Synchro</span>
	<div class="dropdown-content">
		<!-- a href="listqbxml.php">QB-Sku</a>
		<a href="qbcustomer.php">QB-Cust.</a -->
		<a href="synchqbq.php">QB_WCT</a>
		<!-- a href="qbdeact.php">QB_Status</a -->
		<a href="synchtrm.php">Ws_Terminal</a>
		<a href="synchro.php">Web_QB</a>
		<a href="cmpUVW.php?dm=0&sel=0">Web_Vnd</a>
	</div>
</li>
<li class="dropdown"><span style="color: #999;">Cleanup</span>
	<div class="dropdown-content">
		<!-- a href="clcust.php">Customers</a -->
		<a href="wduplicates.php">WS Duplicates</a>
		<a href="qbdeact.php">QB Deactivate</a>
		<a href="updims.php">Update Wght/Size</a>
	</div>
</li>
<li class="dropdown"><a href="erreview.php">Queries</a>
	<div class="dropdown-content">
	<a href="qbwlog.php" title="User '.$lghsh['Id'].'">Log</a>
	</div>
</li>
'; 

break;

	case 'B': 
	$tmenu = '
<li style="margin-left: 30px;" class="dropdown"><a href="qbw.php">WCT</a>
	<div class="dropdown-content">
		<a href="p20202ord.php">2020</a>
		<a href="p2020XML.php">2020 XML</a>
	</div>
</li>
<li class="dropdown"><span style="color: #999;">Payments</span>
	<div class="dropdown-content">
		<a href="tx_au.php?pm=authnet" onclick="return load_console(this.href, \'70%\', \'90%\');">Authorize.net</a>
		<a href="tx_pp.php?pm=paypal" onclick="return load_console(this.href, \'70%\', \'90%\');">Paypal</a>
	</div>
</li>
<li class="dropdown"><a href="dblqbchck.php">DBL-Check</a>
	<div class="dropdown-content">
		<a href="uscdgnrder.php">USCD Purch. Order</a>
	</div>
	<!-- div class="dropdown-content">
		<a href="porder_fm.php">Forevermark</a>
		<a href="purchaseord.php">Purchase Order</a>
		<a href="purchordQB.php">QuickBooks</a>
	</div -->
</li>
<li class="dropdown"><span style="color: #999;">Synchro</span>
	<div class="dropdown-content">
		<!-- a href="listqbxml.php">QB-Sku</a>
		<a href="qbcustomer.php">QB-Cust.</a -->
		<a href="synchqbq.php">QB_WCT</a>
		<a href="synchro.php">Web_QB</a>
		<a href="cmpUVW.php?dm=0&sel=0">Web_Vnd</a>
		<a href="unipricel.php">U_Price</a>
	</div>
</li>
<li class="dropdown"><span style="color: #999;">Cleanup</span>
	<div class="dropdown-content">
		<!-- a href="clcust.php">Customers</a -->
		<a href="wduplicates.php">WS Duplicates</a>
		<a href="qbdeact.php">QB Deactivate</a>
		<a href="updims.php">Update Wght/Size</a>
	</div>
</li>
'; 

break;

	case 'C': 
	case 'E': 
	case 'F': 
	$tmenu = '
<li style="margin-left: 30px;" class="dropdown"><a href="qbw.php">WCT</a>
	<div class="dropdown-content">
		<a href="p20202ord.php">2020</a>
		<a href="p2020XML.php">2020 XML</a>
	</div>
</li>
<li class="dropdown"><span style="color: #999;">Payments</span>
	<div class="dropdown-content">
		<a href="tx_au.php?pm=authnet" onclick="return load_console(this.href, \'70%\', \'90%\');">Authorize.net</a>
		<a href="tx_pp.php?pm=paypal" onclick="return load_console(this.href, \'70%\', \'90%\');">Paypal</a>
	</div>
</li>
<li class="dropdown"><a href="dblqbchck.php">DBL-Check</a>
	<div class="dropdown-content">
		<a href="uscdgnrder.php">USCD Purch. Order</a>
	</div>
	<!-- div class="dropdown-content">
		<a href="porder_fm.php">Forevermark</a>
		<a href="purchaseord.php">Purchase Order</a>
		<a href="purchordQB.php">QuickBooks</a>
	</div -->
</li>
<li class="dropdown"><span style="color: #999;">Synchro</span>
	<div class="dropdown-content">
		<!-- a href="listqbxml.php">QB-Sku</a>
		<a href="qbcustomer.php">QB-Cust.</a -->
		<a href="synchqbq.php">QB_WCT</a>
		<a href="synchro.php">Web_QB</a>
		<a href="cmpUVW.php?dm=0&sel=0">Web_Vnd</a>
		<a href="unipricel.php">U_Price</a>
	</div>
</li>
'; 

break;
	default: 
break;
}

global  $accss;
switch($accss){	case 1:		$acbgr='#f90'; $acttl='View only';
break;			case 2:		$acbgr='#0c0'; $acttl='View and Edit';
break;			default:	$acbgr='#f00'; $acttl='Access Denied';
break; }

$loginf = '
<div style="float: right;">'.$lghsh['Login'].' - <a href="'.ROOT_URL.'/index.php?cmd=logout">Logout</a></div>';
}

?>