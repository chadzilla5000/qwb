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

      $args = array(

               'post_type' => 'product',
'meta_query' => [
            [
                'key'     => '_sku',
                'value'   => 'SAMPLE-',
                'compare' => 'LIKE'
            ]
        ],			   
              'posts_per_page' => 3000

               );

       $i=0;

       $loop = new WP_Query( $args );

 
$d = 0;
       if ( $loop->have_posts() ) {

           while ( $loop->have_posts() ) : $loop->the_post();

 $sku = preg_replace('/^SAMPLE-/', 'S-', $loop->post->_sku);

echo ++$d . '. ' .$sku . '<br>';

 //              update_post_meta($loop->post->ID,'_sku',$sku);

 

               $i++;

           endwhile;

       } else {

           echo __( 'No products found' );

       }

//       wp_reset_postdata();

exit;






$user = 'qbvipuser';
$pass = 'eMa7--CFv4';
echo QuickBooks_Utilities::createUser($dsn, $user, $pass);
	QuickBooks_Utilities::initialize($dsn);



if (!QuickBooks_Utilities::initialized($dsn))
{
	// Initialize creates the neccessary database schema for queueing up requests and logging
	QuickBooks_Utilities::initialize($dsn);
	
	// This creates a username and password which is used by the Web Connector to authenticate
	QuickBooks_Utilities::createUser($dsn, $user, $pass);
	
	// Queueing up a test request
	// 
	// You can instantiate and use the QuickBooks_Queue class to queue up 
	//	actions whenever you want to queue something up to be sent to 
	//	QuickBooks. So, for instance, a new customer is created in your 
	//	database, and you want to add them to QuickBooks: 
	//	
	//	Queue up a request to add a new customer to QuickBooks
	//	$Queue = new QuickBooks_Queue($dsn);
	//	$Queue->enqueue(QUICKBOOKS_ADD_CUSTOMER, $primary_key_of_new_customer);
	//	
	// Oh, and that new customer placed an order, so we want to create an 
	//	invoice for them in QuickBooks too: 
	// 
	//	Queue up a request to add a new invoice to QuickBooks
	//	$Queue->enqueue(QUICKBOOKS_ADD_INVOICE, $primary_key_of_new_order);
	// 
	// Remember that for each action type you queue up, you should have a 
	//	request and a response function registered by using the $map parameter 
	//	to the QuickBooks_Server class. The request function will accept a list 
	//	of parameters (one of them is $ID, which will be passed the value of 
	//	$primary_key_of_new_customer/order that you passed to the ->enqueue() 
	//	method and return a qbXML request. So, your request handler for adding 
	//	customers might do something like this: 
	// 
	//	$arr = mysql_fetch_array(mysql_query("SELECT * FROM my_customer_table WHERE ID = " . (int) $ID));
	//	// build the qbXML CustomerAddRq here
	//	return $qbxml;
	// 
	// We're going to queue up a request to add a customer, just as a test...
	// 
	// NOTE: You would normally *never* want to do this in this file! This is 
	//	meant as an initial test ONLY. See example_web_connector_queueing.php for more 
	//	details!
	// 
	// IMPORTANT NOTE: This particular example of queueing something up will 
	//	only ever happen *once* when these scripts are first run/used. After 
	//	this initial test, you MUST do your queueing in another script. DO NOT 
	//	DO YOUR OWN QUEUEING IN THIS FILE! See 
	//	docs/example_web_connector_queueing.php for more details and examples 
	//	of queueing things up.
	
	// $primary_key_of_your_customer = 5;

	// $Queue = new QuickBooks_WebConnector_Queue($dsn);
	// $Queue->enqueue(QUICKBOOKS_ADD_CUSTOMER, $primary_key_of_your_customer);
	
	// Also note the that ->enqueue() method supports some other parameters: 
	// 	string $action				The type of action to queue up
	//	mixed $ident = null			Pass in the unique primary key of your record here, so you can pull the data from your application to build a qbXML request in your request handler
	//	$priority = 0				You can assign priorities to requests, higher priorities get run first
	//	$extra = null				Any extra data you want to pass to the request/response handler
	//	$user = null				If you're using multiple usernames, you can pass the username of the user to queue this up for here
	//	$qbxml = null				
	//	$replace = true				
	// 
	// Of particular importance and use is the $priority parameter. Say a new 
	//	customer is created and places an order on your website. You'll want to 
	//	send both the customer *and* the sales receipt to QuickBooks, but you 
	//	need to ensure that the customer is created *before* the sales receipt, 
	//	right? So, you'll queue up both requests, but you'll assign the 
	//	customer a higher priority to ensure that the customer is added before 
	//	the sales receipt. 
	// 
	//	Queue up the customer with a priority of 10
	// 	$Queue->enqueue(QUICKBOOKS_ADD_CUSTOMER, $primary_key_of_your_customer, 10);
	//	
	//	Queue up the invoice with a priority of 0, to make sure it doesn't run until after the customer is created
	//	$Queue->enqueue(QUICKBOOKS_ADD_SALESRECEIPT, $primary_key_of_your_order, 0);
}









