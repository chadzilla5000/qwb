<?phpinclude_once('inc/_init.php');include_once('inc/functions/general.php');error_reporting(E_ALL | E_STRICT);
$page_description = '';$keywords         = '';$head_ext         = '';$page_title       = 'Unified WC Terminal';
if($lghsh['Id'] == 0){	require_once('inc/functions/authorization.php'); 	$pgcontent = logform(); 	$page_title = 'WC Terminal LogIn';	require_once('inc/_shell.php'); ///	exit;	}
include_once 'inc/functions.php';setlocale(LC_MONETARY, 'en_US');
$ords = $msg = NULL;include_once 'wconfig.php';
error_reporting(E_ERROR | E_WARNING | E_PARSE);ini_set('memory_limit', '1024M');set_time_limit ( 300 );
//////////// Form handler ////////////////////////////////////////////////////$itarr = (isset($_POST['item2send'])) ? $_POST['item2send'] : -1 ;$Queue = (isset($_POST['fsynchro'])) ? new QuickBooks_WebConnector_Queue($dsn, $qbwc_user) : NULL ; 
//			$newid = 1234567;//			$Queue->enqueue(QUICKBOOKS_ADD_DISCOUNTITEM, $newid);
////////////////////////////////////////////////////////////////////$ofs = (isset($_POST['rc_offset'])) ? $_POST['rc_offset'] : 0;$lmt = (isset($_POST['rc_limit'])) ? $_POST['rc_limit'] : -1; //////////////////////////////////////////////////////
$bh = array();$bh['incr']=0;
$i2upd = $i2crt = 0;$dto = (isset($_POST['dateto'])) ? $_POST['dateto'] : date('Y-m-d', time());$dfr = (isset($_POST['datefr'])) ? $_POST['datefr'] : date('Y-m-d', strtotime($dto. ' - 0 days'));
///////////////////////////////////////////////////////////////////////////////////////////////////////$brand = (isset($_POST['brndsrch']) AND $_POST['brndsrch']) ? $_POST['brndsrch']:NULL;$taxqu = ($brand) ? array(	array(		'taxonomy' => 'product_tag',		'field' => 'slug',		'terms' => $brand,		'operator' => 'IN',		)	) : NULL;
$brndsrch_optlist = array(	'<option value="aurafina">Aurafina</option>',	'<option value="cnc">CNC</option>',	'<option value="cubitac">Cubitac</option>',	'<option value="feather-lodge">Feather Lodge</option>',	'<option value="forevermark">Forevermark</option>',	'<option value="ghi">GHI</option>',	'<option value="msi">MSI</option>',	'<option value="us-cabinet-depot">USCD</option>',	'<option value="wlf">Wolf</option>',	);
$skusrch = (isset($_POST['skusrch']) AND strlen($_POST['skusrch'])>=2) ? $_POST['skusrch']:NULL;$brndsrch = (isset($_POST['brndsrch']))?$_POST['brndsrch']:NULL;//////////////////////////////////////////////////////////////////////////////////////////////////////
if(isset($_POST['prepare'])){ /////////////////////////// Prepare Query //////////////////////////////if(isset($_GET['qp'])){ /////////////////////////// Prepare Query ////////////////////////////
	if(isset($_POST['item2send'])){		$tvrows=NULL;		foreach ($_POST['item2send'] as $idp){			$bh['id'] = $idp;			$bh['sku'] = get_post_meta( $idp, '_sku', true );			++$bh['incr'];			$bh[1] = wc_get_product( $bh['id'] );			$bh[2] = get_post_meta ( $bh['id'] );			$bh[3] = getqbinventory( preg_replace('/[^A-Za-z0-9\-\/\(\)\.\s]/','',$bh['sku']) );			$bh['cost'] = calcost_vv($bh);			$bh['IncAccRef'] = 'Merchandise Sales';			$bh['ExpAccRef'] = 'Cost of Goods Sold';$bh[1]->price = ($bh[1]->price) ? $bh[1]->price : 0;			$newid = NULL;
//*  /////     Disable adding query ////////////////////			if($bh[3]['QBListID']){ $i2upd++;				 $newid = insItomod($bh);				 if($newid){ 					if($bh[3]['Inv'])	{ $Queue->enqueue(QUICKBOOKS_MOD_INVENTORYITEM, $newid); }					else				{ $Queue->enqueue(QUICKBOOKS_MOD_NONINVENTORYITEM, $newid); }					}				}			else{ $i2crt++;				 $newid = insItoadd($bh);				 if($newid){  					if($bh[3]['Inv'])	{ $Queue->enqueue(QUICKBOOKS_ADD_INVENTORYITEM, $newid); }					else				{ $Queue->enqueue(QUICKBOOKS_ADD_NONINVENTORYITEM, $newid); }					}				}///////////////////////////////////////////////////// */
			$tvrows.='<tr><td>'.$bh['incr'].'. </td>	<td>'.$bh['sku'].'</td>	<td>'.$bh[1]->name.'</td>	<td>'.number_format($bh['cost'],2).'</td>	<td>'.number_format($bh[1]->price,2).'</td>	<td>'.$bh['IncAccRef'].'</td>	<td>'.$bh['ExpAccRef'].'</td>	<td>'.$newid.'</td>	<td>'.$bh[3]['QBListID'].'</td>	<td>'.$bh[3]['VCost'].'</td>	<td>'.$bh[3]['SPrice'].'</td></tr>';			}
		$pcnt = '<div style="float: left; margin-top: 30px;">
<form action="#" method="post" name="pgrefv" style="margin: 0px;"><input type="hidden" name="fsynchro" value="1" /><input type="button" name="brfrsh" value="Refresh" OnClick="this.form.submit();" style="width: 70px; margin: 0px 9px;" />List dated From - <input type="date" name="datefr" value="'.$dfr.'" />&nbsp; &nbsp;To - <input type="date" name="dateto" value="'.$dto.'" />&nbsp; &nbsp;<input type="submit" name="submitdt" value="List items" style="padding: 1px 5px;" />&nbsp; &nbsp;<input type="text" name="skusrch" value="'.$skusrch.'" placeholder="Search Sku" style="width: 80px;" />&nbsp; &nbsp;<select name="brndsrch"><option value="">Brand</option>'.getopts($brndsrch_optlist, $_POST['brndsrch']).'</select>&nbsp; &nbsp;
<input type="text" name="rc_offset" value="'.$ofs.'" placeholder="Offset" style="width: 50px;" title="Records Offset" /><input type="text" name="rc_limit" value="'.$lmt.'" placeholder="Limit" style="width: 50px;" title="Limit Records" />&nbsp; &nbsp;
&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; items - (<b>'.$bh['incr'].'</b>)&nbsp; <b style="color: #f00;">'.$i2crt.'</b> - to send,&nbsp;&nbsp; <b style="color: #f90;">'.$i2upd.'</b> - to update&nbsp;</div><table class="TBL" width="100%" cellspacing="1"><tr><th></th>	<th style="width: 180px;">Sku</th>	<th style="width: 500px;">Title</th>	<th style="width: 70px;">Purch.</th>	<th style="width: 70px;">Sale</th>	<th style="width: 120px;">In Account</th>	<th style="width: 120px;">Exp Account</th>	<th style="width: 70px;">Prepared</th>	<th style="width: 180px;">QB List ID</th>	<th style="width: 70px;">Cost</th>	<th style="width: 70px;">Sale</th></tr>
'.$tvrows.'
</table>';		} else {		$pcnt = '<div style="margin-top: 30px; text-align: center; color: #f00;"><b>None of items were checked!</b></div>';		}	} else { /////////////////////////////// List items //////////////////////////////////////////////////
	$prepare = NULL; //(isset($_POST['prepare']))?$_POST['prepare']:NULL;
	$asds = 'DESC';	$trows = '';	$args = array(		'posts_per_page' => $lmt,		'offset'         => $ofs,		'post_status'    => 'publish',		'post_type'      => 'product',		'orderby'        => 'date',		'order'          => $asds,
		'meta_query'     => array(			'sku_clause'  => array(				'key'     => '_sku',				'value'	  => $skusrch,				'compare' => 'LIKE',				)			),		'tax_query'      => $taxqu,		'date_query'     => array(			'column'      => 'post_modified',			'before'      => $dto.' + 1 days',			'after'       => $dfr.' + 0 days',			'type'        => 'date'			)		);
	$items = get_posts( $args ); 	$mh = NULL;	$mh['cost'] = 0;	$mh['incr'] = 0;	$dchk = NULL;	$mxl = $xl = 0;
	foreach($items as $ii){		$mh['chbx'] = 0;		++$mh['incr'];		$mh[0] = $ii;
		$mh[1] = wc_get_product( $ii->ID );		$mh[2] = get_post_meta($ii->ID);		$mh[3] = getqbinventory( preg_replace('/[^A-Za-z0-9\-\/\(\)\.\s]/','',$ii->_sku) );		$mh['cost'] = calcost($mh);		$mh['IncAccRef'] = NULL;		$mh['ExpAccRef'] = NULL;
//echo '<pre>';print_r($mh[2]);echo '</pre>';

//echo ($mh[2]['_price'][0] !=$mh[2]['_regular_price'][0]) ? $mh[0]->_sku.' - '.$mh[2]['_price'][0].'<br>':'';


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	update_post_meta( $ii->ID, '_sale_price', NULL);  /////////////////////     Clear Sale Prices   ////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



//echo $mh[3]['SPrice'] .'<br>';

//echo $mh[3]['IncAccRef'] . ' - ' . $mh[3]['ExpAccRef'] . '<br>';

//	$xl=strlen($mh[0]->_sku);
//	if($xl>$mxl){$mxl=$xl;}
		if($mh[3]['QBListID']!=NULL){ // Update existing sku			if((round($mh[1]->price, 2) <> round($mh[3]['SPrice'], 2)) 			  OR (round($mh['cost'], 2) <> round($mh[3]['VCost'], 2))) 				{ $i2upd++; $mh['chbx'] = 1; 				// if($prepare AND in_array($mh[0]->_sku, $_POST['item2send'])){ 					// $newid = insertItem2mod($mh);					// if($newid){ $Queue->enqueue(QUICKBOOKS_MOD_NONINVENTORYITEM, $newid); }					// } 				} //else { continue; }			}		else {          // Add sku to QB                                                                          	//		if(round($mh[1]->price, 2)>0){ 	//			if(1){ 				$i2crt++; $mh['chbx'] = 1;				// if($prepare AND in_array($mh[0]->_sku, $_POST['item2send'])){ 					// $mh['IncAccRef'] = 'Merchandise Sales';					// $mh['ExpAccRef'] = 'Cost of Goods Sold';					// $newid = insertItem2add($mh);					// if($newid){ $Queue->enqueue(QUICKBOOKS_ADD_NONINVENTORYITEM, $newid); }					// } 	//			}// else{ continue; }			}
		if($mh['chbx'] AND !$dchk) { $dchk = 'checked'; }
		$trows .= rowtmpl8($mh);	
		

///////////////////////////  Variable product ////////////////////////////////////		$argsv = array(			'post_parent' => $ii->ID,			'post_type'   => 'product_variation',			'numberposts' => -1,			); 
		$vars = get_posts( $argsv ); 		foreach($vars as $iv){ ++$mh['incr'];			$mh['chbx'] = 0;			$mh[0] = $iv;			$mh[1] = wc_get_product( $iv->ID );			$mh[2] = get_post_meta($iv->ID);			$mh[3] = getqbinventory( preg_replace('/[^A-Za-z0-9\-\/\(\)\s]/','',$mh[0]->_sku) );			$mh['cost'] = calcost($mh);			$mh['IncAccRef'] = NULL;			$mh['ExpAccRef'] = NULL;
			if($mh[3]['QBListID']!=NULL){ // Update existing sku 				if((round($mh[1]->price, 2) <> round($mh[3]['SPrice'], 2)) 				  OR (round($mh['cost'], 2) <> round($mh[3]['VCost'], 2))				) { $i2upd++; $mh['chbx'] = 1; 					// if($prepare and in_array($mh[0]->_sku, $_POST['item2send'])){  						// $newid = insertItem2mod($mh);						// if($newid){ $Queue->enqueue(QUICKBOOKS_MOD_NONINVENTORYITEM, $newid); }						// }					} //else {continue;}				}			else{        // Add sku to QB          	//			if(round($mh[1]->price, 2)>0){ 		//		if(1){ 					$i2crt++; $mh['chbx'] = 1;					// if($prepare and in_array($mh[0]->_sku, $_POST['item2send'])){ 						// $mh['IncAccRef'] = 'Merchandise Sales';						// $mh['ExpAccRef'] = 'Cost of Goods Sold';						// $newid = insertItem2add($mh);						// if($newid){ $Queue->enqueue(QUICKBOOKS_ADD_NONINVENTORYITEM, $newid); }						// }		//			}				}
			if($mh['chbx'] AND !$dchk) { $dchk = 'checked'; }			$trows .= rowtmpl8($mh); 			}		}
	$msg = ($prepare)?sizeof($_POST['item2send']) . ' Item(s) checked':$msg;	$nxofs = ($lmt>0)?$ofs + $lmt:0;
	$pcnt = '<div style="float: left; margin-top: 30px;">
<form action="#" method="post" name="pgref" style="margin: 0px;" Dtarget="_blank" DOnSubmit="return load_console(\'?qp=prepare\', \'80%\', \'80%\');" >
<input type="hidden" name="fsynchro" value="1" /><input type="button" name="brfrsh" value="Refresh" OnClick="this.form.submit();" style="width: 70px; margin: 0px 9px;" />List dated From - <input type="date" name="datefr" value="'.$dfr.'" OnChange="load_arrsz();" />&nbsp; &nbsp;To - <input type="date" name="dateto" value="'.$dto.'" OnChange="load_arrsz();" />&nbsp; &nbsp;<input type="submit" name="submitdt" value="List items" style="padding: 1px 5px;" />&nbsp; &nbsp;<input type="text" name="skusrch" value="'.$skusrch.'" placeholder="Search Sku" style="width: 80px;" OnChange="load_arrsz();" />&nbsp; &nbsp;<select name="brndsrch" OnChange="load_arrsz();"><option value="">Brand</option>'.getopts($brndsrch_optlist, $brndsrch).'</select>&nbsp; &nbsp;
<input type="text" name="rc_offset" value="'.$ofs.'" placeholder="Offset" style="width: 50px;" title="Records Offset" /><input type="text" name="rc_limit" value="'.$lmt.'" placeholder="Limit" style="width: 50px;" title="Limit Records" /><input type="submit" name="submitnxt" value=">>" style="padding: 1px 5px;" OnClick="PrpNxt('.$nxofs.');" title="Next from row: '.$nxofs.'" />&nbsp; &nbsp;
</div><div style="float: right; margin-top: 30px;"> items - (<b>'.$mh['incr'].'</b>)&nbsp; <b style="color: #f00;">'.$i2crt.'</b> - to send,&nbsp;&nbsp; <b style="color: #f90;">'.$i2upd.'</b> - to update&nbsp;<input type="submit" name="prepare" value="Prepare" style="padding: 1px 5px;" />&nbsp; &nbsp;</div><div style="clear: both;"></div>
<!-- select name="itemsel">	<option value="0">With selected</option>	<option value="1">Send Customer(s)</option>	<option value="2" disabled>Send Order(s)</option>	<option value="3" disabled>Send Payment(s)</option></select -->
<table class="TBL" width="100%" cellspacing="1"><tr><th></th>	<th style="font-size: 13px;" colspan="3"><div id="itqttydiv" style="float: left; font: normal 11px \'Arial\'; color: #ff0;"> - -</div>Product</th>	<th style="font-size: 13px;" colspan="2">Date / Time</th>	<th style="font-size: 13px;" colspan="3">Price</th>	<th style="font-size: 13px;" colspan="3">Quickbooks</th></tr><tr><th></th><th style="width: 20px;"><input type="checkbox" name="item2sendchkcnt" title="Check/Empty all" value="1" OnChange="chkbxchng(this);" '.$dchk.' /></th>	<th style="width: 180px;">Sku</th>	<th style="width: 500px;">Title</th>	<th style="width: 120px;">Posted</th>	<th style="width: 120px;">Modified</th>	<th style="width: 70px;">Purch.</th>	<th style="width: 70px;">Reg.</th>	<th style="width: 70px;">Sale</th>	<th style="width: 150px;">List ID</th>	<th style="width: 70px;">Cost</th>	<th style="width: 70px;">Sale</th></tr>'.$trows.'</table></form>
<script>function pupnote(o){var id = o.id.replace(/cnotd_/, "cnotediv_");	document.getElementById(id).style.display = "block";	return;	}
function pclsnote(o){var id = o.id.replace(/cnotd_/, "cnotediv_");	document.getElementById(id).style.display = "none";	return;	}
function bgtrput(o){//	document.getElementById(o.id).style.backgroundColor = "rgba(255, 255, 255, 0.7)";	o.style.backgroundColor = "rgba(255, 255, 255, 0.7)";	o.style.boxShadow = "5px 5px 5px #555";	o.style.border = "solid 1px #555";	return;}function bgtrrmv(o){//	alert(o.id);//	document.getElementById(o.id).style.backgroundColor = "rgba(255, 255, 255, 1)";	o.style.backgroundColor = "rgba(255, 255, 255, 1)";	o.style.boxShadow = "10px 15px 15px #555";	o.style.border = "solid 1px #000";	return;}
function chkbxchng(o){	var items = document.getElementsByName("item2send[]");	if(o.checked) {        for (var i = 0; i < items.length; i++) {            if (items[i].type == "checkbox" && items[i].disabled == false) { items[i].checked = true; }			}		}	else { 		for (var i = 0; i < items.length; i++) {			if (items[i].type == "checkbox" && items[i].disabled == false) { items[i].checked = false; }			}		}return;}
function slcustomer(o){if(o.value.length >= 3){var oid = o.id.replace("ptxbx_", "");var url = "dispcns.php?sbj=pmt&oid="+oid;	load_console(url, \'40%\', \'90%\');	}	return false;}
function load_console(url, wd, ht){ //alert(url); return false;var	obj = document.getElementById("CConsole");var	pln = document.getElementById("consolink");	pln.href = url;	obj.style.display = "block";	obj.style.width = wd;	obj.style.height = ht;	query_srv(url, \'InConsole\');	return false;}
function ClsConsole(){	document.getElementById("CConsole").style.display = "none";	return;}
function PrpNxt(nx){	if(pgref.rc_limit.value > 0){ pgref.rc_offset.value=nx; }	return;}function load_arrsz(){ //alert(url); return false;var dfr = pgref.datefr.value;var dto = pgref.dateto.value;var sks = pgref.skusrch.value;var brs = pgref.brndsrch.value;var url = "disparrsz.php?brs="+brs+"&sks="+sks+"&dfr="+dfr+"&dto="+dto;	query_srv(url, \'itqttydiv\');	return false;}
</script>';	}
$pgtitle = '<h4>List</h4>';$hsct = 'snchsku';
$pgcontent = <<<EOD__<!-- Page content start -->$pcnt<!-- Page content end -->EOD__;
require_once('inc/_shell.php'); /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function rowtmpl8($h){
$rpr = ($h[1]->regular_price) ? number_format($h[1]->regular_price, 2) :NULL;$spr = ($h[1]->price) ? number_format($h[1]->price, 2) :NULL;$cpr = ($h['cost']) ? number_format($h['cost'], 2) :NULL;
$chk = ($h['chbx'])?'checked':'';$dsb = ($h['chbx'])?'':'disabled';
$len=strlen($h[0]->_sku);$txtclr=($len>31)?'color: #f00;':'';
$style0 = ($h[3]['QBListID']!=NULL) ? ((round($h['cost'], 2) != round($h[3]['VCost'], 2)) ? 'style="background: #fc0;"' : 'style="color: #090;"' ):'';$style1 = ($h[3]['QBListID']!=NULL) ? ((round($h[1]->price, 2) != round($h[3]['SPrice'], 2)) ? 'style="background: #fc0;"' : 'style="color: #090;"' ):'';$style3 = (!(round($h[1]->price, 2) > 0)) ? 'style="background: #ffc;"' : '';
if( preg_match('/Sample Door/',$h[0]->post_title) OR 	preg_match('/Mini Front/',$h[0]->post_title)){$chk = '';}
return '<tr><td>'.$h['incr'].'. </td>	<td style="text-align: center;"><input type="checkbox" name="item2send[]" title="Send item to QB" value="'.$h[0]->ID.'" '.$dsb.' '.$chk.' /></td>	<td><!-- a href="editsku.php?cmd=edt&sku='.$h[0]->_sku.'" OnClick="return load_console(this.href, \'80%\', \'80%\');" title="Edit this item '.$len.'" style="'.$txtclr.'" --><span title="Edit this item '.$len.'" style="'.$txtclr.'">'.$h[0]->_sku.'</span><!-- /a --></td>	<td><a href="'.$h[0]->guid.'" target="_blank" title="View on WC Website">'.$h[0]->post_title.'</a></td>	<td>'.$h[0]->post_date.'</td>	<td>'.$h[0]->post_modified.'</td>	<td>'.$cpr.'</td>	<td>'.$rpr.'</td>	<td '.$style3.'>'.$spr.'</td>	<td>'.$h[3]['QBListID'].'</td>	<td '.$style0.'>'.$h[3]['VCost'].'</td>	<td '.$style1.'>'.$h[3]['SPrice'].'</td></tr>';}
function getqbinventory($sku){ global $dbh;	$arr=array();	$row = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM qw_item WHERE Sku = '$sku'", MYSQLI_ASSOC));//echo $row['Sku'].' - '.$sku.'<br>';	if($row['id']){ $arr=$row; }	return $arr;}
/*$carr_fm   = array(875, 831, 829, 830, 832, 838, 834, 828, 823, 836, 835, 827, 824, 822, 821, 102, 111, 117, 172, 82, 30, 756, 928, 474, 484, 130, 138, 162, 925, 766, 188);$carr_cub  = array(877, 815, 810, 809, 814, 812, 808, 802, 807, 801, 813, 806, 820, 818, 819, 817, 805, 804, 803, 585, 586, 587, 588, 589, 590, 591, 509, 593, 594, 595, 596, 597, 599, 600, 603, 604, 605);$carr_ghi  = array(753, 418, 423, 434, 438, 442, 449, 453, 459, 306, 211, 288, 220, 314, 400, 235, 249, 264, 292);$carr_fldg = array(333, 345, 326);$carr_uscd = array(840, 841, 774, 775, 776, 842, 873, 843, 845, 846, 777, 778, 779, 847, 848, 868);$carr_msi  = array(908, 916, 920, 892, 907, 893, 902, 899, 905, 904, 900, 909, 910, 911, 912, 913, 915, 906, 901, 917, 918, 919);*/
///////////////////////////////////////////////////////////////////////////////////////////////////function insertItem2add___________($h){ global $dbh;	//echo date('Y-m-d H:m:s', time()); return NULL;	$title = preg_replace('/\&/', 'and', $h[0]->post_title);	$title = preg_replace('/"/', '', $title);
	$que = "INSERT INTO wc_item ( Sku, Title, VCost, RPrice, SPrice, IncAccRef, ExpAccRef ) 		VALUES (			'" . mysqli_escape_string($dbh, $h[0]->_sku ) . "',			'" . mysqli_escape_string($dbh, $title ) . "',			'" . mysqli_escape_string($dbh, $h['cost'] ) . "',			'" . mysqli_escape_string($dbh, $h[1]->regular_price ) . "',			'" . mysqli_escape_string($dbh, $h[1]->price ) . "',			'" . mysqli_escape_string($dbh, $h['IncAccRef'] ) . "',			'" . mysqli_escape_string($dbh, $h['ExpAccRef'] ) . "'			)";	mysqli_query($dbh, $que);	return mysqli_insert_id($dbh);}///////////////////////////////////////////////////////////////////////////////////////////////////function insertItem2mod________($h){ global $dbh;	//echo date('Y-m-d H:m:s', time()); return NULL;	$title = preg_replace('/\&/', 'and', $h[0]->post_title);	$title = preg_replace('/"/', '', $title);	$incacc = ($h['IncAccRef']) ? $h['IncAccRef'] : $h[3]['IncAccRef'];	$expacc = ($h['ExpAccRef']) ? $h['ExpAccRef'] : $h[3]['ExpAccRef'];	$que = "INSERT INTO wc_item ( QBListID, EdSeq, Sku, Title, VCost, RPrice, SPrice, IncAccRef, ExpAccRef ) 		VALUES (			'" . mysqli_escape_string($dbh, $h[3]['QBListID'] ) . "',			'" . mysqli_escape_string($dbh, $h[3]['EdSeq'] ) . "',			'" . mysqli_escape_string($dbh, $h[0]->_sku ) . "',			'" . mysqli_escape_string($dbh, $title ) . "',			'" . mysqli_escape_string($dbh, $h['cost'] ) . "',			'" . mysqli_escape_string($dbh, $h[1]->regular_price ) . "',			'" . mysqli_escape_string($dbh, $h[1]->price ) . "',			'" . mysqli_escape_string($dbh, $incacc ) . "',			'" . mysqli_escape_string($dbh, $expacc ) . "'			)";	mysqli_query($dbh, $que) or die("Inserting to wc_item failed: " . mysqli_connect_error());	return mysqli_insert_id($dbh);}
///////////////////////////////////////////////////////////////////////////////////////////////////function insItoadd($h){ global $dbh;	//echo date('Y-m-d H:m:s', time()); return NULL;	$title = preg_replace('/\&/', 'and', $h[1]->name);	$title = preg_replace('/"/', '', $title);	$vendor = getqbvendor(brand_ident($h['id']));	$rprc  = ($h[1]->regular_price) ? $h[1]->regular_price : 0;	$prc   = ($h[1]->price) ? $h[1]->price : 0;	$cost  = ($h['cost']) ? $h['cost'] : 0;	$que = "INSERT INTO wc_item ( Sku, Title, PrefVnd, VCost, RPrice, SPrice, IncAccRef, ExpAccRef ) 		VALUES (			'" . mysqli_escape_string($dbh, $h['sku'] ) . "',			'" . mysqli_escape_string($dbh, $title ) . "',			'" . mysqli_escape_string($dbh, $vendor ) . "',			'" . mysqli_escape_string($dbh, $cost ) . "',			'" . mysqli_escape_string($dbh, $rprc ) . "',			'" . mysqli_escape_string($dbh, $prc ) . "',			'" . mysqli_escape_string($dbh, $h['IncAccRef'] ) . "',			'" . mysqli_escape_string($dbh, $h['ExpAccRef'] ) . "'			)";//drc($que);
	mysqli_query($dbh, $que) or die("Inserting to wc_item failed: " . mysqli_connect_error());	return mysqli_insert_id($dbh);}
///////////////////////////////////////////////////////////////////////////////////////////////////function insItomod($h){ global $dbh;	//echo date('Y-m-d H:m:s', time()); return NULL;	$title = preg_replace('/\&/', 'and', $h[1]->name);	$title = preg_replace('/"/', '', $title);	$vendor = getqbvendor(brand_ident($h['id']));	$rprc  = ($h[1]->regular_price) ? $h[1]->regular_price : 0;	$prc   = ($h[1]->price) ? $h[1]->price : 0;	$cost  = ($h['cost']) ? $h['cost'] : 0;	$incacc = ($h['IncAccRef']) ? $h['IncAccRef'] : $h[3]['IncAccRef'];	$expacc = ($h['ExpAccRef']) ? $h['ExpAccRef'] : $h[3]['ExpAccRef'];	$que = "INSERT INTO wc_item ( QBListID, EdSeq, Sku, Title, PrefVnd, VCost, RPrice, SPrice, IncAccRef, ExpAccRef ) 		VALUES (			'" . mysqli_escape_string($dbh, $h[3]['QBListID'] ) . "',			'" . mysqli_escape_string($dbh, $h[3]['EdSeq'] ) . "',			'" . mysqli_escape_string($dbh, $h['sku'] ) . "',			'" . mysqli_escape_string($dbh, $title ) . "',			'" . mysqli_escape_string($dbh, $vendor ) . "',			'" . mysqli_escape_string($dbh, $cost ) . "',			'" . mysqli_escape_string($dbh, $rprc ) . "',			'" . mysqli_escape_string($dbh, $prc ) . "',			'" . mysqli_escape_string($dbh, $incacc ) . "',			'" . mysqli_escape_string($dbh, $expacc ) . "'			)";//drc($que);	mysqli_query($dbh, $que);	return mysqli_insert_id($dbh);}//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function getqbvendor($brand){switch ($brand){	case 'CB': 		return 'Cubitac Cabinetry'; break;	case 'CNC': 	return 'CNC Cabinetry'; break;	case 'FM': 		return 'TSG'; break;	case 'GHI': 	return 'Hornings Supply'; break;	case 'USCD': 	return 'US Cabinet Depot'; break;	case 'WLF': 	return 'WOLF'; break;	default: return NULL;	}	echo 'Error'; return;}
?>