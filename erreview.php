<?php
require_once('inc/_init.php');
require_once('inc/functions/general.php');
error_reporting(E_ALL | E_STRICT);

$pgtitle = '<h4>List errors</h4>';
if($lghsh['Id'] == 0){
	echo 'User not logged';
	exit;
	}

include_once 'inc/functions.php';
setlocale(LC_MONETARY, 'en_US');

require_once 'wconfig.php';
error_reporting(E_ERROR | E_WARNING | E_PARSE);

/////////////////
$ofs = 0;     //
$lmt = 5000; //
//////////////

$trow = $pcnt = NULL;

//$res = mysqli_query($dbh, "SELECT * FROM quickbooks_queue ORDER BY enqueue_datetime DESC");
$res = mysqli_query($dbh, "SELECT * FROM quickbooks_queue ORDER BY enqueue_datetime DESC LIMIT $lmt OFFSET $ofs");

$t = 0;

//foreach(mysqli_fetch_assoc($res) as $row){
 while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) { ++$t;

	$rstyle = ($row['qb_status']=='s')?'#9c9':
	(($row['qb_status']=='q')?'#f90':
	(($row['qb_status']=='e')?'#f00':'transparent'))
	;

	$que = NULL;

$r = array();

switch($row['qb_action']){
	case 'ItemNonInventoryMod':
		$xfld = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM wc_item WHERE id='$row[ident]'"), MYSQLI_ASSOC);
		$r['xfld'][0] = $xfld['Sku'];
		$r['xfld'][1] = $xfld['Title'];
		$r['xfld'][2] = $xfld['VCost'];
		$r['xfld'][3] = $xfld['RPrice'];
		$r['xfld'][4] = $xfld['SPrice'];


	break;
	case 'ItemNonInventoryAdd':
		$xfld = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM wc_item WHERE id='$row[ident]'"), MYSQLI_ASSOC);
		$r['xfld'][0] = $xfld['Sku'];
		$r['xfld'][1] = $xfld['Title'];
		$r['xfld'][2] = $xfld['VCost'];
		$r['xfld'][3] = $xfld['RPrice'];
		$r['xfld'][4] = $xfld['SPrice'];
	break;
	case 'CustomerAdd':
		$xfld = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM wc_customer WHERE id='$row[ident]'"), MYSQLI_ASSOC);
		$r['xfld'][0] = $xfld['CName'];
		$r['xfld'][1] = $xfld['FName'] . ' ' . $xfld['LName'] . ', ' . $xfld['Phone'] . ', ' . $xfld['EMail'];
	break;
	case 'SalesOrderAdd':
		$xfld = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM wc_order WHERE id='$row[ident]'"), MYSQLI_ASSOC);
		$r['xfld'][0] = $xfld['OrderID'];
		$r['xfld'][1] = $xfld['CName'] . ' ' . $xfld['FName'] . ' ' . $xfld['LName'];
		$r['xfld'][3] = $xfld['OrderTT'];
	break;
	case 'ItemPaymentAdd':
		$xfld = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM wc_payment WHERE id='$row[ident]'"), MYSQLI_ASSOC);
		$r['xfld'][0] = $xfld['TransactionID'];
		$r['xfld'][1] = $xfld['CName'] . ' ' . $xfld['FName'] . ' ' . $xfld['LName'] . ' ' . $xfld['PayMethod'];
		$r['xfld'][3] = $xfld['AmountPaid'];
	break;
	default:
	break;
}




	 $trow .= '
<tr><td>' .$t . '. </td>
	<td style="width: 120px; background: '.$rstyle.'">' .$row['qb_action'] . '</td>
	<td style="width: 120px;">' .$row['enqueue_datetime'] . '</td>
	<td>' .$row['qb_username'] . '</td>


	<td>' .$r['xfld'][0] . '</td>
	<td>' .$r['xfld'][1] . '</td>
	<td>' .$r['xfld'][2] . '</td>
	<td>' .$r['xfld'][3] . '</td>
	<td>' .$r['xfld'][4] . '</td>
	<td>' .$r['xfld'][5] . '</td>


	<td>' .$row['msg'] . '</td>


</tr>';
	
}


$pcnt = '<table style="margin-top: 45px;" class="TBL" width="100%" cellspacing="1">
<tr><th></th>
	<th style="width: 100px;">Action</th>
	<th style="width: 120px;">DateTime</th>
	<th style="width: 70px;">User</th>
	<th style="width: 120px;"></th>
	<th style="width: 300px;"></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th style="width: 30%;">Msg</th>
</tr>
'.$trow.'
</table>
';








function getques(){ global $dbh;










return;
}



mysqli_free_result($res);
mysqli_close($dbh);




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


exit;
?>