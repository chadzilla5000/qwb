<?php
require_once('inc/_init.php');
require_once('inc/functions/general.php');
error_reporting(E_ALL | E_STRICT);
if($lghsh['Id'] == 0){
	require_once('inc/functions/authorization.php'); 
	$pgcontent = logform(); 
	$page_title       = 'WC Terminal LogIn';
	$page_description = '';
	$keywords         = '';
	$head_ext         = '';
	require_once('inc/_shell.php'); ///
	exit;
	}
include_once 'inc/functions.php';
setlocale(LC_MONETARY, 'en_US');
$msg = NULL;
	require_once 'wconfig.php';
error_reporting(E_ERROR | E_WARNING | E_PARSE);

$cincnt=$user=NULL;

////////////////////////////////////////////////////////////////////
$oid = isset($_GET['oid'])?$_GET['oid']:NULL;
if(!$oid) { die('Missing or invalid Order ID'); }

$inc=0;

$tquery = "SELECT * FROM st_jobstat WHERE OID='$oid'"; 
$tresult = mysqli_query($dbh, $tquery) or die (mysqli_error($dbh));
	while ($tr = mysqli_fetch_array($tresult)) {
		if(!$user){ $user = get_usr_name($tr['User']); }
		$tstart = date("Y/m/d H:i:s", $tr['TimeStart']/1000);
		$tend   = date("Y/m/d H:i:s", $tr['TimeEnd']/1000);
		$tdiff  = calctdiff($tr['TimeStart'], $tr['TimeEnd']);
		$days = ($tdiff['day'])?$tdiff['day']:'';
		$tb.='
<tr><td style="color: #ccc;">'.++$inc.'</td>
	<td>'.$tr['Qtty'].'</td>
	<td>'.$tr['Sku'].'</td>
	<td>'.$tstart.'</td>
	<td>'.$tend.'</td>
	<td>'.$days.'&nbsp;'.sprintf("%02d", $tdiff['hr']).':'.sprintf("%02d", $tdiff['min']).':'.sprintf("%02d", $tdiff['sec']).'</td>
	<td>'.$tr['Note'].'</td>
</tr>';	
		}  // Cycle end  ////////////////////////////////////////////////////////////////////

$cincnt='<div style="height: 7px;"></div>
<table class="TBL" cellpadding="0" cellspacing="1">
<tr><th colspan="7"><div style="float: left; padding-left: 20px;">Order - '.$oid.'</div><div style="float: right; padding-right: 20px;">User: '.$user.'</div></th>
</tr>
<tr><th style="width: 20px;"></th>
	<th style="width: 30px;">Q-ty</th>
	<th style="width: 150px;">Sku</th>
	<th style="width: 120px;">Time Start</th>
	<th style="width: 120px;">Time End</th>
	<th style="width: 70px;">T-Exp</th>
	<th>Notes</th>
</tr>
'.$tb.'
</table>
';

$ccontent = <<<EOCD__
<!-- Page content start -->
$cincnt
<!-- Page content end -->
EOCD__;
require_once('inc/_Cshell.php'); ///
//////////////////////////////////
/////////////////////////////////
////////////////////////////////
///////////////////////////////

exit;


function calctdiff($tStart, $tEnd){
	$tr=array();
	$sec = round(($tEnd-$tStart)/1000);
	$tr['sec'] = $sec%60;
	$min = floor($sec/60);
	$tr['min'] = $min%60;
	$hr  = floor($min/60);
	$tr['hr'] = $hr%24;
	$tr['day'] = floor($hr/24);
	return $tr;
}

function get_usr_name($uid){ global $dbh;
	// $q = "SELECT Name FROM qb_user WHERE Id='$uid'";
	// $result = mysqli_query($dbh, $q);



	// if($result->num_rows){ return 1; }

	$rn = mysqli_fetch_row(mysqli_query($dbh, "SELECT Name FROM qb_user WHERE Id='$uid'"));
	if($rn){ return $rn[0]; }
	return NULL;
}












?>