<?php
$page_description = '';
if($lghsh['Id'] == 0){
include_once 'inc/functions.php';
$ords = $msg = NULL;
error_reporting(E_ERROR | E_WARNING | E_PARSE);
//////////// Form handler ////////////////////////////////////////////////////
//			$newid = 1234567;
////////////////////////////////////////////////////////////////////
$bh = array();
$i2upd = $i2crt = 0;
///////////////////////////////////////////////////////////////////////////////////////////////////////
$brndsrch_optlist = array(
$skusrch = (isset($_POST['skusrch']) AND strlen($_POST['skusrch'])>=2) ? $_POST['skusrch']:NULL;
if(isset($_POST['prepare'])){ /////////////////////////// Prepare Query ////////////////////////////
	if(isset($_POST['item2send'])){
//*  /////     Disable adding query ////////////////////
			$tvrows.='
		$pcnt = '
<form action="#" method="post" name="pgrefv" style="margin: 0px;"><input type="hidden" name="fsynchro" value="1" />
<input type="text" name="rc_offset" value="'.$ofs.'" placeholder="Offset" style="width: 50px;" title="Records Offset" />
&nbsp; &nbsp;&nbsp; &nbsp;
'.$tvrows.'
</table>
	$prepare = NULL; //(isset($_POST['prepare']))?$_POST['prepare']:NULL;
	$asds = 'DESC';
		'meta_query'     => array(
	$items = get_posts( $args ); 
	foreach($items as $ii){
		$mh[1] = wc_get_product( $ii->ID );
//echo '<pre>';print_r($mh[2]);echo '</pre>';

//echo ($mh[2]['_price'][0] !=$mh[2]['_regular_price'][0]) ? $mh[0]->_sku.' - '.$mh[2]['_price'][0].'<br>':'';


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	update_post_meta( $ii->ID, '_sale_price', NULL);  /////////////////////     Clear Sale Prices   ////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



//echo $mh[3]['SPrice'] .'<br>';

//echo $mh[3]['IncAccRef'] . ' - ' . $mh[3]['ExpAccRef'] . '<br>';

//	$xl=strlen($mh[0]->_sku);
//	if($xl>$mxl){$mxl=$xl;}
		if($mh[3]['QBListID']!=NULL){ // Update existing sku
		if($mh['chbx'] AND !$dchk) { $dchk = 'checked'; }
		$trows .= rowtmpl8($mh);	
		

///////////////////////////  Variable product ////////////////////////////////////
		$vars = get_posts( $argsv ); 
			if($mh[3]['QBListID']!=NULL){ // Update existing sku 
			if($mh['chbx'] AND !$dchk) { $dchk = 'checked'; }
	$msg = ($prepare)?sizeof($_POST['item2send']) . ' Item(s) checked':$msg;
	$pcnt = '
<form action="#" method="post" name="pgref" style="margin: 0px;" 
<input type="hidden" name="fsynchro" value="1" />
<input type="text" name="rc_offset" value="'.$ofs.'" placeholder="Offset" style="width: 50px;" title="Records Offset" />
</div>
<!-- select name="itemsel">
<table class="TBL" width="100%" cellspacing="1">
<script>
function pclsnote(o){
function bgtrput(o){
function chkbxchng(o){
function slcustomer(o){
function load_console(url, wd, ht){ //alert(url); return false;
function ClsConsole(){
function PrpNxt(nx){
</script>
$pgtitle = '<h4>List</h4>';
$pgcontent = <<<EOD__
require_once('inc/_shell.php'); ///
function rowtmpl8($h){
$rpr = ($h[1]->regular_price) ? number_format($h[1]->regular_price, 2) :NULL;
$chk = ($h['chbx'])?'checked':'';
$len=strlen($h[0]->_sku);
$style0 = ($h[3]['QBListID']!=NULL) ? ((round($h['cost'], 2) != round($h[3]['VCost'], 2)) ? 'style="background: #fc0;"' : 'style="color: #090;"' ):'';
if( preg_match('/Sample Door/',$h[0]->post_title) OR 
return '
function getqbinventory($sku){ global $dbh;
/*
///////////////////////////////////////////////////////////////////////////////////////////////////
	$que = "INSERT INTO wc_item ( Sku, Title, VCost, RPrice, SPrice, IncAccRef, ExpAccRef ) 
///////////////////////////////////////////////////////////////////////////////////////////////////
	mysqli_query($dbh, $que) or die("Inserting to wc_item failed: " . mysqli_connect_error());
///////////////////////////////////////////////////////////////////////////////////////////////////
function getqbvendor($brand){
?>