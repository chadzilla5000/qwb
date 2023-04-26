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


$oid = NULL;
////////////////////////////////////////////////////////////////////
if(!isset($_GET['oid'])) { die('Missing or invalid order ID'); }
else { $oid = $_GET['oid']; }
///////// Get order object
$order = ($oid) ? new WC_Order($oid) : NULL;


$styleadd=($order->customer_note)?'border-right: double 5px #00c; cursor: pointer;':'';
$jspp = ($order->customer_note)?'OnMouseOver="pupnote(this);" OnMouseOut="pclsnote(this);"':'';
//$ord->order_date

$itemsfrm = '';

// Get and Loop Over Order Items
foreach ( $order->get_items() as $item_id => $item ) {
	$product_id   = $item->get_product_id();
	$variation_id = $item->get_variation_id();
	$product      = $item->get_product();
	$name         = $item->get_name();
	$quantity     = $item->get_quantity();
	$subtotal     = $item->get_subtotal();
	$total        = $item->get_total();
	$tax          = $item->get_subtotal_tax();
	$taxclass     = $item->get_tax_class();
	$taxstat      = $item->get_tax_status();
	$allmeta      = $item->get_meta_data();
	$somemeta     = $item->get_meta( '_whatever', true );
	$type         = $item->get_type();

//print_r($allmeta);
/*
		foreach($allmeta as $sk=>$sv){
	echo $sk . ' - ' . $sv . '<br>';		
			}

*/


//echo $allmeta[0] . '<br><br><br>';
//	echo $product . '<br><br><br>';
//print_r($allmeta)['timezone_type'][0];
  
   $itemsfrm .= '
<tr><td style="text-align: center;">'.$quantity.'</td>
	<td>'.$product->sku.'</td>
	<td>'.$product->attribute_summary.'</td>
	<td>'.$name.'</td>
	<td style="text-align: right;">$'.number_format((float) $product->regular_price, 2).'</td>
	<td style="text-align: right;">$'.number_format((float) $product->price, 2).'</td>
	<td style="text-align: right;">$'.number_format((float) $subtotal, 2).'</td>
</tr>
   
   ';
}


/*


	<td style="text-align: right;">$'.number_format((float) $product->regular_price, 2).'</td>
	<td style="text-align: right;">$'.number_format((float) $product->price, 2).'</td>
	<td style="text-align: right;">$'.number_format((float) $subtotal, 2).'</td>



*/


$stdprttl = $order->get_subtotal();
$discnt   = $order->get_discount_total();
$netsbf   = $stdprttl - $discnt;
$freight  = $order->get_shipping_total();
$frdiscnt = 0;
$netsbtx  = $netsbf + $freight - $frdiscnt;
$sttax    = $order->get_total_tax();


$pcnt = '

<style>
#TRght, #TBase, #TList{
	width: 100%; background: #999;
	}
#TRght td, #TBase td, #TList td{
	 background: #fff;
	 padding: 5px;
	}

#TBase{ margin-top: 50px;}
	
.LRB{
	width: 48%;
	height: auto;
	border: solid 1px #eee;
	padding: 9px 15px;
	background: #fff;
	font-size: 17px;
}

.TSP01{
	width: 70%;
	text-align: right;
	font-size: 15px;
	color: #999;
}
</style>


<div style="margin: 0px; padding: 0px; margin-top: 10px; padding: 5px;">
	<div style="float: left; width: 300px; font: normal 15px \'Arial\'">
		<table id="TRght" cellspacing="1">
			<tr><td style="width: 70%;">Date / Time</td>
				<td>Order #</td>
			</tr>
			<tr><td>'.$order->get_date_created().'</td>
				<td>'.$oid.'</td>
			</tr>
		</table>
	</div>

	<form action="#" method="post" name="ordersend">
	<input type="hidden" name="fsbm" value="1" />

	</form>

	<div style="clear: both; height: 35px;"></div>

	<div class="LRB" style="float: left;">
		<h2>Sale</h2>
'.$order->get_formatted_billing_address().'<br><br>

Email: '.$order->get_billing_email()      .'<br>
Phone: '.$order->get_billing_phone()      .'<br>

		<br>Ship to: 
