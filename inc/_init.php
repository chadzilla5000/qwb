<?php 

//ini_set("display_errors", 1);
//ini_set('display_startup_errors',1);
error_reporting(E_ALL);
include_once('inc/functions/basics.php');

if(! session_id()) { session_start(); }
$logmsg = $loginf = $tmenu  = '';

if(isset($_GET['cmd']) AND $_GET['cmd']=='login'){ require_once('inc/functions/authorization.php'); 
	if(login()) {$logmsg = '<div style="color: green;">Access granted</div>';}
	else {$logmsg = '<div style="color: red;">Incorrect login/password</div>';}
	}

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
		<a href="proK_XML.php">Pro K XML</a>
	</div>
</li>
<li class="dropdown"><span style="color: #999;">Payments</span>
	<div class="dropdown-content">
		<a href="tx_au.php?pm=authnet" onclick="return load_console(this.href, \'70%\', \'90%\');">Authorize.net</a>
		<a href="tx_au.php?pm=authnet&oaut=1" onclick="return load_console(this.href, \'70%\', \'90%\');">Authorize Old</a>
		<a href="tx_pp.php?pm=paypal" onclick="return load_console(this.href, \'70%\', \'90%\');">Paypal</a>
	</div>
</li>
<li class="dropdown"><a href="dblqbchck.php">DBL-Check</a>
	<!-- div class="dropdown-content">
		<a href="uscdgnrder.php">USCD Purch. Order</a>
	</div -->
	<!-- div class="dropdown-content">
		<a href="porder_fm.php">Forevermark</a>
		<a href="purchaseord.php">Purchase Order</a>
		<a href="purchordQB.php">QuickBooks</a>
	</div -->
</li>
<li class="dropdown"><span style="color: #999;">Website</span>
	<div class="dropdown-content">
		<a href="updims.php" title="Bulk update of Weight/Size of items on the Website">Update Wght/Size</a>
		<a href="s2v.php" title="Transform Simple products to Variables and vice-versa">Simple <> Variable</a>
	</div>
</li>
<li class="dropdown"><span style="color: #999;">Synchro</span>
	<div class="dropdown-content">
		<!-- a href="listqbxml.php">QB-Sku</a>
		<a href="qbcustomer.php">QB-Cust.</a -->
		<a href="synchqbq.php" title="Synchronize Inventory/NonInventory items, Sales orders, Purchase orders, Customers, Payments">QB_WCT</a>
		<!-- a href="qbdeact.php">QB_Status</a -->
		<a href="synchtrm.php">Ws_Terminal</a>
		<a href="synchro.php" title="Compare and Synchronize QB items to items on the website">Web_QB</a>
		<a href="cmpUVW.php?dm=0&sel=0" title="Compare vendor items and prices to items and prices on the website and vice-versa">Web_Vnd</a>
		<a href="unipricel.php" title="Upload data from vendor\'s price sheet to price list center in the terminal ">U_Price</a>
		<a href="factbl.php" title="View/Edit discount and/or factor in the terminal">Factor\'s Table</a>
	</div>
</li>
<li class="dropdown"><span style="color: #999;">Cleanup</span>
	<div class="dropdown-content">
		<!-- a href="clcust.php">Customers</a -->
		<a href="wduplicates.php">WS Duplicates</a>
		<a href="qbact.php">QB Act</a>
		<a href="lqbxml.php">WS->QB</a>
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
		<a href="proK_XML.php">Pro K XML</a>
	</div>
</li>
<li class="dropdown"><span style="color: #999;">Payments</span>
	<div class="dropdown-content">
		<a href="tx_au.php?pm=authnet" onclick="return load_console(this.href, \'70%\', \'90%\');">Authorize.net</a>
		<a href="tx_au.php?pm=authnet&oaut=1" onclick="return load_console(this.href, \'70%\', \'90%\');">Authorize Old</a>
		<a href="tx_pp.php?pm=paypal" onclick="return load_console(this.href, \'70%\', \'90%\');">Paypal</a>
	</div>
</li>
<li class="dropdown"><a href="dblqbchck.php">DBL-Check</a>
	<!-- div class="dropdown-content">
		<a href="uscdgnrder.php">USCD Purch. Order</a>
	</div -->
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
		<a href="qbact.php">QB Act</a>
		<a href="lqbxml.php">WS->QB</a>
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
		<a href="proK_XML.php">Pro K XML</a>
	</div>
</li>
<li class="dropdown"><span style="color: #999;">Payments</span>
	<div class="dropdown-content">
		<a href="tx_au.php?pm=authnet" onclick="return load_console(this.href, \'70%\', \'90%\');">Authorize.net</a>
		<a href="tx_au.php?pm=authnet&oaut=1" onclick="return load_console(this.href, \'70%\', \'90%\');">Authorize Old</a>
		<a href="tx_pp.php?pm=paypal" onclick="return load_console(this.href, \'70%\', \'90%\');">Paypal</a>
	</div>
</li>
<li class="dropdown"><a href="dblqbchck.php">DBL-Check</a>
	<!-- div class="dropdown-content">
		<a href="uscdgnrder.php">USCD Purch. Order</a>
	</div -->
	<!-- div class="dropdown-content">
		<a href="porder_fm.php">Forevermark</a>
		<a href="purchaseord.php">Purchase Order</a>
		<a href="purchordQB.php">QuickBooks</a>
	</div -->
</li>
<li class="dropdown"><span style="color: #999;">Website</span>
	<div class="dropdown-content">
		<a href="updims.php" title="Bulk update of Weight/Size of items on the Website">Update Wght/Size</a>
		<a href="s2v.php" title="Transform Simple products to Variables and vice-versa">Simple <> Variable</a>
	</div>
</li>
<li class="dropdown"><span style="color: #999;">Synchro</span>
	<div class="dropdown-content">
		<!-- a href="listqbxml.php">QB-Sku</a>
		<a href="qbcustomer.php">QB-Cust.</a -->
		<a href="synchqbq.php" title="Synchronize Inventory/NonInventory items, Sales orders, Purchase orders, Customers, Payments">QB_WCT</a>
		<!-- a href="qbdeact.php">QB_Status</a -->
		<a href="synchtrm.php">Ws_Terminal</a>
		<a href="synchro.php" title="Compare and Synchronize QB items to items on the website">Web_QB</a>
		<a href="cmpUVW.php?dm=0&sel=0" title="Compare vendor items and prices to items and prices on the website and vice-versa">Web_Vnd</a>
		<a href="unipricel.php" title="Upload data from vendor\'s price sheet to price list center in the terminal ">U_Price</a>
		<a href="factbl.php" title="View/Edit discount and/or factor in the terminal">Factor\'s Table</a>
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
<div style="float: right;">'.$lghsh['Login'].' - <a href="index.php?cmd=logout">Logout</a></div>';
}

?>