<?php

include_once 'config/basic.cfg';

// define('NI_ITEMS_XML',       'data/sinchro/noninvitems_inc.xml');
// define('QB_CUSTOMER_XML',    'data/sinchro/qbcustomers.xml');
// define('QBESTIMATE_XML',     'data/sinchro/qbestimates.xml');
// //define('SALES_ORDER_XML',    '/qbw/data/sinchro/sorder_inc.xml');
// define('SALES_ORDER_XML',    'data/sinchro/sorder_base.xml');
// define('PURCHASE_ORDER_XML', 'data/sinchro/porder_inc.xml');
// define('QBPAYMENT_XML',      'data/sinchro/qbpayments.xml');

define('I_ITEMS_XML',        'data/sinchro/_qb_inventory_items_b.xml');
define('NI_ITEMS_XML',       'data/sinchro/_qb_noninventory_items_b.xml');
define('QB_CUSTOMER_XML',    'data/sinchro/_qb_customers_b.xml');
define('QBESTIMATE_XML',     'data/sinchro/_qb_estimates_b.xml');
define('SALES_ORDER_XML',    'data/sinchro/_qb_sales_orders_b.xml');
define('PURCHASE_ORDER_XML', 'data/sinchro/_qb_purchase_orders_b.xml');
define('QBPAYMENT_XML',      'data/sinchro/_qb_payments_b.xml');


$qbwc_user = NULL;
$qbwc_pass = NULL;

//		$qbwc_user = 'qbvipuser';  
//		$qbwc_pass = 'eMa7--CFv4';

//		$qbwc_user = 'qbadmin';  
//		$qbwc_pass = 'w81HHy04';


//*
switch($lghsh['Id']){
	case 1 : 
		$qbwc_user = 'qbadmin';  
		$qbwc_pass = 'w81HHy04';
	break;
	case 7  : 
		$qbwc_user = 'qb_user7';  
		$qbwc_pass = 'eMa7--CFv4';
	break;	
	case 8  : 
		$qbwc_user = 'qbvipuser';  
		$qbwc_pass = 'eMa7--CFv4';
	break;
	case 9  : 
		$qbwc_user = 'qb_user9';  
		$qbwc_pass = 'eMa7--CFv4';
	break;
	case 10 : 
		$qbwc_user = 'qb_user10'; 
		$qbwc_pass = 'eMa7--CFv4';
	break;
	case 11 : 
		$qbwc_user = 'qb_user11'; 
		$qbwc_pass = 'eMa7--CFv4';
	break;
	case 12 : 
		$qbwc_user = 'qb_user12'; 
		$qbwc_pass = 'eMa7--CFv4';
	break;
	case 13 : 
		$qbwc_user = 'qb_user13'; 
		$qbwc_pass = 'eMa7--CFv4';
	break;
	case 14 : 
		$qbwc_user = 'qb_user14'; 
		$qbwc_pass = 'eMa7--CFv4';
	break;
	case 15 : 
		$qbwc_user = 'qb_user15'; 
		$qbwc_pass = 'eMa7--CFv4';
	break;
	case 16 : 
		$qbwc_user = 'qb_user16'; 
		$qbwc_pass = 'eMa7--CFv4';
	break;
	case 17 : 
		$qbwc_user = 'qb_user17'; 
		$qbwc_pass = 'eMa7--CFv4';
	break;
	case 18 : 
		$qbwc_user = 'qb_user18'; 
		$qbwc_pass = 'eMa7--CFv4';
	break;
	default :
		$qbwc_user = NULL;
		$qbwc_pass = NULL;
	break;	
	}

//    */

// usernames/passs /////
//$qbwc_user = 'qbvipuser';
//$qbwc_pass = 'eMa7--CFv4';
// $db_name   = 'it_qbwc';
// $db_host   = 'localhost';
// $db_user   = 'it_qbuser';
// $db_pass   = 'Prise4Wc$';
/////////////////////////////////



// * MAKE SURE YOU CHANGE THE DATABASE CONNECTION STRING BELOW TO A VALID MYSQL USERNAME/PASSWORD/HOSTNAME *
$dsn = 'mysqli://'.$db_user.':'.$db_pass.'@'.$db_host.'/'.$db_name;

// Extra db handler I had to create to adapt older functions to new PHP 7
$dbh = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
 
// We need to make sure the correct timezone is set, or some PHP installations will complain
if (function_exists('date_default_timezone_set'))
{
	// List of valid timezones is here: http://us3.php.net/manual/en/timezones.php
	date_default_timezone_set('America/New_York');
}

// I always program in E_STRICT error mode... 
//error_reporting(E_ALL | E_STRICT);

//require_once dirname(__FILE__) . '/QuickBooks.php'; // the framework
require_once 'QuickBooks.php';