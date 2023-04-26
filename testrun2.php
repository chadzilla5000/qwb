<?php
require_once('inc/_init.php');
require_once('inc/functions/general.php');
error_reporting(E_ALL | E_STRICT);

if($lghsh['Id'] == 0){
	require_once('inc/functions/authorization.php'); 
	$pgcontent = logform(); 
	$page_title       = 'WC Terminal LogIn';
	$page_description = '';
	$keywords         = '';
	$head_ext         = '';
	require_once('inc/_shell.php'); ///
	exit;
	}

include_once 'inc/functions.php';
//require_once("../wp-load.php");
//exit;
setlocale(LC_MONETARY, 'en_US');

$msg = NULL;
require_once 'wconfig.php';




/////////////*  ///////////////////// 



$bar = array();
$csvarr = array();
if(isset($_FILES['csv']) AND $_FILES['csv']['error'] == 0){ // check there are no errors
	$tmpName = $_FILES['csv']['tmp_name'];
	$csvarr = csv_r($tmpName);
	}

$vnd = 'ghi';


//		echo '<pre>'; print_r($csvarr); echo '</pre>';





$pi = 0;

$itfr = '<br><br><table cellpadding="0" cellspacing="1" class="vtbl">
<tr><th></th>
	<th>Sku</th>
	<th>Prf</th>
	<th>RPr</th>
	<th>Title</th>
	<th>Slug</th>
	<th>Category</th>
	<th>Tags</th>
	<th>Img</th>
</tr>
';


