<?php
require_once('inc/_init.php');
require_once('inc/functions/general.php');

if($lghsh['Id'] == 0){
	require_once('inc/functions/authorization.php'); 
	$pgcontent = logform(); 
	}

include_once 'inc/functions.php';
setlocale(LC_MONETARY, 'en_US');

error_reporting(E_ERROR | E_WARNING | E_PARSE);

///////////////////////////////////////////////////////////////////////////////
$brand = 'cubitac'; //(isset($_GET['br']))?$_GET['br']:NULL;
$fname = ($brand)?'files/dump/plist/'.$brand.'.csv':'files/dump/blank.csv';


$srs = 'I';


if(isset($_GET['ms'])){
echo	$_GET['ms'];
exit;
	// if($idln){copmod($idln, $ln, $cvarr, $wsh);}
	}



$bln = $lnsrs = array();

switch($srs){
	case ('B'):
	$bln = array('ridgefield-latte','oxford-latte','ridgefield-pastel','oxford-pastel'); 
	$lnsrs = array('BLR-','BLO-','BPR-','BPO-');
	break;
	case ('P'):
	$bln = array('dover-espresso', 'dover-cafe','newport-cafe','milan-latte','dover-latte','newport-latte','dover-shale','milan-shale'); 
	$lnsrs = array('ED-','CD-','CN-','LM-','LD-','LN-','SD-','SM-');
	break;
	case ('I'):
	$bln = array('belmont-cafe-glaze','bergen-shale','bergen-latte','sofia-pewter','sofia-sable','sofia-caramel'); 
	$lnsrs = array('CBG-','ISB-','ILB-','LNPG-','LNSG-','LNCG-');
	break;
	default:
	break;
	}

$flsv = getcsvar('data/priclst/cubitacexp.csv'); 

$taxqu = (1) ? array(
	array(
		'taxonomy' => 'product_tag',
		'field' => 'slug',
		'terms' => $bln,
		'operator' => 'IN',
		)
	) : NULL;


$args = array(
	'posts_per_page' => 30000,
	'post_status'    => 'publish',
	'post_type'      => 'product',
	'orderby'        => 'date',
	'order'          => 'DESC',

	'tax_query' => $taxqu
	);

$items = get_posts( $args ); 


echo '
<style>
.VT5 td{
	width: 200px;
	padding: 0px;
	text-align: right;
	font-size: 13px;
}
</style>
<table class="VT5">';

//echo '</table>';




foreach($flsv as $ck=>$cvarr){ // Cycle begin /////////////////////////////////////////////////////
	if($cvarr[11]==$srs){ $wsh = 2;
		$adtd = NULL;
		echo '<tr><td style="width: 30px; color: #999;">'.++$i.'.</td><td style="width: 150px; color: #999; font-weight: bold;">'.$cvarr[0].'</td>';
		foreach($lnsrs as $ln){
			$plst=($cvarr[$wsh]>0)?'#fff':'#fc9';
			$plsku = $ln.$cvarr[0];
			$iid = wc_get_product_id_by_sku($plsku);
			if($iid){ //$pbj = wc_get_product($iid);
				echo '<td style="background: '.$plst.';">'.$plsku.'</td>'; 
				}
			else { 
				echo '<td style="background: '.$plst.';">---</td>'; 
				$olns = look4inolns($cvarr[0]);
				$idln = look4inolnId($cvarr[0]);
//				$adtd = ($idln)?'<td><a style="color: #090;" href="cmpWP.php?ms='.$idln.'">'.$olns.'</a></td>':'<td style="color: #f00;">N/A</td>';
				if($idln){
					if($cvarr[$wsh]>0){copmod($idln, $ln, $cvarr, $wsh);}
					$adtd = '<td><a style="color: #090;" href="cmpWP.php?ms='.$idln.'">'.$olns.'</a></td>';
					}
				else{
					$adtd = '<td style="color: #f00;">N/A</td>';
					}
				}
			$wsh++;
			}
		echo $adtd.'</tr>';
//		echo '</tr>';
		}
	}	
			
echo '</table>';


		
//		echo '<tr><td>'.++$i.'.</td><td>'.$cvarr[0].'</td><td>'.$cvarr[1].'</td><td>'.$cvarr[2].'</td><td>'.$cvarr[3].'</td><td>'.$cvarr[4].'</td><td>'.$cvarr[5].'</td><td>'.$cvarr[6].'</td><td>'.$cvarr[7].'</td><td>'.$cvarr[8].'</td><td>'.$cvarr[9].'</td>'; 
//		$xst = 0;
//		$t1 = $t2 = $t3 = $t4 = $t5 = $t6 = $t7 = $t8 = '';
//		foreach($items as $itm){
			
//	$wpsku = preg_replace('/\s$/','',$itm->_sku);


	//		$clr=(floatval($itm->_regular_price)==floatval($cvarr[4]))?'#fff':'#fc9';
	//		$rsku = preg_replace('/\-/', ' ',$itm->_sku);
	//		echo $itm->_sku . '<br>';
//			if($wpsku AND preg_match("/$wpsku/", $plsku)){ $t1 = 'Ridgefield latte'; }  // Basic series
//			if($itm->_sku=='BLO-'.$cvarr[0]){ $t2 = 'Oxford latte'; }
//			if($itm->_sku=='BPR-'.$cvarr[0]){ $t3 = 'Ridgefield pastel'; }
//			if($itm->_sku=='BPO-'.$cvarr[0]){ $t4 = 'Oxford pastel'; }
/*
			if($itm->_sku=='ED-'.$cvarr[0]){ $t1 = 'Dover Espresso'; } // Prestige
			if($itm->_sku=='CD-'.$cvarr[0]){ $t2 = 'Dover Cafe'; }
			if($itm->_sku=='CN-'.$cvarr[0]){ $t3 = 'Newport Cafe'; }
			if($itm->_sku=='LM-'.$cvarr[0]){ $t4 = 'Milan Latte'; }
			if($itm->_sku=='LD-'.$cvarr[0]){ $t5 = 'Dover Latte'; }
			if($itm->_sku=='LN-'.$cvarr[0]){ $t6 = 'Newport Latte'; }
			if($itm->_sku=='SD-'.$cvarr[0]){ $t7 = 'Dover Shale'; }
			if($itm->_sku=='SM-'.$cvarr[0]){ $t8 = 'Milan Shale'; }

			if($itm->_sku=='CBG-' .$cvarr[0]){ $t1 = 'Belmont Cafe Glaze'; } // Imperial
			if($itm->_sku=='ISB-' .$cvarr[0]){ $t2 = 'Bergen Shale'; }
			if($itm->_sku=='ILB-' .$cvarr[0]){ $t3 = 'Bergen Latte'; }
			if($itm->_sku=='LNPG-'.$cvarr[0]){ $t4 = 'Sofia Pewter'; }
			if($itm->_sku=='LNSG-'.$cvarr[0]){ $t5 = 'Sofia Sable'; }
			if($itm->_sku=='LNCG-'.$cvarr[0]){ $t6 = 'Sofia Caramel'; }
*/			
//			}
//		echo '<td>'.$t1.'</td><td>'.$t2.'</td><td>'.$t3.'</td><td>'.$t4.'</td><td>'.$t5.'</td><td>'.$t6.'</td><td>'.$t7.'</td><td>'.$t8.'</td>';

