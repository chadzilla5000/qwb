<?php

require '../paypal/autoload.php';
use Sample\PayPalClient;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;

$crd = array(
'user' => 'john_api1.waverlycabinets.com',
'pass' => 'R7X75AAXPCQEGG2H',
'sign' => 'A56.d8EqHJcvb7w09.digzydcyG.A1Hg7x3g1r-nEP9yRyqZ1i2KcRZX'
);
// $crd['user'] = 'john_api1.waverlycabinets.com';
// $crd['pass'] = 'R7X75AAXPCQEGG2H';
// $crd['sign'] = 'A56.d8EqHJcvb7w09.digzydcyG.A1Hg7x3g1r-nEP9yRyqZ1i2KcRZX';

function get_pplist(){ global $crd;  // Get list of paypal transactions
error_reporting(E_ERROR | E_WARNING | E_PARSE);

$info =   'USER='       . urlencode( $crd['user'] )
		. '&PWD='       . urlencode( $crd['pass'] )
		. '&SIGNATURE=' . urlencode( $crd['sign'] )
		. '&VERSION=76.0'
		. '&METHOD=TransactionSearch'
		. '&TRANSACTIONCLASS=RECEIVED'
		. '&STARTDATE=2015-01-08T05:38:48Z'
//		. '&ENDDATE=2020-07-14T05:38:48Z'
		. '&VERSION=94';
			
$curl = curl_init('https://api-3t.paypal.com/nvp');
curl_setopt($curl, CURLOPT_FAILONERROR, true);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

curl_setopt($curl, CURLOPT_POSTFIELDS,  $info);
curl_setopt($curl, CURLOPT_HEADER, 0);
curl_setopt($curl, CURLOPT_POST, 1);

$result = curl_exec($curl);
$temp = array();


$result = explode("&", $result);
foreach($result as $value){
    $value = explode("=", $value);
    $temp[$value[0]] = $value[1];
}

for($i=0; $i<count($temp)/11; $i++){
    $f_array[$i] = array(
        "timestamp"         =>    urldecode($temp["L_TIMESTAMP".$i]),
        "timezone"          =>    urldecode($temp["L_TIMEZONE".$i]),
        "type"              =>    urldecode($temp["L_TYPE".$i]),
        "email"             =>    urldecode($temp["L_EMAIL".$i]),
        "name"              =>    urldecode($temp["L_NAME".$i]),
        "transaction_id"    =>    urldecode($temp["L_TRANSACTIONID".$i]),
        "status"            =>    urldecode($temp["L_STATUS".$i]),
        "amt"               =>    urldecode($temp["L_AMT".$i]),
        "currency_code"     =>    urldecode($temp["L_CURRENCYCODE".$i]),
        "fee_amount"        =>    urldecode($temp["L_FEEAMT".$i]),
        "net_amount"        =>    urldecode($temp["L_NETAMT".$i]));
}

//echo '<pre>'; print_r ($f_array); echo '</pre>';
return $f_array;
}


function get_transaction_details( $transaction_id ) { global $crd;  // Get particular transaction details using transaction ID
    $api_request = 'USER='           . urlencode( $crd['user'] )
                .  '&PWD='           . urlencode( $crd['pass'] )
                .  '&SIGNATURE='     . urlencode( $crd['sign'] )
                .  '&VERSION=76.0'
                .  '&METHOD=GetTransactionDetails'
                .  '&TransactionID=' . $transaction_id;

    $ch = curl_init();
//    curl_setopt( $ch, CURLOPT_URL, 'https://api-3t.sandbox.paypal.com/nvp' ); // For live transactions, change to 'https://api-3t.paypal.com/nvp'
    curl_setopt( $ch, CURLOPT_URL, 'https://api-3t.paypal.com/nvp' );
    curl_setopt( $ch, CURLOPT_VERBOSE, 1 );

    // Uncomment these to turn off server and peer verification
    // curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
    // curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt( $ch, CURLOPT_POST, 1 );

    // Set the API parameters for this transaction
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $api_request );

    // Request response from PayPal
    $response = curl_exec( $ch );
    // print_r($response);

    // If no response was received from PayPal there is no point parsing the response
    if( ! $response )
        die( 'Calling PayPal to change_subscription_status failed: ' . curl_error( $ch ) . '(' . curl_errno( $ch ) . ')' );

    curl_close( $ch );

    // An associative array is more usable than a parameter string
    parse_str( $response, $parsed_response );

    return $parsed_response;
}










function getPPOrder($orderId)
  {

    // 3. Call PayPal to get the transaction details
    $client = PayPalClient::client();
    $response = $client->execute(new OrdersGetRequest($orderId));
    /**
     *Enable the following line to print complete response as JSON.
     */
    //print json_encode($response->result);
    print "Status Code: {$response->statusCode}\n";
    print "Status: {$response->result->status}\n";
    print "Order ID: {$response->result->id}\n";
    print "Intent: {$response->result->intent}\n";
    print "Links:\n";
    foreach($response->result->links as $link)
    {
      print "\t{$link->rel}: {$link->href}\tCall Type: {$link->method}\n";
    }
    // 4. Save the transaction in your database. Implement logic to save transaction to your database for future reference.
    print "Gross Amount: {$response->result->purchase_units[0]->amount->currency_code} {$response->result->purchase_units[0]->amount->value}\n";

    // To print the whole response body, uncomment the following line
    // echo json_encode($response->result, JSON_PRETTY_PRINT);
  }





/**
 *This driver function invokes the getOrder function to retrieve
 *sample order details.
 *
 *To get the correct order ID, this sample uses createOrder to create an order
 *and then uses the newly-created order ID with GetOrder.
 */
// if (!count(debug_backtrace()))
// {
  // GetOrder::getOrder('315943', true);
// }

?>