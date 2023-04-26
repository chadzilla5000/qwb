<?php
include_once('inc/_init.php');
include_once('inc/functions/general.php');
require_once('inc/functions/qbw_fs.php');
error_reporting(E_ALL | E_STRICT);

$page_description = '';
$keywords         = '';
$head_ext         = '';
$page_title       = 'Unified WC Terminal';

if($lghsh['Id'] == 0){
	require_once('inc/functions/authorization.php'); 
	$pgcontent = logform(); 
	$page_title = 'WC Terminal LogIn';
	require_once('inc/_shell.php'); ///
	exit;
	}

include_once 'inc/functions.php';
setlocale(LC_MONETARY, 'en_US');

$ords = $msg = NULL;
include_once 'wconfig.php';











$brand = 'forevermark';

$i=0;

$its = get_posts( 
	array(
		'post_type' => 'product',
		'numberposts' => -1,
		'post_status' => 'publish',
		'tax_query' => array(
			array(
				'taxonomy' => 'product_tag',
				'field' => 'slug',
				'terms' => $brand,
				'operator' => 'IN',
				)
			),
		) 
	);



foreach ( $its as $v ) {
	
$vits = get_posts( 
	array(
        'post_parent' => $v->ID,
        'post_type'   => 'product_variation',
		'numberposts' => -1,
		'post_status' => 'publish',
		) 
	);

	
	
	if(!chckSku($v->_sku)){ 
		
		$q1 = "INSERT INTO wc_consolisku (Sku_WC, Vendor) VALUES ('$v->_sku', 'TSG')";
		mysqli_query($dbh, $q1);
		}



	echo ++$i.'. '.$v->_sku.'<br>';
	foreach ( $vits as $vv ) {
		if(!chckSku($vv->_sku)){ 
			
			$q2 = "INSERT INTO wc_consolisku (Sku_WC, Vendor) VALUES ('$vv->_sku', 'TSG')";
			mysqli_query($dbh, $q2);
			}
		echo ++$i.'. : : : '.$vv->_sku.'<br>';
		}
	}



exit;
























$barr = $carr = array();

//$mdbh = mysqli_connect('localhost', 'waverly', 'ZxcAsdqwe123!', 'waverly');
//global $wpdb;
$pcnt = NULL;

$lim = 7000;
$ii = 0;
//	$tmquery = "SELECT * FROM qw_item WHERE Title LIKE 'US Cabinet Depot%'"; 
	$tmquery = "SELECT * FROM qw_item LIMIT $lim"; 
	$tresult = mysqli_query($dbh, $tmquery) or die (mysqli_error($dbh));
	while ($tr = mysqli_fetch_array($tresult)) {


//	$res = mysqli_query($dbh, "SELECT * FROM qw_item WHERE Sku='$tr[Sku_WC]'") or die (mysqli_error($dbh));
//	$vh = mysqli_fetch_array($res);

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


//	$res = mysqli_query($mdbh, "SELECT * FROM wp_posts WHERE ID=(SELECT post_id FROM wp_postmeta WHERE meta_key='_sku' AND meta_value='$tr[Sku]')") or die (mysqli_error($dbh));
//	$res = mysqli_query($mdbh, "SELECT post_id FROM wp_postmeta WHERE meta_key='_sku' AND meta_value='$tr[Sku]'") or die (mysqli_error($dbh));
//	$vh = mysqli_fetch_array($res);

//$xst = (mysqli_query($mdbh, "SELECT EXISTS(SELECT post_id FROM wp_postmeta WHERE meta_key='_sku' AND meta_value='$tr[Sku]')"))?1:0;

//$stl = ($xst)?'':'style="color: #f00;"';

//$stl = ($wpdb->get_results("SELECT * FROM wp_postmeta WHERE meta_key='_sku' AND meta_value='$tr[Sku]'"))?'style="color: #090;"':'style="color: #f00;"';


$barr[$ii] = $tr['Sku'];

$ii++;


//.'. '.$tr['Sku'].' - ' .$xst. '<br>';
//}
		}