//echo '<tr><td>'.++$i.'.</td><td>'.$cvarr[0].'</td><td>'.$cvarr[1].'</td><td style="padding-left: 50px;">'.$t1.' <b>'.$cvarr[2].'</b></td><td>'.$t2.' <b>'.$cvarr[3].'</b></td><td>'.$t3.' <b>'.$cvarr[4].'</b></td><td>'.$t4.' <b>'.$cvarr[5].'</b></td><td>'.$t5.' <b>'.$cvarr[6].'</b></td><td>'.$t6.' <b>'.$cvarr[7].'</b></td><td>'.$t7.' <b>'.$cvarr[8].'</b></td><td>'.$t8.' <b>'.$cvarr[9].'</b></td></tr>';


//	if(!$xst){ 
//	echo '<tr><td>'.++$i.'.</td><td>'.$cvarr[0].'</td><td>'.$cvarr[1].'</td><td>'.$cvarr[4].'</td></tr>'; 
//	}

//		echo '</tr>';
//	echo ++$i.'. '.$cvarr[0].' - '.$cvarr[1].' - '.$cvarr[2].' - '.$cvarr[3].' - '.$cvarr[4].' - '.$cvarr[5].' - '.$cvarr[6].' - '.$cvarr[7].' - '.$cvarr[8].' - '.$cvarr[9].'<br>';
//		}
//	}

//echo '<pre>';print_r($items);echo '</pre>'; exit;


exit;

//*
$i =0;
//$ifr = 0;
//$ito = 1;

//$flsv = getcsvar('files/dump/plist/FMRIO_Vista.csv'); 
$flsv = getcsvar('data/priclst/cubitacexp.csv'); 


foreach($flsv as $ck=>$cvarr){ // Cycle begin /////////////////////////////////////////////////////

//if($cvarr[11]!=''){
if($cvarr[11]=='B'){

echo ++$i.'. '.$cvarr[0].' - '.$cvarr[1].' - '.$cvarr[2].' - '.$cvarr[3].' - '.$cvarr[4].' - '.$cvarr[5].' - '.$cvarr[6].' - '.$cvarr[7].' - '.$cvarr[8].' - '.$cvarr[9].'<br>';

}

// if($cvarr[3]>0){
	
	// $sku = preg_replace('/\* /','',$cvarr[1]); /// This string has to be updated because of comment issues
	// $sku = preg_replace('/\"/','',$sku);
	// $sku = preg_replace('/\”/','',$sku);
	// $sku = preg_replace('/ NEW$/','',$sku);
	// $sku = preg_replace('/ \(Base\)$/','',$sku);
	// $sku = preg_replace('/ \(EZR3612407$/','',$sku);
	
	// $sku = 'AW-'.$sku;
	
	// $oldid = productId_by_sku($sku);

//echo ++$i.'. '.$cvarr[1].' - '.$cvarr[3];

//if($i>$ifr AND $i<=$ito){
// $wc_adp = new WC_Admin_Duplicate_Product;
// $probj = $wc_adp->product_duplicate( wc_get_product($oldid) );
//echo ' - To be processed<br>';
//	$probj = wc_get_product($oldid);
//	lstdpl($probj, $cvarr[3]);
//}
//echo '<br>';
	}
//}

exit;

function lstdpl($prod, $nrprice){

$pid = $prod->id;

$pcat = wc_get_product_terms($pid, 'product_cat');
$ptag = wc_get_product_terms($pid, 'product_tag');

$catarr = replarray($pcat, 'Forevermark Ice White Shaker', 'Forevermark Rio Vista White Shaker');
$tagarr = replarray($ptag, 'ice-white-shaker', 'rio-vista-white-shaker');

$ir = hndplct($pid);
$ir['nrprice'] = $nrprice;
$ir['catarr']  = $catarr;
$ir['tagarr']  = $tagarr;

echo 
	$ir['sku'] . ' : ' . 
	$ir['ttl'] . ' : ' . 
	$ir['slg'] . ' : ' . 
	$ir['rpr'] . '<br>';

$vars = ($prod AND ( $prod->is_type( 'variable' ) ))?$prod->get_available_variations():NULL;
if($vars){
	foreach ($vars as $key => $v) {
		$vr = hndplct($v['variation_id']);
		$vr['nrprice'] = $nrprice;
		chngvrplmnt($v['variation_id'], $vr);
echo 
	$vr['sku'] . ' : ' . 
	$vr['ttl'] . ' : ' . 
	$vr['slg'] . ' : ' . 
	$vr['rpr'] . '<br>';
		}
	}

	chngrplmnt($prod, $ir);
	
	foreach($catarr as $ct){ echo $ct.', ';} echo '<br>';
	foreach($tagarr as $ft){ echo $ft.', ';} echo '<br>';
	
	
	return;
}

function hndplct($id){
$arr = array();
$pobj = wc_get_product($id);

$arr['ttl'] = nameoff($pobj->name);
$arr['slg'] = slugoff($arr['ttl']);
$arr['sku'] = skuoff($pobj->sku);
$arr['rpr'] = $pobj->regular_price; //intval($nrprice);

return $arr;	
}

/*
function chngrplmnt($prod, $ir){
$pid = $prod->id;
wp_set_object_terms( $pid, $ir['catarr'], 'product_cat' );
wp_set_object_terms( $pid, $ir['tagarr'], 'product_tag' );

$prod->set_name($ir['ttl']);
$prod->set_sku($ir['sku']);
$prod->set_regular_price((float)$ir['nrprice']);
$prod->set_price(NULL);

$prod->save();

chngvrplmnt($pid, $ir);

$uargs = array(
	'ID' => $pid, 
	'post_slug'     => $ir['slg'],
	'post_content'  => $ir['ttl'],
	'post_status'   => 'publish',
	);		

wp_update_post( $uargs ); 

return;	
}


function chngvrplmnt($pid, $ir){
//$pid = $prod->id;

update_post_meta( $pid, '_sku', $ir['sku'] );
update_post_meta( $pid, '_regular_price', (float)$ir['nrprice'] );
update_post_meta( $pid, '_price', NULL );

return;	
}

////////// */













if(isset($_POST['ws_skubx'])){
	foreach($_POST['ws_skubx'] as $dmr){
		$marr = explode("::",$dmr);
		
		if($marr[1]){ update_post_meta($marr[0], '_regular_price', $marr[2]); }
		else{$uargs = array('ID' => $marr[0], 'post_status' => 'pending');		
			wp_update_post( $uargs, false );                        
			}
		}
	}




