<?php
require_once('inc/functions/general.php');
//error_reporting(E_ALL | E_STRICT);

//include_once 'inc/functions.php';
require_once 'wconfig.php';

$on = $_GET['onum'];
$st = $_GET['stt'];

$rec = mysqli_fetch_array(mysqli_query($dbh, "SELECT id FROM tw_close WHERE OrNum='$on'", MYSQLI_ASSOC));
if($rec){ mysqli_query($dbh, "UPDATE tw_close SET Status='$st' WHERE OrNum='$on'") 		or die (mysqli_error($dbh)); }
else 	{ mysqli_query($dbh, "INSERT INTO tw_close (OrNum, Status) VALUES('$on', '$st')") 	or die (mysqli_error($dbh)); }

echo $on.':::'.$st;
exit;
?>