'.$order->get_formatted_shipping_address().'

		<table id="TList" cellspacing="1">
		<tr><td style="width: 5%;">Qtty</td>
			<td>Item Code</td>
			<td>Asm</td>
			<td>Description</td>
			<td>List Price</td>
			<td>Std. Price</td>
			<td>Total</td>
		</tr>
'.$itemsfrm.'
		<tr><td colspan="4" rowspan="2" style="vertical-align: middle;"><b>Notes:</b> '.$order->customer_note.'</td>
			<td rowspan="2" style="vertical-align: middle;">List Total: </td>
			<td colspan="2" style="text-align: right;">
				<table style="width: 100%">
					<tr><td class="TSP01">Std Price Total:</td>
						<td>$'.number_format($stdprttl, 2).'</td>
					</tr>
					<tr><td class="TSP01">Contractor Discount:</td>
						<td>$'.number_format($discnt, 2).'</td>
					</tr>
					<tr><td class="TSP01">Net Sale Before Freight:</td>
						<td>$'.number_format($netsbf, 2).'</td>
					</tr>
					<tr><td class="TSP01">Freight:</td>
						<td>$'.number_format($freight, 2).'</td>
					</tr>
					<tr><td class="TSP01">Net Sale Before Tax:</td>
						<td>$'.number_format($netsbtx, 2).'</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><td colspan="2" style="text-align: right;">
				<table style="width: 100%">
					<tr><td class="TSP01">Tax (6.0%):</td>
						<td>$'.number_format($sttax, 2).'</td>
					</tr>
					<tr><td class="TSP01"><b>Total Cost:</b></td>
						<td><b>'.$order->get_formatted_order_total().'</b></td>
					</tr>
				</table>
			</td>
		</tr>
		</table>
	</div>
	<div class="LRB" style="float: right;">
		<h2>Purchase</h2> 
	</div>
	<div style="clear: both;"></div>
</div>
';

$pgtitle = '<h4>Order Summary</h4>';
$pgcontent = <<<EOD__
<!-- Page content start -->
$pcnt
<!-- Page content end -->
EOD__;
echo $pgcontent;
exit;

$page_title       = 'WC Terminal - Order review';
$page_description = '';
$keywords         = '';
$head_ext         = '';
	
require_once('inc/_shell.php'); ///
//////////////////////////////////
/////////////////////////////////
////////////////////////////////
///////////////////////////////