if(isset($_POST['cs_skubx'])){
	foreach($_POST['cs_skubx'] as $dmr){
		
//		echo $dmr . '<br><br>';
		
		
		$carr = explode("::",$dmr);


	$nsku = $carr[1]; 
	$nrpr = $carr[3]; 	
		
echo $nsku . ' - ' . $nrpr . '<br>';		
if(0){
	$tmprd = productId_by_sku($nsku);		
	if($tmprd){	dpl_fm($tmprd, $nrpr);	}
}

 



$tmpsku = NULL;
if(preg_match('/-RGO$/', $nsku)) //// New Castle Gray
{ $tmpsku = preg_replace('/-RGO$/', '-NCG', $nsku); }

// if(preg_match('/-CCB$/', $nsku)) //// Concord Blue
// { $tmpsku = preg_replace('/-CCB$/', '-SHG', $nsku); }

// if(preg_match('/-CSG$/', $nsku)) //// Coastal Gray
// { $tmpsku = preg_replace('/-CSG$/', '-SHG', $nsku); }

//$tmpsku = 'AW-'.$nsku;






// $tmpdp = productId_by_sku($tmpsku);		
// if($tmpdp){	dpl_ghi($tmpdp, $nrpr);	}





 
 







		
		if(0
//		$carr[3]
		){
			if($carr[2]){ update_post_meta($carr[2], '_regular_price', $carr[3]); }
			else{ 
			
			
			
			
/*			
			$postdata = array(
					'post_author' => $lghsh['Id'],
					'post_type' => "product",
					'post_parent' => '',
					'post_title' => $flcsv[$carr[4]][2],
					'post_content' => $flcsv[$carr[4]][2],
					'post_status' => "publish",	);
//				$post_id = wp_insert_post( $postdata, false );
		
				if($post_id){
					
					$pmcat = $prcat = NULL;
					if(preg_match('/SDC$/', $carr[1])){ $pmcat = 'Cabinets'; $prcat = 'GHI Sedona Chestnut'; }
					if(preg_match('/ACW$/', $carr[1])){ $pmcat = 'Cabinets'; $prcat = 'GHI Arcadia White Shaker'; }
					
    // $attach_id = get_post_meta($product->parent_id, "_thumbnail_id", true);
    // add_post_meta($post_id, '_thumbnail_id', $attach_id);


			wp_set_object_terms( $post_id, $pmcat, 'product_cat' );
			wp_set_object_terms( $post_id, $prcat, 'product_cat' );
			wp_set_object_terms( $post_id, 'simple', 'product_type');

			update_post_meta( $post_id, '_visibility', 'visible' );
// update_post_meta( $post_id, '_stock_status', 'instock');
// update_post_meta( $post_id, 'total_sales', '0');
// update_post_meta( $post_id, '_downloadable', 'yes');
// update_post_meta( $post_id, '_virtual', 'yes');
			update_post_meta( $post_id, '_regular_price', $carr[3] );
// update_post_meta( $post_id, '_sale_price', "1" );
// update_post_meta( $post_id, '_purchase_note', "" );
// update_post_meta( $post_id, '_featured', "no" );
// update_post_meta( $post_id, '_weight', "" );
// update_post_meta( $post_id, '_length', "" );
// update_post_meta( $post_id, '_width', "" );
// update_post_meta( $post_id, '_height', "" );
			update_post_meta( $post_id, '_sku', $carr[1]);
// update_post_meta( $post_id, '_product_attributes', array());
// update_post_meta( $post_id, '_sale_price_dates_from', "" );
// update_post_meta( $post_id, '_sale_price_dates_to', "" );
			update_post_meta( $post_id, '_price', $carr[3] );
// update_post_meta( $post_id, '_sold_individually', "" );
// update_post_meta( $post_id, '_manage_stock', "no" );
// update_post_meta( $post_id, '_backorders', "no" );
// update_post_meta( $post_id, '_stock', "" );
		}		
		
*/		
		
		
		
//	echo $flcsv[$carr[4]][2] . '<br>';	
	$nsku = $carr[1]; 
	$nrpr = $carr[3]; 	




$tmpsku = NULL;

// if(preg_match('/-NCG$/', $nsku)) //// New Castle Gray
// { $tmpsku = preg_replace('/-NCG$/', '-SHG', $nsku); }

// if(preg_match('/-CCB$/', $nsku)) //// Concord Blue
// { $tmpsku = preg_replace('/-CCB$/', '-SHG', $nsku); }

// if(preg_match('/-CSG$/', $nsku)) //// Coastal Gray
// { $tmpsku = preg_replace('/-CSG$/', '-SHG', $nsku); }

$tmpsku = 'AW-'.$nsku;

$tmpdp = productId_by_sku($tmpsku);		

if($tmpdp){	dpl_ghi($tmpdp, $nrpr);	}
//if($tmpdp){	echo $tmpsku.' - found in Stone Harbor Gray. '.$carr[1].'<br>';	}
else{ echo $tmpsku.' - Not found in Stone Harbor Gray. <br>';}

//echo $tmpdp . '<br>';
//		echo '<pre>'; print_r($postdata); echo '</pre>';
				}
			}
		}
	}
























//$brand = $flcsv[0][0];
//echo $brand; exit;

$wsiarr = getwsitems($brand); /// Get items array from website by brand name (Sku, Title, Regular price, Sale price) /////////////////

//echo '<pre>'; print_r($wsiarr); echo '</pre>';


$mcls = (isset($_GET['sel']))?$_GET['sel']:NULL;


$inc =0;
//	$flsv = getcsvar($_FILES["file"]["tmp_name"]); 
$bdy = NULL;









$buttoname = NULL;
$vw=array();



if($_GET['dm']){
	$vw[0] = 'Vendor'; $vw[1] = 'WCWeb';
	foreach($flcsv as $csk=>$csarr){ // Cycle begin /////////////////////////////////////////////////////

//		if(!preg_match('/\$\d+/',$csarr[3])){continue;}
		if(!preg_match('/[\d+]/',$csarr[3])){continue;}
//echo $csarr[0].' - '.$csarr[1].' - '.$csarr[2].' - '.$csarr[3].'-<br>';
		$msku = $mskualt_1 = NULL;
		if(preg_match('/^JIFFY/', $csarr[2])){ $msku = $csarr[1]; }
		else {switch($brand){
				case 'ghi'           : $msku = convertcsku_GHI($csarr[1]); break;
				case 'feather-lodge' : $msku = convertcsku_FL($csarr[1]);  break;
				case 'forevermark'    : $msku = convertcsku_FM($csarr[1]);  break;
				}
			}

//		$msku = (preg_match('/^JIFFY/', $csarr[2])) ? $csarr[1] : convertsku_GHI($csarr[1]);
//$msku = 'AW-'.$csarr[1];
		$vpr1 = getcsvint($csarr[3]); //
		// $vpr1 = preg_replace('/\$/', '', $csarr[3]);
		// $vpr1 = preg_replace('/\,/', '', $vpr1);
		// $vpr1 = round($vpr1, 2);

		$vprice = ($vpr1 AND $csarr[1])?number_format($vpr1, 2):NULL;

		$ws = get_wsku($wsiarr, $msku);
		if(!$ws){ $cs = get_wsku($wsiarr, $mskualt_1); }

		if($ws AND $mcls==1){continue;}
		if((!$ws OR $vpr1==$ws['rprice']) AND $mcls==2){continue;}

//		$chbchk = (0)?'disabled':'';
		$chbchk = ($vpr1==$ws['rprice'])?'disabled':'';

		$bst_1 = ($vpr1!=$ws['rprice'])?'background: #fc9;':NULL;
		$bdy.='
<tr><td style="color: #ccc;">'.++$inc.'</td>
	<td><input type="checkbox" name="cs_skubx[]" value="'.$csarr[1].'::'.$msku.'::'.$ws['id'].'::'.$vpr1.'::'.$csk.'" '.$chbchk.' /></td>
	<td>'.$csarr[1].'</td>
	<td>'.$csarr[2].'</td>
	<td style="text-align: right;">'.$vprice.'</td>

	<td style="width: 150px;">'.$csk.'</td>
	<td style="width: 200px;color: #999;">'.$msku.'</td>
		
	<td>'.$ws['id'].'</td>
	<td>'.$ws['sku'].'</td>
	<td>'.$ws['title'].'</td>
	<td style="text-align: right; '.$bst_1.'">'.$ws['rprice'].'</td>
</tr>';	
		}  // Cycle end  ////////////////////////////////////////////////////////////////////
	}