/*

//$vi = 0;
$mdbh = mysqli_connect('localhost', 'waverly', 'ZxcAsdqwe123!', 'waverly');

	$wcquery = "SELECT t1.post_id, t1.meta_key, t1.meta_value FROM wp_postmeta AS t1 JOIN wp_posts as t0 ON t1.post_id=t0.ID WHERE (t1.meta_key='_sku' OR t1.meta_key='_price' OR t1.meta_key='_regular_price') AND (t0.post_type='product' OR t0.post_type='product_variation') AND t0.post_status='publish' LIMIT $lim"; 
	$res2 = mysqli_query($mdbh, $wcquery) or die (mysqli_error($mdbh));
	while ($tv = mysqli_fetch_array($res2)) {
		$carr[$tv[0]][$tv[1]] = $tv[2];
//		$vi++;
		}



$ci=1;

foreach($carr as $id=>$cv){
	
	$rprice = ($cv['_regular_price']>$cv['_price'])?$cv['_regular_price']:$cv['_price'];
	
	$h1 = wc_get_product( $id );
//	$h2 = get_post_meta ( $id );
	
	
	$trows .= '
<tr><td>'.$ci++.'</td>
	<td>'.$id.'</td>
	<td>'.$cv['_sku'].'</td>
	<td>'.$carr[$id]['_regular_price'].'</td>
	<td>'.$carr[$id]['_price'].'</td>
	<td>'.$rprice.'</td>
	<td>'.$h1->price.'</td>
</tr>
';

}

*/




/*

$categories = get_categories( array(
    'orderby' => 'name',
    'order'   => 'ASC'
) );
 
foreach( $categories as $category ) {
    $category_link = sprintf( 
        '<a href="%1$s" alt="%2$s">%3$s</a>',
        esc_url( get_category_link( $category->term_id ) ),
        esc_attr( sprintf( __( 'View all posts in %s', 'textdomain' ), $category->name ) ),
        esc_html( $category->name )
    );
     
    echo '<p>' . sprintf( esc_html__( 'Category: %s', 'textdomain' ), $category_link ) . '</p> ';
    echo '<p>' . sprintf( esc_html__( 'Description: %s', 'textdomain' ), $category->description ) . '</p>';
    echo '<p>' . sprintf( esc_html__( 'Post Count: %s', 'textdomain' ), $category->count ) . '</p>';
} 

*/






$cat_args = array(
    'orderby'    => 'name',
    'order'      => 'asc',
    'hide_empty' => false,
    'echo'       => 1,
    'taxonomy'   => 'category',
    'hierarchical' =>1,
    'show_count'   => 1,

);
 
$product_categories = get_terms( 'product_cat', $cat_args );
 
if( !empty($product_categories) ){
    $pcnt .= '
 
<ul>';
    foreach ($product_categories as $key => $category) {
        $pcnt .=  '
 
<li>';
        $pcnt .=  '<a href="'.get_term_link($category).'" >';
        $pcnt .=  $category->name;
        $pcnt .=  '</a>';
        $pcnt .=  '</li>';
    }
    $pcnt .=  '</ul>
 
 
';
}


$ci=1;

$args = array( 
	'numberposts' => 100, 
	'status' => 'publish', 
	'category' => array( 
	'Cabinets' 
	)
);
$parr = wc_get_products( $args );


// echo '<pre>';
// print_r($parr);
// echo '</pre>';

// echo '<br><br>';
// echo $parr[157]->sku;
$i=0;
// foreach($parr as $pr){
	// echo ++$i.'. '.$pr->sku . '<br>';
// }



//exit;





foreach($parr as $cv){
	
//	$rprice = ($cv['_regular_price']>$cv['_price'])?$cv['_regular_price']:$cv['_price'];
	
//	$h1 = wc_get_product( $id );
//	$h2 = get_post_meta ( $id );
	
	$trows .= '
<tr><td>'.$ci++.'</td>
	<td>'.$cv->sku.'</td>
	<td>'.$cv->price.'</td>
</tr>
';

}




foreach($barr as $bv){
	// $trows .= '
// <tr><td></td>
	// <td>'.$bv.'</td>
// </tr>
// ';

}


// $csvarr = array();
// $tmpName = 'data/csv/uscd.csv';
// if(($handle = fopen($tmpName, 'r')) !== FALSE) {
	// set_time_limit(0);
	// $row = 0;
	// while(($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
		// $col_count = count($data);
		// for($col=0; $col<$col_count; $col++){
			// $csvarr[$row][$col]=$data[$col];
			// }
		// $row++;
		// }
	// fclose($handle);
	// }


//echo displaycsvarr($csvarr);
// echo '<pre>';
// print_r($csvarr);
// echo '</pre>';
//exit;




