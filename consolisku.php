<?php
require_once('inc/_init.php');
require_once('inc/functions/general.php');
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

require_once 'wconfig.php';

$Queue = new QuickBooks_WebConnector_Queue($dsn, $qbwc_user);

//	$newid = rand();
//	$Queue->enqueue(QUICKBOOKS_QUERY_CUSTOMER, $newid);
//exit;



//echo 'mOOO <br>';
//sinchro_customer(); exit;

//sinchro_items_qb2tb(); exit;

//////////// Form handler ////////////////////////////////////////////////////
//if(isset($_POST['fsynchro'])){ $Queue = new QuickBooks_WebConnector_Queue($dsn); 


//sinchro_items(); exit;
/*
$Queue = new QuickBooks_WebConnector_Queue($dsn);
$newid = rand();
$Queue->enqueue(QUICKBOOKS_QUERY_NONINVENTORYITEM, $newid);
exit;
//   */


$qmsg = 'Web Connector is needed to run after performing "Query".<br>Upon finish it should update the database by clicking "Update" below. Note: it may take minutes';
$cactive = 'disabled';

if(isset($_GET['act']) AND $_GET['act']=='prep'){ //echo 'queryconsolidate submitted<br><br>'; 
	$newid = rand();
	$Queue->enqueue(QUICKBOOKS_QUERY_NONINVENTORYITEM, $newid);
	$qmsg = 'Query prepared. Run web Connector before updating DB';
	$cactive = '';
	$pactive = 'disabled';
	}
if(isset($_GET['act']) AND $_GET['act']=='cons'){ //echo 'updatable submitted<br><br>'; 
	sinchro_items();
	$qmsg = 'Table updated';
	$pactive = 'disabled';
	$cactive = 'disabled';
	}




if(0){ $newid = rand();
	$Queue->enqueue(QUICKBOOKS_MOD_NONINVENTORYITEM, $newid);
	}

if(0){ sinchro_items(); }

$pcnt = '

<div style="width: 90%; margin: 0 auto;">
<a title="Prepare QB query for consolidation" href="consolisku.php?act=prep" OnClick="return load_console(this.href, \'50%\', \'30%\');">
<input type="button" name="queryconsolidate" value="Query" style="padding: 1px 5px;" '.$pactive.' /></a>
<br><br>
'.$qmsg.'
<br><br>
<a title="Prepare QB query for consolidation" href="consolisku.php?act=cons" OnClick="return load_console(this.href, \'50%\', \'30%\');">
<input type="button" name="updatable" value="Update" style="padding: 1px 5px;" '.$cactive.' /></a>

</div>';

$pgtitle = '<h4>Consolidation</h4>';
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






function sinchro_itemsZapas(){ global $dbh;


if (file_exists("data/sinchro/noninventoryitems.xml")) {
	$xml = simplexml_load_file("data/sinchro/noninventoryitems.xml");


//echo $xml; return;

	foreach($xml->QBXMLMsgsRs->ItemNonInventoryQueryRs->ItemNonInventoryRet as $d){
		
		
//		echo $d->ListID . ' - ' . $d->Name . ' - ' . $d->SalesAndPurchase->SalesDesc . ' - ' . $d->SalesAndPurchase->PurchaseCost . ' - ' . $d->SalesAndPurchase->SalesPrice . '<br>';
		
		
		
/*		
		
		
		if(mysqli_fetch_array(mysqli_query($dbh, "SELECT id FROM qw_item WHERE Sku = '$d->Name'")))	{ // item exists





			// mysqli_query($dbh, "UPDATE qw_item SET 
				// QBListID   = '".mysqli_escape_string($dbh, $d->ListID)."',
				// EdSeq      = '".mysqli_escape_string($dbh, $d->EditSequence)."',
				// Title      = '".mysqli_escape_string($dbh, $d->SalesAndPurchase->SalesDesc)."', 
				// VCost      = '".mysqli_escape_string($dbh, $d->SalesAndPurchase->PurchaseCost)."', 
				// SPrice     = '".mysqli_escape_string($dbh, $d->SalesAndPurchase->SalesPrice)."',
				// DTCreated  = '".mysqli_escape_string($dbh, $d->TimeCreated)."', 
				// DTModified = '".mysqli_escape_string($dbh, $d->TimeModified)."', 
				// Vendor     = ''
			// WHERE Sku = '$d->Name'");
			}
		else{ // item does not exist



			// mysqli_query($dbh, "INSERT INTO	qw_item ( QBListID, EdSeq, Sku, Title, VCost, SPrice, DTCreated, DTModified ) VALUES (
			// '" . mysqli_escape_string($dbh, $d->ListID) . "',
			// '" . mysqli_escape_string($dbh, $d->EditSequence) . "',
			// '" . mysqli_escape_string($dbh, $d->Name) . "',
			// '" . mysqli_escape_string($dbh, $d->SalesAndPurchase->SalesDesc) . "',
			// '" . mysqli_escape_string($dbh, $d->SalesAndPurchase->PurchaseCost) . "',
			// '" . mysqli_escape_string($dbh, $d->SalesAndPurchase->SalesPrice) . "',
			// '" . mysqli_escape_string($dbh, $d->TimeCreated) . "',
			// '" . mysqli_escape_string($dbh, $d->TimeModified) . "'
			// )");
			}
			
			*/
		}

echo 'Done';		
	}
return;
}


