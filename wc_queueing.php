<?php

/**
 * Example integration with an application
 * 
 * The idea behind the action queue is basically just that you want to add an 
 * action/ID pair to the queue whenever something happens in your application 
 * that you need to tell QuickBooks about. 
 * 
 * @author Keith Palmer <keith@consolibyte.com>
 * 
 * @package QuickBooks
 * @subpackage Documentation
 */
 
// Error reporting for easier debugging
ini_set('display_errors', true);
error_reporting(E_ALL | E_STRICT);
 
// Require the queueuing class
require_once 'QuickBooks.php';

//$Queue = new QuickBooks_WebConnector_Queue('mysqli://admin:ccm321@localhost/qbwc');
//$Queue->enqueue(QUICKBOOKS_QUERY_CUSTOMER, 123);

if (isset($_POST['customer']))
{
	// Oooh, here's a new customer, let's do some stuff with them
	
	// Connect to your own MySQL server....
	$link = mysqli_connect('localhost', 'admin', 'ccm321');
	if (!$link) 
	{
		die('Could not connect to MySQL: ' . mysql_error());
	}
	
	// ... and use the correct database
	$selected = mysqli_select_db($link, 'qbwc');
	if (!$selected) 
	{
		die ('Could not select database: ' . mysql_error());
	}	
	
	// Insert into our local MySQL database
	mysqli_query($link, "INSERT INTO my_customer_table ( name, phone, email ) VALUES ( '" . $_POST['customer']['name'] . "', '" . $_POST['customer']['phone'] . "', '" . $_POST['customer']['email'] . "' ) ");
	$id_value = mysqli_insert_id($link);
	
	// QuickBooks queueing class
	$Queue = new QuickBooks_WebConnector_Queue('mysqli://admin:ccm321@localhost/qbwc');
	
	// Queue it up!
	$Queue->enqueue(QUICKBOOKS_ADD_CUSTOMER, $id_value);
}

?>

<html>




<head>
</head>





<body>
<form action="#" method="post" name="ff4">
<input name="customer[name]" value="john" /><br>
<input name="customer[phone]" value="smith" /><br>
<input name="customer[email]" value="smith@varsi.com" /><br>
<input type="submit" name="submit" value="Submit" />

</form>
</body>


</html>