//global $dbh;
// $mdbh = mysqli_connect('localhost', 'waverly', 'ZxcAsdqwe123!', 'waverly');

// $trows = NULL;
// $ii = 0;


// foreach($csvarr as $r){
	
// $pr1 = (preg_match('/^\$/', $r[2])) ? intval(preg_replace('/^\$/','',$r[2])) : $r[2];
// $pr2 = (preg_match('/^\$/', $r[3])) ? intval(preg_replace('/^\$/','',$r[3])) : $r[3];
// $pr3 = (preg_match('/^\$/', $r[4])) ? intval(preg_replace('/^\$/','',$r[4])) : $r[4];

//}


//}






//		$incnt .= displaycsvarr($csvarr);



$pcnt .= '
<div style="float: left; margin-top: 30px;">

<table class="TBL" width="100%" cellspacing="1">
<tr><th></th>
	<th style="font-size: 13px;" colspan="3">Product</th>
	<th style="font-size: 13px;" colspan="2">Date / Time</th>
	<th style="font-size: 13px;"></th>
	<th style="font-size: 13px;" colspan="3"></th>
</tr>
<tr><th></th><th><input type="checkbox" name="item2sendchkcnt" title="Check/Empty all" value="1" OnChange="chkbxchng(this);" '.$dchk.' /></th>
	<th style="width: 180px;">Sku</th>
	<th style="width: 200px;">Title</th>
	<th style="width: 120px;">Posted</th>
	<th style="width: 120px;">Modified</th>
	<th style="width: 70px;">Prefix</th>
	<th style="width: 70px;">USCD Sku</th>
	<th style="width: 70px;">Reg.</th>
	<th style="width: 70px;">New price</th>
	<!-- th style="width: 150px;">List ID</th>
	<th style="width: 70px;">Cost</th>
	<th style="width: 70px;">Sale</th -->
</tr>
'.$trows.'
</table>

<script>
function pupnote(o){
var id = o.id.replace(/cnotd_/, "cnotediv_");
	document.getElementById(id).style.display = "block";
	return;
	}

function pclsnote(o){
var id = o.id.replace(/cnotd_/, "cnotediv_");
	document.getElementById(id).style.display = "none";
	return;
	}

function bgtrput(o){
//	document.getElementById(o.id).style.backgroundColor = "rgba(255, 255, 255, 0.7)";
	o.style.backgroundColor = "rgba(255, 255, 255, 0.7)";
	o.style.boxShadow = "5px 5px 5px #555";
	o.style.border = "solid 1px #555";
	return;
}
function bgtrrmv(o){
//	alert(o.id);
//	document.getElementById(o.id).style.backgroundColor = "rgba(255, 255, 255, 1)";
	o.style.backgroundColor = "rgba(255, 255, 255, 1)";
	o.style.boxShadow = "10px 15px 15px #555";
	o.style.border = "solid 1px #000";
	return;
}

function chkbxchng(o){
	var items = document.getElementsByName("item2send[]");
	if(o.checked) {
        for (var i = 0; i < items.length; i++) {
            if (items[i].type == "checkbox" && items[i].disabled == false) { items[i].checked = true; }
			}
		}
	else { 
		for (var i = 0; i < items.length; i++) {
			if (items[i].type == "checkbox" && items[i].disabled == false) { items[i].checked = false; }
			}
		}
return;
}


function slcustomer(o){
if(o.value.length >= 3){
var oid = o.id.replace("ptxbx_", "");
var url = "dispcns.php?sbj=pmt&oid="+oid;
	load_console(url, \'40%\', \'90%\');
	}
	return false;
}

function load_console(url, wd, ht){ //alert(url); return false;
var	obj = document.getElementById("CConsole");
var	pln = document.getElementById("consolink");
	pln.href = url;
	obj.style.display = "block";
	obj.style.width = wd;
	obj.style.height = ht;
	query_srv(url, \'InConsole\');
	return false;
}

function ClsConsole(){
	document.getElementById("CConsole").style.display = "none";
	return;
}

</script>

';


$pgtitle = '<h4>List</h4>';
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
//////////////////////////////


function chckSku($sku){ global $dbh;
	$q = mysqli_query($dbh, "SELECT id FROM wc_consolisku WHERE Sku_WC='$sku'") or die('Error: ' . mysqli_error($dbh));
	return mysqli_num_rows($q);
}




?>