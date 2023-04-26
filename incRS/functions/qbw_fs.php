<?php 

function chkqbcust($cml){ global $dbh; // Function to check if customer exists in QB (need to add more thorough check with qb import)
	$res = mysqli_query($dbh, "SELECT * FROM qw_customer WHERE email='$cml'");
	return mysqli_fetch_assoc($res)['id'];
}


/// spcharhndl(); // 

function chkcustomer($hm){ global $dbh; // Function to check if customer has been sent to QB (need to add more thorough check with qb import)
$em = $hm['_billing_email'][0];
$ph = trtphonum($hm['_billing_phone'][0], false);
$cn = spcharhndl($hm['_billing_company'][0]); // preg_replace('/\W/', ' ', $hm['_billing_company'][0]);
$fn = spcharhndl($hm['_billing_first_name'][0]); // preg_replace('/\W/', '', $hm['_billing_first_name'][0]);
$ln = spcharhndl($hm['_billing_last_name'][0]); // preg_replace('/\W/', '', $hm['_billing_last_name'][0]);

$rs2 = mysqli_query($dbh, "SELECT * FROM qw_customer WHERE 
	(email='$em' AND ((name!='' AND name='$cn') OR (fname!='' AND fname='$fn' AND lname!='' AND lname='$ln'))) OR 
	(phone='$ph' AND ((name!='' AND name='$cn') OR (fname!='' AND fname='$fn' AND lname!='' AND lname='$ln'))) OR 
	(phone!='' AND phone='$ph' AND email!='' AND email='$em')");
return mysqli_fetch_assoc($rs2)['quickbooks_listid'];
}


function getqbcustomerTblHsh($lid){ global $dbh;
	$res = mysqli_query($dbh, "SELECT * FROM qw_customer WHERE quickbooks_listid='$lid'");
	return mysqli_fetch_assoc($res);
}


function insertCustomer($sv){ global $dbh;
	$omd = get_post_meta($sv);
	$phonum = trtphonum( $omd['_billing_phone'][0], false );

//	$cname = preg_replace("/\’/", '', $omd['_billing_company'][0]);
	$cname = spcharhndl($omd['_billing_company'][0]); // preg_replace("/\W/", ' ', $omd['_billing_company'][0]);
	$shname = spcharhndl($omd['_shipping_company'][0]); //preg_replace("/\W/", ' ', $omd['_shipping_company'][0]);
	$blname = spcharhndl($omd['_billing_last_name'][0]);
	$bfname = spcharhndl($omd['_billing_first_name'][0]);
	$shlname = spcharhndl($omd['_shipping_last_name'][0]);
	$shfname = spcharhndl($omd['_shipping_first_name'][0]);
	$baddress = upstrhndl($omd['_billing_address_1'][0]);
	$shaddress = upstrhndl($omd['_shipping_address_1'][0]);

	$que = "INSERT INTO	wc_customer	(	
			OrderID, CName,	FName, LName, Phone, EMail,	Street,	Line2, City, State,	Zip, Country,
			CNameAlt, FNameAlt,	LNameAlt, StreetAlt, Line2Alt, CityAlt, StateAlt, ZipAlt, CountryAlt
		) VALUES (
			'" . mysqli_escape_string($dbh, $sv )                             . "',
			'" . mysqli_escape_string($dbh, $cname )     . "',
			'" . mysqli_escape_string($dbh, $bfname )  . "',
			'" . mysqli_escape_string($dbh, $blname )   . "',
			'" . mysqli_escape_string($dbh, $phonum )       . "',
			'" . mysqli_escape_string($dbh, $omd['_billing_email'][0] )       . "',
			'" . mysqli_escape_string($dbh, $baddress )   . "',
			'" . mysqli_escape_string($dbh, '' )                              . "',
			'" . mysqli_escape_string($dbh, $omd['_billing_city'][0] )        . "',
			'" . mysqli_escape_string($dbh, $omd['_billing_state'][0] )       . "',
			'" . mysqli_escape_string($dbh, $omd['_billing_postcode'][0] )    . "',
			'" . mysqli_escape_string($dbh, $omd['_billing_country'][0] )     . "',
			'" . mysqli_escape_string($dbh, $shname )    . "',
			'" . mysqli_escape_string($dbh, $shfname ) . "',
			'" . mysqli_escape_string($dbh, $shlname )  . "',
			'" . mysqli_escape_string($dbh, $shaddress )  . "',
			'" . mysqli_escape_string($dbh, '' )                              . "',
			'" . mysqli_escape_string($dbh, $omd['_shipping_city'][0] )       . "',
			'" . mysqli_escape_string($dbh, $omd['_shipping_state'][0] )      . "',
			'" . mysqli_escape_string($dbh, $omd['_shipping_postcode'][0] )   . "',
			'" . mysqli_escape_string($dbh, $omd['_shipping_country'][0] )    . "'
		)";
	mysqli_query($dbh, $que);
	$insid = mysqli_insert_id($dbh);

	if(srchcst($omd['_billing_email'][0])){ return $insid; }

	$que1 = "INSERT INTO qw_customer	(	
			name, fname, lname, phone, email) VALUES (
			'" . mysqli_escape_string($dbh, $cname )     . "',
			'" . mysqli_escape_string($dbh, $omd['_billing_first_name'][0] )  . "',
			'" . mysqli_escape_string($dbh, $omd['_billing_last_name'][0] )   . "',
			'" . mysqli_escape_string($dbh, $phonum )       . "',
			'" . mysqli_escape_string($dbh, $omd['_billing_email'][0] )       . "'
		)";
	mysqli_query($dbh, $que1);
	return $insid; 
}


