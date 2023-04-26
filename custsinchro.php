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
$errmap = array(
	3070 => '_quickbooks_error_stringtoolong',				// Whenever a string is too long to fit in a field, call this function: _quickbooks_error_stringtolong()
	);

// An array of callback hooks
$hooks = array(
	// There are many hooks defined which allow you to run your own functions/methods when certain events happen within the framework
	// QuickBooks_WebConnector_Handlers::HOOK_LOGINSUCCESS => '_quickbooks_hook_loginsuccess', 	// Run this function whenever a successful login occurs
	);

//$log_level = QUICKBOOKS_LOG_NORMAL;
//$log_level = QUICKBOOKS_LOG_VERBOSE;
$log_level = QUICKBOOKS_LOG_DEBUG;				
//$log_level = QUICKBOOKS_LOG_DEVELOP;		// Use this level until you're sure everything works!!!

$soapserver = QUICKBOOKS_SOAPSERVER_BUILTIN;		// A pure-PHP SOAP server (no PHP ext/soap extension required, also makes debugging easier)

$soap_options = array(		// See http://www.php.net/soap
	);

$handler_options = array(
	'deny_concurrent_logins' => false, 
	'deny_reallyfast_logins' => false, 
	);		// See the comments in the QuickBooks/Server/Handlers.php file

$driver_options = array(		// See the comments in the QuickBooks/Driver/<YOUR DRIVER HERE>.php file ( i.e. 'Mysql.php', etc. )
	//'max_log_history' => 1024,	// Limit the number of quickbooks_log entries to 1024
	//'max_queue_history' => 64, 	// Limit the number of *successfully processed* quickbooks_queue entries to 64
	);

$callback_options = array(
	);

if (!QuickBooks_Utilities::initialized($dsn))
{
	// Initialize creates the neccessary database schema for queueing up requests and logging
	QuickBooks_Utilities::initialize($dsn);
	
	// This creates a username and password which is used by the Web Connector to authenticate
	QuickBooks_Utilities::createUser($dsn, $user, $pass);
	
	$primary_key_of_your_customer = 5;

	$Queue = new QuickBooks_WebConnector_Queue($dsn, $qbwc_user);
	$Queue->enqueue(QUICKBOOKS_ADD_CUSTOMER, $primary_key_of_your_customer);
}

// Create a new server and tell it to handle the requests
// __construct($dsn_or_conn, $map, $errmap = array(), $hooks = array(), $log_level = QUICKBOOKS_LOG_NORMAL, $soap = QUICKBOOKS_SOAPSERVER_PHP, $wsdl = QUICKBOOKS_WSDL, $soap_options = array(), $handler_options = array(), $driver_options = array(), $callback_options = array()
$Server = new QuickBooks_WebConnector_Server($dsn, $map, $errmap, $hooks, $log_level, $soapserver, QUICKBOOKS_WSDL, $soap_options, $handler_options, $driver_options, $callback_options);
$response = $Server->handle(true, true);

/*
echo _quickbooks_customer_query_request('','',);

function _quickbooks_customer_query_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{	global $dbh;
$fnamelist = '';

$data = mysqli_query($con,"SELECT * FROM qw_customer");
while($build = mysqli_fetch_assoc($data))
{ 

$fnamelist .= '<FullName>'.$build['fname'].' '.$build['lname'].'</FullName>
';
    // echo $build[idex]."<br>";
	
}
	
	$xml = '<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="'.$version.'"?>
<QBXML>
    <QBXMLMsgsRq onError = "continueOnError">
       <CustomerQueryRq requestID = "'.$requestID.'">
'.$fnamelist.'
       </CustomerQueryRq>
    </QBXMLMsgsRq>
</QBXML>';

	return $xml;
}


function _quickbooks_customer_query_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{	global $dbh;
	mysqli_query($dbh, "
		UPDATE 
			qw_customer 
		SET 
			quickbooks_listid = '" . mysqli_escape_string($dbh, $idents['ListID']) . "', 
			quickbooks_editsequence = '" . mysqli_escape_string($dbh, $idents['EditSequence']) . "'
		WHERE 
			id = " . (int) $ID);
}

*/



        // Map QuickBooks actions to handler functions
 //       $map = array(
  //          QUICKBOOKS_QUERY_CUSTOMER => array( '_quickbooks_customer_query_request', '_quickbooks_customer_query_response', '_quickbooks_error_handler' ),
 //           QUICKBOOKS_ADD_CUSTOMER => array( '_quickbooks_customer_add_request', '_quickbooks_customer_add_response' ),
 //           QUICKBOOKS_ADD_INVOICE => array( '_quickbooks_invoice_add_request', '_quickbooks_invoice_add_response' ),
 //           QUICKBOOKS_ADD_SALESORDER => array( '_quickbooks_salesorder_add_request', '_quickbooks_salesorder_add_response' ),
  //          );  


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


