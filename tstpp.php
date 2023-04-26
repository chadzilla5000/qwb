<?php namespace Sample;

require_once('inc/_init.php');
require_once('inc/functions/general.php');
require_once('inc/functions/paypal.php');
error_reporting(E_ALL | E_STRICT);

include_once 'inc/functions.php';
setlocale(LC_MONETARY, 'en_US');

$pcnt = $msg = NULL;
require_once 'wconfig.php';
/////////////////////////////////


get_pplist();
exit;
//require 'vendor/autoload.php';

// $apiContext = new \PayPal\Rest\ApiContext(
  // new \PayPal\Auth\OAuthTokenCredential(
    // 'john_api1.waverlycabinets.com',
    // 'R7X75AAXPCQEGG2H'
  // )
// );



$paypalClientID = urlencode( 'john_api1.waverlycabinets.com' );
$paypalSecret   = urlencode( 'R7X75AAXPCQEGG2H' );









//	require __DIR__  . '/PayPal-PHP-SDK/autoload.php';
//	require __DIR__  . '/PayPal-PHP-SDK/resultprinter.php';
// # GetPaymentSample
// This sample code demonstrate how you can
// retrieve a list of all Payment resources
// you've created using the Payments API.
// Note various query parameters that you can
// use to filter, and paginate through the
// payments list.
// API used: GET /v1/payments/payments
	
/** @var Payment $createdPayment */
use PayPal\Api\Payment;
// ### Retrieve payment
// Retrieve the payment object by calling the
// static `get` method
// on the Payment class by passing a valid
// Payment ID
// (See bootstrap.php for more on `ApiContext`)
	

$apiContext = new \PayPal\Rest\ApiContext(
        new \PayPal\Auth\OAuthTokenCredential(
            $paypalClientID,     // ClientID
            $paypalSecret    // ClientSecret
        )
);

$apiContext->setConfig( array( 'mode' => 'live', 'log.LogEnabled' => true, 'log.FileName' => 'PayPal.log', 'log.LogLevel' => 'DEBUG', 'cache.enabled' => true, ) );
	
try {
    $params = array('count' => 1000, 'start_index' => 0);

    $payments = Payment::all($params, $apiContext);
} catch (Exception $ex) {

//    ResultPrinterSmiley Frustratedmiley TonguerintResult("List Payments", "Payment", null, $params, $ex);
    ResultPrinter::printError("List Payments", "Payment", null, $params, $ex);
    exit(1);
}

//ResultPrinterSmiley Frustratedmiley TonguerintResult("List Payments", "Payment", null, $params, $payments);
ResultPrinter::printResult("List Payments", "Payment", null, $params, $payments);


exit(1);



/*

//require __DIR__ . '/CreatePaymentUsingPayPal.php';
use PayPal\Api\Payment;





$apiContext = new \PayPal\Rest\ApiContext(
  new \PayPal\Auth\OAuthTokenCredential(
    'john_api1.waverlycabinets.com',
    'R7X75AAXPCQEGG2H'
  )
  

);

          $apiContext->setConfig([
            'mode' => 'production',
        ]);


// $apiContext = new ApiContext(new OAuthTokenCredential($paypalClientID, $paypalSecret));
        // $apiContext->setConfig([
            // 'mode' => 'production',
        // ]);



    // $api_request = 'USER='           . urlencode( 'john_api1.waverlycabinets.com' )
                // .  '&PWD='           . urlencode( 'R7X75AAXPCQEGG2H' )
                // .  '&SIGNATURE='     . urlencode( 'A56.d8EqHJcvb7w09.digzydcyG.A1Hg7x3g1r-nEP9yRyqZ1i2KcRZX' )
                // .  '&VERSION=76.0'
                // .  '&METHOD=GetTransactionDetails'
                // .  '&TransactionID=' . $transaction_id;


// ### Retrieve payment
// Retrieve the PaymentHistory object by calling the
// static `get` method on the Payment class, 
// and pass a Map object that contains
// query parameters for paginations and filtering.
// Refer the method doc for valid values for keys
// (See bootstrap.php for more on `ApiContext`)
try {
    $params = array('count' => 10, 'start_index' => 5);

    $payments = Payment::all($params, $apiContext);
//    $payments = Payment::all($params, $api_request);
} catch (Exception $ex) {
    // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
    ResultPrinter::printError("List Payments", "Payment", null, $params, $ex);
    exit(1);
}

// NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
 ResultPrinter::printResult("List Payments", "Payment", null, $params, $payments);


*/


?>

