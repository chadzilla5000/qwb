<?php 

require '../aunet/autoload.php';
require_once '../aunet/constants/SampleCodeConstants.php';
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
  
define("AUTHORIZENET_LOG_FILE", "phplog");




function getqbcustomers___________($cn){ global $dbh;
	$opts = NULL;
	$query = mysqli_query($dbh, "SELECT * FROM qw_customer GROUP BY lname, name ORDER BY name, lname ASC");
	while($row = mysqli_fetch_assoc($query)){
		$name=($row['name'])?$row['name']:$row['lname'].', '.$row['fname'];
		$tnm =($row['name'])?$row['name']:$row['fname'].' '.$row['lname'];
		$sel =($tnm==$cn)?' selected':'';
		$opts .= '<option value="'.$tnm.'"'.$sel.'>'.$name.'</option>';
		}
	return $opts;
	}




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

function getUnsettledTransactionList()
{
    /* Create a merchantAuthenticationType object with authentication details
       retrieved from the constants file */
    $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
    $merchantAuthentication->setName(\SampleCodeConstants::MERCHANT_LOGIN_ID);
    $merchantAuthentication->setTransactionKey(\SampleCodeConstants::MERCHANT_TRANSACTION_KEY);
    
    // Set the transaction's refId
    $refId = 'ref' . time();


    $request = new AnetAPI\GetUnsettledTransactionListRequest();
    $request->setMerchantAuthentication($merchantAuthentication);


    $controller = new AnetController\GetUnsettledTransactionListController($request);

//    $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
    $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);

    if (($response != null) && ($response->getMessages()->getResultCode() == "Ok"))
    {
    }
    else
    {
        echo "ERROR :  Invalid response\n";
        $errorMessages = $response->getMessages()->getMessage();
        echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
    }

    return $response;
  }

?>