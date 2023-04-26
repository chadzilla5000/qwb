<?php
require_once('inc/_init.php');
require_once('inc/functions/general.php');
error_reporting(E_ALL | E_STRICT);

if($lghsh['Id'] == 0){
	require_once('inc/functions/authorization.php'); 
	$pgcontent = logform(); 
	require_once('inc/_shell.php'); ///
	exit;
	}

include_once 'inc/functions.php';
setlocale(LC_MONETARY, 'en_US');

$msg = NULL;
	require_once 'wconfig.php';
	
	



$qbxml = simplexml_load_file("qb.xml");

//$xml=simplexml_load_string($xml) or die("Error: Cannot create object");
//print_r($xml);
//echo $xml->requestID;
//$dg = array_slice(json_decode(json_encode((array) $xml ),  true ), 0 );





//echo($qbxml->QBXMLMsgsRs->CustomerQueryRs->CustomerRet[2]->ListID);
$rt = 0;
/*
foreach($qbxml->QBXMLMsgsRs->CustomerQueryRs->CustomerRet as $dk=>$dv){
	echo ++$rt.'. '.$dv->ListID . ' - ' . $dv->Email . '<br>';
	
}
*/
//$dvb = $xml->QBXML->QBXMLMsgsRs->CustomerQueryRs->CustomerRet->ListID[0];
$dvb = $xml['QBXML']['QBXMLMsgsRs']['CustomerQueryRs']['CustomerRet'][0]['ListID'];
//echo $xml->CountryCode; // US
//echo $xml->ZipCode; // 94043


	
	
/*
// Map QuickBooks actions to handler functions
$map = array(
	QUICKBOOKS_ADD_CUSTOMER => array( '_quickbooks_customer_add_request', '_quickbooks_customer_add_response' ),

	QUICKBOOKS_IMPORT_PURCHASEORDER => array( '_quickbooks_purchaseorder_import_request', '_quickbooks_purchaseorder_import_response' ),
	QUICKBOOKS_IMPORT_INVOICE => array( '_quickbooks_invoice_import_request', '_quickbooks_invoice_import_response' ),
	QUICKBOOKS_IMPORT_CUSTOMER => array( '_quickbooks_customer_import_request', '_quickbooks_customer_import_response' ), 
	QUICKBOOKS_IMPORT_SALESORDER => array( '_quickbooks_salesorder_import_request', '_quickbooks_salesorder_import_response' ), 
	QUICKBOOKS_IMPORT_ITEM => array( '_quickbooks_item_import_request', '_quickbooks_item_import_response' ), 

	);

*/
//////////// Form handler ////////////////////////////////////////////////////
if(isset($_POST['fsbm'])){
//	require_once 'wconfig.php';

	if($_POST['customer2send']){
		foreach($_POST['customer2send'] as $sv){
			$newid = insert2db4xml($sv);
			$Queue = new QuickBooks_WebConnector_Queue($dsn); 
			$Queue->enqueue(QUICKBOOKS_ADD_CUSTOMER, $newid);
			}
		$msg .= 'Customer(s) checked';
		}
	
	if($_POST['order2send']){
		foreach($_POST['order2send'] as $sv){
			
			}
		$msg .= 'Order(s) checked';
		}
	
	if($_POST['payment2send']){
		foreach($_POST['payment2send'] as $sv){
			
			}
		$msg .= 'Payment(s) checked';
		}

	$msg .=($msg)?'':'No data to send to QB';
	}
	
//	echo $msg;
////////////////////////////////////////////////////////////////////
$lmt = (isset($_POST['rowsonpage']))?$_POST['rowsonpage']:800;
$trows = '';
$t = 0; $vt = 0;
$args = array(
    'limit' => $lmt,
    'orderby' => 'date',
    'order'   => 'DESC',
    'return'  => 'ids'
);
$query = new WC_Order_Query( $args );
$orders = $query->get_orders();

/*
$res = mysqli_query($dbh, "SELECT * FROM qw_customer WHERE phone!=''");
	while($r = mysqli_fetch_assoc($res)){
		$ph = trtphonum($r['phone']);
		
		mysqli_query($dbh, "UPDATE qw_customer SET phone='$ph' WHERE id='$r[id]'");
		
//		echo ++$cht . '. ' . $r['name'] . ' - ' . $r['fname'] . ' ' . $r['lname'] .  ' ' . $r['email'] . '<br>';
		
		
//			$Que1 = new QuickBooks_WebConnector_Queue($dsn); 
//			$Que1->enqueue(QUICKBOOKS_QUERY_CUSTOMER, $r['id']);

	}

*/