else{
	$vw[0] = 'WCWeb'; $vw[1] = 'Vendor';
	foreach($wsiarr as $wsk=>$wsarr){ // Cycle begin /////////////////////////////////////////////////////
		$msku = $mskualt_1 = NULL;
		if(preg_match('/^Jiffy/', $wsarr['title'])){ $msku = $wsarr['sku']; }
		else {switch($brand){
				case 'ghi'           : $msku = convertwsku_GHI($wsarr['sku']); $mskualt_1 = convertwsku_GHI($wsarr['sku'], 1); break;
				case 'feather-lodge' : $msku = convertwsku_FL($wsarr['sku']);  break;
				}
			}

		$vprice = ($vpr1 AND $csarr[1])?number_format($vpr1, 2):NULL;

		$cs = get_csku($flcsv, $msku);
		if(!$cs){ $cs = get_csku($flcsv, $mskualt_1); }
		$vpr1 = getcsvint($cs[3]); //preg_replace('/\$/', '', $cs[3]);
//		$vpr1 = preg_replace('/\,/', '', $vpr1);
//		$vpr1 = round($vpr1, 2);


		if($cs AND $mcls==1){continue;}
		if((!$cs OR $vpr1==$wsarr['rprice']) AND $mcls==2){continue;}

		$chbchk = ($vpr1==$wsarr['rprice'])?'disabled':'';
//		$nmsku = preg_replace('/\s/', '_', $wsarr['sku']);
//		$nmsku = $wsarr['sku'];

		$bst_1 = ($vpr1!=$wsarr['rprice'])?'background: #fc9;':NULL;

		$bdy.='
<tr><td style="color: #ccc;">'.++$inc.'</td>
	<td><input type="checkbox" name="ws_skubx[]" value="'.$wsarr['id'].'::'.$cs[1].'::'.$vpr1.'" '.$chbchk.' /></td>
	<td>'.$wsarr['sku'].'</td>
	<td>'.$wsarr['title'].'</td>
	<td style="text-align: right;">'.$wsarr['rprice'].'</td>

	<td style="width: 150px;"></td>
	<td style="width: 200px;color: #999;">'.$msku.'</td>
		
	<td></td>
	<td>'.$cs[1].'</td>
	<td>'.$cs[2].'</td>
	<td style="text-align: right; '.$bst_1.'">'.$cs[3].'</td>
</tr>';	
		}  // Cycle end  ////////////////////////////////////////////////////////////////////
	
	}


$subcontent = '
<form method="post" name="bfmm31" action="#">
<table id="LTbl" cellspacing="1">
<tr><th></th>
	<th style="border: solid 1px #f60; border-width: 1px 2px 0px 2px; color: #f60;" colspan="5">'.$vw[0].'</th>
	<th></th>
	<th style="border: solid 1px #f60; border-width: 1px 2px 0px 2px; color: #f60;" colspan="4">'.$vw[1].'</th>
</tr>
<tr><th></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th><input type="submit" name="sbm_1" value="Update" style="padding: 3px 9px;" /></th>
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
<a style="margin-left: 35px; color: #fee;" OnClick="return pickurl(0);" href="cmpVW.php?dm=1&sel=0">All Sku</a>
<a style="margin-left: 35px; color: #fee;" OnClick="return pickurl(1);" href="cmpVW.php?dm=1&sel=1">Non Avail.</a>
<a style="margin-left: 35px; color: #fee;" OnClick="return pickurl(2);" href="cmpVW.php?dm=1&sel=2">Price MSM.</a>
<select style="margin-left: 35px;" name="parsebrand" id="parsebrand" OnChange="prsbrand(this);">
'.getopts($br_optlist, $_GET['br']).'
</select>
<!-- select style="margin-left: 35px;" name="chkdirection" id="parsebrand" OnChange="prsdirection(this);">
'.getopts($chkdirection_optlist, $_GET['dm']).'
</select -->
</div>

<div style="float: left; margin-left: 35px; cursor: pointer" title="Direction switch" id="rsrchdirection" OnClick="sw_direction();">'.(($_GET['dm'])?'Vendor-Website':'Website-Vendor').'</div>
<div style="clear: both;"></div>
</div>


'.$subcontent.'

<script>
function pickurl(s){
var hr = window.location.href;
	hr = hr.replace(/\&sel=\d/g, "\&sel="+s);
	window.location.href = hr;
return false;	
}

function prsdirection(o){
	var hr = window.location.href;
	if(hr.match(/\?dm=\d/)){ 
	hr = hr.replace(/\?dm=\d/, "\?dm="+o.value); 
	}
	window.location.href = hr;
}

function sw_direction(){
var hr = window.location.href;
var d = document.getElementById("rsrchdirection");
var val;
	if(d.innerHTML == "Vendor-Website") { val=0; d.innerHTML = "Website-Vendor"; }
	else                                { val=1; d.innerHTML = "Vendor-Website"; }
	if(hr.match(/\?dm=\d/)){ 
	hr = hr.replace(/\?dm=\d/, "\?dm="+val); 
	}
	window.location.href = hr;
}

function prsbrand(o){
	var hr = window.location.href;
	if(hr.match(/\&br=[\w\-].*/)){ 
	hr = hr.replace(/\&br=[\w\-].*/, "\&br="+o.value); 
	}
	else { hr += "\&br="+o.value;}
	window.location.href = hr;
}
</script>

<!-- Page content end -->
';

$page_title       = 'Unified Management System - Prices';
$page_description = '';
$keywords         = '';
$head_ext         = '';
	
require_once('inc/_shell.php'); /////////////////////////
////////////////////////////////////////////////////////
///////////////////////////////////////////////////////
//////////////////////////////////////////////////////
/////////////////////////////////////////////////////

function convertcsku_GHI($sku, $altsrch=0){
	$wcsku = NULL;
if( $altsrch ) { return $sku; }
if( preg_match('/SDC$/', $sku)){ $wcsku = preg_replace('/SDC$/', '-SDC', $sku); }
if( preg_match('/ACW$/', $sku)){ $wcsku = preg_replace('/ACW$/', '-ACW', $sku); }
if( preg_match('/ACL$/', $sku)){ $wcsku = preg_replace('/ACL$/', '-ACL', $sku); }
if( preg_match('/LW$/',  $sku)){ $wcsku = preg_replace('/LW$/',  '-LW',  $sku); }
if( preg_match('/SHG$/', $sku)){ $wcsku = preg_replace('/SHG$/', '-SHG', $sku); }
if( preg_match('/NTL$/', $sku)){ $wcsku = preg_replace('/NTL$/', '-NTL', $sku); }
if( preg_match('/NCG$/', $sku)){ $wcsku = preg_replace('/NCG$/', '-NCG', $sku); }
if( preg_match('/RMA$/', $sku)){ $wcsku = preg_replace('/RMA$/', '-RMA', $sku); }
if( preg_match('/CTC$/', $sku)){ $wcsku = preg_replace('/CTC$/', '-CTC', $sku); }
if( preg_match('/RGO$/', $sku)){ $wcsku = preg_replace('/RGO$/', '-RGO', $sku); }
if( preg_match('/CCB$/', $sku)){ $wcsku = preg_replace('/CCB$/', '-CCB', $sku); }
if( preg_match('/CSG$/', $sku)){ $wcsku = preg_replace('/CSG$/', '-CSG', $sku); }
if( preg_match('/GRC$/', $sku)){ $wcsku = preg_replace('/GRC$/', '-GRC', $sku); }
if( preg_match('/NPW$/', $sku)){ $wcsku = preg_replace('/NPW$/', '-NPW', $sku); }

	return preg_replace('/--/', '-', $wcsku);
}


