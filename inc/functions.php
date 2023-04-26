<?php

require_once SITE_CONFIG_PATH.'/wp-config.php';


//require_once (isset($_POST['wsite']) AND $_POST['wsite']=='live') ? '/home/waverlycabinets/public_html/wp-config.php' : '/home/waverlycabinets/public_html/test/wp-config.php';

function getdbh(){
	$dbh = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	if ($mysqli->connect_error) { die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error); }
	return $dbh;
	}

function getcsvar($file){
	$csv = array_map('str_getcsv', file($file));
	array_walk($csv, function(&$a) use ($csv) {
	});
return $csv;
}

function getnprc($m, $fl){
	$pv=NULL;
	foreach ($fl as $fk=>$fv){
		$fv[0] = strtoupper($fv[0]);				
		if(($m[1]=='BLR')  AND ($fv[0]==$m[3]) AND ($fv[11]=='B')){ $pv = $fv[2]; }		
		if(($m[1]=='BPR')  AND ($fv[0]==$m[3]) AND ($fv[11]=='B')){ $pv = $fv[4]; }		
		if(($m[1]=='BLO')  AND ($fv[0]==$m[3]) AND ($fv[11]=='B')){ $pv = $fv[3]; }		
		if(($m[1]=='BPO')  AND ($fv[0]==$m[3]) AND ($fv[11]=='B')){ $pv = $fv[5]; }		

		if(($m[1]=='ILB')  AND ($fv[0]==$m[3]) AND ($fv[11]=='I')){ $pv = $fv[4]; }		
		if(($m[1]=='ISB')  AND ($fv[0]==$m[3]) AND ($fv[11]=='I')){ $pv = $fv[3]; }		
		if(($m[1]=='LNPG') AND ($fv[0]==$m[3]) AND ($fv[11]=='I')){ $pv = $fv[5]; }		
		if(($m[1]=='LNCG') AND ($fv[0]==$m[3]) AND ($fv[11]=='I')){ $pv = $fv[7]; }		
		if(($m[1]=='LNSG') AND ($fv[0]==$m[3]) AND ($fv[11]=='I')){ $pv = $fv[6]; }		
		if(($m[1]=='CBG')  AND ($fv[0]==$m[3]) AND ($fv[11]=='I')){ $pv = $fv[2]; }		

		if(($m[1]=='LN')   AND ($fv[0]==$m[3]) AND ($fv[11]=='P')){ $pv = $fv[7]; }		
		if(($m[1]=='CN')   AND ($fv[0]==$m[3]) AND ($fv[11]=='P')){ $pv = $fv[4]; }		
		if(($m[1]=='LM')   AND ($fv[0]==$m[3]) AND ($fv[11]=='P')){ $pv = $fv[5]; }		
		if(($m[1]=='SM')   AND ($fv[0]==$m[3]) AND ($fv[11]=='P')){ $pv = $fv[9]; }		
		if(($m[1]=='LD')   AND ($fv[0]==$m[3]) AND ($fv[11]=='P')){ $pv = $fv[6]; }		
		if(($m[1]=='SD')   AND ($fv[0]==$m[3]) AND ($fv[11]=='P')){ $pv = $fv[8]; }		
		if(($m[1]=='CD')   AND ($fv[0]==$m[3]) AND ($fv[11]=='P')){ $pv = $fv[3]; }		
		if(($m[1]=='ED')   AND ($fv[0]==$m[3]) AND ($fv[11]=='P')){ $pv = $fv[2]; }	
		}					
	return $pv;
}


function comprc($m, $fl){
	$p=NULL;
//	foreach ($fl as $fk=>$fv){
		$fl[0] = strtoupper($fl[0]);				
		if(($m[1]=='BLR')  AND ($fl[0]==$m[3]) AND ($fl[11]=='B')){ $p = $fl[2]; }		
		if(($m[1]=='BPR')  AND ($fl[0]==$m[3]) AND ($fl[11]=='B')){ $p = $fl[4]; }		
		if(($m[1]=='BLO')  AND ($fl[0]==$m[3]) AND ($fl[11]=='B')){ $p = $fl[3]; }		
		if(($m[1]=='BPO')  AND ($fl[0]==$m[3]) AND ($fl[11]=='B')){ $p = $fl[5]; }		

		if(($m[1]=='ILB')  AND ($fl[0]==$m[3]) AND ($fl[11]=='I')){ $p = $fl[4]; }		
		if(($m[1]=='ISB')  AND ($fl[0]==$m[3]) AND ($fl[11]=='I')){ $p = $fl[3]; }		
		if(($m[1]=='LNPG') AND ($fl[0]==$m[3]) AND ($fl[11]=='I')){ $p = $fl[5]; }		
		if(($m[1]=='LNCG') AND ($fl[0]==$m[3]) AND ($fl[11]=='I')){ $p = $fl[7]; }		
		if(($m[1]=='LNSG') AND ($fl[0]==$m[3]) AND ($fl[11]=='I')){ $p = $fl[6]; }		
		if(($m[1]=='CBG')  AND ($fl[0]==$m[3]) AND ($fl[11]=='I')){ $p = $fl[2]; }		

		if(($m[1]=='LN')   AND ($fl[0]==$m[3]) AND ($fl[11]=='P')){ $p = $fl[7]; }		
		if(($m[1]=='CN')   AND ($fl[0]==$m[3]) AND ($fl[11]=='P')){ $p = $fl[4]; }		
		if(($m[1]=='LM')   AND ($fl[0]==$m[3]) AND ($fl[11]=='P')){ $p = $fl[5]; }		
		if(($m[1]=='SM')   AND ($fl[0]==$m[3]) AND ($fl[11]=='P')){ $p = $fl[9]; }		
		if(($m[1]=='LD')   AND ($fl[0]==$m[3]) AND ($fl[11]=='P')){ $p = $fl[6]; }		
		if(($m[1]=='SD')   AND ($fl[0]==$m[3]) AND ($fl[11]=='P')){ $p = $fl[8]; }		
		if(($m[1]=='CD')   AND ($fl[0]==$m[3]) AND ($fl[11]=='P')){ $p = $fl[3]; }		
		if(($m[1]=='ED')   AND ($fl[0]==$m[3]) AND ($fl[11]=='P')){ $p = $fl[2]; }	
//		}					
	return $p;
}

function comprcVL($m, $fl){
	$p=NULL;
//	foreach ($fl as $fk=>$fv){
		$fl['Sku'] = strtoupper($fl['Sku']);				
		if(($m[1]=='BLR')  AND ($fl['Sku']==$m[3]) AND ($fl['Serie']=='Basic')){ $p = $fl[2]; }		
		if(($m[1]=='BPR')  AND ($fl['Sku']==$m[3]) AND ($fl['Serie']=='Basic')){ $p = $fl[4]; }		
		if(($m[1]=='BLO')  AND ($fl['Sku']==$m[3]) AND ($fl['Serie']=='Basic')){ $p = $fl[3]; }		
		if(($m[1]=='BPO')  AND ($fl['Sku']==$m[3]) AND ($fl['Serie']=='Basic')){ $p = $fl[5]; }		

		if(($m[1]=='ILB')  AND ($fl['Sku']==$m[3]) AND ($fl['Serie']=='Imperial')){ $p = $fl[4]; }		
		if(($m[1]=='ISB')  AND ($fl['Sku']==$m[3]) AND ($fl['Serie']=='Imperial')){ $p = $fl[3]; }		
		if(($m[1]=='LNPG') AND ($fl['Sku']==$m[3]) AND ($fl['Serie']=='Imperial')){ $p = $fl[5]; }		
		if(($m[1]=='LNCG') AND ($fl['Sku']==$m[3]) AND ($fl['Serie']=='Imperial')){ $p = $fl[7]; }		
		if(($m[1]=='LNSG') AND ($fl['Sku']==$m[3]) AND ($fl['Serie']=='Imperial')){ $p = $fl[6]; }		
		if(($m[1]=='CBG')  AND ($fl['Sku']==$m[3]) AND ($fl['Serie']=='Imperial')){ $p = $fl[2]; }		

		if(($m[1]=='LN')   AND ($fl['Sku']==$m[3]) AND ($fl['Serie']=='Prestige')){ $p = $fl[7]; }		
		if(($m[1]=='CN')   AND ($fl['Sku']==$m[3]) AND ($fl['Serie']=='Prestige')){ $p = $fl[4]; }		
		if(($m[1]=='LM')   AND ($fl['Sku']==$m[3]) AND ($fl['Serie']=='Prestige')){ $p = $fl[5]; }		
		if(($m[1]=='SM')   AND ($fl['Sku']==$m[3]) AND ($fl['Serie']=='Prestige')){ $p = $fl[9]; }		
		if(($m[1]=='LD')   AND ($fl['Sku']==$m[3]) AND ($fl['Serie']=='Prestige')){ $p = $fl[6]; }		
		if(($m[1]=='SD')   AND ($fl['Sku']==$m[3]) AND ($fl['Serie']=='Prestige')){ $p = $fl[8]; }		
		if(($m[1]=='CD')   AND ($fl['Sku']==$m[3]) AND ($fl['Serie']=='Prestige')){ $p = $fl[3]; }		
		if(($m[1]=='ED')   AND ($fl['Sku']==$m[3]) AND ($fl['Serie']=='Prestige')){ $p = $fl[2]; }	
//		}					
	return $p;
}


