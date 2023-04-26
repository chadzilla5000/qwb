<?php
require_once('inc/_init.php');
require_once('inc/functions/general.php');
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
require_once 'wconfig.php';

//////////// Form handler ////////////////////////////////////////////////////
//if(isset($_POST['fsynchro'])){ $Queue = new QuickBooks_WebConnector_Queue($dsn); 




//sinchro_items(); exit;
/*
$Queue = new QuickBooks_WebConnector_Queue($dsn);
$newid = rand();
$Queue->enqueue(QUICKBOOKS_QUERY_NONINVENTORYITEM, $newid);
exit;
//   */
//*
	$newid = rand();
	$Queue->enqueue(QUICKBOOKS_MOD_NONINVENTORYITEM, $newid);
	exit;
// */


/*
  $taxonomy     = 'product_cat';
  $orderby      = 'name';  
  $show_count   = 1;      // 1 for yes, 0 for no
  $pad_counts   = 1;      // 1 for yes, 0 for no
  $hierarchical = 1;      // 1 for yes, 0 for no  
  $title        = '';  
  $empty        = 0;

  $args = array(
         'taxonomy'     => $taxonomy,
         'orderby'      => $orderby,
         'show_count'   => $show_count,
         'pad_counts'   => $pad_counts,
         'hierarchical' => $hierarchical,
         'title_li'     => $title,
         'hide_empty'   => $empty
  );
 $all_categories = get_categories( $args );
 foreach ($all_categories as $cat) {
    if($cat->category_parent == 0) {
        $category_id = $cat->term_id;       
        echo '<li>'. $category_id .' <a href="'. get_term_link($cat->slug, 'product_cat') .'">'. $cat->name .'</a></li>';

        $args2 = array(
                'taxonomy'     => $taxonomy,
                'child_of'     => 0,
                'parent'       => $category_id,
                'orderby'      => $orderby,
                'show_count'   => $show_count,
                'pad_counts'   => $pad_counts,
                'hierarchical' => $hierarchical,
                'title_li'     => $title,
                'hide_empty'   => $empty
        );
        $sub_cats = get_categories( $args2 );
            if($sub_cats) {
                foreach($sub_cats as $sub_category) {
if(preg_match('/US Cabinet Depot/', $sub_category->name)){
	
$ts .= $sub_category->term_id . ',
';
}

                    echo  '<li>&nbsp;&nbsp;&nbsp;'. $sub_category->term_id .' <a href="'. get_term_link($sub_category->slug, 'product_cat') .'">'. $sub_category->name .'</a></li>';
                }
            }
        }       
}




//echo $ts;










	
exit;


*/
$Queue = new QuickBooks_WebConnector_Queue($dsn, $qbwc_user); 

	// $newid = insertItem($h);
	// if($newid){ $Queue->enqueue(QUICKBOOKS_ADD_NONINVENTORYITEM, $newid); }

// exit;


if(1){ //$Queue = new QuickBooks_WebConnector_Queue($dsn); 

/*
	if($_POST['customer2send']){ ////////////////////////////////////////   Preparing customer(s) to send ///////
		foreach($_POST['customer2send'] as $sv){
			$newid = insertCustomer($sv);
			$Queue->enqueue(QUICKBOOKS_ADD_CUSTOMER, $newid);
			}
		$msg .= 'Customer(s) checked';
		}
*/
	

	$msg .=($msg)?'':'No data to send to QB';      ////////////////////////////////// ////////////////////////////////////  /////////////////////////
	}

////////////////////////////////////////////////////////////////////
$lmt = 5000; //(isset($_POST['rowsonpage']))?$_POST['rowsonpage']:50;

$dto = (isset($_POST['dateto'])) ? $_POST['dateto'] : date('Y-m-d', time());

//echo $dto . '<br><br>';
$dfr = (isset($_POST['datefr'])) ? $_POST['datefr'] : date('Y-m-d', strtotime($dto. ' - 31 days'));
$dto1 = date('Y-m-d', strtotime($dto. ' + 1 days'));
$dfr1 = date('Y-m-d', strtotime($dfr. ' + 1 days'));
$d = explode('-',$dto1);

