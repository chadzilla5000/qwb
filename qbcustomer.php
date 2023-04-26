<?php
include_once('inc/_init.php');
include_once('inc/functions/general.php');
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

$ords = $msg = NULL;
include_once 'wconfig.php';

$trows = '';
$updb  = 0;

if (!file_exists("data/sinchro/qbcustomers.xml")) { die("XML file not found"); }

if(isset($_POST['updb'])){
	if ($dbh){ 
		mysqli_query($dbh, "TRUNCATE TABLE qw_customer");
		$updb = 1;
		}
	else{ die("Connection failed: " . mysqli_connect_error()); }
	}

$xml = simplexml_load_file("data/sinchro/qbcustomers.xml");
$tm = 0;

foreach($xml->QBXMLMsgsRs->CustomerQueryRs->CustomerRet as $d){ $tm++;

	$tcreat = substr($d->TimeCreated,  0, 19);
	$tmodif = substr($d->TimeModified, 0, 19);

	if($updb){
		
		$nm = preg_replace('/\W/','',$d->FullName);
		$fn = preg_replace('/\W/','',$d->FirstName);
		$ln = preg_replace('/\W/','',$d->LastName);
		$ph = trtphonum($d->Phone);
		
		$que = "INSERT INTO	qw_customer	( name, fname, lname, phone, email, quickbooks_listid, quickbooks_editsequence
		) VALUES (
			'" . mysqli_escape_string($dbh, $nm ) . "',
			'" . mysqli_escape_string($dbh, $fn ) . "',
			'" . mysqli_escape_string($dbh, $ln ) . "',
			'" . mysqli_escape_string($dbh, $ph ) . "',
			'" . mysqli_escape_string($dbh, $d->Email ) . "',
			'" . mysqli_escape_string($dbh, $d->ListID ) . "',
			'" . mysqli_escape_string($dbh, $d->EditSequence ) . "'
			)";
		mysqli_query($dbh, $que) or die("DB update failed: " . mysqli_connect_error());
		}

	$trows .= '
<tr><td>' . $tm.' . </td>
	<td>' . $d->ListID . '</td>
	<td>' . $d->FullName . '</td>
	
	<td>' . $d->LastName . ', ' . $d->FirstName . '</td>
	<td>' . $d->Phone . '</td>
	<td>' . $d->Email . '</td>
	
	<td>' . $tcreat . '</td>
	<td>' . $tmodif . '</td>
</tr>';
	}

$pcnt = '
<form method="post" action="qbcustomer.php" name="DForm">
<div style="margin-top: 25px;">
<div style="float: left;">
&nbsp; &nbsp;<input type="button" name="xmlreq" value="Request XML" style="padding: 1px 5px;" OnClick="return requestqbcustomersxml();" /> &nbsp; &nbsp;
</div>
<div style="float: right;">
&nbsp; &nbsp;<input type="Submit" name="updb" value="Update DB" style="padding: 1px 5px;" /> &nbsp; &nbsp;
</div>
<div style="clear: both;"></div>
</div>
<table class="TBL" width="100%" cellspacing="1">
<tr><th colspan="2"></th>
	<th style="font-size: 13px;" colspan="4">QB Customer</th>
	<th style="font-size: 13px;" colspan="2">Date / Time</th>
</tr>
<tr><th style="width: 50px;"></th>
	<th style="width: 120px;">List ID</th>
	<th style="width: 250px;">Company</th>
	<th style="width: 200px;">Name</th>
	<th style="width: 150px;">Phone</th>
	<th style="width: 70px;">Email</th>
	<th style="width: 120px;">DT created</th>
	<th style="width: 120px;">DT modified</th>
</tr>
'.$trows.'
</table>
</form>
<script>

function requestqbcustomersxml(){
var url = "qbcustomerquery.php";
var prt = perform(url); 
	if(prt){alert(prt);}
//	else{window.location.reload();}
	
return false;
}

</script>

';

$pgtitle = '<h4>List</h4>';
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


?>