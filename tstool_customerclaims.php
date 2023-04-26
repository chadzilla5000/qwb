<?php
require_once('inc/_init.php');
require_once('inc/functions/general.php');

if($lghsh['Id'] == 0){
	require_once('inc/functions/authorization.php'); 
	$pgcontent = logform(); 
	}

include_once 'inc/functions.php';
include_once 'wconfig.php';
setlocale(LC_MONETARY, 'en_US');

error_reporting(E_ERROR | E_WARNING | E_PARSE);

ini_set('memory_limit', '84M');
$lwh = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$i=0;

//$sz = '<span class="alignnone" style="width: 125px; display: inline-block;"><script type="text/javascript" data-pp-payerid="J645HTK7KKLXS" data-pp-placementtype="180x150" data-pp-style="BLUWHTYCSS"> (function (d, t) { "use strict"; var s = d.getElementsByTagName(t)[0], n = d.createElement(t); n.src = "//www.paypalobjects.com/upstream/bizcomponents/js/merchant.js"; s.parentNode.insertBefore(n, s); }(document, "script"));</script></span>';

$dv = 'Damage/Warranty/Missing Claim Form';

$cnt = NULL;
//	mysqli_query($lwh, "UPDATE wp_term_taxonomy SET description=REPLACE(description,'$sz','') WHERE term_id='600'");

$cnt .= '<table class="TBL" width="100%" cellspacing="1">';

$query = mysqli_query($lwh, "SELECT * FROM wp_cf7dbplugin_submits WHERE form_name='$dv' GROUP BY submit_time");
	while($r = mysqli_fetch_assoc($query)){
//	mysqli_query($lwh, "UPDATE wp_term_taxonomy SET description=REPLACE(description,'$sz','') WHERE term_id='$r[term_id]'");
		$cnt .= '<tr><td>'.++$i.'. </td><td>'.$r['submit_time'].'</td>';

		$q2 = mysqli_query($lwh, "SELECT * FROM wp_cf7dbplugin_submits WHERE submit_time='$r[submit_time]'");
		while($r2 = mysqli_fetch_assoc($q2)){
			
//			if(preg_match('/^[\d]{10}/', $r2['field_value'])){
			if($r2['field_value']=='1587216678.9554'){
				
		$cnt .= '<td><img src="https://www.waverlycabinets.com/wp-admin/admin-ajax.php?action=cfdb-file&s=' . $r2['field_value'] . '&form=Damage%2FWarranty%2FMissing+Claim+Form&field=file-1-1" /></td>';
				
			}
else{ //<td>'.$r2['field_name'].'</td>
				$cnt .= '<td>'.$r2['field_value'].'</td>';

}	
	
			}
			
			
//		$cnt .= '<td><img src="https://www.waverlycabinets.com/wp-admin/admin-ajax.php?action=cfdb-file&s=1598314743.4737&form=Damage%2FWarranty%2FMissing+Claim+Form&field=file-1-1" /></td>';

		$cnt .= '</tr>';
//		echo ++$i.'. '.$r[''].'<br>';
		
		}
	$cnt .= '</table>';


$pgcontent = $cnt;


$page_title       = 'Unified Management System - Tool #1';
$hsct = 'pl2snch';
$page_description = '';
$keywords         = '';
$head_ext         = '';
	
require_once('inc/_shell.php'); /////////////////////////
////////////////////////////////////////////////////////
///////////////////////////////////////////////////////
//////////////////////////////////////////////////////










exit();








$args4 = array(
	'posts_per_page' => 10000,
	'post_status'    => 'publish',
	'post_type'      => 'product',
	'orderby'        => 'date',
	'order'          => 'ASC',
	'tax_query' => array(
	array(
		'taxonomy' => 'product_tag',
		'field' => 'slug',
		'terms' => 'us-cabinet-depot',
		'operator' => 'IN',
		)
	)
	);

$ii=0;
$msits = get_posts( $args4 ); 

//$mc = get_post_terms( '47093', 'product_shipping_class');


