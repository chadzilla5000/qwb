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

$pcnt  = $msg = NULL;
$trows = '';
$t = 0;

require_once 'wconfig.php';
/////////////////////////////////
require '../aunet/autoload.php';
require_once '../aunet/constants/SampleCodeConstants.php';
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
  
define("AUTHORIZENET_LOG_FILE", "phplog");






//$cdt = date("Y-m-d");
//$dto = (isset($_GET['dateto'])) ? $_GET['dateto'] : date('Y-m-d', strtotime($cdt. ' + 1 days'));
$dto = (isset($_GET['dateto'])) ? $_GET['dateto'] : date('Y-m-d');
$dfr = (isset($_GET['datefr'])) ? $_GET['datefr'] : date('Y-m-d', strtotime($dto. ' - 31 days'));
$dto1 = date('Y-m-d', strtotime($dto. ' + 1 days'));
$dfr1 = date('Y-m-d', strtotime($dfr. ' + 1 days'));
$d = explode('-',$dto1);

// both the first and last dates must be in the same time zone
// a date constructed from an ISO8601 format date string
$firstSettlementDate = new DateTime($dfr1."T00:00:00Z");
// a date constructed manually
$lastSettlementDate  = new DateTime();
$lastSettlementDate->setDate($d[0],$d[1],$d[2]);
$lastSettlementDate->setTime(23,59,59);
//$lastSettlementDate->setTimezone(new DateTimeZone('UTC'));


      
if(!defined('DONT_RUN_SAMPLES')){
	if(isset($_GET['trid'])){
		$tid = $_GET['trid'];
		$rs = getTransactionDetails($tid);
		echo "<pre>";
		print_r($rs);
		echo "</pre>";
		}
	else{
		$res0 = getSettledBatchList($firstSettlementDate, $lastSettlementDate);
		foreach($res0->getBatchList() as $bt){
			$btid = $bt->getBatchId();
			$res1 = getTransactionList($btid);
	//		$res2 = getCustomerPaymentProfile();

			foreach ($res1->getTransactions() as $trxs) {
				$tid = $trxs->getTransId();
		echo "<pre>";
				
			print_r($trxs);	
		echo "</pre>";
		echo '<br><br><br><br><br><br><br><br>';		
				$trows .= '
	<tr><td>'.++$t.'. </td><td style="width: 50px;"><a href="trnx.php?trid='.$tid.'" DOnClick="return load_console(this.href, \'80%\', \'80%\');">'.$tid.'</a></td>
		<td>'.$btid.'</td>
		<td>'.date_format($trxs->getSubmitTimeLocal(), 'Y-m-d H:i:s').'</td>
		<td>'.$trxs->getTransactionStatus().'</td>
		<td>'.$trxs->getfirstName().' '.$trxs->getlastName().'</td>
		<td>'.number_format($trxs->getSettleAmount(), 2, '.', '').'</td>
	</tr>';
				}
			}
		}
	}

	$pcnt = '
<form action="#" method="get" name="audform">
List dated From - 
<input type="date" name="datefr" value="'.$dfr.'" />&nbsp; &nbsp;To - 
<input type="date" name="dateto" value="'.$dto.'" />&nbsp; &nbsp;
<input type="submit" name="submitdt" value="List Transactions" style="padding: 1px 5px;" OnChange="dateformsubmit();" />
</form>

<form action="#" method="post" name="aupform">
<table class="TBL" width="100%" cellspacing="1">
<tr><th></th>
	<th style="width: 130px;">Transaction ID</th>
	<th style="width: 130px;">Batch ID</th>
	<th style="width: 130px;">Date/Time</th>
	<th style="width: 130px;">Status</th>
	<th style="width: 130px;">Name</th>
	<th style="width: 130px;">Amount</th>
</tr>
'.$trows.'
<tr><th></th>
	<th>Transaction ID</th>
	<th>Batch ID</th>
	<th>Date/Time</th>
	<th>Status</th>
	<th style="width: 130px;">Name</th>
	<th>Amount</th>
</tr>
</table>

</form>';


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


function getSettledBatchList($firstSettlementDate, $lastSettlementDate)
{
    /* Create a merchantAuthenticationType object with authentication details
       retrieved from the constants file */
    $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
    $merchantAuthentication->setName(\SampleCodeConstants::MERCHANT_LOGIN_ID);
    $merchantAuthentication->setTransactionKey(\SampleCodeConstants::MERCHANT_TRANSACTION_KEY);
    
    // Set the transaction's refId
    $refId = 'ref' . time();

    $request = new AnetAPI\GetSettledBatchListRequest();
    $request->setMerchantAuthentication($merchantAuthentication);
    $request->setIncludeStatistics(true);
    
    // Both the first and last dates must be in the same time zone
    // The time between first and last dates, inclusively, cannot exceed 31 days.
    $request->setFirstSettlementDate($firstSettlementDate);
    $request->setLastSettlementDate($lastSettlementDate);

    $controller = new AnetController\GetSettledBatchListController ($request);

//    $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
    $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);

    if (($response != null) && ($response->getMessages()->getResultCode() == "Ok"))
    {
		
	/*	
        foreach($response->getBatchList() as $batch)
        {
  		echo "\n\n";
          echo "Batch ID: " . $batch->getBatchId() . "\n";
  		echo "Batch settled on (UTC): " . $batch->getSettlementTimeUTC()->format('r') . "\n";
  		echo "Batch settled on (Local): " . $batch->getSettlementTimeLocal()->format('D, d M Y H:i:s') . "\n";
  		echo "Batch settlement state: " . $batch->getSettlementState() . "\n";
  		echo "Batch market type: " . $batch->getMarketType() . "\n";
  		echo "Batch product: " . $batch->getProduct() . "\n";
		echo '<br>';
  		foreach($batch->getStatistics() as $statistics)
  		{
  			echo "Account type: ".$statistics->getAccountType()."\n";
  			echo "Total charge amount: ".$statistics->getChargeAmount()."\n";
  			echo "Charge count: ".$statistics->getChargeCount()."\n";
  			echo "Refund amount: ".$statistics->getRefundAmount()."\n";
  			echo "Refund count: ".$statistics->getRefundCount()."\n";
  			echo "Void count: ".$statistics->getVoidCount()."\n";
  			echo "Decline count: ".$statistics->getDeclineCount()."\n";
  			echo "Error amount: ".$statistics->getErrorCount()."\n";
		echo '<br><br>';
  		}
        }
		echo '<br><br>';
		echo '<br><br>';
		echo '<br><br>';
		*/
    }
    else
    {
        echo "ERROR :  Invalid response\n";
        $errorMessages = $response->getMessages()->getMessage();
        echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
		return NULL;
    }

    return $response;
  }