function postitem($darr){
	
	$post = array(
    'post_author' => $user_id,
    'post_content' => $darr['descr'],
    'post_status' => "publish",
    'post_title' => $darr['ttl'],
    'post_parent' => '',
    'post_type' => "product",
);

//Create post
$post_id = wp_insert_post( $post, $wp_error );

if($post_id){
    $attach_id = get_post_meta($product->parent_id, "_thumbnail_id", true);
    add_post_meta($post_id, '_thumbnail_id', $attach_id);
}

wp_set_object_terms( $post_id, $darr['ctg'], 'product_cat' );
wp_set_object_terms( $post_id, 'simple', 'product_type');

update_post_meta( $post_id, '_visibility', 'visible' );
update_post_meta( $post_id, '_stock_status', 'instock');
update_post_meta( $post_id, 'total_sales', '0');
update_post_meta( $post_id, '_downloadable', 'no');
update_post_meta( $post_id, '_virtual', 'no');
update_post_meta( $post_id, '_regular_price', $darr['prc'] );
update_post_meta( $post_id, '_sale_price', "" );
update_post_meta( $post_id, '_purchase_note', "" );
update_post_meta( $post_id, '_featured', "no" );
update_post_meta( $post_id, '_weight', "" );
update_post_meta( $post_id, '_length', "" );
update_post_meta( $post_id, '_width', "" );
update_post_meta( $post_id, '_height', "" );
update_post_meta( $post_id, '_sku', $darr['sku']);
update_post_meta( $post_id, '_product_attributes', array());
update_post_meta( $post_id, '_sale_price_dates_from', "" );
update_post_meta( $post_id, '_sale_price_dates_to', "" );
update_post_meta( $post_id, '_price', "" );
update_post_meta( $post_id, '_sold_individually', "" );
update_post_meta( $post_id, '_manage_stock', "no" );
update_post_meta( $post_id, '_backorders', "no" );
update_post_meta( $post_id, '_stock', "" );

// file paths will be stored in an array keyed off md5(file path)
$downdloadArray =array('name'=>"Test", 'file' => $uploadDIR['baseurl']."/video/".$video);
$file_path =md5($uploadDIR['baseurl']."/video/".$video);

$_file_paths[  $file_path  ] = $downdloadArray;
// grant permission to any newly added files on any existing orders for this product
// do_action( 'woocommerce_process_product_file_download_paths', $post_id, 0, $downdloadArray );
update_post_meta( $post_id, '_downloadable_files', $_file_paths);
update_post_meta( $post_id, '_download_limit', '');
update_post_meta( $post_id, '_download_expiry', '');
update_post_meta( $post_id, '_download_type', '');
update_post_meta( $post_id, '_product_image_gallery', '');

return $post_id;
}

function bldcat($ctree){
	$lcat = NULL;
	$carr = explode('/', $ctree);
	for ($i=0;$i<sizeof($carr);$i++){
		$pcat=($i>0)?term_exists($carr[$i-1]):term_exists('MSI Tiles & Flooring'); 
		if(!term_exists($carr[$i])){
			$texonomy_ID = wp_insert_term($carr[$i], 'product_cat', array('description'=> '', 'slug' => $carr[$i], 'parent'=> $pcat));
			}
		$lcat = $carr[$i];
		}	
	return $lcat;
}



function dlpic($v){ // modify pic link

$url = (preg_match('/https:/', "$v")) ? $v : 'https://'.$v;
$agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';

$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_VERBOSE, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, $agent);
curl_setopt($ch, CURLOPT_URL,$url);
$result=curl_exec($ch);

if(preg_match('/(.*)(Object Moved\<\/h1\>This document may be found \<a HREF=")(.*)(".*)/', $result, $m2)) { // If link redirects to MSI -
	return dlpic($m2[3]); }                          // function calls itself with redirecting link as an argument until it gets final URL

$cl = array();
//echo "<textarea rows='20' cols='80'>$result</textarea>"; return;

preg_match_all('/(data-image=")(https:\/\/cdn.msisurfaces.com\/images\/[\w\/\-\.]+)("><img class=)/', "$result", $m, PREG_OFFSET_CAPTURE);

foreach($m[2] as $q){ array_push($cl, $q[0]); }
return $cl;
}

function piclnk($v){
	if(1){	return dlpic($v); }
	else {
		$mstr = NULL;
		$parr = explode(',',dlpic($v));
		foreach($parr as $m){
			$mstr .= $m . '<br>';//'<a href="'.$m.'" target="_blank" title="View full size picture"><img style="max-width: 20px;" src="'.$m.'" /></a> ';
			}
		return $mstr; //'<a href="'.$plnk.'" target="_blank" title="View full size picture"><img style="max-width: 20px;" src="'.$plnk.'" /></a>';
		}
	}


function parseImg($image_n, $post_id, $np)
{
    $upload_dir = wp_upload_dir();
    $image_data = file_get_contents($image_n);
    $filename = 'im-' . $np . '-' . basename($image_n);
	
    if (wp_mkdir_p($upload_dir['path'])) { $file = $upload_dir['path'] . '/' . $filename; } 
	else                                 { $file = $upload_dir['basedir'] . '/' . $filename; }

    file_put_contents($file, $image_data);

    $wp_filetype = wp_check_filetype($filename, null);
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => sanitize_file_name($filename),
        'post_content' => '',
        'post_status' => 'inherit',
    );
    $attach_id = wp_insert_attachment($attachment, $file, $post_id);
    require_once ABSPATH . 'wp-admin/includes/image.php';
    $attach_data = wp_generate_attachment_metadata($attach_id, $file);
    $res1 = wp_update_attachment_metadata($attach_id, $attach_data);
    return $attach_id;
}

function metapdd($id, $mkey, $d, $gID){ $dv = NULL; /////////////////////////////////////
$kv = NULL;
	$mkhsh = '_cfwc_'.$gID.'_'. sha1($mkey);
//	$mv  = strtolower($mkey);
//	$mv = '_'.preg_replace('/\s/', '_', strtolower($mkey));
//	$kv = mupd($id, $mv, $mkhsh);
	$kv = mupd($id, $mkhsh, $d);
	if($kv){
global $wpdb;

if($wpdb->get_results("SELECT * FROM ".$wpdb->prefix."acf_filter WHERE ACField = '$mkhsh' AND FGroup = '$gID'")){
	$wpdb->query("UPDATE ".$wpdb->prefix."acf_filter SET Title = '$mkey' WHERE ACField = '$mkhsh' AND FGroup = '$gID'");	
}
else{
	$wpdb->query("INSERT INTO ".$wpdb->prefix."acf_filter (ACField,FGroup,Title) VALUES ('$mkhsh','$gID','$mkey')");
}

	}
	
	return;
}

function mupd($id, $mkey, $d){
	if(get_post_meta($id, $mkey, false)){ $dv = update_post_meta($id, $mkey, $d); }
	else                                { $dv = add_post_meta($id, $mkey, $d); }
	return $dv;  //////////////////////////////////////////////////////////////////

}



/////////////// MSI Tiles //////////////////////////////////////////////////////////////////////////////////////////////////////////