$product_id = 324886;
$shipping_class_id = 'uscdsmall';

 // if(wp_set_post_terms( $product_id, array($shipping_class_id), 'product_shipping_class' )){

 // echo 'Shipping class is set';
 // }

echo '<table class="TBL" width="100%" cellspacing="1" style="background: #999; font: normal 10px \'Arial\'">';





foreach($msits as $it){


$_product = wc_get_product($it->ID);
$shipclass = $_product->get_shipping_class();


if(!$shipclass){

if(preg_match('/Touch-up Kit/', $it->post_title)){
wp_set_post_terms( $it->ID, array($shipping_class_id), 'product_shipping_class' );
}
	
}

echo '<tr><td>'.++$ii.'</td><td>'.$it->ID.'</td><td>'.$it->_sku.'</td><td>'.$it->post_title.'</td><td>'.$shipclass . '</td></tr>';

}  

echo '</table>';
//->get_shipping_class();


exit();

// $i=0;

	// $query = mysqli_query($dbh, "SELECT * FROM cr_vndpl ORDER BY ID ASC");
	// while($row = mysqli_fetch_assoc($query)){
		// echo ++$i.'. '.$row['Vnd'].' --- '.$row['Ln'].'-'.$row['Sku'].'<br>';
		// }

//$dbh->query("UPDATE cr_vndpl SET Weight=NULL, Length=NULL, Width=NULL, Height=NULL WHERE 1");

/*
	echo filesize(NI_ITEMS_XML).'<br><br><br>';

	$res = mysqli_query($dbh, "SELECT * FROM qw_item") or die (mysqli_error($dbh));
	while($vh = mysqli_fetch_array($res)){
		echo $vh['id'].' - '.$vh['Sku'].'<br>';
		}
*/



//$brand = 'forevermark';
$brand = 'cubitac';
//$brand = 'ghi';
//$brand = 'feather-lodge';

//$brand = 'us-cabinet-depot';



$i=0;
$wsiarr = getwsitems2($brand);
		foreach($wsiarr as $wsk=>$wr){
//			if(preg_match('/ROS-AF3006/', $wr['sku'])){
				
				if($wr['rprice'] < $wr['price']){
			echo ++$i.'. '.$wr['id'].' - '.$wr['sku'].' - '.$wr['rprice'].' - '.$wr['price'].'<br>';
			
//			update_post_meta( $wr['id'], '_price', $wr['rprice'] );
			
				}

//echo '<pre>';print_r($wr);echo '</pre>';


//			}
			}






exit;



///////////////////////////////////////////////////////////////////////////////
//$brand = 'forevermark';
//$brand = 'us-cabinet-depot';
$brand = 'ghi';