foreach($csvarr as $r){
		
	$pr = $r[1];
	$ir = array();

	$ttl = $slg = $vn = $wcsku = NULL;
	$cat = NULL;
	$tag = NULL;

//echo $r[7].'<br>';
//	$r[7] = (isset($r[7]))?$r[7]:NULL;



	switch($vnd){
		case 'ghi':  /////////////////////////////////  GHI

		$ir = hgnric($r, $vnd);
		if(isset($ir['sku'])){ array_push($bar, $ir); }
		else{	
			$ir['ttl'] = array('GHI');  
			$ir['cat'] = array(); //'Cabinets';
			$ir['tag'] = array('ghi');  

			switch($r[1]){
	//			case 'WDC': $ir['ttl'][1] = ''; break;
	//			case 'CCB': $ir['ttl'][1] = ''; break;
				case 'RGO': array_push($ir['ttl'], 'Regal Oak');                     break;
				case 'CTC': array_push($ir['ttl'], 'Charleston Traditional Cognac'); break;
				case 'RMA': array_push($ir['ttl'], 'Richmond Auburn');               break;
				case 'NCG': array_push($ir['ttl'], 'New Castle Gray');               break;
				case 'NTL': array_push($ir['ttl'], 'Nantucket Linen');               break;
				case 'SHG': array_push($ir['ttl'], 'Stone Harbor Gray');             break;
				case 'ACL': array_push($ir['ttl'], 'Arcadia Linen');          break;
				case 'ACW': array_push($ir['ttl'], 'Arcadia White Shaker');          break;
				case 'SDC': array_push($ir['ttl'], 'Sedona Chestnut');               break;
				default: break;
				}

			if(isset($ir['ttl'][1])){ $ir['tag'][1] = preg_replace('/[\s]{1,3}/','-',strtolower($ir['ttl'][1])); }
			
			$sk = preg_replace("/^G/",'',$r[0]);
			$sk = preg_replace("/$pr$/",'',$sk);


			if(preg_match('/SAMPLEDR/',$sk)){ 
				array_push($ir['cat'], 'Sample Doors');
				array_push($ir['ttl'], 'Sample Door'); 
				}
			if(preg_match('/WDC/',$sk)){ $ir['ttl'][9] = 'Wall Diagonal Corner'; }


			$fdx = get_findx($ir['ttl']);

			if(preg_match('/([\D]{1,6})([\d]{1,6})/',$sk,$m)){
				if(preg_match('/M/',$m[1])){ 
					$ir['ttl'][$fdx] = 'Microwave'; 
					$ir['tag'][3] = 'base-cabinet-other'; 
					$ir['tag'][4] = 'microwave'; 
					}
				if(preg_match('/LRM/',$m[1])){ 
					$ir['ttl'][$fdx] = 'Light Rail Molding'; 
					$ir['tag'][3] = 'accessories'; 
					$ir['tag'][4] = 'molding'; 
					}

				$fdx = get_findx($ir['ttl']);
				if(preg_match('/B/',$m[1])){ 
					$ir['ttl'][$fdx] = 'Base';
					$ir['ttl'][$fdx+1] = 'Cabinet';
//					$ir['ttl'][9] = 'Base Cabinet'; 
					$ir['tag'][2] = 'base-cabinet'; 
					}

				}
			
			if(array_search('Cabinet', $ir['ttl'])){ array_push($ir['cat'], 'Cabinets'); }
			if(isset($ir['ttl'][1])){ $ir['cat'][1] = $ir['ttl'][0].' '.$ir['ttl'][1]; }

			ksort($ir['ttl']);
			ksort($ir['cat']);
			ksort($ir['tag']);

			$ttl = join(' ', $ir['ttl']);
			$cat = join(',', $ir['cat']);
			$tag = join(',', $ir['tag']);
			
			$ttl = preg_replace('/[\s]{1,7}$/','',$ttl);
			$slg = preg_replace('/[\s]{1,3}/','-',strtolower($ttl));
			
			$sku = preg_replace("/$r[1]/","-$r[1]",$r[0]);
			$sku = preg_replace("/[-]{2,3}/","-",$sku);
			
			
			$img = (preg_match('/MB30/',$r[0]))?'https://waverlycabinets.com/qbw/files/dump/lart/MB30.jpg':NULL;

			array_push($bar, array('sku'=>$sku,'prf'=>$r[1],'rpr'=>$r[2],'ttl'=>$ttl,'slg'=>$slg,'cat'=>$cat,'tag'=>$tag,'img'=>$img));
			}	
	//$ttl = 	$vn .' '.$stl.' '.$t1.' '.$t2.' '.$t3;
		break;

















		case 'uscd':  //////////////////////////////////////////// USCD

		$ir['ttl'][0] = 'US Cabinet Depot';  
		$ir['cat'][0] = 'Cabinets';
		$ir['tag'][0] = 'us-cabinet-depot';  

		switch($r[1]){
			case 'CW': $ir['ttl'][1] = 'Casselberry Antique White'; break;
			case 'CS': $ir['ttl'][1] = 'Casselberry Saddle'; break;
			case 'SA': $ir['ttl'][1] = 'Shaker Antique White'; break;
			case 'SC': $ir['ttl'][1] = 'Shaker Cinder'; break;
			case 'SD': $ir['ttl'][1] = 'Shaker Dove'; break;
			case 'SG': $ir['ttl'][1] = 'Shaker Grey'; break;
			case 'SW': $ir['ttl'][1] = 'Shaker White'; break;
			case 'TD': $ir['ttl'][1] = 'Torrance Dove'; break;
			case 'TW': $ir['ttl'][1] = 'Torrance White'; break;
			case 'YW': $ir['ttl'][1] = 'York Antique White'; break;
			default:   $ir['ttl'][1] = ''; break;
			}

		$ir['tag'][1] = preg_replace('/[\s]{1,3}/','-',strtolower($ir['ttl'][1]));

		$sk = preg_replace("/^G/",'',$r[0]);
		$sk = preg_replace("/$pr$/",'',$sk);

		if(preg_match('/([\D]{1,6})([\d]{1,6})/',$sk,$m)){
			if(preg_match('/B/',$m[1])){ 
				$ir['ttl'][9] = 'Base Cabinet';
				$ir['tag'][2] = 'base-cabinet'; 
				}
			if(preg_match('/M/',$m[1])){ 
				$ir['ttl'][2] = 'Microwave'; 
				$ir['tag'][3] = 'base-cabinet-other'; 
				$ir['tag'][4] = 'microwave'; 
				}
			if(preg_match('/CM/',$m[1])){ 
				$ir['ttl'][2] = 'Crown Molding'; 
				$ir['tag'][3] = 'accessory'; 
				$ir['tag'][4] = 'molding'; 
				}
			if(preg_match('/OCM/',$m[1])){ 
				$ir['ttl'][2] = 'Outside Corner Molding'; 
				$ir['tag'][3] = 'accessory'; 
				$ir['tag'][4] = 'molding'; 
				}
			if(preg_match('/ICM/',$m[1])){ 
				$ir['ttl'][2] = 'Inside Corner Molding'; 
				$ir['tag'][3] = 'accessory'; 
				$ir['tag'][4] = 'molding'; 
				}
			}

		
		if(isset($ir['ttl'][10])){
		switch($ir['ttl'][10]){
			case 'Cabinet': $ir['cat'][0] = 'Cabinets'; break;
			default: break;
			}
		}
		$ir['cat'][1] = $ir['ttl'][0].' '.$ir['ttl'][1];

		ksort($ir['ttl']);
		ksort($ir['cat']);
		ksort($ir['tag']);

		$ttl = join(' ', $ir['ttl']);
		$cat = join(',', $ir['cat']);
		$tag = join(',', $ir['tag']);
		
		$slg = preg_replace('/[\s]{1,3}/','-',strtolower($ttl));
		
		$wcsku= preg_replace("/$pr$/","-$pr",$r[0]);		



		
		
		
		break;
		default: break;
		}


//echo $m[1] . ' - ' . $m[2] . '<br>';


//$imgseo = 'ghi';		





		
// $itfr .=  '
// <tr><td>'.++$pi.'.</td>
	// <td>'.$r[0].'</td>
	// <td>'.$r[1].'</td>
	// <td>'.$r[2].'</td>
	// <td>'.$ttl.'</td>
	// <td>'.$slg.'</td>
	// <td>'.$cat.'</td>
	// <td>'.$tag.'</td>
	// <td><a target="_blank" href="'.$r[7].'"><img src="'.$r[7].'" style="width: 30px;" /></a></td>
	// <td>'.$imgseo.'</td>
	// <td>'.$imgseo.'.jpg'.'</td>
// </tr>';

}