function insertOrder($sv){ global $dbh; 

	$ord = new WC_Order($sv);
	$cobj = NULL;
	$coupons  = $ord->get_coupon_codes();

	$uid = $ord->get_user_id();
	$user_meta = get_userdata($uid);
	$user_roles = $user_meta->roles;

	$omd = get_post_meta($sv);

	$qbcust_listid = chkcustomer($omd);

	$cname  = spcharhndl($omd['_billing_company'][0]);
	$shname = spcharhndl($omd['_shipping_company'][0]);
	$phonum = trtphonum( $omd['_billing_phone'][0], false );
	
	$que = "INSERT INTO	wc_order	(	
			OrderID, OrderDT, QBCustListID, CName, FName, LName, Phone, EMail, Street, Line2, City, State, Zip, Country,
			CNameAlt, FNameAlt,	LNameAlt, StreetAlt, Line2Alt, CityAlt, StateAlt, ZipAlt, CountryAlt, OrderTT, TaxPrc, Notes
		) VALUES (
			'" . mysqli_escape_string($dbh, $sv )                             . "',
			'" . mysqli_escape_string($dbh, $ord->order_date )                . "',
			'" . mysqli_escape_string($dbh, $qbcust_listid )     . "',
			'" . mysqli_escape_string($dbh, $cname )     . "',
			'" . mysqli_escape_string($dbh, $omd['_billing_first_name'][0] )  . "',
			'" . mysqli_escape_string($dbh, $omd['_billing_last_name'][0] )   . "',
			'" . mysqli_escape_string($dbh, $phonum )       . "',
			'" . mysqli_escape_string($dbh, $omd['_billing_email'][0] )       . "',
			'" . mysqli_escape_string($dbh, $omd['_billing_address_1'][0] )   . "',
			'" . mysqli_escape_string($dbh, '' )                              . "',
			'" . mysqli_escape_string($dbh, $omd['_billing_city'][0] )        . "',
			'" . mysqli_escape_string($dbh, $omd['_billing_state'][0] )       . "',
			'" . mysqli_escape_string($dbh, $omd['_billing_postcode'][0] )    . "',
			'" . mysqli_escape_string($dbh, $omd['_billing_country'][0] )     . "',
			'" . mysqli_escape_string($dbh, $shname )    . "',
			'" . mysqli_escape_string($dbh, $omd['_shipping_first_name'][0] ) . "',
			'" . mysqli_escape_string($dbh, $omd['_shipping_last_name'][0] )  . "',
			'" . mysqli_escape_string($dbh, $omd['_shipping_address_1'][0] )  . "',
			'" . mysqli_escape_string($dbh, '' )                              . "',
			'" . mysqli_escape_string($dbh, $omd['_shipping_city'][0] )       . "',
			'" . mysqli_escape_string($dbh, $omd['_shipping_state'][0] )      . "',
			'" . mysqli_escape_string($dbh, $omd['_shipping_postcode'][0] )   . "',
			'" . mysqli_escape_string($dbh, $omd['_shipping_country'][0] )    . "',
			'" . mysqli_escape_string($dbh, $omd['_order_total'][0] )         . "',
			'" . mysqli_escape_string($dbh, '' )                              . "',
			'" . mysqli_escape_string($dbh, $ord->customer_note )             . "'
		)";

	mysqli_query($dbh, $que);
	$newID = mysqli_insert_id($dbh);
	insertOrderItems($sv);
//	if($user_roles[0]=='contractor'){ insertOrderDiscount($sv, 1); }
	foreach( array_reverse($coupons) as $coupon_code ) {
		insertSubtotal($sv);
		insertCouponDiscount($coupon_code, $sv);
		}
	insertSubtotal($sv);
	insertOrderShipping($sv);
	return $newID;
}

function insertOrderManual($sv, $lid){ global $dbh; 
	$ord = new WC_Order($sv);
	$coupons  = $ord->get_coupon_codes();

	$omd = get_post_meta($sv);
	$csh = getqbcustomerTblHsh($lid);
	$shname = spcharhndl($omd['_shipping_company'][0]);

	$qbcust_listid = chkcustomer($omd);
	$que = "INSERT INTO	wc_order	(	
			OrderID, OrderDT, QBCustListID, CName, FName, LName, Phone, EMail, Street, Line2, City, State, Zip, Country,
			CNameAlt, FNameAlt,	LNameAlt, StreetAlt, Line2Alt, CityAlt, StateAlt, ZipAlt, CountryAlt, OrderTT, TaxPrc, Notes
		) VALUES (
			'" . mysqli_escape_string($dbh, $sv )                             . "',
			'" . mysqli_escape_string($dbh, $ord->order_date )                . "',
			'" . mysqli_escape_string($dbh, $lid )     . "',
			'" . mysqli_escape_string($dbh, $csh['name'] )     . "',
			'" . mysqli_escape_string($dbh, $csh['fname'] )  . "',
			'" . mysqli_escape_string($dbh, $csh['lname'] )   . "',
			'" . mysqli_escape_string($dbh, $csh['phone'] )       . "',
			'" . mysqli_escape_string($dbh, $csh['email'] )       . "',
			'" . mysqli_escape_string($dbh, $omd['_billing_address_1'][0] )   . "',
			'" . mysqli_escape_string($dbh, '' )                              . "',
			'" . mysqli_escape_string($dbh, $omd['_billing_city'][0] )        . "',
			'" . mysqli_escape_string($dbh, $omd['_billing_state'][0] )       . "',
			'" . mysqli_escape_string($dbh, $omd['_billing_postcode'][0] )    . "',
			'" . mysqli_escape_string($dbh, $omd['_billing_country'][0] )     . "',
			'" . mysqli_escape_string($dbh, $shname )    . "',
			'" . mysqli_escape_string($dbh, $omd['_shipping_first_name'][0] ) . "',
			'" . mysqli_escape_string($dbh, $omd['_shipping_last_name'][0] )  . "',
			'" . mysqli_escape_string($dbh, $omd['_shipping_address_1'][0] )  . "',
			'" . mysqli_escape_string($dbh, '' )                              . "',
			'" . mysqli_escape_string($dbh, $omd['_shipping_city'][0] )       . "',
			'" . mysqli_escape_string($dbh, $omd['_shipping_state'][0] )      . "',
			'" . mysqli_escape_string($dbh, $omd['_shipping_postcode'][0] )   . "',
			'" . mysqli_escape_string($dbh, $omd['_shipping_country'][0] )    . "',
			'" . mysqli_escape_string($dbh, $omd['_order_total'][0] )         . "',
			'" . mysqli_escape_string($dbh, '' )                              . "',
			'" . mysqli_escape_string($dbh, $ord->customer_note )             . "'
		)";
	mysqli_query($dbh, $que);
	$newID = mysqli_insert_id($dbh);

	insertOrderItems($sv);

	foreach( array_reverse($coupons) as $coupon_code ) {
		insertSubtotal($sv);
		insertCouponDiscount($coupon_code, $sv);
		}
	insertSubtotal($sv);

