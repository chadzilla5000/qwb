<?php
	
	$hs=(isset($_GET['hs']) AND $_GET['hs'])?$_GET['hs']:'general';	
	$htmlnk = 'help/'.$hs.'.htm';
	require_once($htmlnk);

exit;

?>