$wsiarr = getwsitems2($brand); /// Get items array from website by brand name (Sku, Title, Regular price, Sale price) /////////////////


	$st1 = $st2 = NULL;
	$vw[0] = 'WCWeb'; $vw[1] = 'Vendor';

	if($mcls==1){ $sbm_bttn = '<input type="submit" name="sku_unpub" value="Unpublish Sku" style="padding: 3px 9px;" />'; }

	if($brand){
		foreach($wsiarr as $wsk=>$wsarr){ $inc_all++; // Cycle begin /////////////////////////////////////////////////////
			$prf = 'TDW';
			$mfx = NULL;
			$sfx = NULL;
			if(1){ //(preg_match("/^($prf-)(\w.*)/",$wsarr['sku'],$m)){
//echo $wsarr['sku'].'<br>';
				if(chckSku($wsarr['sku'])){ continue; }  /// Omit existing records/////////

				if(preg_match("/^(U-[\w]{2})(-)(\w.*)/",$wsarr['sku'],$m)
				OR preg_match("/^([\w]{3})(-)(\w.*)/",$wsarr['sku'],$m)){


					echo $m[1].' - '.$m[2].'<br>';
$bsk = $wsarr['sku'];

$q = "INSERT INTO wc_consolisku( Sku_WC, Vendor, Sku_2020, Style_2020, Asm	) 
							VALUES ( 
							'$bsk',
							'GHI',
							'$m[3]',
							'$m[1]',
							'')";
//					mysqli_query($dbh, $q) or die("DB update failed: " . mysqli_connect_error());

				}


				$Pr_ID  = wc_get_product_id_by_sku($wsarr['sku']);
				$prod = wc_get_product($Pr_ID);

//				if($prod){
				if(1){
				ins2csku($wsarr['sku'], $mfx, $prf, $sfx);      ///////////////////      Inserts to table //////////////////////////

					$bdy.='
<tr><td style="color: #999;">'.++$inc.'</td>
	<td></td>
	<td style="'.$st1.'">'.$wsarr['sku'].'</td>
	<td style="'.$st1.'">'.$wsarr['title'].'</td>
	<td style="text-align: right;">'.$wsarr['rprice'].'</td>

	<td style="width: 150px; border-right: solid 2px #f00;"></td>
	<td style="width: 200px;color: #999;"></td>
</tr>';	
/*
					if($prod->is_type('variable')){ //// For variables items  ////////////////////
						foreach($prod->get_available_variations() as $var){
							$sfx = NULL;
							$vard = wc_get_product($var['variation_id']);			
							$vsku = $vard->get_sku();
							if(preg_match("/^($prf-)(\w.*)(-)([\w]{3,5})$/",$vsku,$mv)){

								$sfx = $mv[4];											
	//							ins2csku($vsku, $mv[2], $prf, $sfx);   ///////////////////      Inserts to table //////////////////////////
								
								$bdy.='
<tr><td style="color: #999;">'.++$inc.'</td>
	<td></td>
	<td style="text-align: right;">'.$vsku.'</td>
	<td style="'.$st1.'"></td>
	<td style="text-align: right;"></td>
	<td style="width: 150px; border-right: solid 2px #f00;"></td>
	<td style="width: 200px;color: #999;"></td>
</tr>';	
								}
							}
						}
*/						
						
					}
				}
			}  // Cycle end  ////////////////////////////////////////////////////////////////////
		}


$subcontent = '
<form method="post" name="bfmm31" action="#">
<table id="LTbl" cellspacing="1">
<tr><th></th>
	<th style="border: solid 1px #f60; border-width: 1px 0px 0px 2px; color: #f60;"><input type="checkbox" name="chckall" id="chckall" value="1" OnChange="chckallbx(this);" /></th>
	<th style="border: solid 1px #f60; border-width: 1px 2px 0px 0px; color: #f60;" colspan="4">'.$vw[0].'</th>
	<th></th>
	<th style="border: solid 1px #f60; border-width: 1px 2px 0px 2px; color: #f60;" colspan="4">'.$vw[1].'</th>
</tr>
<tr><th></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th>'.$sbm_bttn.'</th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
</tr>
'.$bdy.'
</table>
</form>
';

$chkdirection_optlist = array(
	'<option value="1">Vendor -> Website</option>',
	'<option value="0">Website -> Vendor</option>',
);

$br_optlist = array(
	'<option value="">Select Vendor</option>',
	'<option value="cnc">CNC</option>',
	'<option value="cubitac">Cubitac</option>',
	'<option value="feather-lodge">Feather Lodge</option>',
	'<option value="forevermark">Forevermark</option>',
	'<option value="ghi">GHI</option>',
	'<option value="msi">MSI</option>',
	'<option value="us-cabinet-depot">USCD</option>',
);

$pgtitle = '<h4>Prices synchronization</h4>';
//$pgcontent = <<<EOD__
$pgcontent = '
<!-- Page content start -->

<style>
#TopLn{
	width: 100%;
	height: 20px;
	background: #009; 
	background-image: linear-gradient(to left, #012, #09c); 
	padding: 3px 0px; 
	color: #fff;
}
.vcell{ padding: 1px 3px; background: #fff;}
.vcell:first-child {
	background: #555; color: #aa9; text-align: right;
	min-width: 140px;		
	}

#LTbl td{
	padding: 2px 5px;
	font: normal 11px "Tahoma";
}
#LTbl tr:nth-child(odd){
  background-color: #eee;
}