function get_order_details($order_id){

    // 1) Get the Order object
    $order = wc_get_order( $order_id );

    // OUTPUT
    echo '<h3>RAW OUTPUT OF THE ORDER OBJECT: </h3>';
    print_r($order);
    echo '<br><br>';
    echo '<h3>THE ORDER OBJECT (Using the object syntax notation):</h3>';
    echo '$order->order_type: ' . $order->order_type . '<br>';
    echo '$order->id: ' . $order->id . '<br>';
    echo '<h4>THE POST OBJECT:</h4>';
    echo '$order->post->ID: ' . $order->post->ID . '<br>';
    echo '$order->post->post_author: ' . $order->post->post_author . '<br>';
    echo '$order->post->post_date: ' . $order->post->post_date . '<br>';
    echo '$order->post->post_date_gmt: ' . $order->post->post_date_gmt . '<br>';
    echo '$order->post->post_content: ' . $order->post->post_content . '<br>';
    echo '$order->post->post_title: ' . $order->post->post_title . '<br>';
    echo '$order->post->post_excerpt: ' . $order->post->post_excerpt . '<br>';
    echo '$order->post->post_status: ' . $order->post->post_status . '<br>';
    echo '$order->post->comment_status: ' . $order->post->comment_status . '<br>';
    echo '$order->post->ping_status: ' . $order->post->ping_status . '<br>';
    echo '$order->post->post_password: ' . $order->post->post_password . '<br>';
    echo '$order->post->post_name: ' . $order->post->post_name . '<br>';
    echo '$order->post->to_ping: ' . $order->post->to_ping . '<br>';
    echo '$order->post->pinged: ' . $order->post->pinged . '<br>';
    echo '$order->post->post_modified: ' . $order->post->post_modified . '<br>';
    echo '$order->post->post_modified_gtm: ' . $order->post->post_modified_gtm . '<br>';
    echo '$order->post->post_content_filtered: ' . $order->post->post_content_filtered . '<br>';
    echo '$order->post->post_parent: ' . $order->post->post_parent . '<br>';
    echo '$order->post->guid: ' . $order->post->guid . '<br>';
    echo '$order->post->menu_order: ' . $order->post->menu_order . '<br>';
    echo '$order->post->post_type: ' . $order->post->post_type . '<br>';
    echo '$order->post->post_mime_type: ' . $order->post->post_mime_type . '<br>';
    echo '$order->post->comment_count: ' . $order->post->comment_count . '<br>';
    echo '$order->post->filter: ' . $order->post->filter . '<br>';
    echo '<h4>THE ORDER OBJECT (again):</h4>';
    echo '$order->order_date: ' . $order->order_date . '<br>';
    echo '$order->modified_date: ' . $order->modified_date . '<br>';
    echo '$order->customer_message: ' . $order->customer_message . '<br>';
    echo '$order->customer_note: ' . $order->customer_note . '<br>';
    echo '$order->post_status: ' . $order->post_status . '<br>';
    echo '$order->prices_include_tax: ' . $order->prices_include_tax . '<br>';
    echo '$order->tax_display_cart: ' . $order->tax_display_cart . '<br>';
    echo '$order->display_totals_ex_tax: ' . $order->display_totals_ex_tax . '<br>';
    echo '$order->display_cart_ex_tax: ' . $order->display_cart_ex_tax . '<br>';
    echo '$order->formatted_billing_address->protected: ' . $order->formatted_billing_address->protected . '<br>';
    echo '$order->formatted_shipping_address->protected: ' . $order->formatted_shipping_address->protected . '<br><br>';
    echo '- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - <br><br>';

    // 2) Get the Order meta data
    $order_meta = get_post_meta($order_id);

    echo '<h3>RAW OUTPUT OF THE ORDER META DATA (ARRAY): </h3>';
    print_r($order_meta);
    echo '<br><br>';
    echo '<h3>THE ORDER META DATA (Using the array syntax notation):</h3>';
    echo '$order_meta[_order_key][0]: ' . $order_meta['_order_key'][0] . '<br>';
    echo '$order_meta[_order_currency][0]: ' . $order_meta['_order_currency'][0] . '<br>';
    echo '$order_meta[_prices_include_tax][0]: ' . $order_meta['_prices_include_tax'][0] . '<br>';
    echo '$order_meta[_customer_user][0]: ' . $order_meta['_customer_user'][0] . '<br>';
    echo '$order_meta[_billing_first_name][0]: ' . $order_meta['_billing_first_name'][0] . '<br><br>';
    echo 'And so on ……… <br><br>';
    echo '- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - <br><br>';

    // 3) Get the order items
    $items = $order->get_items();

    echo '<h3>RAW OUTPUT OF THE ORDER ITEMS DATA (ARRAY): </h3>';

    foreach ( $items as $item_id => $item_data ) {

        echo '<h4>RAW OUTPUT OF THE ORDER ITEM NUMBER: '. $item_id .'): </h4>';
        print_r($item_data);
        echo '<br><br>';
        echo 'Item ID: ' . $item_id. '<br>';
        echo '$item_data["product_id"] <i>(product ID)</i>: ' . $item_data['product_id'] . '<br>';
        echo '$item_data["name"] <i>(product Name)</i>: ' . $item_data['name'] . '<br>';

        // Using get_item_meta() method
        echo 'Item quantity <i>(product quantity)</i>: ' . $order->get_item_meta($item_id, '_qty', true) . '<br><br>';
        echo 'Item line total <i>(product quantity)</i>: ' . $order->get_item_meta($item_id, '_line_total', true) . '<br><br>';
        echo 'And so on ……… <br><br>';
        echo '- - - - - - - - - - - - - <br><br>';
    }
    echo '- - - - - - E N D - - - - - <br><br>';
}


