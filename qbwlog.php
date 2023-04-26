<?php
require_once('inc/_init.php');
require_once('inc/functions/general.php');
error_reporting(E_ALL | E_STRICT);

$page_title       = 'Unified WC Terminal';
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


$ords = NULL;

////////////////////////////////////////////////////////////////////
$lmt = (isset($_POST['rowsonpage']))?$_POST['rowsonpage']:1000;
$trows = '';
$t = 0; $vt = 0;



$rs = mysqli_query($dbh, "SELECT * FROM quickbooks_log ORDER BY log_datetime DESC LIMIT ".$lmt);
//	$rs2 = mysqli_query($dbh, "SELECT * FROM qw_customer WHERE email='$eml'");
//	$exiinqb = mysqli_fetch_assoc($rs2)['id'];

while( $r=mysqli_fetch_assoc($rs)){
	$trows .= '<tr><td>'.++$t.'. </td><td style="cursor: pointer;" OnMouseOver="pupnote(this);" OnClick="pinnote(this);" OnMouseOut="pclsnote(this);" id="cnotd_'.$r['quickbooks_log_id'].'">'.$r['log_datetime'].'
	<div style="display: none; position: absolute; background: #ff0; font: normal 15px \'Arial\'; margin-left: 150px; padding: 15px; max-width: 750px; border: solid 1px #999; border-radius: 5px;" id="cnotediv_'.$r['quickbooks_log_id'].'">'.$r['msg'].'</div>
	</td>
	<td>'.$r['quickbooks_ticket_id'].'</td>';
	
	$trows .= '</tr>';
	
}



$pcnt = '
<form action="#" method="post" name="qform" style="margin-top: 20px;">
<input type="hidden" name="fsbm" value="1" />
<!-- select name="itemsel">
	<option value="0">With selected</option>
	<option value="1">Send Customer(s)</option>
	<option value="2" disabled>Send Order(s)</option>
	<option value="3" disabled>Send Payment(s)</option>
</select -->
<input type="submit" name="button" value="Send" style="float: right; width: 70px; margin: 0px 9px;" />
<table class="TBL" width="100%" cellspacing="1">
<tr><th></th>
	<th style="font-size: 13px;" colspan="2"></th>
</tr>
<tr><th></th>
	<th style="width: 120px;">Log date</th>
	<th>Ticket</th>
</tr>
'.$trows.'
<tr><th></th>
	<th>Log date</th>
	<th>Ticket</th>
</tr>
</table>
</form>

<script>
var statv = 0;

function pupnote(o){
	if(statv){return;}
var id = o.id.replace(/cnotd_/, "cnotediv_");
	document.getElementById(id).style.display = "block";
	return;
	}

function pclsnote(o){
	if(statv){return;}
var id = o.id.replace(/cnotd_/, "cnotediv_");
	document.getElementById(id).style.display = "none";
	return;
	}

function pinnote(o){
var id = o.id.replace(/cnotd_/, "cnotediv_");
if(	statv==0){
	document.getElementById(id).style.display = "block";
	statv = 1;
}
else{
	document.getElementById(id).style.display = "none";
	statv = 0;
}

	return;
	}

function divcls(o){
	
//	alert(o.id);
//var id = o.id.replace(/cnotd_/, "cnotediv_");
//	o.style.display = "none";
//	statv = 0;
var	id = o.id;
	document.getElementById(id).style.display = "none";
	alert(id);
	return;
	}

</script>

';


$pgtitle = '<h4>Customer list</h4>';
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