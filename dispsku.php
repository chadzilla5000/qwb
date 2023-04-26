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
setlocale(LC_MONETARY, 'en_US');

$msg = NULL;
	require_once 'wconfig.php';


$lmt = (isset($_POST['rowsonpage']))?$_POST['rowsonpage']:50;
$trows = '';
$t = 0; $vt = 0;
$args = array(
	'post_type'      => 'product',
	'posts_per_page' => $lmt,
    'orderby' => 'date',
    'order'   => 'DESC'
);

global $product;
$t=0;

$Queue = new QuickBooks_WebConnector_Queue($dsn, $qbwc_user); 
$newid = rand();
//$Queue->enqueue(QUICKBOOKS_ADD_NONINVENTORYITEM, $newid);
//$Queue->enqueue(QUICKBOOKS_QUERY_NONINVENTORYITEM, $newid);
//$Queue->enqueue(QUICKBOOKS_ADD_PAYMENTITEM, $newid);

//exit;

$xmld = simplexml_load_file("wpq32.xml") or die('error');
foreach($xmld->QBXMLMsgsRs->ItemNonInventoryQueryRs->ItemNonInventoryRet as $seg){


$que = "INSERT INTO	qw_item ( Sku, Title, VCost, SPrice ) VALUES (
'" . mysqli_escape_string($dbh, $seg->Name) . "',
'" . mysqli_escape_string($dbh, $seg->SalesAndPurchase->SalesDesc) . "',
'" . mysqli_escape_string($dbh, $seg->SalesAndPurchase->PurchaseCost) . "',
'" . mysqli_escape_string($dbh, $seg->SalesAndPurchase->SalesPrice) . "'
		)";
//	mysqli_query($dbh, $que);
	

//    echo $seg->Name . ' - ' . $seg->SalesAndPurchase->SalesDesc . ' - ' . $seg->SalesAndPurchase->PurchaseCost . ' - ' . $seg->SalesAndPurchase->SalesPrice . '<br>';
}
//print_r ($xmld);

exit;
/////////////////////////////////////////////////////////////////////////////////////////////

    $loop = new WP_Query( $args );

    while ( $loop->have_posts() ) : $loop->the_post();
		$trows .= '<tr><td>'.++$t.'. </td><td style="width: 150px;"><a target="_blank" href="'.$product->get_permalink().'">'.$product->get_sku().'</a></td>
		<td style="text-align: center;">'.get_the_date('Y-m-d h:m:s', $product->get_id()).'</td>
		';
	
//        global $product;
//        echo '<br /><a href="'.get_permalink().'">' . woocommerce_get_product_thumbnail().' '.get_the_title().'</a>';
//        echo $product->get_sku().'<br />';
    endwhile;

    wp_reset_query();

$optlist = array(
	'<option value="50">50</option>',
	'<option value="100">100</option>',
	'<option value="200">200</option>',
	'<option value="500">500</option>'
	);


$pcnt = '


<table class="TBL" width="100%" cellspacing="1">
<tr><th></th>
	<th style="font-size: 13px;" colspan="4">
	<form action="#" method="post" name="pgref" style="float: left; margin: 0px;">
Records - <select name="rowsonpage" id="rowsonpage" OnChange="this.form.submit();">
'.getopts($optlist, $_POST['rowsonpage']).'
</select>
</form>

	</th>
	<th style="font-size: 13px;" colspan="8">Customers</th>
	<th style="font-size: 13px;" colspan="3">Payments</th>
</tr>
<tr><th></th>
	<th>Sku</th>
	<th style="width: 120px;">Date / Time</th>
	<th></th>
	<th>Created (Date Time)</th>
	<th>QB List ID</th>
	<th>Company</th>
	<th>Name</th>
	<th><input type="checkbox" name="chb2_0" value="1" dOnChange="chkallb(this);" Title="Check all" /></th>
	<th>EMail</th>
	<th>Phone</th>
	<th>Billing Address</th>
	<th>Shipping Address</th>
	<th>Total (USD)</th>
	<th><input type="checkbox" name="chb2_0" value="1" dOnChange="chkallb(this);" Title="Check all" /></th>
	<th>Paid (Date Time)</th>
</tr>
'.$trows.'
<tr><th></th>
	<th>Sku</th>
	<th></th>
	<th></th>
	<th>Created (Date Time)</th>
	<th>QB List ID</th>
	<th>Company</th>
	<th>Name</th>
	<th></th>
	<th>EMail</th>
	<th>Phone</th>
	<th>Billing Address</th>
	<th>Shipping Address</th>
	<th>Total (USD)</th>
	<th></th>
	<th>Paid (Date Time)</th>
</tr>
</table>
';












$pgtitle = '<h4>Customer list</h4>';
$pgcontent = <<<EOD__
<!-- Page content start -->
$pcnt
<!-- Page content end -->
EOD__;

//	}
$page_title       = 'Unified WC Terminal';
$page_description = '';
$keywords         = '';
$head_ext         = '';
	
require_once('inc/_shell.php'); ///
//////////////////////////////////
/////////////////////////////////
////////////////////////////////
///////////////////////////////
?>