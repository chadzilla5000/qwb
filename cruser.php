<?php
require_once('inc/_init.php');
require_once('inc/functions/general.php');
error_reporting(E_ALL | E_STRICT);

	$pgtitle = '<h4>Create User</h4>';
	$page_description = '';
	$keywords         = '';
	$head_ext         = '';
if($lghsh['Id'] == 0){
	require_once('inc/functions/authorization.php'); 
	$pgcontent = logform(); 
	$page_title       = 'WC Terminal LogIn';
	require_once('inc/_shell.php'); ///
	exit;
	}

include_once 'inc/functions.php';
setlocale(LC_MONETARY, 'en_US');

$msg = NULL;
require_once 'wconfig.php';

$user = 'qb_user14';
$pass = 'eMa7--CFv4';
echo QuickBooks_Utilities::createUser($dsn, $user, $pass);
//	QuickBooks_Utilities::initialize($dsn);





$pgcontent = <<<EOD__
<!-- Page content start -->
$pcnt
<!-- Page content end -->
EOD__;

//	}
	
require_once('inc/_shell.php'); ///
//////////////////////////////////
/////////////////////////////////
////////////////////////////////
///////////////////////////////
?>