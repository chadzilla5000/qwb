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
error_reporting(E_ALL | E_STRICT);
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
///////////////////////////////////////////////////////////////////////////////
$brand = (isset($_GET['br']))?$_GET['br']:NULL;
$sline = (isset($_POST['slline']))?$_POST['slline']:NULL;
$postp = NULL;
$dflag = true;


//$lines = get_allines($vnd, $vnd2);

ini_set('memory_limit', '2048M');
set_time_limit ( 1500 );


$subc = $sku = $chckbx = NULL;

$ofs = 0;
$ppp = 30000;

$wsiarr = getwsitems2($brand, $ppp, $ofs);
$i=0;


$r4 = array(
'ttl' => 'Arcadia White Shaker',
'slg' => 'arcadia-white-shaker',
'prf' => '-ACW',
'cat' => 'GHI Arcadia White Shaker',
'tag' => 'arcadia-white-shaker'
);
$r2 = array(
'ttl' => 'Concord Blue',
'slg' => 'concord-blue',
'prf' => '-CCB',
'cat' => 'GHI Concord Blue',
'tag' => 'concord-blue'
);



foreach($_POST['vn_skubx'] as $n=>$v){
	$mr = explode("::",$v);
	$fsk = $sku2lk = NULL;
	if(preg_match("/^(.*)($mr[1])$/", $mr[0], $m)){
		switch($brand){
			case 'forevermark': 	$sku2lk = $sline.'-'.$m[1]; break;
			case 'ghi': 			$sku2lk = $m[1].'-'.$sline; break;
			default: break;
			}
		foreach($wsiarr as $wsarr){ // Cycle begin /////////////////////////////////////////////////////
			if($sku2lk==$wsarr['sku']){
				$chckbx = '<input type="checkbox" name="'.$m[1].'-'.$m[2].'" value="'.$wsarr['sku'].'" />';
				$fsk = $wsarr['sku'];	
				

//				$probj = $wc_adp->product_duplicate( wc_get_product($ws['id']) );
//				echo $probj->id.'<br>';


//lstdpl($probj, $r4, $r2);


				
				
				}
			}  // Cycle end  ////////////////////////////////////////////////////////////////////
		}
		
	$subc .='
<tr><td>'.++$i.'</td>
	<td>'.$chckbx.'</td>
	<td>'.$mr[0].'</td>
	<td>'.$fsk.'</td>
	<td></td>
</tr>';
	}


$pgcontent = '
<form method="post" name="bfmm32" action="#">
<table class="Tbl" cellspacing="1">
<tr><th></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
</tr>
<tr><th style="width: 30px;"></th>
	<th style="width: 10px;"></th>
	<th style="width: 100px;"></th>
	<th style="width: 100px;"></th>
	<th><input type="submit" name="submit_2_dpl" value="Duplicate" /></th>
</tr>
'.$subc.'
</table>
</form>
';

$page_title			= 'UMS - Duplicate product';
$hsct				= '';
$page_description	= '';
$keywords			= '';
$head_ext			= '';
require_once('inc/_shell.php'); /////////////////////////
////////////////////////////////////////////////////////
///////////////////////////////////////////////////////
//////////////////////////////////////////////////////
/////////////////////////////////////////////////////

///////////////////////////////////////////////////
///////////////// Functions //////////////////////
/////////////////////////////////////////////////

function lstdpl($prod, $rp4, $rp2){
$pid = $prod->id;
$pcat = wc_get_product_terms($pid, 'product_cat');
$ptag = wc_get_product_terms($pid, 'product_tag');
$catarr = replarray($pcat, $rp4['cat'], $rp2['cat']);
$tagarr = replarray($ptag, $rp4['tag'], $rp2['tag']);
$ir['ttl'] = preg_replace("/$rp4[ttl]/", $rp2['ttl'], preg_replace('/ \(Copy\)/','', $prod->name)); // nameoff($pobj->name);
$ir['ttl'] = preg_replace('/ \(PCG\)-/','', $ir['ttl']);

$ir['slg'] = preg_replace('/[-]{2,7}/', '-', strtolower( preg_replace('/\s/','-', $ir['ttl']))) ; // slugoff($arr['ttl']);
$ir['sku'] = preg_replace("/$rp4[prf]$/", $rp2['prf'], preg_replace('/-\d$/', '', $prod->sku)); //skuoff($pobj->sku);
$ir['catarr']  = $catarr;
$ir['tagarr']  = $tagarr;
echo 
	$ir['sku'] . ' : ' . 
	$ir['ttl'] . ' : ' . 
	$ir['slg'] . ' : ' .'<br>';
//	chngrplmnt($prod, $ir);
	
	foreach($catarr as $ct){ echo $ct.', ';} echo '<br>';
	foreach($tagarr as $ft){ echo $ft.', ';} echo '<br>';
	
	
	return;
}

function chngrplmnt($prod, $ir){ //////// Actually updates product ///////
$pid = $prod->id;
wp_set_object_terms( $pid, $ir['catarr'], 'product_cat' );
wp_set_object_terms( $pid, $ir['tagarr'], 'product_tag' );
$prod->set_name($ir['ttl']);
$prod->set_sku($ir['sku']);
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

function replarray($parr, $nsrch, $nrplc){
	$r = array();
	foreach($parr as $k=>$v){
		if($v->name==$nsrch){ array_push($r, $nrplc); }
		else { array_push($r, $v->name); }
		}
	return $r;
}



?>