function convertwsku_GHI($sku, $altsrch=0){
	$csku = NULL;
if( $altsrch ) { return $sku; }
if( preg_match('/-SDC$/', $sku)){ $csku = preg_replace('/-SDC$/', 'SDC', $sku); }
if( preg_match('/-ACW$/', $sku)){ $csku = preg_replace('/-ACW$/', 'ACW', $sku); }
if( preg_match('/-ACL$/', $sku)){ $csku = preg_replace('/-ACL$/', 'ACL', $sku); }
if( preg_match('/-LS$/',  $sku)){ $csku = preg_replace('/-LS$/',  'LS',  $sku); }
if( preg_match('/-LW$/',  $sku)){ $csku = preg_replace('/-LW$/',  'LW',  $sku); }
if( preg_match('/LW$/',   $sku)){ $csku = $sku; }
if( preg_match('/NM$/',   $sku)){ $csku = $sku; }
if( preg_match('/^SCG$/', $sku)){ $csku = $sku; }
if( preg_match('/^SCH$/', $sku)){ $csku = $sku; }
if( preg_match('/-SHG$/', $sku)){ $csku = preg_replace('/-SHG$/', 'SHG', $sku); }
if( preg_match('/-NTL$/', $sku)){ $csku = preg_replace('/-NTL$/', 'NTL', $sku); }
if( preg_match('/-NCG$/', $sku)){ $csku = preg_replace('/-NCG$/', 'NCG', $sku); }
if( preg_match('/-RMA$/', $sku)){ $csku = preg_replace('/-RMA$/', 'RMA', $sku); }
if( preg_match('/-CTC$/', $sku)){ $csku = preg_replace('/-CTC$/', 'CTC', $sku); }
if( preg_match('/-RGO$/', $sku)){ $csku = preg_replace('/-RGO$/', 'RGO', $sku); }
if( preg_match('/-CCB$/', $sku)){ $csku = preg_replace('/-CCB$/', 'CCB', $sku); }
if( preg_match('/-CSG$/', $sku)){ $csku = preg_replace('/-CSG$/', 'CSG', $sku); }
if( preg_match('/-GRC$/', $sku)){ $csku = preg_replace('/-GRC$/', 'GRC', $sku); }
if( preg_match('/-NPW$/', $sku)){ $csku = preg_replace('/-NPW$/', 'NPW', $sku); }
if( preg_match('/-BDM$/', $sku)){ $csku = preg_replace('/-BDM$/', 'BDM', $sku); }

	return preg_replace('/--/', '-', $csku);
}


function convertcsku_FL($sku){
	$wcsku = NULL;
if( preg_match('/GRC$/', $sku)){ $wcsku = preg_replace('/GRC$/', '-GRC', $sku); }
if( preg_match('/NPW$/', $sku)){ $wcsku = preg_replace('/NPW$/', '-NPW', $sku); }
	return preg_replace('/--/', '-', $wcsku);
}

function convertwsku_FL($sku){
	$csku = NULL;
if( preg_match('/^SCG$/', $sku)){ $csku = $sku; }
if( preg_match('/-GRC$/', $sku)){ $csku = preg_replace('/-GRC$/', 'GRC', $sku); }
if( preg_match('/-NPW$/', $sku)){ $csku = preg_replace('/-NPW$/', 'NPW', $sku); }
	return preg_replace('/--/', '-', $csku);
}

function convertcsku_FM($sku){
	$wcsku = 'VW-'.$sku;
	return preg_replace('/--/', '-', $wcsku);
}




function get_wsku($wsiarr, $sku) {
	foreach($wsiarr as $wi){
		if($sku == $wi['sku']){
			return $wi;
			}
		}
	return null;
}

function get_csku($csiarr, $sku) {
	foreach($csiarr as $ci){
		if($sku == $ci[1]){
			return $ci;
			}
		}
	return null;
}

function getcsvint($v){
	$vpr = preg_replace('/\$/', '', $v);
	$vpr = preg_replace('/\,/', '', $vpr);
	return round($vpr, 2);
}


 function duplicate($post_id) {
    $title   = get_the_title($post_id);
    $oldpost = get_post($post_id);
    $post    = array(
      'post_title' => $title,
      'post_status' => 'publish',
      'post_type' => $oldpost->post_type,
      'post_author' => 1
    );
    $new_post_id = wp_insert_post($post);
    // Copy post metadata
    $data = get_post_custom($post_id);
    foreach ( $data as $key => $values) {
      foreach ($values as $value) {
        add_post_meta( $new_post_id, $key, $value );
      }
    }
    // // Copy post metadata
// //    $terms = get_post_custom($post_id);
	// $ptag = wc_get_product_terms($post_id, 'product_tag');
    // foreach ( $ptag as $key => $values) {
// //      foreach ($values as $value) {
        // wp_set_post_terms( $new_post_id, $values );
 // //     }
    // }

    return $new_post_id;
  }


function dpl_ghi($op_id, $nrprice){


$dv = 'RGO';
$dn = 'Regal Oak';



$wc_adp = new WC_Admin_Duplicate_Product;
$dproduct = $wc_adp->product_duplicate( wc_get_product($op_id) );

$pid = $dproduct->id;

$prod = wc_get_product($pid);

// echo $prod->name . '<br>';
// echo $prod->slug . '<br>';
// echo $prod->sku . '<br>';
// echo $prod->regular_price . '<br>';

$nname = preg_replace('/ \(Copy\)/','', $prod->name);
$nname = preg_replace('/NCG/',$dv, $nname);
$nname = preg_replace('/New Castle Gray/',$dn, $nname);


//echo $nname . '<br>';

$nslug = strtolower($nname); 
$nslug = preg_replace('/\s/','-', $nslug);

$nsku = preg_replace('/-\d$/', '', $prod->sku);
$nsku = preg_replace('/-NCG$/', '-'.$dv, $nsku);


//$pmcat = array( 'Cabinets', 'GHI New Castle Gray' );

$pcat = wc_get_product_terms($pid, 'product_cat');
$ptag = wc_get_product_terms($pid, 'product_tag');

$catarr = array();
foreach($pcat as $c){
//	if($c->name=='GHI Stone Harbor Gray'){ array_push($catarr, 'GHI New Castle Gray'); }
//	if($c->name=='GHI Stone Harbor Gray'){ array_push($catarr, 'GHI Concord Blue'); }
	if($c->name=='GHI New Castle Gray'){ array_push($catarr, 'GHI '.$dn); }
	else { array_push($catarr, $c->name); }
}
//foreach($catarr as $ct){ echo $ct.'<br>';}

$dn = preg_replace('/\s/','-',strtolower($dn));


$tagarr = array();
foreach($ptag as $t){
//	if($t->name=='stone-harbor-gray'){ array_push($tagarr, 'new-castle-gray'); }
//	if($t->name=='stone-harbor-gray'){ array_push($tagarr, 'concord-blue'); }
	if($t->name=='new-castle-gray'){ array_push($tagarr, $dn); }
	else { array_push($tagarr, $t->name); }
	}
//foreach($tagarr as $ft){ echo $ft.'<br>';}

wp_set_object_terms( $pid, $catarr, 'product_cat' );
wp_set_object_terms( $pid, $tagarr, 'product_tag' );

$prod->set_name($nname);
$prod->set_sku($nsku);
$prod->set_regular_price($nrprice);
$prod->set_price(NULL);

$prod->save();


$uargs = array(
	'ID' => $pid, 
	'post_slug'     => $nslug,
	'post_content'  => $nname,
	'post_status'   => 'publish',
	);		

wp_update_post( $uargs ); 

	
return;	
}