foreach($bar as $mr){
if($mr['img']){
	$itfr .=  '
<tr><td>'.++$pi.'.</td>
	<td>'.$mr['sku'].'</td>
	<td>'.$mr['prf'].'</td>
	<td>'.$mr['rpr'].'</td>
	<td>'.$mr['ttl'].'</td>
	<td>'.$mr['slg'].'</td>
	<td>'.$mr['cat'].'</td>
	<td>'.$mr['tag'].'</td>
	<td><a target="_blank" href="'.$mr['img'].'"><img src="'.$mr['img'].'" style="width: 30px;" /></a></td>
</tr>';

	create_post($mr);
	}
}


$itfr .= '</table>';




echo '
<style>
.vtbl{
	background: #ccc;
	font-size: 13px;
}
.vtbl td{
	background: #fff;
	padding: 3px;
}
</style>
<form action="#" method="post" name="upord" enctype="multipart/form-data" style="margin: 20px;">
<div>
<input type="file" name="csv" id="csv" />
<input type="submit" name="upfile" id="upfile" value="Open" style="padding: 1px 5px;" />&nbsp; &nbsp;
</div>


'.$itfr.'

</form>


';




////////////////////////// */


function csv_r($n){
	$cr = array();
	if(($handle = fopen($n, 'r')) !== FALSE) {
		set_time_limit(0); 		// necessary if a large csv file
		$row = 0;
		while(($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
			$col_count = count($data);
			for($col=0; $col<$col_count; $col++){
				$cr[$row][$col]=$data[$col];
				}
			$row++;
			}
		fclose($handle);
		}
	return $cr;
}


function create_post($r){
	
	$post = array(
		'post_title' => $r['ttl'],
		'post_name'  => $r['slg'],
		'post_status' => 'publish',
		'post_type' => 'product',
		'meta_input' => array(
			'_regular_price' => $r['rpr'],
			'_sku' => $r['sku']
			),
		'post_author' => 1
		);


$cats = explode(',', $r['cat']);
$tags = explode(',', $r['tag']);

//return;
    $new_post_id = wp_insert_post($post);

	wp_set_object_terms( $new_post_id, 'simple', 'product_type' );
	wp_set_object_terms($new_post_id, $cats, 'product_cat');
	wp_set_object_terms($new_post_id, $tags, 'product_tag');

//update_post_meta( $new_post_id, '_price', '156' );

	$getImageFile = $r['img']; //"https://waverlycabinets.com/qbw/files/dump/lart/MB30.jpg";

	$attach_id  = parseImg($getImageFile,  $new_post_id, 0);
	add_post_meta($new_post_id, '_thumbnail_id', $attach_id); 

	set_imgattr( $attach_id, $new_post_id );
	}


function ___Generate_Featured_Image( $image_url, $post_id  ){
    $upload_dir = wp_upload_dir();
    $image_data = file_get_contents($image_url);
    $filename = basename($image_url);
    if(wp_mkdir_p($upload_dir['path']))     $file = $upload_dir['path'] . '/' . $filename;
    else                                    $file = $upload_dir['basedir'] . '/' . $filename;
    file_put_contents($file, $image_data);

    $wp_filetype = wp_check_filetype($filename, null );
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => sanitize_file_name($filename),
        'post_content' => '',
        'post_status' => 'inherit'
    );
    $attach_id = wp_insert_attachment( $attachment, $file, $post_id );
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
    $res1= wp_update_attachment_metadata( $attach_id, $attach_data );
    $res2= set_post_thumbnail( $post_id, $attach_id );
}


