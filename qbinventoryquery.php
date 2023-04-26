<?php
require_once('inc/functions/general.php');
error_reporting(E_ALL | E_STRICT);

include_once 'inc/functions.php';
require_once 'wconfig.php';

$Queue = new QuickBooks_WebConnector_Queue($dsn, $qbwc_user);

	$newid = rand();
	$Queue->enqueue(QUICKBOOKS_QUERY_NONINVENTORYITEM, $newid);
	echo 'Query prepared. 
Run web Connector to update XML';

exit;
?>