<?php

/**
 * Web Connector application
 * 
 * @package QuickBooks
 * @subpackage Documentation
 */

/**
 * Require some configuration stuff
 */ 
require_once dirname(__FILE__) . '/config.php';

// Handle the form post
if (isset($_POST['submitted']))
{
	// Save the record
	mysqli_query($dbh, "
		INSERT INTO
			wc_customer
		(
			CName, 
			FName, 
			LName
		) VALUES (
			'" . mysqli_escape_string($dbh, $_POST['name'] ) . "', 
			'" . mysqli_escape_string($dbh, $_POST['fname']) . "', 
			'" . mysqli_escape_string($dbh, $_POST['lname']) . "'
		)");

		
	// Get the primary key of the new record
	$id = mysqli_insert_id($dbh);
	
	// Queue up the customer add 
	$Queue = new QuickBooks_WebConnector_Queue($dsn);
	$Queue->enqueue(QUICKBOOKS_ADD_CUSTOMER, $id);
	
	die('Great, queued up a customer!');
}