function hgnric($r, $v){
$ir = array();
if($v=='ghi'){ $pr = 'G';
	switch($r[0]){
		case 'SCHWDC':
			$ir['sku']=$pr.$r[0];
			$ir['prf']=NULL;
			$ir['rpr']=$r[2];
			$ir['ttl']='GHI Soft Close Hinge for WDC Cabinet';
			$ir['slg']='ghi-soft-close-hinge-for-wdc-cabinet';
			$ir['cat']='Accessories, GHI';
			$ir['tag']='accessories, accessories-other, ghi, richmond-auburn, new-castle-gray, nantucket-linen, stone-harbor-gray, arcadia-linen-shaker, arcadia-white-shaker, sedona-chestnut';
			$ir['img']='';
			$ir['seo']='';
		break;
		case 'SCH138':
			$ir['sku']=$pr.$r[0];
			$ir['prf']=NULL;
			$ir['rpr']=$r[2];
			$ir['ttl']='GHI Soft Close Hinge 1 3/8';
			$ir['slg']='ghi-soft-close-hinge-1-38';
			$ir['cat']='Accessories, GHI';
			$ir['tag']='accessories, accessories-other, ghi, nantucket-linen, sedona-chestnut';
			$ir['img']='';
			$ir['seo']='';
		break;
		case 'SCH114':
			$ir['sku']=$pr.$r[0];
			$ir['prf']=NULL;
			$ir['rpr']=$r[2];
			$ir['ttl']='GHI Soft Close Hinge 1 1/4';
			$ir['slg']='ghi-soft-close-hinge-1-14';
			$ir['cat']='Accessories, GHI';
			$ir['tag']='accessories, accessories-other, ghi, richmond-auburn, new-castle-gray, stone-harbor-gray, arcadia-linen-shaker, arcadia-white-shaker';
			$ir['img']='';
			$ir['seo']='';
		break;
		case 'JKCCB':
			$ir['sku']=$r[0];
			$ir['prf']='CCB';
			$ir['rpr']=$r[2];
			$ir['ttl']='Jiffy Kit-Concord Blue';
			$ir['slg']='jiffy-kit-concord-blue';
			$ir['cat']='Cabinets, GHI Concord Blue';
			$ir['tag']='accessories, accessories-other, ghi, concord-blue';
			$ir['img']='';
			$ir['seo']='';
		break;
		default: break;	
		}
	}
return $ir;
}

function get_findx($r){
	$ind = 0;
	while(isset($r[$ind])){ $ind++; }
	return $ind;
}










?>