function initiles($csr){

	$post = array(
		'post_author'  => $user_id,
		'post_content' => $csr[17],
		'post_status'  => "publish",
		'post_title'   => $csr[0],
		'post_parent'  => '',
		'post_type'    => "product"
		);

//Create post /////////////////////////////////////
	$post_id = wp_insert_post( $post, $wp_error );
	if($post_id){
		// $attarr = NULL;
		// $parr = dlpic($csr[13]);
		
		// for ($i=0; $i<sizeof($parr); $i++) {
			// if($i){ $attarr[$i] = parseImg($parr[$i], $post_id, $i); }
			// else  { $attach_id  = parseImg($parr[0],  $post_id, $i); add_post_meta($post_id, '_thumbnail_id', $attach_id); }
			// }
		// update_post_meta ($post_id, '_product_image_gallery', join(',', $attarr));

		$mcat = array();
		$cats = explode(' - ', $csr['topcat'] . ' - '.$csr[16]);
		foreach($cats as $vr){
			$lc = bldcat($vr);
			array_push($mcat, $lc);
			}

		wp_set_object_terms( $post_id, $mcat,    'product_cat' );
		wp_set_object_terms( $post_id, 'simple', 'product_type');
		wp_set_object_terms( $post_id, 'msi_tiles', 'product_shipping_class');

		$prprp = ($csr[8]=='EACH' AND $csr[5]!='N/A')?1:0; ////////////////////////////////////////
		$prcum = ($prprp) ? $csr[10] : $csr[10] * $csr[6]; //////////////////////////////////////////////
		
$prsqf = ($prprp) ? number_format((($csr[5]>0)?$csr[9]/$csr[5]:0), 2): number_format($csr[9], 2);

		$slprc = floatval(number_format(($prcum / 0.51), 2));    ///////////////////////////////////////////////////////
		
		update_post_meta( $post_id, '_visibility',            'visible' );
		update_post_meta( $post_id, '_stock_status',          'instock');
		update_post_meta( $post_id, 'total_sales',            '0');
		update_post_meta( $post_id, '_downloadable',          'no');
		update_post_meta( $post_id, '_virtual',               'no');
		update_post_meta( $post_id, '_regular_price',         $slprc);
		update_post_meta( $post_id, '_sale_price',            "" );
		update_post_meta( $post_id, '_purchase_note',         "" );
		update_post_meta( $post_id, '_featured',              "no" );
		update_post_meta( $post_id, '_weight',                "" );
		update_post_meta( $post_id, '_height',                "" );
		update_post_meta( $post_id, '_sku',                   $csr[1]);
		update_post_meta( $post_id, '_product_attributes',    array());
		update_post_meta( $post_id, '_sale_price_dates_from', "" );
		update_post_meta( $post_id, '_sale_price_dates_to',   "" );
		update_post_meta( $post_id, '_price',                 "" );
		update_post_meta( $post_id, '_sold_individually',     "" );
		update_post_meta( $post_id, '_manage_stock',          "no" );
		update_post_meta( $post_id, '_backorders',            "no" );
		update_post_meta( $post_id, '_stock',                 "" );
	if(preg_match('/^([\d]+)([Xx]{1})([\d]+)$/', $csr[2], $m)){
		update_post_meta( $post_id, '_width',  $m[1] );
		update_post_meta( $post_id, '_length', $m[3] );
		}

// file paths will be stored in an array keyed off md5(file path)
		$downdloadArray =array('name'=>"Test", 'file' => $uploadDIR['baseurl']."/video/".$video);
		$file_path = md5($uploadDIR['baseurl']."/video/".$video);

		$_file_paths[  $file_path  ] = $downdloadArray;
// grant permission to any newly added files on any existing orders for this product
// do_action( 'woocommerce_process_product_file_download_paths', $post_id, 0, $downdloadArray );
		update_post_meta( $post_id, '_downloadable_files', $_file_paths);
		update_post_meta( $post_id, '_download_limit',     '');
		update_post_meta( $post_id, '_download_expiry',    '');
		update_post_meta( $post_id, '_download_type',      '');

		metapdd($post_id, 'SIZE',           $csr[2],  3 );
		metapdd($post_id, 'FINISH',         $csr[3],  3 );
		metapdd($post_id, 'THICKNESS',      $csr[4],  3 );
		metapdd($post_id, 'SQFT PER PIECE', $csr[5],  3 );
		metapdd($post_id, 'PIECES PER BOX', $csr[6],  3 );
		metapdd($post_id, 'SQFT PER BOX',   $csr[7],  3 );
		metapdd($post_id, 'U/M',            $csr[8],  3 );
		metapdd($post_id, 'PRICE / UOM',    $csr[9],  3 );
//		metapdd($post_id, 'Item Id',        $csr[12], 3 );
		metapdd($post_id, 'Web Link',       $csr[13], 3 );
		metapdd($post_id, 'Product Line',   $csr[14], 3 );
		metapdd($post_id, 'Material',       $csr[15], 3 );
		metapdd($post_id, 'Source',         $csr[19], 3 );
		metapdd($post_id, 'Creation Date',  $csr[21], 3 );
		metapdd($post_id, 'PRICE PER SQ FT',  $prsqf, 3 );
		metapdd($post_id, 'WtPerPc',        $csr[24], 3 );
		metapdd($post_id, 'PcLength',       $csr[25], 3 );
		metapdd($post_id, 'PcHeight',       $csr[26], 3 );
		metapdd($post_id, 'PcThickness',    $csr[27], 3 );
		metapdd($post_id, 'IsBox',          $csr[28], 3 );
		metapdd($post_id, 'WtPerBox',       $csr[29], 3 );
		metapdd($post_id, 'BoxLength',      $csr[30], 3 );
		metapdd($post_id, 'BoxHeight',      $csr[31], 3 );
		metapdd($post_id, 'BoxThickness',   $csr[32], 3 );
		return  $post_id;
		} //////////////////////////////////////////////////////////////////////////////////////////////////////// */
	
	return NULL;
}

function initilesample($csr){

//	$ttl =($picrr)?$csr[0].' (Sample)':$csr[0];

	$post = array(
		'post_author'  => $user_id,
		'post_content' => $csr[17],
		'post_status'  => "publish",
		'post_title'   => $csr[0].' ( SAMPLE )',
		'post_parent'  => '',
		'post_type'    => "product"
		);

//Create post /////////////////////////////////////
	$post_id = wp_insert_post( $post, $wp_error );
	if($post_id){


		wp_set_object_terms( $post_id, 'Samples',    'product_cat' );
		wp_set_object_terms( $post_id, 'simple', 'product_type');
		wp_set_object_terms( $post_id, 'tilesample', 'product_shipping_class');

		update_post_meta( $post_id, '_visibility',            'visible' );
		update_post_meta( $post_id, '_stock_status',          'instock');
		update_post_meta( $post_id, 'total_sales',            '0');
		update_post_meta( $post_id, '_downloadable',          'no');
		update_post_meta( $post_id, '_virtual',               'no');
		update_post_meta( $post_id, '_regular_price',         0);
		update_post_meta( $post_id, '_sale_price',            0);
		update_post_meta( $post_id, '_purchase_note',         "" );
		update_post_meta( $post_id, '_featured',              "no" );
		update_post_meta( $post_id, '_weight',                "" );
		update_post_meta( $post_id, '_height',                "" );
		update_post_meta( $post_id, '_sku',                   'SAMPLE-'.$csr[1]);
		update_post_meta( $post_id, '_product_attributes',    array());
		update_post_meta( $post_id, '_sale_price_dates_from', "" );
		update_post_meta( $post_id, '_sale_price_dates_to',   "" );
		update_post_meta( $post_id, '_price',                 0);
		update_post_meta( $post_id, '_sold_individually',     "" );
		update_post_meta( $post_id, '_manage_stock',          "no" );
		update_post_meta( $post_id, '_backorders',            "no" );
		update_post_meta( $post_id, '_stock',                 "" );
	if(preg_match('/^([\d]+)([Xx]{1})([\d]+)$/', $csr[2], $m)){
		update_post_meta( $post_id, '_width',  $m[1] );
		update_post_meta( $post_id, '_length', $m[3] );
		}

// file paths will be stored in an array keyed off md5(file path)
		$downdloadArray =array('name'=>"Test", 'file' => $uploadDIR['baseurl']."/video/".$video);
		$file_path =md5($uploadDIR['baseurl']."/video/".$video);

		$_file_paths[  $file_path  ] = $downdloadArray;
// grant permission to any newly added files on any existing orders for this product
// do_action( 'woocommerce_process_product_file_download_paths', $post_id, 0, $downdloadArray );
		update_post_meta( $post_id, '_downloadable_files', $_file_paths);
		update_post_meta( $post_id, '_download_limit',     '');
		update_post_meta( $post_id, '_download_expiry',    '');
		update_post_meta( $post_id, '_download_type',      '');

		metapdd($post_id, 'SIZE',           $csr[2], 3 );
		metapdd($post_id, 'FINISH',         $csr[3], 3 );
		metapdd($post_id, 'THICKNESS',      $csr[4], 3 );
		metapdd($post_id, 'SQFT PER PIECE', $csr[5], 3 );
		metapdd($post_id, 'PIECES PER BOX', $csr[6], 3 );
		metapdd($post_id, 'SQFT PER BOX',   $csr[7], 3 );
		metapdd($post_id, 'U/M',            $csr[8], 3 );
		metapdd($post_id, 'PRICE / UOM',    $csr[9], 3 );
//		metapdd($post_id, 'Item Id',        $csr[12], 3 );
		metapdd($post_id, 'Web Link',       $csr[13], 3 );
		metapdd($post_id, 'Product Line',   $csr[14], 3 );
		metapdd($post_id, 'Material',       $csr[15], 3 );
		metapdd($post_id, 'Source',         $csr[19], 3 );
		metapdd($post_id, 'Creation Date',  $csr[21], 3 );
		metapdd($post_id, 'PRICE PER SQ FT', floatval(number_format(0, 2)), 3 );
		metapdd($post_id, 'WtPerPc',        $csr[24], 3 );
		metapdd($post_id, 'PcLength',       $csr[25], 3 );
		metapdd($post_id, 'PcHeight',       $csr[26], 3 );
		metapdd($post_id, 'PcThickness',    $csr[27], 3 );
		metapdd($post_id, 'IsBox',          $csr[28], 3 );
		metapdd($post_id, 'WtPerBox',       $csr[29], 3 );
		metapdd($post_id, 'BoxLength',      $csr[30], 3 );
		metapdd($post_id, 'BoxHeight',      $csr[31], 3 );
		metapdd($post_id, 'BoxThickness',   $csr[32], 3 );
		return  $post_id;
		} //////////////////////////////////////////////////////////////////////////////////////////////////////// */
	
	return NULL;
}

