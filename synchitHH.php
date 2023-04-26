<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

if (function_exists('date_default_timezone_set')){ date_default_timezone_set('America/New_York'); }

require_once 'wconfig.php';
//require_once 'inc/qbfunctions.php';

$user = $qbwc_user;
$pass = $qbwc_pass;

$map = array(
	QUICKBOOKS_QUERY_CUSTOMER         => array( '_quickbooks_customer_query_request', '_quickbooks_customer_query_response' ),
	);

// This is entirely optional, use it to trigger actions when an error is returned by QuickBooks
$errmap = array();
    $hooks = array();
    $log_level = QUICKBOOKS_LOG_DEVELOP;
	$soapserver = QUICKBOOKS_SOAPSERVER_BUILTIN;		// A pure-PHP SOAP server (no PHP ext/soap extension required, also makes debugging easier)
    $soap_options = array();
    $handler_options = array(
        'authenticate' => '_quickbooks_custom_auth', 
        'deny_concurrent_logins' => false, 
    );

$soap_options = array(		// See http://www.php.net/soap
	);

$driver_options = array(		// See the comments in the QuickBooks/Driver/<YOUR DRIVER HERE>.php file ( i.e. 'Mysql.php', etc. )
	//'max_log_history' => 1024,	// Limit the number of quickbooks_log entries to 1024
	//'max_queue_history' => 64, 	// Limit the number of *successfully processed* quickbooks_queue entries to 64
	);

$callback_options = array(
	);

// Create a new server and tell it to handle the requests
// __construct($dsn_or_conn, $map, $errmap = array(), $hooks = array(), $log_level = QUICKBOOKS_LOG_NORMAL, $soap = QUICKBOOKS_SOAPSERVER_PHP, $wsdl = QUICKBOOKS_WSDL, $soap_options = array(), $handler_options = array(), $driver_options = array(), $callback_options = array()
$Server = new QuickBooks_WebConnector_Server($dsn, $map, $errmap, $hooks, $log_level, $soapserver, QUICKBOOKS_WSDL, $soap_options, $handler_options, $driver_options, $callback_options);
$response = $Server->handle(true, true);


 function _quickbooks_custom_auth($user, $pass, &$QuickBooksCompanyFile){
        return true;
    }

/*
        $errmap = array(
                    3070 =>  '_quickbooks_error_stringtoolong',
                    3140 => '_quickbooks_reference_error',
                    '*' => '_quickbooks_error_handler',
                );

        $hooks = array();
        $log_level = QUICKBOOKS_LOG_DEVELOP;
        $soapserver = QUICKBOOKS_SOAPSERVER_BUILTIN;        
        $soap_options = array();
        $handler_options = array(
                    'deny_concurrent_logins' => false, 
                    'deny_reallyfast_logins' => false, 
                );      
        $soap_options = array();
        $driver_options = array();
        $callback_options = array();


       QuickBooks_WebConnector_Queue_Singleton::initialize($dsn);  

       if (!QuickBooks_Utilities::initialized($dsn)){
        //    Initialize creates the neccessary database schema for queueing up requests and logging
           QuickBooks_Utilities::initialize($dsn);

         //   This creates a username and password which is used by the Web Connector to authenticate
           QuickBooks_Utilities::createUser($dsn, $user, $pass);   
       }

*/
        function _quickbooks_customer_query_request($requestID , $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale){
		
			global $dbh;
			$res = mysqli_query($dbh, "SELECT * FROM qw_customer WHERE id='$ID' AND quickbooks_listid='N_A'");
			$row = mysqli_fetch_assoc($res);

//			$fullname = ($row['name'])? $row['name'] : $row['fname'].' '.$row['lname'];
			$fullname = $row['lname'].', '.$row['fname'];
//$email = $row['email'];
			mysqli_query($dbh, "UPDATE qw_customer SET quickbooks_listid='NA' WHERE id='$row[id]'");
//			mysqli_close($dbh);

	$xml = ($fullname)?'<?xml version="1.0" encoding="utf-8"?>
		<?qbxml version="' . $version . '"?>
		<QBXML>
			<QBXMLMsgsRq onError="continueOnError">
				<CustomerQueryRq requestID="' . $requestID . '">
					<FullName>'.$fullname.'</FullName>
					<IncludeRetElement>ListID</IncludeRetElement>
					<IncludeRetElement>Name</IncludeRetElement>
					<IncludeRetElement>Email</IncludeRetElement>
					<IncludeRetElement>Phone</IncludeRetElement>
				</CustomerQueryRq>	
			</QBXMLMsgsRq>
		</QBXML>':'';
		
	return $xml;
//echo $xml; 					<FullName>'.$fullname.'</FullName>

			}

        function _quickbooks_customer_query_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents){ 
          
			global $dbh;
			
//			if($idents['statusMessage']=='Status OK'){
				mysqli_query($dbh, "UPDATE qw_customer SET quickbooks_listid = '$idents[ListID]' 
				WHERE id=(SELECT ident FROM quickbooks_queue WHERE quickbooks_queue_id='$idents[requestID]')");
//				}

//mysqli_close($dbh);

/*
$content = NULL;
foreach($idents as $ik=>$iv){
	$content .= $ik . ' - ' . $iv . ' ; ';
	
}

		  $content .= $xml;
            $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/qbw/storef/qbdp/wc.txt","wb");
            fwrite($fp,$content);
            fclose($fp);

*/

/*
$content = NULL;
foreach($idents as $ik=>$iv){
	$content .= $ik . ' - ' . $iv . ' ; ';
	
}

		  $content .= $xml;
            $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/qbw/storef/qbdp/wc.txt","wb");
            fwrite($fp,$content);
            fclose($fp);
        
*/
			}
			
        function _quickbooks_error_handler($requestID, $user, $action, $ID, $extra, &$err, $xml, $errnum, $errmsg){
            $content = "##########################################################";
            $content .= $idents;
            $content .= "##########################################################";
            $content .= $xml;
            $content .= "##########################################################";
            $content .= $errmsg;
            // $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/store/qbsdkm/docs/myText.txt","a");
            // fwrite($fp,$content);
            // fclose($fp);
        }

        function _quickbooks_reference_error($requestID, $user, $action, $ID, $extra, &$err, $xml, $errnum, $errmsg){
            $content = "##########################################################";
            $content .= $idents;
            $content .= "##########################################################";
            $content .= $xml;
            $content .= "##########################################################";
            $content .= $errmsg;
            // $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/store/qbsdkm/docs/myText.txt","a");
            // fwrite($fp,$content);
            // fclose($fp);
        }

	$Queue = QuickBooks_WebConnector_Queue_Singleton::getInstance();

 //   $Queue->enqueue(QUICKBOOKS_ADD_CUSTOMER, mt_rand());
 // $Queue->enqueue(QUICKBOOKS_QUERY_CUSTOMER, mt_rand());
 //   $Queue->enqueue(QUICKBOOKS_ADD_SALESORDER, mt_rand());
 
 ?>