///////////////////////////////////////////////////////////////////////////////
function getTransactionList($batchId)
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
//    $batchId = "789707669";
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

/*
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
	  
	  */
     }
    else
    {
        echo "ERROR :  Invalid response\n";
        $errorMessages = $response->getMessages()->getMessage();
        echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
		return NULL;
    }

    return $response;
  }



function getCustomerPaymentProfile($customerProfileId="36731856", 
    $customerPaymentProfileId= "33211899"
) {
    /* Create a merchantAuthenticationType object with authentication details
       retrieved from the constants file */
    $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
    $merchantAuthentication->setName(\SampleCodeConstants::MERCHANT_LOGIN_ID);
    $merchantAuthentication->setTransactionKey(\SampleCodeConstants::MERCHANT_TRANSACTION_KEY);
    
    // Set the transaction's refId
    $refId = 'ref' . time();

	//request requires customerProfileId and customerPaymentProfileId
	$request = new AnetAPI\GetCustomerPaymentProfileRequest();
	$request->setMerchantAuthentication($merchantAuthentication);
	$request->setRefId( $refId);
	$request->setCustomerProfileId($customerProfileId);
	$request->setCustomerPaymentProfileId($customerPaymentProfileId);

	$controller = new AnetController\GetCustomerPaymentProfileController($request);
//	$response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
    $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);
	if(($response != null)){
		if ($response->getMessages()->getResultCode() == "Ok")
		{
			echo "GetCustomerPaymentProfile SUCCESS: " . "\n";
			echo "Customer Payment Profile Id: " . $response->getPaymentProfile()->getCustomerPaymentProfileId() . "\n";
			echo "Customer Payment Profile Billing Address: " . $response->getPaymentProfile()->getbillTo()->getAddress(). "\n";
			echo "Customer Payment Profile Card Last 4 " . $response->getPaymentProfile()->getPayment()->getCreditCard()->getCardNumber(). "\n";

			if($response->getPaymentProfile()->getSubscriptionIds() != null) 
			{
				if($response->getPaymentProfile()->getSubscriptionIds() != null)
				{

					echo "List of subscriptions:";
					foreach($response->getPaymentProfile()->getSubscriptionIds() as $subscriptionid)
						echo $subscriptionid . "\n";
				}
			}
		}
		else
		{
			echo "GetCustomerPaymentProfile ERROR :  Invalid response\n";
			$errorMessages = $response->getMessages()->getMessage();
		    echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
		}
	}
	else{
		echo "NULL Response Error";
	}
	return $response;
}


function getTransactionDetails($transactionId)
{
    /* Create a merchantAuthenticationType object with authentication details
       retrieved from the constants file */
    $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
    $merchantAuthentication->setName(\SampleCodeConstants::MERCHANT_LOGIN_ID);
    $merchantAuthentication->setTransactionKey(\SampleCodeConstants::MERCHANT_TRANSACTION_KEY);
    
    // Set the transaction's refId
    // The refId is a Merchant-assigned reference ID for the request.
    // If included in the request, this value is included in the response. 
    // This feature might be especially useful for multi-threaded applications.
    $refId = 'ref' . time();

    $request = new AnetAPI\GetTransactionDetailsRequest();
    $request->setMerchantAuthentication($merchantAuthentication);
    $request->setTransId($transactionId);

    $controller = new AnetController\GetTransactionDetailsController($request);

//    $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
    $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);

    if (($response != null) && ($response->getMessages()->getResultCode() == "Ok"))
    {
        // echo "SUCCESS: Transaction Status:" . $response->getTransaction()->getTransactionStatus() . "\n";
        // echo "                Auth Amount:" . $response->getTransaction()->getAuthAmount() . "\n";
        // echo "                   Trans ID:" . $response->getTransaction()->getTransId() . "\n";
     }
    else
    {
        echo "ERROR :  Invalid response\n";
        $errorMessages = $response->getMessages()->getMessage();
        echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
    }

    return $response;
  }

//  if(!defined('DONT_RUN_SAMPLES'))
//    getTransactionDetails("2238968786");
?>