/*

$cht = 0;
$res = mysqli_query($dbh, "SELECT * FROM qw_customer WHERE quickbooks_listid='N_A'");
	while($r = mysqli_fetch_assoc($res)){
		
		echo ++$cht . '. ' . $r['name'] . ' - ' . $r['fname'] . ' ' . $r['lname'] .  ' ' . $r['email'] . '<br>';
		
		
			$Que1 = new QuickBooks_WebConnector_Queue($dsn); 
			$Que1->enqueue(QUICKBOOKS_QUERY_CUSTOMER, $r['id']);

	}


*/

	$res = mysqli_query($dbh, "SELECT * FROM qw_customer WHERE 1");
while($lex = mysqli_fetch_assoc($res)){

$ph = trtphonum($lex['phone']);

//	echo ++$vt . '. ' . $lex['fname'] . ' ' . $lex['lname'] . ' ' . $ph . '<br>';
}



foreach($orders as $oid){
	$ord_sent = $pay_sent = 0;
	$trows .= '<tr><td>'.++$t.'. </td><td style="width: 50px;">'.$oid.'</td>';

	$od = new WC_Order($oid);
	$om = get_post_meta($oid);



$cml = $om['_billing_email'][0];
$cph = $om['_billing_phone'][0];

foreach($qbxml->QBXMLMsgsRs->CustomerQueryRs->CustomerRet as $dk=>$dv){
	$wcphone = trtphonum($cph);
	$qbphone = trtphonum($dv->Phone);
	if($dv->Email==$cml OR $wcphone==$qbphone){
//		mysqli_query($dbh, "UPDATE qw_customer SET quickbooks_listid='$dv->ListID', phone='$wcphone' WHERE email='$cml' OR phone='$wcphone'");
		mysqli_query($dbh, "UPDATE qw_customer SET quickbooks_listid='$dv->ListID', phone='$wcphone' WHERE email='$cml' OR phone='$wcphone'");
		}
	}


$phonum = trtphonum($cph);
$phstyle=(strlen($phonum)<10)?'#f00':'#000';

//mysqli_query($dbh, "UPDATE qw_customer SET phone='$cph' WHERE email='$cml'");



	$cust_sent = chksent($cml);

/*
	$res = mysqli_query($dbh, "SELECT * FROM qw_customer WHERE email='$cml'");
//	$res = mysqli_query($dbh, "SELECT * FROM qw_customer WHERE ISNULL(quickbooks_listid)");
//	$res = mysqli_query($dbh, "SELECT * FROM qw_customer WHERE quickbooks_listid='N_A'");
	$exid = mysqli_fetch_assoc($res)['id'];

if($exid){}
else{ 

$que = "INSERT INTO	qw_customer	( name, fname, lname, email ) 
		VALUES (
			'" . mysqli_escape_string($dbh, $om['_billing_company'][0] ) . "',
			'" . mysqli_escape_string($dbh, $om['_billing_first_name'][0] ) . "',
			'" . mysqli_escape_string($dbh, $om['_billing_last_name'][0] ) . "',
			'" . mysqli_escape_string($dbh, $om['_billing_email'][0] ) . "')";
	mysqli_query($dbh, $que);
	$nid = mysqli_insert_id($dbh);

$vt++;
			$Que1 = new QuickBooks_WebConnector_Queue($dsn); 
			$Que1->enqueue(QUICKBOOKS_QUERY_CUSTOMER, $nid);
}

*/

	$dsbl_custchbx  = $dsbl_ordchbx = $dsbl_paychbx = $dsbl_ordest = 'disabled';
	$chk_custchbx  = $chk_ordchbx  = $chk_paychbx  = '';
	$clr_custchbx = $clr_ordchbx  = $clr_paychbx  = 'transparent';

if($om['_billing_email'][0]){
	if($cust_sent=='NA'){$chk_custchbx = 'checked'; $clr_custchbx = '#fc9'; $dsbl_custchbx = ''; }
elseif($cust_sent==NULL){ $dsbl_custchbx = ''; }
else					{ $chk_custchbx = 'checked'; $clr_custchbx = '#9c9'; }

	
	// if($cust_sent==2){	$chk_custchbx = 'checked'; $clr_custchbx = '#9c9'; }
	// elseif($cust_sent==1){$chk_custchbx = 'checked'; $clr_custchbx = '#fc9'; $dsbl_custchbx = ''; }
	// else{ $dsbl_custchbx = ''; }
}

	if($ord_sent){	$chk_ordchbx = 'checked'; $clr_ordchbx = '#9c9'; }
	else{$chk_ordchbx = ''; $clr_ordchbx = '#fc9'; if($om['_billing_email'][0] AND $cust_sent){ $dsbl_ordchbx = ''; }}

	if($pay_sent){	$chk_paychbx = 'checked'; $clr_paychbx = '#9c9'; }
	else{$chk_paychbx = ''; $clr_paychbx = '#fc9'; if($om['_billing_email'][0] AND $cust_sent){ $dsbl_paychbx = ''; }}	
//	$clr_custchbx  =($sent)?'#9c9':'#fc9';
//	$dsbl_custchbx =($om['_billing_email'][0] AND !$sent)?'':'disabled'; 


	$shipdiff = (
		($om['_shipping_first_name'][0] != '' AND $om['_shipping_first_name'][0] != $om['_billing_first_name'][0]) OR
		($om['_shipping_last_name'][0]  != '' AND $om['_shipping_last_name'][0]  != $om['_billing_last_name'][0])  OR
		($om['_shipping_address_1'][0]  != '' AND $om['_shipping_address_1'][0]  != $om['_billing_address_1'][0])  OR
		($om['_shipping_city'][0]       != '' AND $om['_shipping_city'][0]       != $om['_billing_city'][0])       OR
		($om['_shipping_state'][0]      != '' AND $om['_shipping_state'][0]      != $om['_billing_state'][0])      OR
		($om['_shipping_postcode'][0]   != '' AND $om['_shipping_postcode'][0]   != $om['_billing_postcode'][0])) ?
		 $om['_shipping_first_name'][0] . ' ' . 
		 $om['_shipping_last_name'][0]  . ' ' . 
		 $om['_shipping_address_1'][0]  . ' ' . 
		 $om['_shipping_city'][0]       . ' ' . 
		 $om['_shipping_state'][0]      . ' ' . 
		 $om['_shipping_postcode'][0]   : '<label style="color: #999;">Same as billing</lable>';
	if( !$om['_billing_email'][0] ) { $shipdiff = ''; }

	$trows .= '
<td style="width: 20px; text-align: center;"><input type="checkbox" name="order2send[]" title="Send order to QB" value="'.$oid.'" '.$dsbl_ordchbx.' '.$chk_ordchbx.' /></td>
<td style="width: 80px; text-align: center;"><select name="ordest_'.$oid.'" style="font-size: 12px;" title="Send to QB as" '.$dsbl_ordest.'>
	<option value="Est">Estimate</option>
	<option value="Ord" selected>Order</option>
</select></td>
<td style="width: 130px; text-align: center; background-color: '.$clr_ordchbx.';">'.$od->order_date.'</td>
<td nowrap>'.$cust_sent.'</td>
<td>'.$om['_billing_company'][0].'</td>
<td style="background-color: '.$clr_custchbx.';">'.$om['_billing_last_name'][0].', '.$om['_billing_first_name'][0].'</td>
<td style="width: 20px; text-align: center;"><input type="checkbox" name="customer2send[]" title="Send customer to QB" value="'.$oid.'" '.$dsbl_custchbx.' '.$chk_custchbx.' /></td>
<td>'.$om['_billing_email'][0].'</td>
<td style="color: '.$phstyle.';">'.$phonum.'</td>
<td>'.$om['_billing_first_name'][0].' '.$om['_billing_last_name'][0].' '.$om['_billing_address_1'][0].' '.$om['_billing_city'][0].' '.$om['_billing_state'][0].' '.$om['_billing_postcode'][0].'</td>
<td>'.$shipdiff.'</td>
<td style="text-align: right;">'.$om['_order_total'][0].'</td>
<td style="width: 20px; text-align: center;"><input type="checkbox" name="payment2send[]" title="Send payment to QB" value="'.$oid.'" '.$dsbl_paychbx.' '.$chk_paychbx.' /></td>
<td style="width: 130px; text-align: center; background-color: '.$clr_paychbx.';">'.$om['_paid_date'][0].'</td>
'; 
	$trows .= '</tr>';
	}