//	if($ord->get_discount_total()>0){ insertOrderDiscount($sv, 1); }
	insertOrderShipping($sv);
	return $newID;
}


function CreateCoupon($c, $on){ global $dbh; 
	$que = "INSERT INTO	wc_coupon( OrderID, Dsc_Type, Amount, Code
		) VALUES (
			'" . mysqli_escape_string($dbh, $on ) . "',
			'" . mysqli_escape_string($dbh, $c->discount_type ) . "',
			'" . mysqli_escape_string($dbh, $c->amount ) . "',
			'" . mysqli_escape_string($dbh, $c->code ) . "'
		)";
	mysqli_query($dbh, $que);
	return mysqli_insert_id($dbh);
}


///////////////////////////////////////////////////////////////////////////////////////////////////
function insertOrderItems($sv){ global $dbh;
	$ord = new WC_Order($sv);
	mysqli_query($dbh, "DELETE FROM wc_order_items WHERE OrderID='$sv'");
	
	foreach ( $ord->get_items() as $item_id => $item ) {
		$product_id   = $item->get_product_id();
		$variation_id = $item->get_variation_id();
		$product      = $item->get_product();
		$name         = cleaname($item->get_name());
		$quantity     = $item->get_quantity();
		$subtotal     = $item->get_subtotal();
		$total        = $item->get_total();
		$tax          = $item->get_subtotal_tax();
		$taxclass     = $item->get_tax_class();
		$taxstat      = $item->get_tax_status();
		$allmeta      = $item->get_meta_data();
		$somemeta     = $item->get_meta( '_whatever', true );
		$type         = $item->get_type();

		$que1 = "INSERT INTO wc_order_items ( OrderID, Sku, Title, Qtty, STtl ) 
			VALUES (
				'" . mysqli_escape_string($dbh, $sv ) . "',
				'" . mysqli_escape_string($dbh, $product->sku ) . "',
				'" . mysqli_escape_string($dbh, $name ) . "',
				'" . mysqli_escape_string($dbh, $quantity ) . "',
				'" . mysqli_escape_string($dbh, $subtotal ) . "'
				)";

		mysqli_query($dbh, $que1);
		}
	return NULL;
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////////////////////
function insertOrderDiscount($sv, $cst){ global $dbh;

	mysqli_query($dbh, "DELETE FROM wc_order_items WHERE OrderID='$sv' AND Sku='Freight'");

	$que1 = NULL;
	$res1 = mysqli_query($dbh, "SELECT * FROM wc_order_items WHERE OrderID='$sv' AND Sku='Subtotal'");
	if(mysqli_fetch_assoc($res1)['id']){
		$que1 = "UPDATE wc_order_items SET 
			Title  = 'Subtotal',
			Qtty   = NULL
		WHERE OrderID='$sv' AND Sku='Subtotal'";
		}
	else{
		$que1 = "INSERT INTO wc_order_items ( OrderID, Sku, Title ) 
			VALUES (
			'" . mysqli_escape_string($dbh, $sv ) . "',
			'Subtotal',
			'Subtotal'
			)";
		}
	mysqli_query($dbh, $que1);

	$que2 = NULL;
//	$dstype = ($cst)?'Discount:Contractor Discount 1'.$cst:'Discount:Contractor Discount 1';
	$res2 = mysqli_query($dbh, "SELECT * FROM wc_order_items WHERE OrderID='$sv' AND Sku='Discount:Contractor Discount 1'");
	if(mysqli_fetch_assoc($res2)['id']){
		$que2 = "UPDATE wc_order_items SET 
			Title  = 'Contractor Discount 1',
			Qtty   = NULL
		WHERE OrderID='$sv' AND Sku='Discount:Contractor Discount 1'";
		}
	else{
		$que2 = "INSERT INTO wc_order_items ( OrderID, Sku, Title ) 
			VALUES (
			'" . mysqli_escape_string($dbh, $sv ) . "',
			'Discount:Contractor Discount 1',
			'Contractor Discount 1'
			)";
		}
	mysqli_query($dbh, $que2);
	return NULL;
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function insertCouponDiscount_____($sv){ global $dbh;

	mysqli_query($dbh, "DELETE FROM wc_order_items WHERE OrderID='$sv' AND Sku='Freight'");

	$que1 = NULL;
	$res1 = mysqli_query($dbh, "SELECT * FROM wc_order_items WHERE OrderID='$sv' AND Sku='Subtotal'");
	if(mysqli_fetch_assoc($res1)['id']){
		$que1 = "UPDATE wc_order_items SET 
			Title  = 'Subtotal',
			Qtty   = NULL
		WHERE OrderID='$sv' AND Sku='Subtotal'";
		}
	else{
		$que1 = "INSERT INTO wc_order_items ( OrderID, Sku, Title ) 
			VALUES (
			'" . mysqli_escape_string($dbh, $sv ) . "',
			'Subtotal',
			'Subtotal'
			)";
		}
	mysqli_query($dbh, $que1);

$coup = 'TestCoupon';

	$que2 = NULL;
//	$dstype = ($cst)?'Discount:Contractor Discount 1'.$cst:'Discount:Contractor Discount 1';
	$res2 = mysqli_query($dbh, "SELECT * FROM wc_order_items WHERE OrderID='$sv' AND Sku='Discount:$coup'");  ////////////// Coupon
	if(mysqli_fetch_assoc($res2)['id']){
		$que2 = "UPDATE wc_order_items SET 
			Title  = $coup,
			Qtty   = NULL
		WHERE OrderID='$sv' AND Sku='Discount:$coup'";
		}
	else{
		$que2 = "INSERT INTO wc_order_items ( OrderID, Sku, Title ) 
			VALUES (
			'" . mysqli_escape_string($dbh, $sv ) . "',
			'Discount:$coup',
			$coup
			)";
		}
	mysqli_query($dbh, $que2);
	return NULL;
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////

function insertSubtotal($sv){ global $dbh;

//	$que2 = NULL;
//	$dstype = ($cst)?'Discount:Contractor Discount 1'.$cst:'Discount:Contractor Discount 1';
	$que2 = "INSERT INTO wc_order_items ( OrderID, Sku, Qtty ) 
			VALUES (
			'" . mysqli_escape_string($dbh, $sv ) . "',
			'Subtotal',
			NULL
			)";
	mysqli_query($dbh, $que2);
	return NULL;
}



function insertCouponDiscount($cc, $sv){ global $dbh;

//	$que2 = NULL;
//	$dstype = ($cst)?'Discount:Contractor Discount 1'.$cst:'Discount:Contractor Discount 1';
	$que2 = "INSERT INTO wc_order_items ( OrderID, Sku, Qtty ) 
			VALUES (
			'" . mysqli_escape_string($dbh, $sv ) . "',
			'" . mysqli_escape_string($dbh, $cc ) . "',
			NULL
			)";
	mysqli_query($dbh, $que2);
	return NULL;
}



///////////////////////////////////////////////////////////////////////////////////////////////////
function insertOrderShipping($sv){ global $dbh;
	$ord = new WC_Order($sv);
	$shh = $ord->get_shipping_total();

	$que1 = NULL;
	$res = mysqli_query($dbh, "SELECT * FROM wc_order_items WHERE OrderID='$sv' AND Sku='Freight'");
	if(mysqli_fetch_assoc($res)['id']){
		$que1 = "UPDATE wc_order_items SET 
			Title  = 'Shipping Charges', 
			Qtty   = 1,
			SPrice = '" . mysqli_escape_string($dbh, $shh ) . "',
			STtl   = '" . mysqli_escape_string($dbh, $shh ) . "'
		WHERE OrderID='$sv' AND Sku='Freight'";
		}
	else{
		$que1 = "INSERT INTO wc_order_items ( OrderID, Sku, Title, Qtty, SPrice, STtl ) 
			VALUES (
			'" . mysqli_escape_string($dbh, $sv ) . "',
			'Freight',
			'Shipping Charges', 
			1,
			'" . mysqli_escape_string($dbh, $shh ) . "',
			'" . mysqli_escape_string($dbh, $shh ) . "'
			)";
		}
	mysqli_query($dbh, $que1);
	return NULL;
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////

function insertPayment($sv){ global $dbh;
	$omd = get_post_meta($sv); // Getting array from woocommerce

switch($omd['_payment_method'][0])
	{
	case 'authnet':
		$rs = getTransactionDetails($omd['Authorize.Net Payment ID'][0]);
		if($rs->getmessages()->getresultCode() != 'Ok'){ return false; //$rs->getmessages()->getmessage()[0]->getcode();
			break;
			}
//echo "<pre>"; print_r($rs);	echo "</pre>"; break;

		$vu = (array) $rs->getTransaction()->getsubmitTimeUTC();
		$vl = (array) $rs->getTransaction()->getsubmitTimeLocal();
		$dtu = substr($vu['date'], 0, 19);
		$dtl = substr($vl['date'], 0, 19);

		$billtoname = $bladdress = $blcity = $blstate = $blzip = $phone = $email = NULL;
		$shiptoname = $shaddress = $shcity = $shstate = $shzip = NULL;

		if($rs->getTransaction()->getbillto())	{ 
			if($rs->getTransaction()->getbillto()->getemail())       { $email = $rs->getTransaction()->getbillto()->getemail();   }
			if($rs->getTransaction()->getbillto()->getphoneNumber()) { $phone = $rs->getTransaction()->getbillto()->getphoneNumber();   }
			}

		if((!$email) AND $rs->getTransaction()->getcustomer())	{ 
			if($rs->getTransaction()->getcustomer()->getemail())       { $email = $rs->getTransaction()->getcustomer()->getemail(); }
			}
		if((!$phone) AND $rs->getTransaction()->getcustomer())	{ 
			if(null !== $rs->getTransaction()->getcustomer()->getphoneNumber()) { $phone = $rs->getTransaction()->getcustomer()->getphoneNumber(); }
			}

		if($rs->getTransaction()->getbillTo()){
			$billtoname = ($rs->getTransaction()->getbillTo()->getcompany())?
			$rs->getTransaction()->getbillTo()->getcompany().' ('.$rs->getTransaction()->getbillTo()->getfirstName().' '.$rs->getTransaction()->getbillTo()->getlastName().')':
			$rs->getTransaction()->getbillTo()->getfirstName().' '.$rs->getTransaction()->getbillTo()->getlastName();

$billtoname = preg_replace('/\&/','n',$billtoname); //// Needs to work with

			$bladdress  = $rs->getTransaction()->getbillTo()->getaddress();
			$blcity     = $rs->getTransaction()->getbillTo()->getcity(); 
			$blstate    = $rs->getTransaction()->getbillTo()->getstate();
			$blzip      = $rs->getTransaction()->getbillTo()->getzip();

			}

		$qbcust_listid = chkcustomer($omd);
//		$nameres = (isset($_POST['manucustomer']) AND $_POST['manucustomer'])?$_POST['manucustomer']:NULL;

		if($rs->getTransaction()->getshipTo()){
			$shiptoname = ($rs->getTransaction()->getshipTo()->getcompany())?
			$rs->getTransaction()->getshipTo()->getcompany().' ('.$rs->getTransaction()->getshipTo()->getfirstName().' '.$rs->getTransaction()->getshipTo()->getlastName().')':
			$rs->getTransaction()->getshipTo()->getfirstName().' '.$rs->getTransaction()->getshipTo()->getlastName();
			$shaddress  = $rs->getTransaction()->getshipTo()->getaddress();
			$shcity     = $rs->getTransaction()->getshipTo()->getcity(); 
			$shstate    = $rs->getTransaction()->getshipTo()->getstate();
			$shzip      = $rs->getTransaction()->getshipTo()->getzip();
			}


		$btchid = ($rs->getTransaction()->getbatch())? $rs->getTransaction()->getbatch()->getbatchId(): NULL;
		
		$billto_Cp = preg_replace('/&/','n',$rs->getTransaction()->getbillTo()->getcompany());
		$billto_FN = preg_replace('/&/','n',$rs->getTransaction()->getbillTo()->getfirstName());
		$billto_LN = preg_replace('/&/','n',$rs->getTransaction()->getbillTo()->getlastName());

		$shipto_Cp = preg_replace('/&/','n',$rs->getTransaction()->getshipTo()->getcompany());
		$shipto_FN = preg_replace('/&/','n',$rs->getTransaction()->getshipTo()->getfirstName());
		$shipto_LN = preg_replace('/&/','n',$rs->getTransaction()->getshipTo()->getlastName());

		$que = "INSERT INTO	wc_payment	(	
			OrderID, TransactionID, BatchID, DTUTC, DTLocal, QBCustListID, 
			CName, FName, LName, Phone, EMail,	Street,	Line2, City, State,	Zip, Country,
			CNameAlt, FNameAlt,	LNameAlt, StreetAlt, Line2Alt, CityAlt, StateAlt, ZipAlt, CountryAlt,
			Notes, PayMethod, Status, AuthCode, CardCode, Description, WCOrderNum, CardType, CardNumber, IP, ResultCode, AmountPaid, AmountSettled
		) VALUES (
			'" . mysqli_escape_string($dbh, $sv ) . "',
			'" . mysqli_escape_string($dbh, $rs->getTransaction()->gettransId() ) . "',
			'" . mysqli_escape_string($dbh, $btchid ) . "',
			'" . mysqli_escape_string($dbh, $dtu ) . "',
			'" . mysqli_escape_string($dbh, $dtl ) . "',
			'" . mysqli_escape_string($dbh, $qbcust_listid ) . "',
			
			'" . mysqli_escape_string($dbh, $billto_Cp ) . "',
			'" . mysqli_escape_string($dbh, $billto_FN ) . "',
			'" . mysqli_escape_string($dbh, $billto_LN ) . "',
			'" . mysqli_escape_string($dbh, $phone ) . "',
			'" . mysqli_escape_string($dbh, $email ) . "',
			'" . mysqli_escape_string($dbh, $bladdress ) . "',
			'" . mysqli_escape_string($dbh, '' ) . "',
			'" . mysqli_escape_string($dbh, $blcity ) . "',
			'" . mysqli_escape_string($dbh, $blstate ) . "',
			'" . mysqli_escape_string($dbh, $blzip ) . "',
			'" . mysqli_escape_string($dbh, 'US' ) . "',
			'" . mysqli_escape_string($dbh, $shipto_Cp ) . "',
			'" . mysqli_escape_string($dbh, $shipto_FN ) . "',
			'" . mysqli_escape_string($dbh, $shipto_LN ) . "',
			'" . mysqli_escape_string($dbh, $shaddress ) . "',
			'" . mysqli_escape_string($dbh, '' ) . "',
			'" . mysqli_escape_string($dbh, $shcity ) . "',
			'" . mysqli_escape_string($dbh, $shstate ) . "',
			'" . mysqli_escape_string($dbh, $shzip ) . "',
			'" . mysqli_escape_string($dbh, 'US' ) . "',
			
			'" . mysqli_escape_string($dbh, '' ) . "',
			'" . mysqli_escape_string($dbh, 'Authorize.Net' ) . "',
			'" . mysqli_escape_string($dbh, $rs->getTransaction()->gettransactionStatus() ) . "',
			'" . mysqli_escape_string($dbh, $rs->getTransaction()->getauthCode() ) . "',
			'" . mysqli_escape_string($dbh, $rs->getTransaction()->getcardCodeResponse() ) . "',
			'" . mysqli_escape_string($dbh, $rs->getTransaction()->getorder()->getdescription() ) . "',
			'" . mysqli_escape_string($dbh, $rs->getTransaction()->getorder()->getinvoiceNumber() ) . "',
			'" . mysqli_escape_string($dbh, $rs->getTransaction()->getpayment()->getcreditCard()->getcardType() ) . "',
			'" . mysqli_escape_string($dbh, $rs->getTransaction()->getpayment()->getcreditCard()->getcardNumber() ) . "',
			'" . mysqli_escape_string($dbh, $rs->getTransaction()->getcustomerIP() ) . "',
			'" . mysqli_escape_string($dbh, $rs->getmessages()->getresultCode() ) . "',
			'" . mysqli_escape_string($dbh, $rs->getTransaction()->getauthAmount() ) . "',
			'" . mysqli_escape_string($dbh, $rs->getTransaction()->getsettleAmount() ) . "'
		)";
		mysqli_query($dbh, $que);
		return mysqli_insert_id($dbh);
	
	break;
	case 'paypal':
	
		$rsp = get_transaction_details($omd['_transaction_id'][0]);

		$dtl = substr($rsp['TIMESTAMP'], 0, 19);
		$cname = $rsp['FIRSTNAME'] . ' ' . $rsp['LASTNAME'];
//		$cname = preg_replace('/\'/', '', $rsp['BUSINESS']);
		$cname = preg_replace('/\'/', '', $cname);
		$sh2nm = preg_replace('/\'/', '', $rsp['SHIPTONAME']);
		$fname = preg_replace('/\'/', '', $rsp['FIRSTNAME']);
		$lname = preg_replace('/\'/', '', $rsp['LASTNAME']);

		$qbcust_listid = chkcustomer($omd);
//		$qbcust_listid = chksent($omd['_billing_email'][0]);
//		$nameres = (isset($_POST['manucustomer']) AND $_POST['manucustomer'])?$_POST['manucustomer']:NULL;
//echo '<pre>'; print_r($rsp); echo '</pre>'; break;

		$que = "INSERT INTO	wc_payment	(	
			OrderID, TransactionID, DTLocal, QBCustListID, 
			CName, FName, LName, Phone, EMail,
			CNameAlt, StreetAlt, Line2Alt, CityAlt, StateAlt, ZipAlt, CountryAlt,
			Notes, PayMethod, Status, AuthCode, WCOrderNum, ResultCode, AmountPaid, AmountSettled
		) VALUES (
			'" . mysqli_escape_string($dbh, $sv ) . "',
			'" . mysqli_escape_string($dbh, $rsp['TRANSACTIONID'] ) . "',
			'" . mysqli_escape_string($dbh, $dtl ) . "',
			'" . mysqli_escape_string($dbh, $qbcust_listid ) . "',
			
			'" . mysqli_escape_string($dbh, $cname ) . "',
			'" . mysqli_escape_string($dbh, $fname ) . "',
			'" . mysqli_escape_string($dbh, $lname ) . "',
			'" . mysqli_escape_string($dbh, $rsp['SHIPTOPHONENUM'] ) . "',
			'" . mysqli_escape_string($dbh, $rsp['EMAIL'] ) . "',
			
			'" . mysqli_escape_string($dbh, $sh2nm ) . "',
			'" . mysqli_escape_string($dbh, $rsp['SHIPTOSTREET'] ) . "',
			'" . mysqli_escape_string($dbh, $rsp['SHIPTOSTREET2'] ) . "',
			'" . mysqli_escape_string($dbh, $rsp['SHIPTOCITY'] ) . "',
			'" . mysqli_escape_string($dbh, $rsp['SHIPTOSTATE'] ) . "',
			'" . mysqli_escape_string($dbh, $rsp['SHIPTOZIP'] ) . "',
			'" . mysqli_escape_string($dbh, $rsp['SHIPTOCOUNTRYCODE'] ) . "',
			
			'" . mysqli_escape_string($dbh, '' ) . "',
			'" . mysqli_escape_string($dbh, 'PayPal' ) . "',
			'" . mysqli_escape_string($dbh, $rsp['PAYMENTSTATUS'] ) . "',
			'" . mysqli_escape_string($dbh, $rsp['CORRELATIONID'] ) . "',
			'" . mysqli_escape_string($dbh, $rsp['INVNUM'] ) . "',
			'" . mysqli_escape_string($dbh, $rsp['REASONCODE'] ) . "',
			'" . mysqli_escape_string($dbh, $rsp['AMT'] ) . "',
			'" . mysqli_escape_string($dbh, $rsp['L_AMT0'] ) . "'
		)";
		mysqli_query($dbh, $que);
		return mysqli_insert_id($dbh);

	break;
	default:

	break;
	}
return NULL;
}



function insinglePayment_au($txid){ global $dbh;
//echo $_POST['manucustomer']. '<br>';
//echo $txid;

		$rs = getTransactionDetails($txid);
		if($rs->getmessages()->getresultCode() != 'Ok'){ return NULL; }
//echo "<pre>"; print_r($rs);	echo "</pre>"; break;

		$vu = (array) $rs->getTransaction()->getsubmitTimeUTC();
		$vl = (array) $rs->getTransaction()->getsubmitTimeLocal();
		$dtu = substr($vu['date'], 0, 19);
		$dtl = substr($vl['date'], 0, 19);

		$billtoname = $bladdress = $blcity = $blstate = $blzip = $phone = $email = NULL;
		$shiptoname = $shaddress = $shcity = $shstate = $shzip = NULL;

		if($rs->getTransaction()->getbillto())	{ 
			if($rs->getTransaction()->getbillto()->getemail())       { $email = $rs->getTransaction()->getbillto()->getemail();   }
			if($rs->getTransaction()->getbillto()->getphoneNumber()) { $phone = $rs->getTransaction()->getbillto()->getphoneNumber();   }
			}

		if((!$email) AND $rs->getTransaction()->getcustomer())	{ 
			if($rs->getTransaction()->getcustomer()->getemail())       { $email = $rs->getTransaction()->getcustomer()->getemail(); }
			}
		if((!$phone) AND $rs->getTransaction()->getcustomer())	{ 
			if(function_exists($rs->getTransaction()->getcustomer()->getphoneNumber)) { $phone = $rs->getTransaction()->getcustomer()->getphoneNumber(); }
			}

		if($rs->getTransaction()->getbillTo()){
			$billtoname = ($rs->getTransaction()->getbillTo()->getcompany())?
			$rs->getTransaction()->getbillTo()->getcompany().' ('.$rs->getTransaction()->getbillTo()->getfirstName().' '.$rs->getTransaction()->getbillTo()->getlastName().')':
			$rs->getTransaction()->getbillTo()->getfirstName().' '.$rs->getTransaction()->getbillTo()->getlastName();
			$bladdress  = $rs->getTransaction()->getbillTo()->getaddress();
			$blcity     = $rs->getTransaction()->getbillTo()->getcity(); 
			$blstate    = $rs->getTransaction()->getbillTo()->getstate();
			$blzip      = $rs->getTransaction()->getbillTo()->getzip();
			}

		$qbcust_listid = (isset($_POST['manucustomer']) AND $_POST['manucustomer'])?$_POST['manucustomer']:NULL;

		if($rs->getTransaction()->getshipTo()){
			$shiptoname = ($rs->getTransaction()->getshipTo()->getcompany())?
			$rs->getTransaction()->getshipTo()->getcompany().' ('.$rs->getTransaction()->getshipTo()->getfirstName().' '.$rs->getTransaction()->getshipTo()->getlastName().')':
			$rs->getTransaction()->getshipTo()->getfirstName().' '.$rs->getTransaction()->getshipTo()->getlastName();
			$shaddress  = $rs->getTransaction()->getshipTo()->getaddress();
			$shcity     = $rs->getTransaction()->getshipTo()->getcity(); 
			$shstate    = $rs->getTransaction()->getshipTo()->getstate();
			$shzip      = $rs->getTransaction()->getshipTo()->getzip();
			}


		$btchid = ($rs->getTransaction()->getbatch())? $rs->getTransaction()->getbatch()->getbatchId(): NULL;
		$billcn = ($rs->getTransaction()->getbillTo())? $rs->getTransaction()->getbillTo()->getcompany(): NULL;
		$billfn = ($rs->getTransaction()->getbillTo())? $rs->getTransaction()->getbillTo()->getfirstName(): NULL;
		$billln = ($rs->getTransaction()->getbillTo())? $rs->getTransaction()->getbillTo()->getlastName(): NULL;

		$shipcn = ($rs->getTransaction()->getshipTo())? $rs->getTransaction()->getshipTo()->getcompany(): NULL;
		$shipfn = ($rs->getTransaction()->getshipTo())? $rs->getTransaction()->getshipTo()->getfirstName(): NULL;
		$shipln = ($rs->getTransaction()->getshipTo())? $rs->getTransaction()->getshipTo()->getlastName(): NULL;

		$ordescription = ($rs->getTransaction()->getorder())?$rs->getTransaction()->getorder()->getdescription():NULL;
		$orderinvnum = ($rs->getTransaction()->getorder())?$rs->getTransaction()->getorder()->getinvoiceNumber():NULL;
		
		$que = "INSERT INTO	wc_payment	(	
			OrderID, TransactionID, BatchID, DTUTC, DTLocal, QBCustListID, 
			CName, FName, LName, Phone, EMail,	Street,	Line2, City, State,	Zip, Country,
			CNameAlt, FNameAlt,	LNameAlt, StreetAlt, Line2Alt, CityAlt, StateAlt, ZipAlt, CountryAlt,
			Notes, PayMethod, Status, AuthCode, CardCode, Description, WCOrderNum, CardType, CardNumber, IP, ResultCode, AmountPaid, AmountSettled
		) VALUES (
			'" . mysqli_escape_string($dbh, $sv ) . "',
			'" . mysqli_escape_string($dbh, $rs->getTransaction()->gettransId() ) . "',
			'" . mysqli_escape_string($dbh, $btchid ) . "',
			'" . mysqli_escape_string($dbh, $dtu ) . "',
			'" . mysqli_escape_string($dbh, $dtl ) . "',
			'" . mysqli_escape_string($dbh, $qbcust_listid ) . "',
			
			'" . mysqli_escape_string($dbh, $billcn ) . "',
			'" . mysqli_escape_string($dbh, $billfn ) . "',
			'" . mysqli_escape_string($dbh, $billln ) . "',
			'" . mysqli_escape_string($dbh, $phone ) . "',
			'" . mysqli_escape_string($dbh, $email ) . "',
			'" . mysqli_escape_string($dbh, $bladdress ) . "',
			'" . mysqli_escape_string($dbh, '' ) . "',
			'" . mysqli_escape_string($dbh, $blcity ) . "',
			'" . mysqli_escape_string($dbh, $blstate ) . "',
			'" . mysqli_escape_string($dbh, $blzip ) . "',
			'" . mysqli_escape_string($dbh, 'US' ) . "',
			'" . mysqli_escape_string($dbh, $shipcn ) . "',
			'" . mysqli_escape_string($dbh, $shipfn ) . "',
			'" . mysqli_escape_string($dbh, $shipln ) . "',
			'" . mysqli_escape_string($dbh, $shaddress ) . "',
			'" . mysqli_escape_string($dbh, '' ) . "',
			'" . mysqli_escape_string($dbh, $shcity ) . "',
			'" . mysqli_escape_string($dbh, $shstate ) . "',
			'" . mysqli_escape_string($dbh, $shzip ) . "',
			'" . mysqli_escape_string($dbh, 'US' ) . "',
			
			'" . mysqli_escape_string($dbh, '' ) . "',
			'" . mysqli_escape_string($dbh, 'Authorize.Net' ) . "',
			'" . mysqli_escape_string($dbh, $rs->getTransaction()->gettransactionStatus() ) . "',
			'" . mysqli_escape_string($dbh, $rs->getTransaction()->getauthCode() ) . "',
			'" . mysqli_escape_string($dbh, $rs->getTransaction()->getcardCodeResponse() ) . "',
			'" . mysqli_escape_string($dbh, $ordescription ) . "',
			'" . mysqli_escape_string($dbh, $orderinvnum ) . "',
			'" . mysqli_escape_string($dbh, $rs->getTransaction()->getpayment()->getcreditCard()->getcardType() ) . "',
			'" . mysqli_escape_string($dbh, $rs->getTransaction()->getpayment()->getcreditCard()->getcardNumber() ) . "',
			'" . mysqli_escape_string($dbh, $rs->getTransaction()->getcustomerIP() ) . "',
			'" . mysqli_escape_string($dbh, $rs->getmessages()->getresultCode() ) . "',
			'" . mysqli_escape_string($dbh, $rs->getTransaction()->getauthAmount() ) . "',
			'" . mysqli_escape_string($dbh, $rs->getTransaction()->getsettleAmount() ) . "'
		)";
		mysqli_query($dbh, $que);
		return mysqli_insert_id($dbh);
}

function insinglePayment_pp($txid){ global $dbh;

		$rsp = get_transaction_details($txid);

		$dtl = substr($rsp['TIMESTAMP'], 0, 19);
		$cname = $rsp['FIRSTNAME'] . ' ' . $rsp['LASTNAME'];
//		$cname = preg_replace('/\'/', '', $rsp['BUSINESS']);
		$cname = preg_replace('/\'/', '', $cname);
		$sh2nm = preg_replace('/\'/', '', $rsp['SHIPTONAME']);
		$fname = preg_replace('/\'/', '', $rsp['FIRSTNAME']);
		$lname = preg_replace('/\'/', '', $rsp['LASTNAME']);

		$qbcust_listid = (isset($_POST['manucustomer']) AND $_POST['manucustomer'])?$_POST['manucustomer']:NULL;
//		$nameres = (isset($_POST['manucustomer']) AND $_POST['manucustomer'])?$_POST['manucustomer']:NULL;
//echo '<pre>'; print_r($rsp); echo '</pre>'; break;

		$que = "INSERT INTO	wc_payment	(	
			OrderID, TransactionID, DTLocal, QBCustListID, 
			CName, FName, LName, Phone, EMail,
			CNameAlt, StreetAlt, Line2Alt, CityAlt, StateAlt, ZipAlt, CountryAlt,
			Notes, PayMethod, Status, AuthCode, WCOrderNum, ResultCode, AmountPaid, AmountSettled
		) VALUES (
			'" . mysqli_escape_string($dbh, $sv ) . "',
			'" . mysqli_escape_string($dbh, $rsp['TRANSACTIONID'] ) . "',
			'" . mysqli_escape_string($dbh, $dtl ) . "',
			'" . mysqli_escape_string($dbh, $qbcust_listid ) . "',
			
			'" . mysqli_escape_string($dbh, $cname ) . "',
			'" . mysqli_escape_string($dbh, $fname ) . "',
			'" . mysqli_escape_string($dbh, $lname ) . "',
			'" . mysqli_escape_string($dbh, $rsp['SHIPTOPHONENUM'] ) . "',
			'" . mysqli_escape_string($dbh, $rsp['EMAIL'] ) . "',
			
			'" . mysqli_escape_string($dbh, $sh2nm ) . "',
			'" . mysqli_escape_string($dbh, $rsp['SHIPTOSTREET'] ) . "',
			'" . mysqli_escape_string($dbh, $rsp['SHIPTOSTREET2'] ) . "',
			'" . mysqli_escape_string($dbh, $rsp['SHIPTOCITY'] ) . "',
			'" . mysqli_escape_string($dbh, $rsp['SHIPTOSTATE'] ) . "',
			'" . mysqli_escape_string($dbh, $rsp['SHIPTOZIP'] ) . "',
			'" . mysqli_escape_string($dbh, $rsp['SHIPTOCOUNTRYCODE'] ) . "',
			
			'" . mysqli_escape_string($dbh, '' ) . "',
			'" . mysqli_escape_string($dbh, 'PayPal' ) . "',
			'" . mysqli_escape_string($dbh, $rsp['PAYMENTSTATUS'] ) . "',
			'" . mysqli_escape_string($dbh, $rsp['CORRELATIONID'] ) . "',
			'" . mysqli_escape_string($dbh, $rsp['INVNUM'] ) . "',
			'" . mysqli_escape_string($dbh, $rsp['REASONCODE'] ) . "',
			'" . mysqli_escape_string($dbh, $rsp['AMT'] ) . "',
			'" . mysqli_escape_string($dbh, $rsp['L_AMT0'] ) . "'
		)";
		mysqli_query($dbh, $que);
		return mysqli_insert_id($dbh);
}






