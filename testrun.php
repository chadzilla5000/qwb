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


//$x_base = simplexml_load_file("data/sinchro/purchordRR.xml");





//	$file1 = 'data/sinchro/porder_base.xml';



	$f1 = 'data/sinchro/porder_base.xml';
	$f2 = 'data/sinchro/porder_inc.xml';
	$fout = 'data/sinchro/porder_base_add.xml';	
	$x1 = simplexml_load_file( $f1 );
	$x2 = simplexml_load_file( $f2 );

	$r1 = $r2 = NULL;


//$i = 0;
//xmlrecursive($x1->QBXMLMsgsRs->PurchaseOrderQueryRs->PurchaseOrderRet, $r1);
//xmlrecursive($x2->QBXMLMsgsRs->PurchaseOrderQueryRs->PurchaseOrderRet, $r2);


$df1 = $x1->asXML();
$df2 = $x2->asXML();
$ci = 0;
foreach($x2->QBXMLMsgsRs->PurchaseOrderQueryRs->PurchaseOrderRet as $d){
	$fgx = 1;
	foreach($x1->QBXMLMsgsRs->PurchaseOrderQueryRs->PurchaseOrderRet as $c){
		if($d->TxnID==$c->TxnID){ $fgx = 0; }
		}
	if($fgx){ echo ++$ci.'. '. $d->TxnID . '<br>'; }
	}



//echo $df1;

//$xo1 = simplexml_load_string($df);
//$xo2 = simplexml_load_string($r2);

	// foreach($xo1 as $k=>$v){
		// echo $k . '<br>';
		// }


//$r1 = preg_replace('/[\s]{2,}/',' ',$r1);
//$r2 = preg_replace('/[\s]{2,}/',' ',$r2);

//echo $r1;
// echo '<br><br><br>';
// echo $r2;


//echo $xml1->asXML();

	// $xml2 = simplexml_load_file( $file2 );	// loop through the FOO and add them and their attributes to xml1
	// foreach( $xml2->QBXMLMsgsRs->PurchaseOrderQueryRs->PurchaseOrderRet as $foo ) {
		// $new = $xml1->addChild( 'FOO' , $foo );
		// foreach( $foo->attributes() as $key => $value ) {
			// $new->addAttribute( $key, $value );
		// }
	// }	
	
	$fh = fopen( $fout, 'w') or die ( "can't open file $fileout" );
//	fwrite( $fh, $r1 );
	fclose( $fh );





function xmlrecursive($o, &$n){
	foreach($o as $k=>$v){
		$n .= '<'.$k.'>'.$v;
		xmlrecursive($v, $n);
		$n .= '</'.$k.'>';
		}
	}




//$xx = xml_to_array($x_base);

// $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/qbw/data/sinchro/porder_base.xml","w") or die("Unable to open file!");
// fwrite($fp, $x_base);
// fclose($fp);

// foreach($x_base->QBXMLMsgsRs->PurchaseOrderQueryRs->PurchaseOrderRet as $k=>$d){
	// $r = poscan($d);
	// }



//echo '<pre>';print_r($x_base);echo '</pre>'; exit;





function poscan($o){
	$i = $ii = 0;
	$nr = array();
	if($o){
		foreach ( $o->PurchaseOrderLineRet as $ip ) {
			$nr[$i]['sku'] = $ip->ItemRef->FullName;
			$nr[$i]['qty'] = $ip->Quantity;
			$nr[$i]['ttl'] = $ip->Desc;
			$nr[$i]['ppr'] = floatval($ip->Rate);
			$nr[$i]['amt'] = floatval($ip->Amount);
			$i++;
			}
		}
	return $nr;
}





function xml_to_array($sxi){
    $a = array();
    for( $sxi->rewind(); $sxi->valid(); $sxi->next() ) {
        if(!array_key_exists($sxi->key(), $a)){
            $a[$sxi->key()] = array();
        }
        if($sxi->hasChildren()){
            $a[$sxi->key()] = $this->xml_to_array($sxi->current());
        }
        else{
            $a[$sxi->key()] = strval($sxi->current());
        }
    }
    return $a;
}

function array_to_xml( $data, &$xml_data ) {
    foreach( $data as $key => $value ) {
        if( is_numeric($key) ){
            $key = 'item'.$key; //dealing with <0/>..<n/> issues
        }
        if( is_array($value) ) {
            $subnode = $xml_data->addChild($key);
            array_to_xml($value, $subnode);
        } else {
            $xml_data->addChild("$key",htmlspecialchars("$value"));
        }
    }
}














exit;





/////////////*  ///////////////////// 