$optlist = array(
	'<option value="50">50</option>',
	'<option value="100">100</option>',
	'<option value="200">200</option>',
	'<option value="500">500</option>'
	);

$pcnt = '<form action="#" method="post" name="qform" style="margin-top: 20px;">
<input type="hidden" name="fsbm" value="1" />Records - 
<select name="rowsonpage" id="rowsonpage" OnChange="this.form.submit();">
'.getopts($optlist, $_POST['rowsonpage']).'
</select>
<!-- select name="itemsel">
	<option value="0">With selected</option>
	<option value="1">Send Customer(s)</option>
	<option value="2" disabled>Send Order(s)</option>
	<option value="3" disabled>Send Payment(s)</option>
</select -->
<input type="submit" name="button" value="Send" style="float: right; width: 70px;" />
<table class="TBL" width="100%" cellspacing="1">
<tr><th></th>
	<th>Order ID</th>
	<th><input type="checkbox" name="chb1_0" value="1" dOnChange="chkallb(this);" Title="Check all" /></th>
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
	<th>Order ID</th>
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
</form>
';



// $statuses = array_map( 'esc_sql', wc_get_is_paid_statuses() );
// $cemails = $wpdb->get_col("
   // SELECT DISTINCT pm.meta_value FROM {$wpdb->posts} AS p
   // INNER JOIN {$wpdb->postmeta} AS pm ON p.ID = pm.post_id
   // INNER JOIN {$wpdb->prefix}woocommerce_order_items AS i ON p.ID = i.order_id
   // INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS im ON i.order_item_id = im.order_item_id
   // WHERE p.post_status IN ( 'wc-" . implode( "','wc-", $statuses ) . "' )
   // AND pm.meta_key IN ( '_billing_email' )
   // AND im.meta_key IN ( '_product_id', '_variation_id' )
   // AND im.meta_value = $product_id
