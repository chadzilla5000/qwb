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

$pcnt = NULL;
$inc = 0;

$result = mysqli_query($dbh, "SELECT * FROM qw_customer ORDER BY id ASC");
while($rec = mysqli_fetch_assoc($result)){
	
	
	
	$pcnt .= ++$inc.'. '.$rec['id'].' - '.$rec['name'].' - '.$rec['phone'].' - '.$rec['email'].' - '.$rec['fname'].' - '.$rec['lname']. '<br>';
	}
	mysqli_free_result($result);






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