function inisinks($csr){

	$post = array(
		'post_author'  => $user_id,
		'post_content' => $csr[17],
		'post_status'  => "publish",
		'post_title'   => $csr[0],
		'post_parent'  => '',
		'post_type'    => "product"
		);

//Create post /////////////////////////////////////
	$post_id = wp_insert_post( $post, $wp_error );
	if($post_id){
		// $attarr = NULL;
		// $parr = dlpic($csr[13]);
		
		// for ($i=0; $i<sizeof($parr); $i++) {
			// if($i){ $attarr[$i] = parseImg($parr[$i], $post_id, $i); }
			// else  { $attach_id  = parseImg($parr[0],  $post_id, $i); add_post_meta($post_id, '_thumbnail_id', $attach_id); }
			// }
		// update_post_meta ($post_id, '_product_image_gallery', join(',', $attarr));

		$mcat = array();
		$cats = explode(' - ', $csr['topcat'] . ' - ' . $csr[16]);
		foreach($cats as $vr){
			$lc = bldcat($vr);
			array_push($mcat, $lc);
			}

		wp_set_object_terms( $post_id, $mcat,    'product_cat' );
		wp_set_object_terms( $post_id, 'simple', 'product_type');
		wp_set_object_terms( $post_id, 'msi_tiles', 'product_shipping_class');

//		$prprp = ($csr[8]=='EACH' AND $csr[5]!='N/A')?1:0; ////////////////////////////////////////
//		$prcum = ($prprp) ? $csr[10] : $csr[10] * $csr[6]; //////////////////////////////////////////////
		
//$prsqf = ($prprp) ? number_format((($csr[5]>0)?$csr[9]/$csr[5]:0), 2): number_format($csr[9], 2);

		$slprc = floatval(number_format(($csr[10] / 0.51), 2));    ///////////////////////////////////////////////////////
		
		update_post_meta( $post_id, '_visibility',            'visible' );
		update_post_meta( $post_id, '_stock_status',          'instock');
		update_post_meta( $post_id, 'total_sales',            '0');
		update_post_meta( $post_id, '_downloadable',          'no');
		update_post_meta( $post_id, '_virtual',               'no');
		update_post_meta( $post_id, '_regular_price',         $slprc);
		update_post_meta( $post_id, '_sale_price',            "" );
		update_post_meta( $post_id, '_purchase_note',         "" );
		update_post_meta( $post_id, '_featured',              "no" );
		update_post_meta( $post_id, '_weight',                "" );
		update_post_meta( $post_id, '_height',                "" );
		update_post_meta( $post_id, '_sku',                   $csr[1]);
		update_post_meta( $post_id, '_product_attributes',    array());
		update_post_meta( $post_id, '_sale_price_dates_from', "" );
		update_post_meta( $post_id, '_sale_price_dates_to',   "" );
		update_post_meta( $post_id, '_price',                 "" );
		update_post_meta( $post_id, '_sold_individually',     "" );
		update_post_meta( $post_id, '_manage_stock',          "no" );
		update_post_meta( $post_id, '_backorders',            "no" );
		update_post_meta( $post_id, '_stock',                 "" );
	if(preg_match('/^([\d]+)([Xx]{1})([\d]+)$/', $csr[2], $m)){
		update_post_meta( $post_id, '_width',  $m[1] );
		update_post_meta( $post_id, '_length', $m[3] );
		}

// file paths will be stored in an array keyed off md5(file path)
		$downdloadArray =array('name'=>"Test", 'file' => $uploadDIR['baseurl']."/video/".$video);
		$file_path =md5($uploadDIR['baseurl']."/video/".$video);

		$_file_paths[  $file_path  ] = $downdloadArray;
// grant permission to any newly added files on any existing orders for this product
// do_action( 'woocommerce_process_product_file_download_paths', $post_id, 0, $downdloadArray );
		update_post_meta( $post_id, '_downloadable_files', $_file_paths);
		update_post_meta( $post_id, '_download_limit',     '');
		update_post_meta( $post_id, '_download_expiry',    '');
		update_post_meta( $post_id, '_download_type',      '');

		metapdd($post_id, 'SIZE',           $csr[2],  4 );
		metapdd($post_id, 'FINISH',         $csr[3],  4 );
//		metapdd($post_id, 'THICKNESS',      $csr[4],  4 );
//		metapdd($post_id, 'SQFT PER PIECE', $csr[5],  4 );
		metapdd($post_id, 'PIECES PER BOX', $csr[6],  4 );
//		metapdd($post_id, 'SQFT PER BOX',   $csr[7],  4 );
		metapdd($post_id, 'U/M',            $csr[8],  4 );
		metapdd($post_id, 'PRICE / UOM',    $csr[9],  4 );
//		metapdd($post_id, 'Item Id',        $csr[12], 4 );
		metapdd($post_id, 'Web Link',       $csr[13], 4 );
		metapdd($post_id, 'Product Line',   $csr[14], 4 );
		metapdd($post_id, 'Material',       $csr[15], 4 );
		metapdd($post_id, 'Brand',          $csr[19], 4 );
		metapdd($post_id, 'Creation Date',  $csr[21], 4 );
//		metapdd($post_id, 'PRICE PER SQ FT',  $prsqf, 4 );
		metapdd($post_id, 'Weight Per Pc',  $csr[24], 4 );
		metapdd($post_id, 'Length',         $csr[25], 4 );
		metapdd($post_id, 'Height',         $csr[26], 4 );
		metapdd($post_id, 'Thickness',      $csr[27], 4 );
		metapdd($post_id, 'IsBox',          $csr[28], 4 );
		metapdd($post_id, 'Weight Per Box', $csr[29], 4 );
		metapdd($post_id, 'Box Length',     $csr[30], 4 );
		metapdd($post_id, 'Box Height',     $csr[31], 4 );
		metapdd($post_id, 'Box Thickness',  $csr[32], 4 );
		return  $post_id;
		} //////////////////////////////////////////////////////////////////////////////////////////////////////// */
	
	return NULL;
}


function _______tblrow($csr){
		return '
<tr><td style="color: #ccc;">'.$dt.'</td>
	<td>'.$csr[0] .'</td> <!-- PRODUCT COLLECTION -->
	<td>'.$csr[1] .'</td> <!-- ItemNumber         -->
	<td>'.$csr[2] .'</td> <!-- SIZE               -->
	<td>'.$csr[3] .'</td> <!-- FINISH             -->
	<td>'.$csr[4] .'</td> <!-- THICKNESS          -->
	<td>'.$csr[5] .'</td> <!-- SQFT PER PIECE     -->
	<td>'.$csr[6] .'</td> <!-- PIECES PER BOX     -->
	<td>'.$csr[7] .'</td> <!-- SQFT PER BOX       -->
	<td>'.$csr[8] .'</td> <!-- U/M                -->
	<td>'.$csr[9] .'</td> <!-- PRICE / UOM        -->
	<td>'.$csr[10].'</td> <!-- PRICE /  EACH      -->
	<td>'.$csr[11].'</td> <!--                    -->
	<td>'.$csr[12].'</td> <!-- ItemNumber         -->
	<td>'.$csr[13].'</td> <!-- Web Link           -->
	<td>'.$csr[14].'</td> <!-- Product Line       -->
	<td>'.$csr[15].'</td> <!-- Material           -->
	<td>'.$csr[16].'</td> <!-- Product Category   -->
	<td>'.$csr[17].'</td> <!-- PRODUCT COLLECTION -->
	<td>'.$csr[18].'</td> <!-- Description        -->
	<td>'.$csr[19].'</td> <!-- Status             -->
	<td>'.$csr[20].'</td> <!-- Source             -->
	<td>'.$csr[21].'</td> <!-- Creation Date      -->
	<td>'.$csr[22].'</td> <!-- Comments           -->
	<td>'.$csr[24].'</td> <!-- WtPerPc            -->
	<td>'.$csr[25].'</td> <!-- PcLength           -->
	<td>'.$csr[26].'</td> <!-- PcHeight           -->
	<td>'.$csr[27].'</td> <!-- PcThickness        -->
	<td>'.$csr[28].'</td> <!-- IsBox              -->
	<td>'.$csr[29].'</td> <!-- WtPerBox           -->
	<td>'.$csr[30].'</td> <!-- BoxLength          -->
	<td>'.$csr[31].'</td> <!-- BoxHeight          -->
	<td>'.$csr[32].'</td> <!-- BoxThickness       -->
</tr>';	

}