// ");

//print_r( $cemails );

//$users = get_users( array( 'fields' => array( 'ID','display_name','user_email' ) ) );


/*
foreach ($oo as $ok=>$ov){
	$trows .= '
<td><label style="color: #696;">'.$ok.'</lable></td>
<td><b>'.$oo[$ok][0].'</b></td>
';
	}	
*/

/*
_billing_company	
_billing_first_name
_billing_last_name
_billing_phone
_billing_email	
_billing_address_1
_billing_city
_billing_state
_billing_postcode
_billing_country
_shipping_company
_shipping_first_name
_shipping_last_name
_shipping_address_1
_shipping_city
_shipping_state
_shipping_postcode
_shipping_country

_billing_address_index
_shipping_address_index

_order_total
_payment_method

_paid_date
*/



//print_r( $customer_emails );


//foreach ($customer as $bk=>$bv){
	
//$trows .= '<tr><td>'.++$t.'. </td>';

//foreach ($bv as $ck=>$cv){
//$trows .= '<td>'.$bv.'</td>';

//}


//$trows .= '</tr>';
	
//}
//$crows = $wpdb->get_results($wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = '_customer_user' AND meta_value > 0"), true);


/*

$customer_ids = $wpdb->get_col("SELECT DISTINCT meta_value  FROM $wpdb->postmeta
    WHERE meta_key = '_customer_user' AND meta_value > 0");
print_pr($customer_ids);
$currency = ' (' . get_option('woocommerce_currency') . ')';
if (sizeof($customer_ids) > 0){
    foreach ($customer_ids as $customer_id){
        $customer = new WP_User($customer_id);

        $trows .= '<tr>
            <td><?php echo $customer->display_name; ?></td>
            <td><?php echo $customer->user_email; ?></td>
            <td><?php echo wc_get_customer_total_spent($customer_id); ?></td>
            <td><?php echo wc_get_customer_order_count($customer_id); ?></td>
        </tr>
		
        <tr>
            <th data-sort="string"><?php _e("Name", "woocommerce"); ?></th>
            <th data-sort="string"><?php _e("Email", "woocommerce"); ?></th>
            <th data-sort="float"><?php _e("Total spent", "woocommerce"); echo $currency; ?></th>
            <th data-sort="int"><?php _e("Orders placed", "woocommerce"); ?></th>
        </tr>

		
		';
	}
}

*/

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