function dpl_fm($op_id, $nrprice){

//$wc_adp = new WC_Admin_Duplicate_Product;
//$dproduct = $wc_adp->product_duplicate( wc_get_product($op_id) );
//$pid = $dproduct->id;

$pid = productId_by_sku('VW-W0930');
//$prd = wc_get_product($pid);

$prod = wc_get_product(productId_by_sku('VW-W0930'));

$pcat = wc_get_product_terms($pid, 'product_cat');
$ptag = wc_get_product_terms($pid, 'product_tag');

$catarr = replarray($pcat, 'Forevermark Ice White Shaker', 'Forevermark Rio Vista White Shaker');
$tagarr = replarray($ptag, 'ice-white-shaker', 'rio-vista-white-shaker');


//	 echo '<pre>'; print_r($catarr); echo '</pre>';


$iarr = array();
$vars = $prod->get_available_variations();
if($vars){
	foreach ($vars as $key => $v) {
		$vrd = wc_get_product($v['variation_id']);
		$iarr['vslug'] = slugoff($vrd->name);
		$iarr['vsku']  = skuoff($vrd->sku);
		$iarr['vname'] = nameoff($vrd->name);
		$iarr['rgprc'] = intval($nrprice);
		$vid=$vrd->id; //productId_by_sku('AW-W0930-RTA');

//		$dpd = dupupd($varr, $nrprice);
		
		echo $v['variation_id'].' :: '.$iarr['vsku'] . ' - ' . $vname . ' - ' . $slug . ' - ' . $rpr . ' - ' . $vrd->price . '<br>';
		 
	//	 echo '<pre>'; print_r($v); echo '</pre>';
		}
	}



return;	




$nname = nameoff($prod->name); // preg_replace('/ \(Copy\)/','', $prod->name);
$nslug = slugoff($nname); //strtolower($nname); 

$nsku = skuoff($prod->sku); //preg_replace('/-\d$/', '', $prod->sku);

$pcat = wc_get_product_terms($pid, 'product_cat');
$ptag = wc_get_product_terms($pid, 'product_tag');

$catarr = gettags($ptag); /////array();
// foreach($pcat as $c){
// //	if($c->name=='GHI Stone Harbor Gray'){ array_push($catarr, 'GHI New Castle Gray'); }
// //	if($c->name=='GHI Stone Harbor Gray'){ array_push($catarr, 'GHI Concord Blue'); }
	// if($c->name=='Forevermark Ice White Shaker'){ array_push($catarr, 'Forevermark Rio Vista White Shaker'); }
	// else { array_push($catarr, $c->name); }
// }
//foreach($catarr as $ct){ echo $ct.'<br>';}

$tagarr = gettags($ptag); /////// array();

// foreach($ptag as $t){
// //	if($t->name=='stone-harbor-gray'){ array_push($tagarr, 'new-castle-gray'); }
// //	if($t->name=='stone-harbor-gray'){ array_push($tagarr, 'concord-blue'); }
	// if($t->name=='ice-white-shaker'){ array_push($tagarr, 'rio-vista-white-shaker'); }
	// else { array_push($tagarr, $t->name); }
	// }
//foreach($tagarr as $ft){ echo $ft.'<br>';}

wp_set_object_terms( $pid, $catarr, 'product_cat' );
wp_set_object_terms( $pid, $tagarr, 'product_tag' );

$prod->set_name($nname);
$prod->set_sku($nsku);
$prod->set_regular_price($nrprice);
$prod->set_price(NULL);

$prod->save();


$uargs = array(
	'ID' => $pid, 
	'post_slug'     => $nslug,
	'post_content'  => $nname,
	'post_status'   => 'publish',
	);		

wp_update_post( $uargs ); 

	
return;	
}


function replarray($parr, $nsrch, $nrplc){
	$r = array();
	// $key = array_search($nsrch, $parr);
	// echo $parr['name'];
	// if($key){ $parr[$key] = $nrplc; }

 foreach($parr as $k=>$v){
	if($v->name==$nsrch){ array_push($r, $nrplc); }
	else { array_push($r, $v->name); }
	}


	return $r;
}


function newtags($ptag){
$tagarr = array();
foreach($ptag as $t){
	if($t->name=='ice-white-shaker'){ array_push($tagarr, 'rio-vista-white-shaker'); }
		else { array_push($tagarr, $t->name); }
		}
	return $catarr;
}

function newcats($pcat){
$catarr = array();
foreach($pcat as $c){
	if($c->name=='Forevermark Ice White Shaker'){ array_push($catarr, 'Forevermark Rio Vista White Shaker'); }
	else { array_push($catarr, $c->name); }
	}
	return $catarr;
}

function slugoff($ttl){
	$sl = strtolower( preg_replace('/\s/','-', $ttl) );
	return preg_replace('/[-]{2,7}/', '-', $sl);
	}
	
function skuoff($sku){
	$nsku = preg_replace('/-\d$/', '', $sku);
	return preg_replace('/^AW-/', 'VW-', $nsku);
	}

function nameoff($nm){
	$nname = preg_replace('/ \(Copy\)/','', $nm);
	return preg_replace('/Ice White Shaker/','Rio Vista White Shaker', $nname);
	}
	
	
function dupupd($pbj, $drr){
	// wp_set_object_terms( $pid, $catarr, 'product_cat' );
	// wp_set_object_terms( $pid, $tagarr, 'product_tag' );

	$prod->set_name($nname);
	$prod->set_sku($nsku);
	$prod->set_regular_price($nrprice);
	$prod->set_price(NULL);

	$prod->save();


	$uargs = array(
		'ID' => $pid, 
		'post_slug'     => $nslug,
		'post_content'  => $nname,
		'post_status'   => 'publish',
		);		

	wp_update_post( $uargs ); 
}



function look4inolns($skp){
	$pxs = array('BLR-','BLO-','BPR-','BPO-','ED-','CD-','CN-','LM-','LD-','LN-','SD-','SM-','CBG-','ISB-','ILB-','LNPG-','LNSG-','LNCG-');
	foreach($pxs as $px){
		$bsku = $px.$skp;
			$iid = wc_get_product_id_by_sku($bsku);
			if($iid){
				return $bsku;
				}
	}
	return NULL;
}