$sku2upd = array();
$sku2crt = array();


$asds = 'DESC';
$trows = '';
$t = 0; $vt = 0;

$args = array(
	'post_status'    => 'publish',
	'post_type'      => 'product',
	'posts_per_page' => $lmt,
	'orderby'        => 'date',
	'order'          => $asds,
	'date_query'     => array(
		'column'     => 'post_modified',
		'before'     => $dto.' + 1 days',
		'after'      => $dfr.' + 0 days',
		'type'       => 'date'
		)
	);



//Forevermark Townplace Crema
/*
$args = array(
	'post_status'    => 'publish',
	'post_type'      => 'product',
	'posts_per_page' => $lmt,
	'orderby'        => 'date',
	'order'          => $asds,
	);

*/

$items = get_posts( $args ); 

$mh = NULL;
$mh['cost'] = 0;
$mh['incr'] = 0;

foreach($items as $ii){


// $term_list = wp_get_post_terms($ii->ID,'product_cat',array('fields'=>'ids'));
// $cat_id = (int)$term_list[0];
// echo get_term_link ($cat_id, 'product_cat') . '<br>';


	++$mh['incr'];
	$mh[0] = $ii;
	$mh[1] = wc_get_product( $ii->ID );
	$mh[2] = get_post_meta($ii->ID);
	$mh[3] = getqbinventory($mh[0]->_sku);
	$mh['cost'] = calcost($mh);


//if(!preg_match('/Townplace Crema/', $mh[0]->post_title)){continue;}

	$trows .= rowtmpl8($mh);
	if($mh[3]['QBListID']!=NULL){ 
		if((floatval($mh[1]->price) !== floatval($mh[3]['SPrice'])) 
           OR (floatval($mh['cost'])   !== floatval($mh[3]['VCost']))
		   ) { array_push($sku2upd, $mh); }
		}
	else{ 
		if(floatval($mh[1]->price)>0){
			$newid = insertItem($mh);
			if($newid){ $Queue->enqueue(QUICKBOOKS_ADD_NONINVENTORYITEM, $newid); }
			}
		}

//echo '<pre>'; print_r($mh[2]); echo '</pre>'; echo '<br><br><br>';

    $argsv = array(
        'post_parent' => $ii->ID,
        'post_type'   => 'product_variation',
		'orderby'     => 'date',
		'order'       => $asds,
		'date_query'  => array(
			'column'  => 'post_modified',
			'before'  => $dto.' + 1 days',
			'after'   => $dfr.' + 0 days',
			'type'    => 'date'
			),
        'numberposts' => -1,
		); 


/*
    $argsv = array(
        'post_parent' => $ii->ID,
        'post_type'   => 'product_variation',
		'orderby'     => 'date',
		'order'       => $asds,
        'numberposts' => -1,
		); 
*/

	$vars = get_posts( $argsv ); 
	foreach($vars as $iv){ ++$mh['incr'];
		$mh[0]=$iv;
		$mh[1] = wc_get_product( $iv->ID );
		$mh[2] = get_post_meta($iv->ID);
		$mh[3] = getqbinventory($mh[0]->_sku);
		$mh['cost'] = calcost($mh);

//if(!preg_match('/Townplace Crema/', $mh[0]->post_title)){continue;}

		$trows .= rowtmpl8($mh);

// $term_list = wp_get_post_terms($iv->ID,'product_cat',array('fields'=>'ids'));
// $cat_id = (int)$term_list[0];
// echo get_term_link ($cat_id, 'product_cat') . '<br>';


		if($mh[3]['QBListID']!=NULL){ 
			if((floatval($mh[1]->price) !== floatval($mh[3]['SPrice'])) OR
			   (floatval($mh['cost'])   !== floatval($mh[3]['VCost']))
			) { array_push($sku2upd, $mh); }
			}
		else{ 
			if(floatval($mh[1]->price)>0){
				$newid = insertItem($mh);
				if($newid){ $Queue->enqueue(QUICKBOOKS_ADD_NONINVENTORYITEM, $newid); }
				}
			}
		}
	}

