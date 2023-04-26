<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

if (function_exists('date_default_timezone_set')){ date_default_timezone_set('America/New_York'); }

require_once 'wconfig.php';
//require_once 'inc/qbfunctions.php';

$user = $qbwc_user;
$pass = $qbwc_pass;


qr();


function qr(){ global $dbh;			
   mysqli_query($dbh, "UPDATE quickbooks_queue SET quickbooks_ticket_id=NULL, msg=NULL, dequeue_datetime = NULL, qb_status='q' WHERE quickbooks_queue_id='609'");
   mysqli_close($dbh);
	}
		
	echo 'good';	

?>