function initrim($csr){

	$post = array(
		'post_author'  => $user_id,
		'post_content' => $csr[0],
		'post_status'  => "publish",
		'post_title'   => $csr[0],
		'post_parent'  => '',
		'post_type'    => "product"
		);

//Create post /////////////////////////////////////
	$post_id = wp_insert_post( $post, $wp_error );
	if($post_id){
		$cat = 'Trims';
		wp_set_object_terms( $post_id, $cat,    'product_cat' );
		wp_set_object_terms( $post_id, 'simple', 'product_type');
		wp_set_object_terms( $post_id, 'msi_tiles', 'product_shipping_class');

		$slprc = floatval(number_format((35.05 / 0.51), 2));    ///////////////////////////////////////////////////////
		
		update_post_meta( $post_id, '_visibility',            'visible' );
		update_post_meta( $post_id, '_stock_status',          'instock');
		update_post_meta( $post_id, 'total_sales',            '0');
		update_post_meta( $post_id, '_downloadable',          'no');
		update_post_meta( $post_id, '_virtual',               'no');
		update_post_meta( $post_id, '_regular_price',         $slprc);
		update_post_meta( $post_id, '_sale_price',            "" );
		update_post_meta( $post_id, '_purchase_note',         "" );
		update_post_meta( $post_id, '_featured',              "no" );
		update_post_meta( $post_id, '_weight',                "" );
		update_post_meta( $post_id, '_height',                "" );
		update_post_meta( $post_id, '_sku',                   $csr[1]);
		update_post_meta( $post_id, '_product_attributes',    array());
		update_post_meta( $post_id, '_sale_price_dates_from', "" );
		update_post_meta( $post_id, '_sale_price_dates_to',   "" );
		update_post_meta( $post_id, '_price',                 "" );
		update_post_meta( $post_id, '_sold_individually',     "" );
		update_post_meta( $post_id, '_manage_stock',          "no" );
		update_post_meta( $post_id, '_backorders',            "no" );
		update_post_meta( $post_id, '_stock',                 "" );
		update_post_meta( $post_id, '_length', 94 );

// file paths will be stored in an array keyed off md5(file path)
		$downdloadArray =array('name'=>"Test", 'file' => $uploadDIR['baseurl']."/video/".$video);
		$file_path =md5($uploadDIR['baseurl']."/video/".$video);

		$_file_paths[  $file_path  ] = $downdloadArray;
// grant permission to any newly added files on any existing orders for this product
// do_action( 'woocommerce_process_product_file_download_paths', $post_id, 0, $downdloadArray );
		update_post_meta( $post_id, '_downloadable_files', $_file_paths);
		update_post_meta( $post_id, '_download_limit',     '');
		update_post_meta( $post_id, '_download_expiry',    '');
		update_post_meta( $post_id, '_download_type',      '');

		metapdd($post_id, 'FINISH',         'LOW GLOSS',  7 );
		metapdd($post_id, 'U/M',            'EACH',  7 );
		metapdd($post_id, 'Web Link',       $csr[13], 7 );
		metapdd($post_id, 'Product Line',   'LVT', 7 );
		metapdd($post_id, 'Brand',          'MSI', 7 );
		return  $post_id;
		} //////////////////////////////////////////////////////////////////////////////////////////////////////// */
	
	return NULL;
}