$csvarr = NULL;
$FName = 'data/priclst/USCDfd.csv';

			if(($handle = fopen($FName, 'r')) !== FALSE) {
				// necessary if a large csv file
				set_time_limit(0);
				$row = 0;
				while(($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
					$col_count = count($data);
					for($col=0; $col<$col_count; $col++){
						$csvarr[$row][$col]=$data[$col];
						}
					$row++;
					}
				fclose($handle);
				}


$larr = array('SW','SA','SD','SG','SC','CW','CS','TD','TW','YW');


//echo '<pre>'; print_r($csvarr); echo '</pre>';

$pi = 0;
$lst = 0;
$lnd = 4000;


echo '<table>';
foreach($csvarr as $csvrow){


	if(is_numeric($csvrow[1])){
		
foreach($larr as $ln){
	

	if($pi<$lst){++$pi; continue;}
	if($pi>=$lnd){break;}

	
	$price = 0;
	$sku = NULL;
	if(preg_match('/(.*)(\*)(.*)/',$csvrow[0], $m)){$sku='U-'.$ln.'-'.$m[1]; //echo $m[3]; 
		if($m[3]==' Shaker & Torrance ONLY'	 AND ($ln=='CW' OR $ln=='CS' OR $ln=='YW'))	{ continue; }
		if($m[3]==' Excludes Casselberry'	 AND ($ln=='CW' OR $ln=='CS'))	{ continue; }
		if($m[3]==' Casselberry & York ONLY' AND ($ln=='SW' OR $ln=='SA' OR $ln=='SD' OR $ln=='SG' OR $ln=='SC' OR $ln=='TD' OR $ln=='TW'))	{ continue; }
		}
	else { $sku = 'U-'.$ln.'-'.$csvrow[0]; }
	
	if($ln=='SW'){ $price = intval(preg_replace('/[\$\s\,]*/','',$csvrow[2])); }
	if($ln=='SA' OR $ln=='SD' OR $ln=='SG'){ $price = intval(preg_replace('/[\$\s\,]*/','',$csvrow[3])); }
	if($ln=='SC' OR $ln=='CW' OR $ln=='CS' OR $ln=='TD' OR $ln=='TW' OR $ln=='YW'){ $price = intval(preg_replace('/[\$\s\,]*/','',$csvrow[4])); }
	
//	$wpr = cmpwwc($sku);
//	$wsk=($wpr)?$wpr->get_sku():'not found';
	
//	echo ++$pi.'. '.$sku.' - '.$price.'<br>';	
//	($wpr)?$wpr->get_sku():NULL;
//	echo ++$pi .'. '. $sku.' - '.$price.':::'.$wsk.'<br>';	
//	echo $sku.' - '.$price.':::'.$wpr.'<br>';	

//$rpr = ($wpr)?$wpr->get_regular_price():'--';
//$clr = ($wpr)?(($rpr==$price)?'000':'c90'):'f00';
echo '<tr>';	
echo '
	<td>'.++$pi.'.</td>
	<td>'.$sku.'</td>
	<td>'.$price.'</td>
';
echo '</tr>';

}
$vi = 0;
//$pi++;


	foreach($csvrow as $csvcol){
		$ccell = NULL;
		$clr = '000';
		if(preg_match('/^\$/',$csvcol)){

		$ccell = intval(preg_replace('/[\$\s\,]*/','',$csvcol));
//		$ccell = intval($csvcol);
//		$ccell = intval(preg_replace('/\,/','',$ccell));
//		$ccell = intval(preg_replace('/^\$/','',$csvcol));
		$clr = (is_numeric($ccell))?'090':'f00';
		
		}
else{		$ccell = $csvcol;
}		
//		echo '<td style="color: #'.$clr.';">'.$ccell .'</td>';
//		$vi++;
	}
	}
}
echo '</table>';


exit;

////////////////////////// */




/*   ////////  Code to deactivate/activate MSI products 
$i = 0;
$ids = get_posts( array(
        'post_type' => 'product',
        'numberposts' => -1,
        'post_status' => 'publish',
        'fields' => 'ids',
		'tax_query' => array(
			array(
				'taxonomy' => 'product_tag',
				'field' => 'slug',
				'terms' => 'msi',
				'operator' => 'IN',
				)
			),

		
   ) );
   foreach ( $ids as $id ) {
//	   $prd = wc_get_product( $id );
//       echo ++$i.'. ',$id.' - '.$prd->get_sku().' - '.$prd->get_name().'<br>';
//       echo ++$i.'. ',$id.'<br>';
	   
//if($i<500)	{   wp_update_post(array( 'ID' => $id, 'post_status' => 'draft' )); }
//if($i<500)	{   wp_update_post(array( 'ID' => $id, 'post_status' => 'publish' )); }
   }
exit;

///////////////////// */



$Queue = new QuickBooks_WebConnector_Queue($dsn, $qbwc_user); 



$trows = '';



if (!file_exists("data/sinchro/noninventoryitems.xml")) { die("XML file not found"); }

$xml = simplexml_load_file("data/sinchro/noninventoryitems.xml");
$tm = 0;

foreach($xml->QBXMLMsgsRs->ItemNonInventoryQueryRs->ItemNonInventoryRet as $d){ //$tm++;




if(!$d->SalesAndPurchase->ExpenseAccountRef OR ($d->SalesAndPurchase->ExpenseAccountRef->FullName == 'Cost of Goods Sold' OR !($d->SalesAndPurchase->SalesPrice>0))) {continue;}
else {$tm++;


$newid = 0; //insIt2mod($d);
if($newid){ $Queue->enqueue(QUICKBOOKS_MOD_NONINVENTORYITEM, $newid); }


}

	$trows .= '
<tr><td>' . $tm.' . </td>
	<td>' . $d->ListID . '</td>
	<td>' . $d->Name . '</td>
	<td>' . $d->SalesAndPurchase->SalesDesc . '</td>
	<td>' . $d->SalesAndPurchase->PrefVendorRef->FullName . '</td>
	<td>' . $d->SalesAndPurchase->PurchaseCost . '</td>
	<td>' . $d->SalesAndPurchase->SalesPrice . '</td>
	<td nowrap>' . $d->SalesAndPurchase->IncomeAccountRef->FullName . '</td>
	<td nowrap>' . $d->SalesAndPurchase->ExpenseAccountRef->FullName . '</td>
	<td></td>
	<td></td>
</tr>';
	}



$pcnt = '
<form method="post" action="listqbxml.php" name="DForm">
<div style="margin-top: 25px;">
<div style="float: left;">
&nbsp; &nbsp;<input type="button" name="xmlreq" value="Request XML" style="padding: 1px 5px;" OnClick="return requestqbinventoryxml();" /> &nbsp; &nbsp;
</div>
<div style="float: right;">
&nbsp; &nbsp;<input type="Submit" name="updb" value="Update DB" style="padding: 1px 5px;" /> &nbsp; &nbsp;
</div>
<div style="clear: both;"></div>
</div>
<table class="TBL" width="100%" cellspacing="1">
<tr><th></th>
	<th style="font-size: 13px;" colspan="4">QB Product</th>
	<th style="font-size: 13px;" colspan="2">Price</th>
	<th style="font-size: 13px;" colspan="2">Account Ref.</th>
	<th style="font-size: 13px;" colspan="2">Date / Time</th>
</tr>
<tr><th style="width: 50px;"></th>
	<th style="width: 150px;">List ID</th>
	<th style="width: 250px;">Sku</th>
	<th style="width: 700px;">Title</th>
	<th style="width: 150px;">Vendor</th>
	<th style="width: 70px;">Cost</th>
	<th style="width: 70px;">Sale</th>
	<th style="width: 130px;">Income</th>
	<th style="width: 130px;">Expence</th>
	<th style="width: 120px;">DT created</th>
	<th style="width: 120px;">DT modified</th>
</tr>
'.$trows.'
</table>
</form>
<script>

function requestqbinventoryxml(){
var url = "qbinventoryquery.php";
var prt = perform(url); 
	if(prt){alert(prt);}
//	else{window.location.reload();}
	
return false;
}

</script>

';

$head_ext = '';
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



function insIt2mod($d){ global $dbh;
	//echo date('Y-m-d H:m:s', time()); return NULL;
	$que = "INSERT INTO wc_item ( QBListID, EdSeq, Sku, Title, VCost, SPrice, IncAccRef, ExpAccRef ) 
		VALUES (
			'" . mysqli_escape_string($dbh, $d->ListID ) . "',
			'" . mysqli_escape_string($dbh, $d->EditSequence ) . "',
			'" . mysqli_escape_string($dbh, $d->Name ) . "',
			'" . mysqli_escape_string($dbh, $d->SalesAndPurchase->SalesDesc ) . "',
			'" . mysqli_escape_string($dbh, $d->SalesAndPurchase->PurchaseCost ) . "',
			'" . mysqli_escape_string($dbh, $d->SalesAndPurchase->SalesPrice ) . "',
			'" . mysqli_escape_string($dbh, 'Merchandise Sales' ) . "',
			'" . mysqli_escape_string($dbh, 'Cost of Goods Sold' ) . "'
			)";
	mysqli_query($dbh, $que);
	return mysqli_insert_id($dbh);
}

function cmpwwc($s){
	global $wpdb;
//	return 'Gesheft';
	
    $product_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $s ) );
    if ( $product_id ) return new WC_Product( $product_id );
    return null;

	$prod = new WC_Product($s); 
	if($prod){return $prod->get_price_html();}
	return NULL;
	}




?>