function sinchro_items(){ global $dbh;
if (file_exists("data/sinchro/noninventoryitems.xml")) {
	$xml = simplexml_load_file("data/sinchro/noninventoryitems.xml");
	$tm = 0;
	foreach($xml->QBXMLMsgsRs->ItemNonInventoryQueryRs->ItemNonInventoryRet as $d){ $tm++;

		$tcreat = substr($d->TimeCreated, 0, 19);
		$tmodif = substr($d->TimeModified, 0, 19);
		echo $tm . '. ' .$d->ListID . ' - ' . $d->Name . ' - ' . $d->SalesAndPurchase->SalesDesc . ' - ' . $d->SalesAndPurchase->PurchaseCost . ' - ' . $d->SalesAndPurchase->SalesPrice . ' - ' . $d->SalesAndPurchase->PrefVendorRef->FullName . ' - ' . $tcreat . ' - ' . $tmodif . '<br>';

/*
		if(mysqli_fetch_array(mysqli_query($dbh, "SELECT id FROM qw_item WHERE Sku = '$d->Name'")))	{ // item exists
			mysqli_query($dbh, "UPDATE qw_item SET 
				QBListID   = '" . mysqli_escape_string($dbh, $d->ListID) . "',
				EdSeq      = '" . mysqli_escape_string($dbh, $d->EditSequence) . "',
				Title      = '" . mysqli_escape_string($dbh, $d->SalesAndPurchase->SalesDesc) . "', 
				VCost      = '" . mysqli_escape_string($dbh, $d->SalesAndPurchase->PurchaseCost) . "', 
				SPrice     = '" . mysqli_escape_string($dbh, $d->SalesAndPurchase->SalesPrice) . "',
				Vendor     = '" . mysqli_escape_string($dbh, $d->SalesAndPurchase->PrefVendorRef->FullName) . "',
				DTCreated  = '" . mysqli_escape_string($dbh, $tcreat) . "', 
				DTModified = '" . mysqli_escape_string($dbh, $tmodif) . "' 
			WHERE Sku = '" . $d->Name . "'");
			}
		else{ // item does not exist
			mysqli_query($dbh, "INSERT INTO	qw_item ( QBListID, EdSeq, Sku, Title, Vendor, VCost, SPrice, DTCreated, DTModified ) 
			VALUES (
			'" . mysqli_escape_string($dbh, $d->ListID) . "',
			'" . mysqli_escape_string($dbh, $d->EditSequence) . "',
			'" . mysqli_escape_string($dbh, $d->Name) . "',
			'" . mysqli_escape_string($dbh, $d->SalesAndPurchase->SalesDesc) . "',
			'" . mysqli_escape_string($dbh, $d->SalesAndPurchase->PrefVendorRef->FullName) . "',
			'" . mysqli_escape_string($dbh, $d->SalesAndPurchase->PurchaseCost) . "',
			'" . mysqli_escape_string($dbh, $d->SalesAndPurchase->SalesPrice) . "',
			'" . mysqli_escape_string($dbh, $tcreat) . "',
			'" . mysqli_escape_string($dbh, $tmodif) . "'
			)");
			}
			
			
			*/
			
//echo $tm . '. ' .$d->ListID . ' - ' . $d->Name . ' - ' . $mvv . '<br>';



//printf("Affected rows (UPDATE): %d\n", $mysqli->affected_rows); echo '<br>';

//if($tm>30){ return; }
		
/*	if(!
mysqli_query($dbh, "UPDATE qw_item SET 
				QBListID   = '".mysqli_escape_string($dbh, $d->ListID)."',
				EdSeq      = '".mysqli_escape_string($dbh, $d->EditSequence)."',
				Title      = '".mysqli_escape_string($dbh, $d->SalesAndPurchase->SalesDesc)."', 
				VCost      = '".mysqli_escape_string($dbh, $d->SalesAndPurchase->PurchaseCost)."', 
				SPrice     = '".mysqli_escape_string($dbh, $d->SalesAndPurchase->SalesPrice)."',
				Vendor     = '".mysqli_escape_string($dbh, $d->SalesAndPurchase->PrefVendorRef->FullName)."',
				DTCreated  = '".mysqli_escape_string($dbh, $tcreat)."', 
				DTModified = '".mysqli_escape_string($dbh, $tmodif)."' 
			WHERE Sku = '".$d->Name."'")
			) {
*/
//		WHERE QBListID = '".$d->ListID."'");
/*			
		 // item does not exist
			mysqli_query($dbh, "INSERT INTO	qw_item ( QBListID, EdSeq, Sku, Title, Vendor, VCost, SPrice, DTCreated, DTModified ) 
			VALUES (
			'" . mysqli_escape_string($dbh, $d->ListID) . "',
			'" . mysqli_escape_string($dbh, $d->EditSequence) . "',
			'" . mysqli_escape_string($dbh, $d->Name) . "',
			'" . mysqli_escape_string($dbh, $d->SalesAndPurchase->SalesDesc) . "',
			'" . mysqli_escape_string($dbh, $d->SalesAndPurchase->PrefVendorRef->FullName) . "',
			'" . mysqli_escape_string($dbh, $d->SalesAndPurchase->PurchaseCost) . "',
			'" . mysqli_escape_string($dbh, $d->SalesAndPurchase->SalesPrice) . "',
			'" . mysqli_escape_string($dbh, $tcreat) . "',
			'" . mysqli_escape_string($dbh, $tmodif) . "'
			)");
//			}
			
// */			
			
			
		}	
	}
return;
}