function chkqbcust($cml){ global $dbh; // Function to check if customer exists in QB (need to add more thorough check with qb import)
	$res = mysqli_query($dbh, "SELECT * FROM qw_customer WHERE email='$cml'");
	return mysqli_fetch_assoc($res)['id'];
//	return 0;
}


function chksent($eml){ global $dbh; // Function to check if customer has been sent to QB (need to add more thorough check with qb import)
//	$res = mysqli_query($dbh, "SELECT quickbooks_listid FROM wc_customer WHERE OrderID = '$o' AND quickbooks_listid != NULL");
	
	$rs2 = mysqli_query($dbh, "SELECT * FROM qw_customer WHERE email='$eml'");
//	$rs2 = mysqli_query($dbh, "SELECT * FROM qw_customer WHERE email='$eml'");
//	$exiinqb = mysqli_fetch_assoc($rs2)['id'];

	return mysqli_fetch_assoc($rs2)['quickbooks_listid'];
//	return ($kv=='NA')?1:(($kv==NULL)?0:2);
//	return 0;
}

function insert2db4xml($sv){ global $dbh;
	$omd = get_post_meta($sv);
	$que = "INSERT INTO	wc_customer	(	
			OrderID, CName,	FName, LName, Phone, EMail,	Street,	Line2, City, State,	Zip, Country,
			CNameAlt, FNameAlt,	LNameAlt, StreetAlt, Line2Alt, CityAlt, StateAlt, ZipAlt, CountryAlt
		) VALUES (
			'" . mysqli_escape_string($dbh, $sv ) . "',
			'" . mysqli_escape_string($dbh, $omd['_billing_company'][0] ) . "',
			'" . mysqli_escape_string($dbh, $omd['_billing_first_name'][0] ) . "',
			'" . mysqli_escape_string($dbh, $omd['_billing_last_name'][0] ) . "',
			'" . mysqli_escape_string($dbh, $omd['_billing_phone'][0] ) . "',
			'" . mysqli_escape_string($dbh, $omd['_billing_email'][0] ) . "',
			'" . mysqli_escape_string($dbh, $omd['_billing_address_1'][0] ) . "',
			'" . mysqli_escape_string($dbh, '' ) . "',
			'" . mysqli_escape_string($dbh, $omd['_billing_city'][0] ) . "',
			'" . mysqli_escape_string($dbh, $omd['_billing_state'][0] ) . "',
			'" . mysqli_escape_string($dbh, $omd['_billing_postcode'][0] ) . "',
			'" . mysqli_escape_string($dbh, $omd['_billing_country'][0] ) . "',
			'" . mysqli_escape_string($dbh, $omd['_shipping_company'][0] ) . "',
			'" . mysqli_escape_string($dbh, $omd['_shipping_first_name'][0] ) . "',
			'" . mysqli_escape_string($dbh, $omd['_shipping_last_name'][0] ) . "',
			'" . mysqli_escape_string($dbh, $omd['_shipping_address_1'][0] ) . "',
			'" . mysqli_escape_string($dbh, '' ) . "',
			'" . mysqli_escape_string($dbh, $omd['_shipping_city'][0] ) . "',
			'" . mysqli_escape_string($dbh, $omd['_shipping_state'][0] ) . "',
			'" . mysqli_escape_string($dbh, $omd['_shipping_postcode'][0] ) . "',
			'" . mysqli_escape_string($dbh, $omd['_shipping_country'][0] ) . "'
		)";
	mysqli_query($dbh, $que);
	return mysqli_insert_id($dbh);
}


function trtphonum($phn){
//	$ph = preg_replace('/[\+\-\(\)\s]/', '', $phn);	
//	$ph = preg_replace('/[\D]/', '', $phn);	
//	if(strlen($ph)>10){ $ph=substr($ph, -10); }
//	if(strlen($ph)<10){ $ph='<label style="color: #f00;" title="Incomplete phone number">'.$ph.'</label>'; }
//	return (strlen($ph)>10)?substr($ph, -10):$ph;
	return substr(preg_replace('/[\D]/', '', $phn), -10);
}




?>