#rsrchdirection:hover{
	text-decoration: underline;
	
}
</style>
<div id="TopLn">
<div style="float: left;">
<a style="margin-left: 35px; color: #fee;" OnClick="return pickurl(0);" href="cmpUVW.php?dm=1&sel=0">All ('.$inc_all.')</a>
<a style="margin-left: 35px; color: #fee;" OnClick="return pickurl(1);" href="cmpUVW.php?dm=1&sel=1">Not Avail. ('.$in_1.')</a>
<a style="margin-left: 35px; color: #fee;" OnClick="return pickurl(2);" href="cmpUVW.php?dm=1&sel=2">Price MSM. ('.$in_2.')</a>
<select style="margin-left: 35px;" name="parsebrand" id="parsebrand" OnChange="prsbrand(this);">
'.getopts($br_optlist, $_GET['br']).'
</select>
</div>

<div style="float: left; margin-left: 35px; cursor: pointer" title="Direction switch" id="rsrchdirection" OnClick="sw_direction();">'.(($_GET['dm'])?'Vendor-Website':'Website-Vendor').'</div>
<div style="float: right; margin-right: 35px;"><a href="unipricel.php">U-Price</a></div>
<div style="clear: both;"></div>
</div>

'.$subcontent.'
'.$reqntnt.'
'.(($postp)?'<div style="margin-top: 50px;">Items posted:</div>'.$postp:'').'

<script>
function pickurl(s){
var hr = window.location.href;
	hr = hr.replace(/\&sel=\d/g, "\&sel="+s);
	window.location.href = hr;
return false;	
}

function prsdirection_______(o){
	var hr = window.location.href;
	if(hr.match(/\?dm=\d/)){ hr = hr.replace(/\?dm=\d/, "\?dm="+o.value); }
	window.location.href = hr;
}

function sw_direction(){
var hr = window.location.href;
var d = document.getElementById("rsrchdirection");
var val;
	if(d.innerHTML == "Vendor-Website") { val=0; d.innerHTML = "Website-Vendor"; }
	else                                { val=1; d.innerHTML = "Vendor-Website"; }
	if(hr.match(/\?dm=\d/))	{ hr = hr.replace(/\?dm=\d/, "\?dm="+val); }
	else 					{ hr = hr+"?dm=0"; }
	window.location.href = hr;
}

function prsbrand(o){
	var hr = window.location.href;
	if(hr.match(/\&br=[\w\-].*/)){ hr = hr.replace(/\&br=[\w\-].*/, "\&br="+o.value); }
	else { hr += "\&br="+o.value;}
	window.location.href = hr;
}


document.getElementById("chckall").onclick = function() {
var checkboxes = document.querySelectorAll(\'input[type="checkbox"]\');
for(var checkbox of checkboxes) {
	if(!checkbox.disabled){
		checkbox.checked = this.checked;
		}
	}
}


</script>

<!-- Page content end -->
';

$page_title       = 'Unified Management System - Tool #1';
$hsct = 'pl2snch';
$page_description = '';
$keywords         = '';
$head_ext         = '';
	
require_once('inc/_shell.php'); /////////////////////////
////////////////////////////////////////////////////////
///////////////////////////////////////////////////////
//////////////////////////////////////////////////////
/////////////////////////////////////////////////////

function chckSku($sku){ global $dbh;
	$q = mysqli_query($dbh, "SELECT id FROM wc_consolisku WHERE Sku_WC='$sku'") or die('Error: ' . mysqli_error($dbh));
	return mysqli_num_rows($q);
}

function ins2csku($wcsku, $mfx, $pfx, $asm){ global $dbh;
	
						$q = "INSERT INTO wc_consolisku( Sku_WC, Vendor, Sku_2020, Style_2020, Asm	) 
							VALUES ( 
							'$wcsku',
							'GHI',
							'$mfx',
							'$pfx',
							'$asm')";
					mysqli_query($dbh, $q) or die("DB update failed: " . mysqli_connect_error());

	return;
}




	
?>