function look4inolnId($skp){
	$pxs = array('BLR-','BLO-','BPR-','BPO-','ED-','CD-','CN-','LM-','LD-','LN-','SD-','SM-','CBG-','ISB-','ILB-','LNPG-','LNSG-','LNCG-');
	foreach($pxs as $px){
		$iid = wc_get_product_id_by_sku($px.$skp);
		if($iid){
			return $iid;
			}
		}
	return NULL;
}


function copmod($smplid, $ln, $cv, $w){

$wc_adp = new WC_Admin_Duplicate_Product;
$probj = $wc_adp->product_duplicate( wc_get_product($smplid) );
//	$probj = wc_get_product($smplid);

$r4 = array();
$r2 = array();


$prx = (preg_match('/^([\w]{2,4}-)(.*)/', $probj->sku, $m))?$m[1]:NULL;



switch ($prx){
	case 'BLR-': $r4 = array('ttl'=>'Ridgefield Latte', 'slg'=>'ridgefield-latte', 'prf'=>'BLR-','cat'=>'Cubitac Ridgefield Latte', 'tag'=>'ridgefield-latte');  break;
	case 'BLO-': $r4 = array('ttl'=>'Oxford Latte',     'slg'=>'oxford-latte',     'prf'=>'BLO-','cat'=>'Cubitac Oxford Latte',     'tag'=>'oxford-latte');      break;
	case 'BPR-': $r4 = array('ttl'=>'Ridgefield Pastel','slg'=>'ridgefield-pastel','prf'=>'BPR-','cat'=>'Cubitac Ridgefield Pastel','tag'=>'ridgefield-pastel'); break;
	case 'BPO-': $r4 = array('ttl'=>'Oxford Pastel',    'slg'=>'oxford-pastel',    'prf'=>'BPO-','cat'=>'Cubitac Oxford Pastel',    'tag'=>'oxford-pastel');     break;

	case 'ED-': $r4 = array('ttl'=>'Dover Espresso','slg'=>'dover-espresso','prf'=>'ED-','cat'=>'Cubitac Dover Espresso','tag'=>'dover-espresso'); break;
	case 'CD-': $r4 = array('ttl'=>'Dover Cafe',    'slg'=>'dover-cafe',    'prf'=>'CD-','cat'=>'Cubitac Dover Cafe',    'tag'=>'dover-cafe');     break;
	case 'CN-': $r4 = array('ttl'=>'Newport Cafe',  'slg'=>'newport-cafe',  'prf'=>'CN-','cat'=>'Cubitac Newport Cafe',  'tag'=>'newport-cafe');   break;
	case 'LM-': $r4 = array('ttl'=>'Milan Latte',   'slg'=>'milan-latte',   'prf'=>'LM-','cat'=>'Cubitac Milan Latte',   'tag'=>'milan-latte');    break;
	case 'LD-': $r4 = array('ttl'=>'Dover Latte',   'slg'=>'dover-latte',   'prf'=>'LD-','cat'=>'Cubitac Dover Latte',   'tag'=>'dover-latte');    break;
	case 'LN-': $r4 = array('ttl'=>'Newport Latte', 'slg'=>'newport-latte', 'prf'=>'LN-','cat'=>'Cubitac Newport Latte', 'tag'=>'newport-latte');  break;
	case 'SD-': $r4 = array('ttl'=>'Dover Shale',   'slg'=>'dover-shale',   'prf'=>'SD-','cat'=>'Cubitac Dover Shale',   'tag'=>'dover-shale');    break;
	case 'SM-': $r4 = array('ttl'=>'Milan Shale',   'slg'=>'milan-shale',   'prf'=>'SM-','cat'=>'Cubitac Milan Shale',   'tag'=>'milan-shale');    break;

	case 'CBG-':  $r4 = array('ttl'=>'Belmont Cafe Glaze','slg'=>'belmont-cafe-glaze','prf'=>'CBG-', 'cat'=>'Cubitac Belmont Cafe Glaze','tag'=>'belmont-cafe-glaze'); break;
	case 'ISB-':  $r4 = array('ttl'=>'Bergen Shale',      'slg'=>'bergen-shale',      'prf'=>'ISB-', 'cat'=>'Cubitac Bergen Shale',      'tag'=>'bergen-shale');  break;
	case 'ILB-':  $r4 = array('ttl'=>'Bergen Latte',      'slg'=>'bergen-latte',      'prf'=>'ILB-', 'cat'=>'Cubitac Bergen Latte',      'tag'=>'bergen-latte');  break;
	case 'LNPG-': $r4 = array('ttl'=>'Sofia Pewter',      'slg'=>'sofia-pewter',      'prf'=>'LNPG-','cat'=>'Cubitac Sofia Pewter',      'tag'=>'sofia-pewter');  break;
	case 'LNSG-': $r4 = array('ttl'=>'Sofia Sable',       'slg'=>'sofia-sable',       'prf'=>'LNSG-','cat'=>'Cubitac Sofia Sable',       'tag'=>'sofia-sable');   break;
	case 'LNCG-': $r4 = array('ttl'=>'Sofia Caramel',     'slg'=>'sofia-caramel',     'prf'=>'LNCG-','cat'=>'Cubitac Sofia Caramel',     'tag'=>'sofia-caramel'); break;
	
};

switch ($ln){
	case 'BLR-': $r2 = array('ttl'=>'Ridgefield Latte', 'slg'=>'ridgefield-latte', 'prf'=>'BLR-','cat'=>'Cubitac Ridgefield Latte', 'tag'=>'ridgefield-latte', 'rpr' => $cv[$w]); break;
	case 'BLO-': $r2 = array('ttl'=>'Oxford Latte',     'slg'=>'oxford-latte',     'prf'=>'BLO-','cat'=>'Cubitac Oxford Latte',     'tag'=>'oxford-latte',     'rpr' => $cv[$w]); break;
	case 'BPR-': $r2 = array('ttl'=>'Ridgefield Pastel','slg'=>'ridgefield-pastel','prf'=>'BPR-','cat'=>'Cubitac Ridgefield Pastel','tag'=>'ridgefield-pastel','rpr' => $cv[$w]); break;
	case 'BPO-': $r2 = array('ttl'=>'Oxford Pastel',    'slg'=>'oxford-pastel',    'prf'=>'BPO-','cat'=>'Cubitac Oxford Pastel',    'tag'=>'oxford-pastel',    'rpr' => $cv[$w]); break;

	case 'ED-': $r2 = array('ttl'=>'Dover Espresso','slg'=>'dover-espresso','prf'=>'ED-','cat'=>'Cubitac Dover Espresso','tag'=>'dover-espresso','rpr' => $cv[$w]); break;
	case 'CD-': $r2 = array('ttl'=>'Dover Cafe',    'slg'=>'dover-cafe',    'prf'=>'CD-','cat'=>'Cubitac Dover Cafe',    'tag'=>'dover-cafe',    'rpr' => $cv[$w]); break;
	case 'CN-': $r2 = array('ttl'=>'Newport Cafe',  'slg'=>'newport-cafe',  'prf'=>'CN-','cat'=>'Cubitac Newport Cafe',  'tag'=>'newport-cafe',  'rpr' => $cv[$w]); break;
	case 'LM-': $r2 = array('ttl'=>'Milan Latte',   'slg'=>'milan-latte',   'prf'=>'LM-','cat'=>'Cubitac Milan Latte',   'tag'=>'milan-latte',   'rpr' => $cv[$w]); break;
	case 'LD-': $r2 = array('ttl'=>'Dover Latte',   'slg'=>'dover-latte',   'prf'=>'LD-','cat'=>'Cubitac Dover Latte',   'tag'=>'dover-latte',   'rpr' => $cv[$w]); break;
	case 'LN-': $r2 = array('ttl'=>'Newport Latte', 'slg'=>'newport-latte', 'prf'=>'LN-','cat'=>'Cubitac Newport Latte', 'tag'=>'newport-latte', 'rpr' => $cv[$w]); break;
	case 'SD-': $r2 = array('ttl'=>'Dover Shale',   'slg'=>'dover-shale',   'prf'=>'SD-','cat'=>'Cubitac Dover Shale',   'tag'=>'dover-shale',   'rpr' => $cv[$w]); break;
	case 'SM-': $r2 = array('ttl'=>'Milan Shale',   'slg'=>'milan-shale',   'prf'=>'SM-','cat'=>'Cubitac Milan Shale',   'tag'=>'milan-shale',   'rpr' => $cv[$w]); break;

	case 'CBG-':  $r2 = array('ttl'=>'Belmont Cafe Glaze','slg'=>'belmont-cafe-glaze','prf'=>'CBG-', 'cat'=>'Cubitac Belmont Cafe Glaze','tag'=>'belmont-cafe-glaze','rpr' => $cv[$w]); break;
	case 'ISB-':  $r2 = array('ttl'=>'Bergen Shale',      'slg'=>'bergen-shale',      'prf'=>'ISB-', 'cat'=>'Cubitac Bergen Shale',      'tag'=>'bergen-shale', 'rpr' => $cv[$w]); break;
	case 'ILB-':  $r2 = array('ttl'=>'Bergen Latte',      'slg'=>'bergen-latte',      'prf'=>'ILB-', 'cat'=>'Cubitac Bergen Latte',      'tag'=>'bergen-latte', 'rpr' => $cv[$w]); break;
	case 'LNPG-': $r2 = array('ttl'=>'Sofia Pewter',      'slg'=>'sofia-pewter',      'prf'=>'LNPG-','cat'=>'Cubitac Sofia Pewter',      'tag'=>'sofia-pewter', 'rpr' => $cv[$w]); break;
	case 'LNSG-': $r2 = array('ttl'=>'Sofia Sable',       'slg'=>'sofia-sable',       'prf'=>'LNSG-','cat'=>'Cubitac Sofia Sable',       'tag'=>'sofia-sable',  'rpr' => $cv[$w]); break;
	case 'LNCG-': $r2 = array('ttl'=>'Sofia Caramel',     'slg'=>'sofia-caramel',     'prf'=>'LNCG-','cat'=>'Cubitac Sofia Caramel',     'tag'=>'sofia-caramel','rpr' => $cv[$w]); break;
	
};



// $r4 = array(
// 'ttl' => 'Ridgefield Pastel',
// 'slg' => 'ridgefield-pastel',
// 'prf' => 'BPR-',
// 'cat' => 'Cubitac Ridgefield Pastel',
// 'tag' => 'ridgefield-pastel'
// );


// $r2 = array(
// 'ttl' => 'Ridgefield Latte',
// 'slg' => 'ridgefield-latte',
// 'prf' => 'BLR-',
// 'cat' => 'Cubitac Ridgefield Latte',
// 'tag' => 'ridgefield-latte',
// 'rpr' => $cv[$w]
// );
	

hdpl($probj, $r4, $r2);
	
	
//echo $ln . ' - '.$smplid.'<br>'; 		
	
	return;
}