//	echo '<br><br><b>Sku to update</b><br>';
foreach($sku2upd as $h){
//	echo $ts . ', ';
	}
//	echo '<br><br><b>Sku to create</b><br>';
foreach($sku2crt as $hh){
//	$newid = insertItem($hh);
//	if($newid){ $Queue->enqueue(QUICKBOOKS_ADD_NONINVENTORYITEM, $newid); }
	//	$Queue->enqueue(QUICKBOOKS_MOD_NONINVENTORYITEM, $newid);
//	echo $ts . ', ';
	}

$pcnt = '
<div style="float: left;">

<form action="#" method="post" name="pgref" style="margin: 0px;">
<input type="button" name="brfrsh" value="Refresh" OnClick="this.form.submit();" style="width: 70px; margin: 0px 9px;" />

List dated From - 
<input type="date" name="datefr" value="'.$dfr.'" />&nbsp; &nbsp;To - 
<input type="date" name="dateto" value="'.$dto.'" />&nbsp; &nbsp;
<input type="submit" name="submitdt" value="List items" style="padding: 1px 5px;" />
<a href="qbwlog.php">View Log</a>
</form>
</div>
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
	<th style="font-size: 13px;" colspan="2">Product</th>
	<th style="font-size: 13px;" colspan="2">Date / Time</th>
	<th style="font-size: 13px;"></th>
	<th style="font-size: 13px;" colspan="3">Price</th>
	<th style="font-size: 13px;" colspan="3">Quickbooks</th>
</tr>
<tr><th></th>
	<th style="width: 180px;">Sku</th>
	<th style="width: 200px;">Title</th>
	<th style="width: 120px;">DT posted</th>
	<th style="width: 120px;">DT modified</th>
	<th style="width: 70px;">Status</th>
	<th style="width: 70px;">Purch.</th>
	<th style="width: 70px;">Reg.</th>
	<th style="width: 70px;">Sale</th>
	<th style="width: 150px;">List ID</th>
	<th style="width: 70px;">Cost</th>
	<th style="width: 70px;">Sale</th>
</tr>
'.$trows.'
</table>
</form>
<div id="CConsole" OnMouseOver="bgtrrmv(this);" OnMouseOut="bgtrput(this);" 
style="position: fixed; width: 80%; bottom: 0px; left: 200px; height: 90%; background: #fff; border: solid 1px #333; border-radius: 17px 17px 0px 0px; box-shadow: 10px 15px 15px #555; display: none; z-index: 233;">
<div style="text-align: right; padding: 9px;"><img class="zclose" src="files/img/btnclosehover.png" OnClick="ClsConsole();" style="cursor: pointer;" title="Close Console" /></div>
<div id="InConsole" style="height: 95%; overflow: auto;">&nbsp;

</div>
</div>

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