////////////////////////////////////////////////////////
function msi_tblrow($csr, $d, $df=0){
	
$row = ($csr['tt']>1)?'':'
<tr><th>#</th>
	<th>Title</th>
	<th>ID</th>
	<th>Sku</th>
</tr>
';
$row .= '
<tr><td style="color: #999;">'.$csr['tt'].' &nbsp;&nbsp;'.$d.'</td>
	<td>'.$csr[0] .'</td> <!-- PRODUCT COLLECTION -->
	<td>'.$csr['stat'].'</td> <!--                -->
	<td>'.$csr[1] .'</td> <!-- ItemNumber         -->
';

if($df==-1){ $row .= '
	<td>'.$csr['ex_1'].'</td>
	<td>'.$csr['ex_2'].'</td>
	<td>'.$csr['ex_3'].'</td>
	<td>'.$csr['ex_4'].'</td>
	<td>'.$csr['ex_5'].'</td>
	<td>'.$csr['ex_6'].'</td>
	<td>'.$csr['ex_7'].'</td>
	<td>'.$csr['ex_8'].'</td>
	<td>'.$csr['ex_9'].'</td>
	<td>'.$csr['ex_10'].'</td>
';	}

if($df==1){	$row .= '
	<td>'.$csr[2] .'</td> <!-- SIZE               -->
	<td>'.$csr[3] .'</td> <!-- FINISH             -->
	<td>'.$csr[4] .'</td> <!-- THICKNESS          -->
	<td>'.$csr[5] .'</td> <!-- SQFT PER PIECE     -->
	<td>'.$csr[6] .'</td> <!-- PIECES PER BOX     -->
	<td>'.$csr[7] .'</td> <!-- SQFT PER BOX       -->
	<td>'.$csr[8] .'</td> <!-- U/M                -->
	<td>'.$csr[9] .'</td> <!-- PRICE / UOM        -->
	<td>'.$csr[10].'</td> <!-- PRICE /  EACH      -->
	<td>'.$csr[11].'</td> <!--                    -->
';	}

if($df==2){	$row .= '
	<td>'.$csr[12].'</td> <!-- ItemNumber         -->
	<td>'.$csr[13].'</td> <!-- Web Link           -->
	<td>'.$csr[14].'</td> <!-- Product Line       -->
	<td>'.$csr[15].'</td> <!-- Material           -->
	<td>'.$csr[16].'</td> <!-- Product Category   -->
';	}

if($df==3){	$row .= '
	<td>'.$csr[17].'</td> <!-- PRODUCT COLLECTION -->
	<td>'.$csr[18].'</td> <!-- Description        -->
	<td>'.$csr[19].'</td> <!-- Status             -->
	<td>'.$csr[20].'</td> <!-- Source             -->
	<td>'.$csr[21].'</td> <!-- Creation Date      -->
	<td>'.$csr[22].'</td> <!-- Comments           -->
';	}

if($df==4){	$row .= '
	<td>'.$csr[24].'</td> <!-- WtPerPc            -->
	<td>'.$csr[25].'</td> <!-- PcLength           -->
	<td>'.$csr[26].'</td> <!-- PcHeight           -->
	<td>'.$csr[27].'</td> <!-- PcThickness        -->
	<td>'.$csr[28].'</td> <!-- IsBox              -->
	<td>'.$csr[29].'</td> <!-- WtPerBox           -->
	<td>'.$csr[30].'</td> <!-- BoxLength          -->
	<td>'.$csr[31].'</td> <!-- BoxHeight          -->
	<td>'.$csr[32].'</td> <!-- BoxThickness       -->
';	}

return $row . '</tr>';
}



////////////////////////////////////////////////////////
function parstblrow($csr, $d, $df=0){






	
}




function set_imgattr( $imgID, $pID ) { 
	$product = wc_get_product( $pID );

	if ( wp_attachment_is_image( $imgID ) ) { // Check if uploaded file is an image, else do nothing
		$img_title = $product->get_name();
//		$slug = $product->get_slug();
		$catobj = get_the_terms($pID, 'product_cat')[0];


	//	$img_title = preg_replace('/&amp;amp;/', '&', $img_title);
	//	$img_sku   = $product->get_sku();


//echo $tcat.'  -  '.$slug.'<br>'; return;

//		$terms = $product->get_attributes(); //get_the_terms($pID);
//		$term = checkat($pID, 0);
		$categoryslug  = $catobj->slug;
		$tcat = $catobj->name;
		$tcat = preg_replace('/amp;/', '', $tcat);
		$altxt   = $categoryslug . '-' . $product->get_slug() . '-' . $product->get_sku();
		$caption = $tcat . ' - ' . $img_title;
		$descrip = $tcat . ' - ' . $img_title;

	//	$img_title = get_post( $imgID )->post_title;
	//	$img_title = preg_replace( '%\s*[-_\s]+\s*%', ' ', $img_title ); // Sanitize the title: remove hyphens, underscores & extra spaces:

	// Sanitize the title: capitalize first letter of every word
	// (other letters lower case):

	//	$img_title = ucwords( strtolower( $img_title ) );

	// Create an array with the image meta (Title, Caption, Description) to be updated
	// Note: comment out the Excerpt/Caption or Content/Description lines if not needed

		$image_meta = array(
			'ID'           => $imgID,
			'post_title'   => $img_title,
			'post_excerpt' => $caption,                                      // Set image Caption (Excerpt) to sanitized title
			'post_content' => $descrip                                      // Set image Description (Content) to sanitized title
			);
		update_post_meta( $imgID, '_wp_attachment_image_alt', $altxt ); // Set the image Alt-Text
		wp_update_post( $image_meta );
		}
}


function getgroup($tc){
	$grp = NULL;
	switch($tc){
		case 892:
		case 897:
		$grp = 3; break;
		case 916:
		$grp = 4; break;
		case 908:
		$grp = 5; break;
		case 920:
		$grp = 6; break;
		case 907:
		$grp = 7; break;
		default:  break;
		}
	return $grp;
}

function searchbysku($sku){
	global $wpdb;
	return $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
}


function dbscr($garr){
global $wpdb;

$trow = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."postmeta WHERE meta_key='_sku' AND meta_value = '$garr[osku]'");
if($trow->post_id){
	$garr['post_id']=$trow->post_id;
	$garr['_sku']=$trow->meta_value;
	$res2 = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."postmeta WHERE post_id='$trow->post_id'");
	foreach( $res2 as $c2 ) {
		$garr[$c2->meta_key] = $c2->meta_value;
		// if($c2->meta_key=='_regular_price') { $garr['_regular_price'] = $c2->meta_value; }
		// if($c2->meta_key=='_sale_price')    { $garr['_sale_price']    = $c2->meta_value; }
		// if($c2->meta_key=='_price')         { $garr['_price']         = $c2->meta_value; }
		}
	}
return $garr;
}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function updatenpost($npost_id, $nsku, $prfx, $nprc){ // Update newly copied product Name (Title), Slug, Tags, Sku (multiple skus if variable product) and Price(s) ///////
	$npost = wc_get_product($npost_id);

	$np2rp = 'Midtown Grey';
	switch ($prfx){
		case 'KW':
		$np2rp = 'K-Series White'; break;
		case 'SL':
		$np2rp = 'Signature Pearl'; break;
		case 'TS':
		$np2rp = 'Townsquare Grey'; break;
		default: 
		$np2rp = 'Midtown Grey'; break;
		}
	$np2rpl = preg_replace('/\s/', '-', strtolower($np2rp));

	$catarr = array('cabinets', 'Forevermark Townplace Crema');
	wp_set_object_terms( $npost_id, $catarr, 'product_cat' );

	$name = preg_replace('/( \(Copy\))$/', '', $npost->name);
	$name = preg_replace('/TG-/', 'TQ-', $name);
	$slug = preg_replace('/(-copy)$/', '', $npost->slug);
	$slug = preg_replace('/tg-/', 'tq-', $slug);
	$npost->set_name(preg_replace("/$np2rp/", 'Townplace Crema', $name)).'<br>';
	$npost->set_slug(preg_replace("/$np2rpl/", 'townplace-crema', $slug)).'<br>';
	$npost->set_status('publish');
	$npost->save();

	update_post_meta( $npost_id, '_sku', $nsku);
	$terms = get_the_terms( $npost_id, 'product_tag' );
	$ntags = array();
	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
		foreach ( $terms as $term ) {
			$tag = ($term->slug==$np2rpl)?'townplace-crema':$term->slug;
			array_push($ntags, $tag);
			}	
		wp_set_object_terms($npost_id, $ntags, 'product_tag');
		}

	if ( $npost->is_type( 'variable' ) ) {
		$vars = $npost->get_available_variations();
		foreach ( $vars as $vh ) {
			$vt = new WC_Product_Variation( $vh['variation_id'] );
			$chsku = preg_replace('/(-\d)$/', '', $vt->get_sku());
			$chsku = preg_replace('/^(TG)/', 'TQ', $chsku);
			$vt->set_sku($chsku);
			$vt->set_price( $nprc );
			$vt->set_sale_price( $nprc );
			$vt->set_regular_price( $nprc );
			$vt->save();
			}
		}
	else{ update_post_meta( $npost_id, '_regular_price', $nprc); }
return;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function updatenpostAB($npost_id, $nsku, $prfx, $nprc){ // Update newly copied product Name (Title), Slug, Tags, Sku (multiple skus if variable product) and Price(s) ///////
	$npost = wc_get_product($npost_id);

	$np2rp = 'Greystone Shaker';
	switch ($prfx){
		case 'KW':
		$np2rp = 'K-Series White'; break;
		case 'SL':
		$np2rp = 'Signature Pearl'; break;
		case 'TS':
		$np2rp = 'Townsquare Grey'; break;
		default: 
		$np2rp = 'Greystone Shaker'; break;
		}
	$np2rpl = preg_replace('/\s/', '-', strtolower($np2rp));

	$catarr = array('cabinets', 'Forevermark Lait Grey Shaker');
	wp_set_object_terms( $npost_id, $catarr, 'product_cat' );

	$name = preg_replace('/( \(Copy\))$/', '', $npost->name);
	$name = preg_replace('/AG-/', 'AB-', $name);
	$slug = preg_replace('/(-copy)$/', '', $npost->slug);
	$slug = preg_replace('/ag-/', 'ab-', $slug);
	$npost->set_name(preg_replace("/$np2rp/", 'Lait Grey Shaker', $name)).'<br>';
	$npost->set_slug(preg_replace("/$np2rpl/", 'lait-grey-shaker', $slug)).'<br>';
	$npost->set_status('publish');
	$npost->save();

	update_post_meta( $npost_id, '_sku', $nsku);
	$terms = get_the_terms( $npost_id, 'product_tag' );
	$ntags = array();
	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
		foreach ( $terms as $term ) {
			$tag = ($term->slug==$np2rpl)?'lait-grey-shaker':$term->slug;
			array_push($ntags, $tag);
			}	
		wp_set_object_terms($npost_id, $ntags, 'product_tag');
		}

	if ( $npost->is_type( 'variable' ) ) {
		$vars = $npost->get_available_variations();
		foreach ( $vars as $vh ) {
			$vt = new WC_Product_Variation( $vh['variation_id'] );
			$chsku = preg_replace('/(-\d)$/', '', $vt->get_sku());
			$chsku = preg_replace('/^(AG)/', 'AB', $chsku);
			$vt->set_sku($chsku);
			$vt->set_price( $nprc );
			$vt->set_sale_price( $nprc );
			$vt->set_regular_price( $nprc );
			$vt->save();
			}
		}
	else{ update_post_meta( $npost_id, '_regular_price', $nprc); }
return;
}


function getidbysku($sku){ global $wpdb;
	return $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
}


function calclistpricebysku($sku){

    $product_id = get_pr_id($sku);
	$brand   = ( $product_id ) ? brand_ident($product_id) : NULL;
	$row = getprhshbybrand($brand, $sku);

	$product = wc_get_product( $product_id );
	if(!$product){return NULL; }
	$price = $product->get_price();

	return ($row['SPFactor'])?(($price) ? round($price / $row['SPFactor'], 2) : 0):0;
}


function getlistpricebyid($id){
	$product = wc_get_product( $id );
	return round($product->get_regular_price(), 2);
}

function get_pr_id($s){ global $lwh;
	$que = mysqli_query($lwh, "SELECT post_id FROM wp_postmeta WHERE meta_key='_sku' AND meta_value='$s'");
	return mysqli_fetch_assoc($que)['post_id'];
}

function _________________calcostbysku($sku){ //global $wpdb; global $dbh;
	$lpr = calclistpricebysku($sku);

//	$cost = 0;
    $product_id = get_pr_id($sku); //$wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
//	$product = ( $product_id ) ? new WC_Product( $product_id ) : NULL;	
	$brand   = ( $product_id ) ? brand_ident($product_id) : NULL;
	$row = getprhshbybrand($brand, $sku);
	return ($row['SPFactor'])?round($lpr * $row['VCFactor'], 2):0;
}

function getfactor($sku){    //     if(preg_match('/Wolf/', $sku)){ return 0.43; }; /// Temporar factor for wolf
	$product_id = get_pr_id($sku);
	$brand   = ( $product_id ) ? brand_ident($product_id) : NULL;
	$row = getprhshbybrand($brand, $sku);
	return ($row['VCFactor'])?$row['VCFactor']:0;
}


function ____________calcostbyid($id){
	$lpr = getlistpricebyid($id);
	$brand   = ( $id ) ? brand_ident($id) : NULL;
	$row = getprhshbybrand($brand);
	return ($row['SPFactor'])?round($lpr * $row['VCFactor'], 2):0;
}


function brand_ident($id){ //echo $id;
$parent_id = wp_get_post_parent_id($id);
$id=($parent_id)?$parent_id:$id;
$terms = wp_get_post_terms( $id, 'product_tag' );

//echo '<pre>';print_r($terms);echo '</pre>';

$br = NULL;
if( count($terms) > 0 ){
    foreach($terms as $term){ // echo $term->name . '<br>';
		if($term->name == 'aurafina')         { $br = 'AF'; }
		if($term->name == 'cnc')              { $br = 'CNC'; }
		if($term->name == 'Cubitac')          { $br = 'CB'; }
		if($term->name == 'feather-lodge')    { $br = 'FL'; }
		if($term->name == 'forevermark')      { $br = 'FM'; }
		if($term->name == 'ghi')              { $br = 'GHI'; }
		if($term->name == 'msi')              { $br = 'MSI'; }
		if($term->name == 'us-cabinet-depot') { $br = 'USCD'; }
		if($term->name == 'wolf') 			  { $br = 'WLF'; }
		}
	}
//	echo $br.'<br>';
	return $br;
}


function calcost($h){ global $dbh;
// $carr_msi  = array(908, 916, 920, 892, 907, 893, 902, 899, 905, 904, 900, 909, 910, 911, 912, 913, 915, 906, 901, 917, 918, 919);
// $term_list = wp_get_post_terms($h[0]->ID,'product_cat', array('fields'=>'ids'));
// $cat_id = (int)$term_list[0];
//$row = NULL;
	$brand = brand_ident($h[0]->ID);
	$row = getprhshbybrand($brand, $h[0]->_sku);


//echo $brand . '<br>';

return ($row['SPFactor'])?(($h[1]->price) ? round($h[1]->price / $row['SPFactor'] * $row['VCFactor'], 2) : 0):0;
}


function calcost_vv($h){ global $dbh;

	$brand = brand_ident($h['id']);
	$row = getprhshbybrand($brand, $h['sku']);

return ($row['SPFactor'])?(($h[1]->price) ? round($h[1]->price / $row['SPFactor'] * $row['VCFactor'], 2) : 0):0;
}


function getprhshbybrand($brand, $sku){ global $dbh;

//echo $sku . ' - ' . $brand . '<br>';
$row = NULL;
switch($brand){
	case 'CB':
		if( (preg_match('/-ASM/'   , $sku)) OR
			(preg_match('/-RIGHT/' , $sku)) OR
			(preg_match('/-LEFT/'  , $sku))
			){ $row = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM qc_factor WHERE BrAlias = '$brand' AND Misc = 'Asm'", MYSQLI_ASSOC)); }
		else { $row = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM qc_factor WHERE BrAlias = '$brand' AND Misc = 'Flat'", MYSQLI_ASSOC)); }
	break;
	case 'GHI':
		$sff = substr($sku, -3);
		if($sff=='-LS'){$sff='LS';}
		if($sff=='-LW'){$sff='LW';}
		$row = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM qc_factor WHERE BrAlias = '$brand' AND Misc = '$sff'", MYSQLI_ASSOC));
	break;
	case 'FL':
		$sff = substr($sku, -3);
		$row = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM qc_factor WHERE BrAlias = '$brand' AND Misc = '$sff'", MYSQLI_ASSOC));
	break;
	case 'USCD':
		if(preg_match('/^U-SW-/', $sku)){
			$row = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM qc_factor WHERE BrAlias = '$brand' AND BrLine = 'SW'", MYSQLI_ASSOC));
			}
		else{
			$row = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM qc_factor WHERE BrAlias = '$brand' AND Misc = 'Int'", MYSQLI_ASSOC));
			}
	break;
	
	default:  /////////////////   AF, CNC, FM, MSI /////////////
		$row = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM qc_factor WHERE BrAlias = '$brand'", MYSQLI_ASSOC));
	break;
	}
return $row;	
}

function addrchck($oobj, $sh2){

$oaddr1 = preg_replace('/Avenue/', 'Ave', $oobj->shipping_address_1);
$oaddr1 = preg_replace('/Street/', 'St', $oaddr1);
$oaddr1 = preg_replace('/Road/', 'Rd', $oaddr1);
$oaddr1 = preg_replace('/Lane/', 'Ln', $oaddr1);

if( (preg_match("/$oobj->shipping_first_name/", $sh2[0]) OR preg_match("/$oobj->shipping_last_name/", $sh2[0]) OR preg_match("/$oobj->shipping_company_name/", $sh2[0]) OR
	 preg_match("/$oobj->shipping_first_name/", $sh2[1]) OR preg_match("/$oobj->shipping_last_name/", $sh2[1]) OR preg_match("/$oobj->shipping_company_name/", $sh2[1])) AND
	(preg_match("/$oaddr1/", $sh2[1]) OR preg_match("/$oaddr1/", $sh2[2])) AND
	(preg_match("/$oobj->shipping_city/", $sh2[2]) OR preg_match("/$oobj->shipping_city/", $sh2[3])) AND
	(preg_match("/$oobj->shipping_state/", $sh2[2]) OR preg_match("/$oobj->shipping_state/", $sh2[3])) AND
	(preg_match("/$oobj->shipping_postcode/", $sh2[2]) OR preg_match("/$oobj->shipping_postcode/", $sh2[3]))){
	return true;
	}
return false;
}

function ship2phonehck($ph, $sh2){
	return (trtphonum($ph, 1)==trtphonum($sh2[3], 1))?true:false;
}




function getwsitems($brand){

if(!$brand){return NULL;}
$inc=0;
	
$itarr = array();
$args = array(
	'posts_per_page' => -1,
	'post_status'    => 'publish',
	'post_type'      => 'product',
	'tax_query' => array(
		array(
			'taxonomy' => 'product_tag',
			'field' => 'slug',
			'terms' => $brand,
			'operator' => 'IN',
			)
		),
	);

$items = get_posts( $args ); 

//echo '<pre>'; print_r($items); echo '</pre>';

foreach($items as $ii){
	$itarr[$inc]['id']     = $ii->ID;
	$itarr[$inc]['sku']    = $ii->_sku;
	$itarr[$inc]['title']  = $ii->post_title;
	$itarr[$inc]['rprice'] = $ii->_regular_price;
	$itarr[$inc]['price']  = $ii->_price;
	$inc++;
//	echo ++$inc.'. '.$ii->_sku.' - '.$ii->post_title.'<br>';

    $argsv = array(
        'post_parent' => $ii->ID,
        'post_type'   => 'product_variation',
        'numberposts' => -1,
		); 

	$vars = get_posts( $argsv ); 
	foreach($vars as $iv){
		$itarr[$inc]['id']     = $ii->ID;
		$itarr[$inc]['sku']    = $iv->_sku;
		$itarr[$inc]['title']  = $iv->post_title;
		$itarr[$inc]['rprice'] = $iv->_regular_price;
		$itarr[$inc]['price']  = $iv->_price;
		$inc++;
//		echo ++$inc.'. : : : '. $iv->_sku.'<br>';
		}
	}
	return $itarr;
}


function getwsitems2($brand, $ppp, $offs){
if($brand=='wlf'){ $brand='wolf'; }
if(!$brand){return NULL;}
$inc=0;
	
$itarr = array();
$args = array(
	'post_type'             => 'product',
	'post_status'           => 'publish',

//	'fields' => 'ids',
//	'fields' => 'sku',
	'ignore_sticky_posts'   => 1,
	'offset' 				=> $offs,
	'posts_per_page'        => $ppp,
	'tax_query' => array(
		array(
			'taxonomy' => 'product_tag',
			'field' => 'slug',
			'terms' => $brand,
			'operator' => 'IN',
			)
		),
	);

//$items = get_posts_fields( $args ); 
$items = get_posts( $args ); 

//return $items; 
foreach($items as $ii){
	$prod = wc_get_product($ii->ID);

/*	
	$prod = wc_get_product($ii->ID);
if( $prod->is_type('variable') ){

//	$vars = $prod->get_children();
	foreach($prod->get_available_variations() as $var){
		

		$itarr[$inc]['id']     = $var->ID;
		$itarr[$inc]['sku']    = $var->sku;
		$itarr[$inc]['title']  = $var->post_title;
		$itarr[$inc]['rprice'] = $var->_regular_price;
		$itarr[$inc]['price']  = $var->_price;
		$inc++;
		}	
	}
else{
/////////////	*/
	
	$itarr[$inc]['id']     = $ii->ID;
	$itarr[$inc]['sku']    = $ii->_sku;
	$itarr[$inc]['slug']   = $ii->post_name;
	$itarr[$inc]['title']  = $ii->post_title;
	$itarr[$inc]['rprice'] = $ii->_regular_price;
	$itarr[$inc]['price']  = $ii->_price;
	$itarr[$inc]['ptype']  = ( $prod->is_type('variable') )?1:0; // $ii->get_type();
//	$itarr[$inc]['type']   = $ii->post_type;
	$inc++;

// }
	
	
	}
	return $itarr;
}



function getwsitems3($brand, $ppp, $offs){

$lwh = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if(!$brand){return NULL;}
$inc=0;
	
$itarr = array();

$que = mysqli_query($lwh, "SELECT t1.ID AS id, t1.post_title AS title, t2.meta_value AS sku FROM wp_posts AS t1 INNER JOIN wp_postmeta AS t2 ON t1.ID = t2.post_id WHERE t1.post_type='product' AND t1.post_status='publish' AND t2.meta_key='_sku'");

//$que = mysqli_query($lwh, "SELECT * FROM wp_posts WHERE post_type='product' AND post_status='publish'");
while($row = mysqli_fetch_assoc($que)){ $ii++;

/*	
	$prod = wc_get_product($ii->ID);
if( $prod->is_type('variable') ){

//	$vars = $prod->get_children();
	foreach($prod->get_available_variations() as $var){
		

		$itarr[$inc]['id']     = $var->ID;
		$itarr[$inc]['sku']    = $var->sku;
		$itarr[$inc]['title']  = $var->post_title;
		$itarr[$inc]['rprice'] = $var->_regular_price;
		$itarr[$inc]['price']  = $var->_price;
		$inc++;
		}	
	}
else{
	*/
	
	$itarr[$inc]['id']     = $row['id'];
	$itarr[$inc]['sku']    = $row['sku'];
	$itarr[$inc]['slug']   = '';
	$itarr[$inc]['title']  = $row['title'];
	$itarr[$inc]['rprice'] = '';
	$itarr[$inc]['price']  = '';
//	$itarr[$inc]['type']   = ( $prod->is_type('variable') )?'variable':'simple'; // $ii->get_type();
//	$itarr[$inc]['type']   = $ii->post_type;
	$inc++;

// }
	
	
	}
	return $itarr;
}









function getqbcustomers_________($cn){ global $dbh;
	$opts = NULL;
	$query = mysqli_query($dbh, "SELECT * FROM qw_customer GROUP BY lname, name ORDER BY name, lname ASC");
	while($row = mysqli_fetch_assoc($query)){
		$name=($row['name'])?$row['name']:$row['lname'].', '.$row['fname'];
		$tnm =($row['name'])?$row['name']:$row['fname'].' '.$row['lname'];
		$sel =($tnm==$cn)?' selected':'';
		$opts .= '<option value="'.$tnm.'"'.$sel.'>'.$name.'</option>';
		}
	return $opts;
	}

function allqbcustomers(){ global $dbh;
	$opts = NULL;
	$query = mysqli_query($dbh, "SELECT * FROM qw_customer ORDER BY name, lname ASC");
	while($row = mysqli_fetch_assoc($query)){
		$name  =($row['name'])?$row['name']:$row['lname'].', '.$row['fname'];
		$opts .= '<option value="'.$row['quickbooks_listid'].'">'.$name.'</option>';
		}
	return $opts;
	}

function allqbitems(){ global $dbh;
	$opts = NULL;
	$query = mysqli_query($dbh, "SELECT * FROM wc_consolisku ORDER BY Sku_WC ASC");
	while($row = mysqli_fetch_assoc($query)){
		$opts .= '<option value="'.$row['id'].'">'.$row['Sku_WC'].'</option>';
		}
	return $opts;
	}


function productId_by_sku( $sku ) {
    global $wpdb;
    $product_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
    return ( $product_id ) ? $product_id : NULL;
}

function get_product_by_sku( $sku ) {
  global $wpdb;
  $product_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
  if ( $product_id ) return new WC_Product( $product_id );
  return null;
}


function upd_consolisku($sku){ global $dbh;

//echo $sku . '<br>';
return;
	
	$res = mysqli_query($dbh, "SELECT * FROM qw_item WHERE Sku='$sku'") or die (mysqli_error($dbh));
	$vh = mysqli_fetch_array($res);

$vend = '--';

$sffx = NULL;
// if(preg_match('/-SR$/',    $tr['Sku_WC'])){ $sffx = 'SR'; }
// if(preg_match('/-RTA$/',   $tr['Sku_WC'])){ $sffx = 'RTA'; }
// if(preg_match('/-ASM$/',   $tr['Sku_WC'])){ $sffx = 'ASM'; }
//if(preg_match('/-LEFT$/',  $tr['Sku_WC'])){ $sffx = '-L'; }
//if(preg_match('/-RIGHT$/', $tr['Sku_WC'])){ $sffx = '-R'; }

// if(preg_match('/Cubitac/',          $vh['Title'])){ $vend = 'Cubitac'; }
// if(preg_match('/Feather Lodge/',    $vh['Title'])){ $vend = 'FL'; }
// if(preg_match('/Forevermark/',      $vh['Title'])){ $vend = 'TSG'; }
// if(preg_match('/GHI/',              $vh['Title'])){ $vend = 'GHI'; }
// if(preg_match('/US Cabinet Depot/', $vh['Title'])){ $vend = 'USCD'; }

//if(preg_match('/^W\d/', $tr['Sku_2020'])){
	
//mysqli_query($dbh, "UPDATE wc_consolisku SET Sku_2020 = CONCAT(Sku_2020, '$sffx') WHERE id='$tr[id]'") or die (mysqli_error($dbh));
//mysqli_query($dbh, "UPDATE wc_consolisku SET Asm='$sffx' WHERE id='$tr[id]'") or die (mysqli_error($dbh));


	
}





function formslug($ttl){
	return preg_replace('/[\/\-\s]{1,}/','-',strtolower($ttl));
	}



function get_coupon($o){
	$coupons  = $o->get_used_coupons();
	$coupons  = count($coupons) > 0 ? implode(',', $coupons) : '';
	return new WC_Coupon($coupons);
}


function get_posts_fields( $args = array() ) {
  $valid_fields = array(
    'ID'=>'%d', 'post_author'=>'%d',
    'post_type'=>'%s', 'post_mime_type'=>'%s',
    'post_title'=>false, 'post_name'=>'%s', 
    'post_date'=>'%s', 'post_modified'=>'%s',
    'menu_order'=>'%d', 'post_parent'=>'%d', 
    'post_excerpt'=>false, 'post_content'=>false,
    'post_status'=>'%s', 'comment_status'=>false, 'ping_status'=>false,
    'to_ping'=>false, 'pinged'=>false, 'comment_count'=>'%d'
  );
  $defaults = array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'orderby' => 'post_date',
    'order' => 'DESC',
    'posts_per_page' => get_option('posts_per_page'),
  );
  global $wpdb;
  $args = wp_parse_args($args, $defaults);
  $where = "";
  foreach ( $valid_fields as $field => $can_query ) {
    if ( isset($args[$field]) && $can_query ) {
      if ( $where != "" )  $where .= " AND ";
      $where .= $wpdb->prepare( $field . " = " . $can_query, $args[$field] );
    }
  }
  if ( isset($args['search']) && is_string($args['search']) ) {
      if ( $where != "" )  $where .= " AND ";
      $where .= $wpdb->prepare("post_title LIKE %s", "%" . $args['search'] . "%");
  }
  if ( isset($args['include']) ) {
     if ( is_string($args['include']) ) $args['include'] = explode(',', $args['include']); 
     if ( is_array($args['include']) ) {
      $args['include'] = array_map('intval', $args['include']); 
      if ( $where != "" )  $where .= " OR ";
      $where .= "ID IN (" . implode(',', $args['include'] ). ")";
    }
  }
  if ( isset($args['exclude']) ) {
     if ( is_string($args['exclude']) ) $args['exclude'] = explode(',', $args['exclude']); 
     if ( is_array($args['exclude']) ) {
      $args['exclude'] = array_map('intval', $args['exclude']);
      if ( $where != "" ) $where .= " AND "; 
      $where .= "ID NOT IN (" . implode(',', $args['exclude'] ). ")";
    }
  }
  extract($args);
  $iscol = false;
  if ( isset($fields) ) { 
    if ( is_string($fields) ) $fields = explode(',', $fields);
    if ( is_array($fields) ) {
      $fields = array_intersect($fields, array_keys($valid_fields)); 
      if( count($fields) == 1 ) $iscol = true;
      $fields = implode(',', $fields);
    }
  }
  if ( empty($fields) ) $fields = '*';
  if ( ! in_array($orderby, $valid_fields) ) $orderby = 'post_date';
  if ( ! in_array( strtoupper($order), array('ASC','DESC')) ) $order = 'DESC';
  if ( ! intval($posts_per_page) && $posts_per_page != -1)
     $posts_per_page = $defaults['posts_per_page'];
  if ( $where == "" ) $where = "1";
  $q = "SELECT $fields FROM $wpdb->posts WHERE " . $where;
  $q .= " ORDER BY $orderby $order";
  if ( $posts_per_page != -1) $q .= " LIMIT $posts_per_page";
  return $iscol ? $wpdb->get_col($q) : $wpdb->get_results($q);
}


function getWCprodata($lh, $br=''){
$q = "SELECT t1.ID AS id, t1.post_title AS title, t2.meta_value AS sku FROM wp_posts AS t1 INNER JOIN wp_postmeta AS t2 ON t1.ID = t2.post_id 
	WHERE (	t1.post_type='product' OR t1.post_type='product_variation') AND 
			t1.post_status='publish' AND 
			t1.post_name LIKE '$br%' AND 
			t2.meta_key='_sku'";
return mysqli_query($lh, $q);
}

function getcsvarr(){
	$varr = array();
//		$csvfname = $_FILES['csv']['name'];
		$ext     = strtolower(end(explode('.', $_FILES['csv']['name'])));
		$type    = $_FILES['csv']['type'];
		$tmpName = $_FILES['csv']['tmp_name'];
		if($ext === 'csv'){
			if(($handle = fopen($tmpName, 'r')) !== FALSE) {
				// necessary if a large csv file
				set_time_limit(0);
				$row = 0;
				while(($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
					$col_count = count($data);
					for($col=0; $col<$col_count; $col++){
						$varr[$row][$col]=$data[$col];
						}
					$row++;
					}
				fclose($handle);
				}
			}
	return $varr;
}


?>