////////////////////////////////////////////////////////////////////////////////////
function hdpl($prod, $rp4, $rp2){

$pid = $prod->id;

$pcat = wc_get_product_terms($pid, 'product_cat');
$ptag = wc_get_product_terms($pid, 'product_tag');

$catarr = replarray($pcat, $rp4['cat'], $rp2['cat']);
$tagarr = replarray($ptag, $rp4['tag'], $rp2['tag']);

$ir['ttl'] = preg_replace("/$rp4[ttl]/", $rp2['ttl'], preg_replace('/ \(Copy\)/','', $prod->name)); // nameoff($pobj->name);
$ir['slg'] = preg_replace('/[-]{2,7}/', '-', strtolower( preg_replace('/\s/','-', $ir['ttl']))) ; // slugoff($arr['ttl']);
$ir['sku'] = preg_replace("/^$rp4[prf]/", $rp2['prf'], preg_replace('/-\d$/', '', $prod->sku)); //skuoff($pobj->sku);

$ir['nrpr'] = intval($rp2['rpr']);

$ir['catarr']  = $catarr;
$ir['tagarr']  = $tagarr;


// echo 
	// $ir['sku'] . ' : ' . 
	// $ir['ttl'] . ' : ' . 
	// $ir['slg'] . ' : ' . 
	// $ir['nrpr'] . '<br>';


$vars = ($prod AND ( $prod->is_type( 'variable' ) ))?$prod->get_available_variations():NULL;
if($vars){
	foreach ($vars as $key => $v) {
//		$vr = hndplct($v['variation_id']);
		$prdv = wc_get_product($v['variation_id']);
//		$vr['ttl'] = preg_replace("/$rp4[ttl]/", $rp2['ttl'], preg_replace('/ \(Copy\)/','', $prdv->name)); // nameoff($pobj->name);
//		$vr['slg'] = preg_replace('/[-]{2,7}/', '-', strtolower( preg_replace('/\s/','-', $ir['ttl']))) ; // slugoff($arr['ttl']);
		$vr['sku'] = preg_replace("/^$rp4[prf]/", $rp2['prf'], preg_replace('/-\d$/', '', $prdv->sku)); //skuoff($pobj->sku);
		$vr['nrpr'] = intval($rp2['rpr']);

		chngvrplmnt($v['variation_id'], $vr);
// echo 
	// $vr['sku'] . ' : ' . 
	// $vr['nrpr'] . '<br>';
		}
	}

	chngrplmnt($prod, $ir);
	
	// foreach($catarr as $ct){ echo $ct.', ';} echo '<br>';
	// foreach($tagarr as $ft){ echo $ft.', ';} echo '<br>';
	
	
	return;
}


function chngrplmnt($prod, $ir){
$pid = $prod->id;
wp_set_object_terms( $pid, $ir['catarr'], 'product_cat' );
wp_set_object_terms( $pid, $ir['tagarr'], 'product_tag' );

$prod->set_name($ir['ttl']);
$prod->set_sku($ir['sku']);
$prod->set_regular_price((float)$ir['nrpr']);
$prod->set_price(NULL);

$prod->save();

//chngvrplmnt($pid, $ir);

$uargs = array(
	'ID' => $pid, 
	'post_slug'     => $ir['slg'],
	'post_content'  => $ir['ttl'],
	'post_status'   => 'publish',
	);		

wp_update_post( $uargs ); 

return;	
}


function chngvrplmnt($pid, $ir){
//$pid = $prod->id;

update_post_meta( $pid, '_sku', $ir['sku'] );
update_post_meta( $pid, '_regular_price', (float)$ir['nrpr'] );
update_post_meta( $pid, '_price', NULL );

return;	
}

	
?>







