<?php
include_once('inc/functions/general.php');
include_once 'inc/functions.php';
error_reporting(E_ALL | E_STRICT);

include_once 'inc/functions.php';
require_once 'wconfig.php';

//echo '$' . 34;
	if(isset($_GET['lp'])){	
		echo '$' . round($_GET['lp'] * $_GET['f'], 2);
		
		}
exit;






?>