function check_orderitems($oid){

			// Get and Loop Over Order Items
			foreach ( $ord->get_items() as $item_id => $item ) {

				$product = $item->get_product();

		$record = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM qw_item WHERE Sku = " . $product->sku, MYSQLI_ASSOC));
		if($record['id']){
			
		mysqli_query($dbh, "UPDATE qw_item SET 
			Differ   = 1
		WHERE id = '$record[id]' AND (SPrice != '$product->price')");

			
			
		}



		echo $product->sku. ' <br>';
		}
	return ;	
	}



function chk_seop($on, $iid){ global $dbh; // Function to check if Estimate/Order/Payment is sent to QB
	$rv = mysqli_query($dbh, "SELECT * FROM qw_xt WHERE oname='$on' AND (IID='$iid' OR IID='W-$iid')", MYSQLI_ASSOC);
	if($rv){ 
		$record = mysqli_fetch_array($rv);
		return $record['QBListID'];
		}
	return NULL;
}

function cleaname($n){
	$name = preg_replace('/\″/', '', $n);
	return  preg_replace('/\&/', 'and', $name);
	}

function chkordcls($on){ global $dbh;
	$rv = mysqli_query($dbh, "SELECT Status FROM tw_close WHERE OrNum='$on'", MYSQLI_ASSOC);
//	$rec = ($rv) ? mysqli_fetch_array($rv) : NULL;
	return mysqli_fetch_array($rv)[0];
//	return $rec;
}

function updordcls($on, $st){ global $dbh;
	$rec = mysqli_fetch_array(mysqli_query($dbh, "SELECT id FROM tw_close WHERE OrNum='$on'", MYSQLI_ASSOC));
	if($rec){ mysqli_query($dbh, "UPDATE tw_close SET Status = '$st' WHERE OrNum='$on'"); }
	else 	{ mysqli_query($dbh, "INSERT INTO tw_close (OrNum, Status) VALUES('$on', '$st')"); }
	return;
}

function clean4qb______($n){
	$rn = str_replace("\'", "'", $n);
	$rn = str_replace("\"", "''", $rn);
	$rn = str_replace("&", "and", $rn);
	$rn = str_replace("/\W/", " ", $rn);
	return $rn;
}

function srchcst($em){ global $dbh;
	return mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM qw_customer WHERE email = '$em'", MYSQLI_ASSOC))[0];
}












?>