//        $dsn = 'mysql://wholecab_user3:Sehar123#@localhost/wholecab_clickdb3';

        QuickBooks_WebConnector_Queue_Singleton::initialize($dsn);  

        if (!QuickBooks_Utilities::initialized($dsn)){
            // Initialize creates the neccessary database schema for queueing up requests and logging
            QuickBooks_Utilities::initialize($dsn);

            // This creates a username and password which is used by the Web Connector to authenticate
            QuickBooks_Utilities::createUser($dsn, $user, $pass);   
        }

        // Create a new server and tell it to handle the requests
        // __construct($dsn_or_conn, $map, $errmap = array(), $hooks = array(), $log_level = QUICKBOOKS_LOG_NORMAL, $soap = QUICKBOOKS_SOAPSERVER_PHP, $wsdl = QUICKBOOKS_WSDL, $soap_options = array(), $handler_options = array(), $driver_options = array(), $callback_options = array()
 

// $Server = new QuickBooks_WebConnector_Server($dsn, $map, $errmap, $hooks, $log_level, $soapserver, QUICKBOOKS_WSDL, $soap_options, $handler_options, $driver_options, $callback_options);
 //       $response = $Server->handle(true, true);

        function _quickbooks_salesorder_add_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale){
            // We're just testing, so we'll just use a static test request:
            $xml = '<?xml version="1.0" encoding="utf-8"?>
                <?qbxml version="5.0"?>
                <QBXML>
                    <QBXMLMsgsRq onError="stopOnError">
                        <SalesOrderAddRq requestID="'.$requestID.'">
                            <SalesOrderAdd>
                                <CustomerRef>
                                    <FullName>Muralidhar, LLC (1249573828)</FullName>
                                </CustomerRef>
                                <TxnDate>2014-04-25</TxnDate>
                                <RefNumber>23112628110</RefNumber>
                                <BillAddress>
                                    <Addr1>Pam  Barker</Addr1>
                                    <Addr2>500 Kirts Boulevard</Addr2>
                                    <Addr3/>
                                    <City>Troy</City>
                                    <State>Mi</State>
                                    <PostalCode>48084</PostalCode>
                                    <Country>US</Country>
                                </BillAddress>
                                <ShipAddress>
                                    <Addr1/>
                                    <Addr2>7322 Southwest Freeway</Addr2>
                                    <Addr3>Ste, 170</Addr3>
                                    <City>Houston</City>
                                    <State>TX</State>
                                    <PostalCode>77074</PostalCode>
                                    <Country>US</Country>
                                </ShipAddress>                      
                                <Memo>Shipping to Pinnacle Senior Care Houston</Memo>
                                <SalesOrderLineAdd>
                                    <ItemRef>
                                        <FullName>ARCTIC WHITE SHAKER:AWS-1530MD</FullName>
                                    </ItemRef>
                                    <Desc>MULLION DOOR FOR W1530 - ARCTIC WHITE SHAKER</Desc>
                                    <Quantity>1</Quantity>
                                    <Amount>59.25</Amount>
                                </SalesOrderLineAdd>
                                <SalesOrderLineAdd>
                                    <ItemRef>
                                        <FullName>ARCTIC WHITE SHAKER:AWS-1536MD</FullName>
                                    </ItemRef>
                                    <Desc>MULLION DOOR FOR W1536 - ARCTIC WHITE SHAKER</Desc>
                                    <Quantity>1</Quantity>
                                    <Amount>59.25</Amount>
                                </SalesOrderLineAdd>                        
                            </SalesOrderAdd>
                        </SalesOrderAddRq>
                    </QBXMLMsgsRq>
                </QBXML>';

            return $xml;
        }

        function _quickbooks_salesorder_add_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents){
            $content = "##########################################################";
            $content .= $idents;
            $content .= "##########################################################";
            $content .= $xml;
            $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/store/qbsdkm/docs/myText.txt","wb");
            fwrite($fp,$content);
            fclose($fp);
            return; 
        }

        function _quickbooks_invoice_add_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale){
            // We're just testing, so we'll just use a static test request:
            $xml = '<?xml version="1.0" encoding="utf-8"?>
                <?qbxml version="5.0"?>
                <QBXML>
                  <QBXMLMsgsRq onError="stopOnError">
                    <InvoiceAddRq requestID="'.$requestID.'">
                      <InvoiceAdd>
                        <CustomerRef>
                          <FullName>Muralidhar, LLC (1249573828)</FullName>
                        </CustomerRef>
                        <TxnDate>2014-04-23</TxnDate>
                        <RefNumber>9668</RefNumber>
                        <BillAddress>
                          <Addr1>56 Cowles Road</Addr1>
                          <City>Willington</City>
                          <State>CT</State>
                          <PostalCode>06279</PostalCode>
                          <Country>United States</Country>
                        </BillAddress>
                        <PONumber></PONumber>
                        <Memo></Memo>

                        <InvoiceLineAdd>
                          <ItemRef>
                            <FullName>Test Item</FullName>
                          </ItemRef>
                          <Desc>Item 1 Description Goes Here</Desc>
                          <Quantity>1</Quantity>
                          <Rate>295</Rate>
                        </InvoiceLineAdd>

                        <InvoiceLineAdd>
                          <ItemRef>
                            <FullName>Test Item</FullName>
                          </ItemRef>
                          <Desc>Item 2 Description Goes Here</Desc>
                          <Quantity>3</Quantity>
                          <Rate>25</Rate>
                        </InvoiceLineAdd>

                      </InvoiceAdd>
                    </InvoiceAddRq>
                  </QBXMLMsgsRq>
                </QBXML>';

            return $xml;
        }

        function _quickbooks_invoice_add_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents){
            $content = "##########################################################";
            $content .= $idents;
            $content .= "##########################################################";
            $content .= $xml;
            $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/store/qbsdkm/docs/myText.txt","wb");
            fwrite($fp,$content);
            fclose($fp);
            return; 
        }

        function _quickbooks_customer_add_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale){
            // We're just testing, so we'll just use a static test request:  
            $xml = '<?xml version="1.0" encoding="utf-8"?>
                <?qbxml version="5.0"?>
                <QBXML>
                    <QBXMLMsgsRq onError="stopOnError">
                        <CustomerAddRq requestID="' . $requestID . '">
                            <CustomerAdd>
                                <Name>Muralidhar, LLC (' . mt_rand() . ')</Name>
                                <CompanyName>Muralidhar, LLC</CompanyName>
                                <FirstName>Murali</FirstName>
                                <LastName>Developer</LastName>
                                <BillAddress>
                                    <Addr1>Muralidhar, LLC</Addr1>
                                    <Addr2>134 Stonemill Road</Addr2>
                                    <City>NewYork</City>
                                    <State>NY</State>
                                    <PostalCode>10001</PostalCode>
                                    <Country>United States</Country>
                                </BillAddress>
                                <Phone>860-634-1602</Phone>
                                <AltPhone>860-429-0021</AltPhone>
                                <Fax>860-429-5183</Fax>
                                <Email>Murali@Muralidhar.com</Email>
                                <Contact>Murali Developer</Contact>
                            </CustomerAdd>
                        </CustomerAddRq>
                    </QBXMLMsgsRq>
                </QBXML>';

            return $xml;
        }

        /**
         * Receive a response from QuickBooks 
         */
        function _quickbooks_customer_add_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents){
            $content = $xml;
            $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/store/qbsdkm/docs/myText.txt","wb");
            fwrite($fp,$content);
            fclose($fp);
            return; 
        }

        function _quickbooks_customer_query_request($requestID , $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale){
            /*$xml = '<?xml version="1.0" encoding="utf-8"?>
                <?qbxml version="5.0"?>
                <QBXML>
                  <QBXMLMsgsRq onError="continueOnError">
                    <CustomerQueryRq>
                      <MaxReturned>5</MaxReturned>
                      <FromModifiedDate>1984-01-29T22:03:19</FromModifiedDate>
                      <OwnerID>0</OwnerID>
                    </CustomerQueryRq>
                  </QBXMLMsgsRq>
                </QBXML>';
                $xml = '<?xml version="1.0" encoding="utf-8"?>
                        <?qbxml version="'.$version.'"?>
                        <QBXML>
                            <QBXMLMsgsRq onError="continueOnError">
                                <CustomerQueryRq requestID="' . $requestID . '" iterator="Start">
                                    <MaxReturned>10</MaxReturned>
									<IncludeRetElement>Name</IncludeRetElement>
                                    <FromModifiedDate>1984-01-29T22:03:19</FromModifiedDate>
                                    <OwnerID>0</OwnerID>
                                </CustomerQueryRq>
                            </QBXMLMsgsRq>
                        </QBXML>';
						
						
///////////     Cycles infinitely						
$xml = '<?xml version="1.0" encoding="utf-8"?>
                        <?qbxml version="'.$version.'"?>
<QBXML>
	<QBXMLMsgsRq onError="stopOnError">
		<CustomerQueryRq requestID="' . $requestID . '"> 
			<IncludeRetElement>Name</IncludeRetElement>
		</CustomerQueryRq>
	</QBXMLMsgsRq>
</QBXML>';			

*/


$xml = '<?xml version="1.0" encoding="utf-8"?>
                        <?qbxml version="'.$version.'"?>
<QBXML>
	<QBXMLMsgsRq onError="stopOnError">
		<CustomerQueryRq requestID="' . $requestID . '"> 
		        <FullName>Katy Morton</FullName>
		</CustomerQueryRq>
	</QBXMLMsgsRq>
</QBXML>';			






            return $xml;
        }

        function _quickbooks_customer_query_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents){ 
            $content = $xml;
            $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/qbw/storef/qbdp/wc.txt","wb");
            fwrite($fp,$content);
            fclose($fp);
        }

        function _quickbooks_error_handler($requestID, $user, $action, $ID, $extra, &$err, $xml, $errnum, $errmsg){
            $content = "##########################################################";
            $content .= $idents;
            $content .= "##########################################################";
            $content .= $xml;
            $content .= "##########################################################";
            $content .= $errmsg;
            $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/store/qbsdkm/docs/myText.txt","a");
            fwrite($fp,$content);
            fclose($fp);
        }

        function _quickbooks_reference_error($requestID, $user, $action, $ID, $extra, &$err, $xml, $errnum, $errmsg){
            $content = "##########################################################";
            $content .= $idents;
            $content .= "##########################################################";
            $content .= $xml;
            $content .= "##########################################################";
            $content .= $errmsg;
            $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/store/qbsdkm/docs/myText.txt","a");
            fwrite($fp,$content);
            fclose($fp);
        }



$Queue = QuickBooks_WebConnector_Queue_Singleton::getInstance();

 //   $Queue->enqueue(QUICKBOOKS_ADD_CUSTOMER, mt_rand());
    $Queue->enqueue(QUICKBOOKS_QUERY_CUSTOMER,mt_rand());
 //   $Queue->enqueue(QUICKBOOKS_ADD_SALESORDER, mt_rand());