function load_console(url, wd, ht){ //alert(wd); return false;
var	obj = document.getElementById("CConsole");
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

function slcustomer(o){
if(o.value.length >= 3){
var oid = o.id.replace("ptxbx_", "");
var url = "dispcns.php?sbj=pmt&oid="+oid;
	load_console(url, \'40%\', \'90%\');
	}
	return false;
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



function rowtmpl8($h){

$rpr = ($h[1]->regular_price) ? number_format($h[1]->regular_price, 2) :NULL;
$spr = ($h[1]->price) ? number_format($h[1]->price, 2) :NULL;
$cpr = ($h['cost']) ? number_format($h['cost'], 2) :NULL;

$style0 = ($h[3]['QBListID']!=NULL) ? ((floatval($h['cost']) !== floatval($h[3]['VCost'])) ? 'style="background: #fc0;"' : 'style="color: #090;"' ):'';
$style1 = ($h[3]['QBListID']!=NULL) ? ((floatval($h[1]->price) !== floatval($h[3]['SPrice'])) ? 'style="background: #fc0;"' : 'style="color: #090;"' ):'';
return '
<tr><td>'.$h['incr'].'. </td>
<td><a href="dispcns.php?sbj=item&iid='.$h[0]->ID.'" OnClick="return load_console(this.href, \'80%\', \'80%\');">'.$h[0]->_sku.'</a></td>
<td>'.$h[0]->post_title.'</td>
<td>'.$h[0]->post_date.'</td>
<td>'.$h[0]->post_modified.'</td>
<td>'.$h[0]->post_status.'</td>
<td>'.$cpr.'</td>
<td>'.$rpr.'</td>
<td>'.$spr.'</td>
<td>'.$h[3]['QBListID'].'</td>
<td '.$style0.'>'.$h[3]['VCost'].'</td>
<td '.$style1.'>'.$h[3]['SPrice'].'</td>
</tr>';

}

function getqbinventory($sku){ global $dbh;
	$arr=array();
	
	$row = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM qw_item WHERE Sku = '$sku'", MYSQLI_ASSOC));
		if($row['id']){

			
		// mysqli_query($dbh, "UPDATE qw_item SET 
			// Differ   = 1
		// WHERE id = '$record[id]' AND (SPrice != '$product->price')");

	$arr=$row;		
			
		}
	
	return $arr;
}

function calcost($h){ global $dbh;
$br = null;

/*
$carr_fm   = array(875, 831, 829, 830, 832, 838, 834, 828, 823, 836, 835, 827, 824, 822, 821, 102, 111, 117, 172, 82, 30, 756, 928, 474, 484, 130, 138, 162, 925, 766, 188);
$carr_cub  = array(877, 815, 810, 809, 814, 812, 808, 802, 807, 801, 813, 806, 820, 818, 819, 817, 805, 804, 803, 585, 586, 587, 588, 589, 590, 591, 509, 593, 594, 595, 596, 597, 599, 600, 603, 604, 605);
$carr_ghi  = array(753, 418, 423, 434, 438, 442, 449, 453, 459, 306, 211, 288, 220, 314, 400, 235, 249, 264, 292);
$carr_fldg = array(333, 345, 326);
$carr_uscd = array(840, 841, 774, 775, 776, 842, 873, 843, 845, 846, 777, 778, 779, 847, 848, 868);
$carr_msi  = array(908, 916, 920, 892, 907, 893, 902, 899, 905, 904, 900, 909, 910, 911, 912, 913, 915, 906, 901, 917, 918, 919);

$term_list = wp_get_post_terms($h[0]->ID,'product_cat',array('fields'=>'ids'));
$cat_id = (int)$term_list[0];
//$cat = get_term_link ($cat_id, 'product_cat');

if (in_array($cat_id, $carr_fm))   { $br = 'Forevermark'; }
if (in_array($cat_id, $carr_cub))  { $br = 'Cubitac'; }
if (in_array($cat_id, $carr_ghi))  { $br = 'GHI'; }
if (in_array($cat_id, $carr_fldg)) { $br = 'Featherlodge'; }
if (in_array($cat_id, $carr_uscd)) { $br = 'USCD'; }
if (in_array($cat_id, $carr_msi))  { $br = 'MSI'; }

return $cat_id;

*/

$carr_msi  = array(908, 916, 920, 892, 907, 893, 902, 899, 905, 904, 900, 909, 910, 911, 912, 913, 915, 906, 901, 917, 918, 919);
$term_list = wp_get_post_terms($h[0]->ID,'product_cat',array('fields'=>'ids'));
$cat_id = (int)$term_list[0];

$bn = NULL;
$adcls = NULL;

if(preg_match('/^Forevermark/',      $h[0]->post_title)) { $br = 'Forevermark';   }
if(preg_match('/^Feather Lodge/',    $h[0]->post_title)) { $br = 'Featherlodge'; }
if(preg_match('/^Adornus/',          $h[0]->post_title)) { $br = 'Adornus';     }
if(preg_match('/^Cubitac/',          $h[0]->post_title)) { $br = 'Cubitac';    }
if(preg_match('/^GHI/',              $h[0]->post_title)) { $br = 'GHI';       }
if(preg_match('/^US Cabinet Depot/', $h[0]->post_title)) { $br = 'USCD';     }
if(in_array($cat_id, $carr_msi))                         { $br = 'MSI';     }

//	echo $h[0]->_sku . ' - ' . $h[0]->post_title . ' - Category ID ' .$cat_id. '<br>';

$spf = 0;
$vcf = 0;
$row = NULL;

//echo $cat . '<br>';
switch($br){
	case 'Forevermark':
		$row = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM qc_factor WHERE BrName = '$br'", MYSQLI_ASSOC));
	break;
	case 'Cubitac':
		if( (preg_match('/-ASM/'   , $h[0]->_sku)) OR
			(preg_match('/-RIGHT/' , $h[0]->_sku)) OR
			(preg_match('/-LEFT/'  , $h[0]->_sku))
			){ $row = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM qc_factor WHERE BrName = '$br' AND Misc = 'Asm'", MYSQLI_ASSOC)); }
		else { $row = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM qc_factor WHERE BrName = '$br' AND Misc = 'Flat'", MYSQLI_ASSOC)); }
	break;
	case 'GHI':
		$sff = substr($h[0]->_sku, -3);
		$row = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM qc_factor WHERE BrName = '$br' AND Misc = '$sff'", MYSQLI_ASSOC));
	break;
	case 'Featherlodge':
		$sff = substr($h[0]->_sku, -3);
		$row = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM qc_factor WHERE BrName = '$br' AND Misc = '$sff'", MYSQLI_ASSOC));
	break;
	case 'USCD':
		$row = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM qc_factor WHERE BrName = '$br' AND Misc = 'Int'", MYSQLI_ASSOC));
	break;
	case 'MSI':
		$row = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM qc_factor WHERE BrName = '$br'", MYSQLI_ASSOC));
	break;
	
	default:
	break;
}

/*

	if(preg_match('/^(Forevermark)(.*)/', $h[0]->post_title)) { $bn = 'Forevermark'; $adcls = 1; }
	if(preg_match('/^(Adornus)(.*)/', $h[0]->post_title)) { $bn = 'Adornus'; $adcls = 1; }
	if(preg_match('/^(Cubitac)(.*)/', $h[0]->post_title)) { $bn = 'Cubitac'; 
	echo $h[0]->_sku . ' - ' . $h[0]->post_title . '<br>';
		if( (preg_match('/-ASM/'   , $h[0]->_sku)) OR
			(preg_match('/(.*)(-RIGHT)/' , $h[0]->_sku)) OR
			(preg_match('/-LEFT/'  , $h[0]->_sku))
			){ $adcls = 'Misc = \'Asm\''; echo $h[0]->_sku . '<br>'; }
		else { $adcls = 'Misc = \'Flat\''; return 'NNN'; }
		}
	if(preg_match('/^(GHI)(.*)/', $h[0]->post_title)) { $bn = 'GHI'; 
		$sff = substr($h[0]->_sku, -3);
		$adcls = 'Misc = \''.$sff.'\''; 
		}
	if(preg_match('/^(US Cabinet Depot)(.*)/', $h[0]->post_title)) { $bn = 'USCD'; $adcls = 1; }









if(preg_match('/(.*)(\/tiles-and-flooring\/)$/', $cat)) {
$spf = 0.7;
$vcf = 0.51;
}
else{
	if(preg_match('/^(Forevermark)(.*)/', $h[0]->post_title)) { $bn = 'Forevermark'; $adcls = 1; }
	if(preg_match('/^(Adornus)(.*)/', $h[0]->post_title)) { $bn = 'Adornus'; $adcls = 1; }
	if(preg_match('/^(Cubitac)(.*)/', $h[0]->post_title)) { $bn = 'Cubitac'; 
	echo $h[0]->_sku . ' - ' . $h[0]->post_title . '<br>';
		if( (preg_match('/-ASM/'   , $h[0]->_sku)) OR
			(preg_match('/(.*)(-RIGHT)/' , $h[0]->_sku)) OR
			(preg_match('/-LEFT/'  , $h[0]->_sku))
			){ $adcls = 'Misc = \'Asm\''; echo $h[0]->_sku . '<br>'; }
		else { $adcls = 'Misc = \'Flat\''; return 'NNN'; }
		}
	if(preg_match('/^(GHI)(.*)/', $h[0]->post_title)) { $bn = 'GHI'; 
		$sff = substr($h[0]->_sku, -3);
		$adcls = 'Misc = \''.$sff.'\''; 
		}
	if(preg_match('/^(US Cabinet Depot)(.*)/', $h[0]->post_title)) { $bn = 'USCD'; $adcls = 1; }
*/
//	$row = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM qc_factor WHERE BrName = '$bn' AND $adcls", MYSQLI_ASSOC));
//	$row = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM qc_factor WHERE 0", MYSQLI_ASSOC));
$spf = $row['SPFactor'];
$vcf = $row['VCFactor'];

if($spf){
$cost = ($h[1]->price) ? round($h[1]->price / $spf * $vcf, 2) : 0; 
}
return $cost; //floatval(number_format($cost, 2));

}






///////////////////////////////////////////////////////////////////////////////////////////////////
function insertItem($h){ global $dbh;

	// $h[0] = $item;
	// $h[1] = wc_get_product( $itd );
	// $h[2] = get_post_meta($itd);
	// $h['cost'] = calcost($h);


//echo date('Y-m-d H:m:s', time());

//return null;

//echo $h[0]->_sku . ' - ' . $h[0]->post_title . ' - '.$h['cost'] . '<br>';
//return;
	
	$que = "INSERT INTO wc_item ( Sku, Title, VCost, RPrice, SPrice ) 
		VALUES (
			'" . mysqli_escape_string($dbh, $h[0]->_sku ) . "',
			'" . mysqli_escape_string($dbh, $h[0]->post_title ) . "',
			'" . mysqli_escape_string($dbh, $h['cost'] ) . "',
			'" . mysqli_escape_string($dbh, $h[1]->regular_price ) . "',
			'" . mysqli_escape_string($dbh, $h[1]->price ) . "'
			)";
	mysqli_query($dbh, $que);
	return mysqli_insert_id($dbh);
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////





function sinchro_items(){ global $dbh;
if (file_exists("data/sinchro/noninventoryitems.xml")) {
	$xml = simplexml_load_file("data/sinchro/noninventoryitems.xml");
	foreach($xml->QBXMLMsgsRs->ItemNonInventoryQueryRs->ItemNonInventoryRet as $d){
		if(mysqli_fetch_array(mysqli_query($dbh, "SELECT id FROM qw_item WHERE Sku = '$d->Name'")))	{ // item exists
			mysqli_query($dbh, "UPDATE qw_item SET 
				QBListID   = '".mysqli_escape_string($dbh, $d->ListID)."',
				Title      = '".mysqli_escape_string($dbh, $d->SalesAndPurchase->SalesDesc)."', 
				VCost      = '".mysqli_escape_string($dbh, $d->SalesAndPurchase->PurchaseCost)."', 
				SPrice     = '".mysqli_escape_string($dbh, $d->SalesAndPurchase->SalesPrice)."',
				DTCreated  = '".mysqli_escape_string($dbh, $d->TimeCreated)."', 
				DTModified = '".mysqli_escape_string($dbh, $d->TimeModified)."', 
				Vendor     = ''
			WHERE Sku = '$d->Name'");
			}
		else{ // item does not exist
			mysqli_query($dbh, "INSERT INTO	qw_item ( QBListID, Sku, Title, VCost, SPrice, DTCreated, DTModified ) VALUES (
			'" . mysqli_escape_string($dbh, $d->ListID) . "',
			'" . mysqli_escape_string($dbh, $d->Name) . "',
			'" . mysqli_escape_string($dbh, $d->SalesAndPurchase->SalesDesc) . "',
			'" . mysqli_escape_string($dbh, $d->SalesAndPurchase->PurchaseCost) . "',
			'" . mysqli_escape_string($dbh, $d->SalesAndPurchase->SalesPrice) . "',
			'" . mysqli_escape_string($dbh, $d->TimeCreated) . "',
			'" . mysqli_escape_string($dbh, $d->TimeModified) . "'
			)");
			}
		}	
	}
return;
}






?>