function sinchro_items_qb2tb(){ global $dbh;

	$xml = simplexml_load_file("data/sinchro/noninventoryitems.xml");

// echo '<pre>';
// print_r ($xml);
// echo '</pre>';

$result = mysqli_query($dbh, "SELECT * FROM qw_item");
	while($r = mysqli_fetch_assoc($result)){

if(!substr($r['Sku'], $xtxt)){

echo $r['Sku'] . '<br>';
}

			
		 }


return;
}







function sinchro_customer(){ global $dbh;
if (file_exists("data/sinchro/qbcustomers.xml")) {



	$xml = simplexml_load_file("data/sinchro/qbcustomers.xml");
// echo '<pre>';
// print_r($xml);
// echo '</pre>';

	$tm = 0;
	foreach($xml->QBXMLMsgsRs->CustomerQueryRs->CustomerRet as $d){ $tm++;

//print_r($xml);

		$carr = explode(", ", $d->FullName);
		
echo sizeof($carr) . ' ' .$carr[1] . ' - ' . $carr[0] . '<br>'; //$carr[0]. ' '. $carr[1];


//		$tcreat = substr($d->TimeCreated, 0, 19);
//		$tmodif = substr($d->TimeModified, 0, 19);
//		echo $tm . '. ' .$d->ListID . ' - ' . $d->Name . ' - ' . $d->SalesAndPurchase->SalesDesc . ' - ' . $d->SalesAndPurchase->PurchaseCost . ' - ' . $d->SalesAndPurchase->SalesPrice . ' - ' . $d->SalesAndPurchase->PrefVendorRef->FullName . ' - ' . $tcreat . ' - ' . $tmodif . '<br>';


			mysqli_query($dbh, "UPDATE qw_customer SET 
				quickbooks_listid   = '".mysqli_escape_string($dbh, $d->ListID)."'
			WHERE name = '".$d->FullName."' OR (fname = '$carr[1]' AND lname = '$carr[0]')");
			
		}	
	}
return;
}






?>