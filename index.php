<?php session_start();

ini_set("display_errors", 1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

include_once('inc/_init.php');
include_once('inc/functions/general.php');

if($lghsh['Id']){
	$pgtitle = '<h4>Home</h4>';
	$pgcontent = <<<EOD__
<link href="files/css/Page.css" type="text/css" rel="stylesheet" />
<!-- Page content start -->
<h4>Homepage</h4>
<!-- $lghsh[Id] -->
<!-- Page content end -->
EOD__;
	}
else{
	require_once('inc/functions/authorization.php'); 
	$pgcontent = logform();
}

$page_title       = 'Unified Management System - Home';
$page_description = '';
$keywords         = '';
$head_ext         = '';
	
require_once('inc/_shell.php'); 
?>