//echo QuickBooks_Utilities::initialize($dsn);
//echo '<br>';
//echo QuickBooks_Utilities::createUser($dsn, 'qbadmin', 'w81HHy04');
//QuickBooks_Utilities::initialize($dsn);


exit;









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

/////////////////////////////////////////////////////////////////////////////////////////////////////////












  require '../aunet/autoload.php';
  require_once '../aunet/constants/SampleCodeConstants.php';
  use net\authorize\api\contract\v1 as AnetAPI;
  use net\authorize\api\controller as AnetController;
  
  define("AUTHORIZENET_LOG_FILE", "phplog");

function getTransactionList()
{
    /* Create a merchantAuthenticationType object with authentication details
       retrieved from the constants file */
    $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
    $merchantAuthentication->setName(\SampleCodeConstants::MERCHANT_LOGIN_ID);
    $merchantAuthentication->setTransactionKey(\SampleCodeConstants::MERCHANT_TRANSACTION_KEY);
    
    // Set the request's refId
    $refId = 'ref' . time();


$req0 = new AnetAPI\GetSettledBatchListRequest();

    //Setting a valid batch Id for the Merchant
    $batchId = "789707669";
    $request = new AnetAPI\GetTransactionListRequest();
    $request->setMerchantAuthentication($merchantAuthentication);
    $request->setBatchId($batchId);


//    $contr0 = new AnetController\GetSettledBatchListController($req0);

    $controller = new AnetController\GetTransactionListController($request);

    //Retrieving transaction list for the given Batch Id
//    $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
    $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);

    if (($response != null) && ($response->getMessages()->getResultCode() == "Ok"))
    {
    		echo "SUCCESS: Get Transaction List for BatchID : " . $batchId  . "\n\n";
			
			
  	  if ($response->getTransactions() == null) {
  	  	echo "No Transaction to display in this Batch.";
  	  	return ;
  	  }
  	  //Displaying the details of each transaction in the list
  	  foreach ($response->getTransactions() as $transaction) {
  	  	echo "		->Transaction Id	: " . $transaction->getTransId() . "\n"; 
  	  	echo "		Submitted on (Local)	: " . date_format($transaction->getSubmitTimeLocal(), 'Y-m-d H:i:s') . "\n";
  	  	echo "		Status			: " . $transaction->getTransactionStatus() . "\n";
  	  	echo "		Settle amount		: " . number_format($transaction->getSettleAmount(), 2, '.', '') . "\n";
  	  }
     }
    else
    {
        echo "ERROR :  Invalid response\n";
        $errorMessages = $response->getMessages()->getMessage();
        echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
    }

    return $response;
  }

  if(!defined('DONT_RUN_SAMPLES'))
    getTransactionList();


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////












exit;

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