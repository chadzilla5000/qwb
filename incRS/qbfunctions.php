<?php



/////////////////////////////////////////////////////////  ADD Customer ///////////////////////////////////////////////////////////////////////////////
function _quickbooks_customer_add_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{	global $dbh;
	$record = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM wc_customer WHERE id = " . (int) $ID, MYSQLI_ASSOC));
	$name = ($record['CName']) ? $record['CName'] : $record['LName'] . ', ' . $record['FName'];	
	$name = spcharhndl($name);
//	$name = preg_replace("/\’/", '', $name); //// Replace special character
	$bnm  = $name; // ($record['CName']) ? $record['CName'] : $record['FName'] . ' '  . $record['LName'];	
	$tax  = ($record['StateAlt']) ? (($record['StateAlt']=='PA') ? 'TAX' : 'NON') : (($record['State']=='PA') ? 'TAX' : 'NON');	
	$phonum = trtphonum( $record['Phone'], false );

	$xml = '<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="'.$version.'"?>
<QBXML>
	<QBXMLMsgsRq onError="continueOnError">
		<CustomerAddRq requestID="' . $requestID . '">
			<CustomerAdd>
				<Name>'        . $name . '</Name>
				<CompanyName>' . $record['CName'] . '</CompanyName>
				<FirstName>'   . $record['FName'] . '</FirstName>
				<LastName>'    . $record['LName'] . '</LastName>
				<BillAddress>
					<Addr1>'      . $bnm               . '</Addr1>
					<Addr2>'      . $record['Street']  . '</Addr2>
					<City>'       . $record['City']    . '</City>
					<State>'      . $record['State']   . '</State>
					<PostalCode>' . $record['Zip']     . '</PostalCode>
					<Country>'    . $record['Country'] . '</Country>
				</BillAddress>
				<Phone>' . $phonum . '</Phone>
				<AltPhone></AltPhone>
				<Fax></Fax>
				<Email>' . $record['EMail'] . '</Email>
				<Contact>' . $record['FName'] . ' '  . $record['LName'] . '</Contact>
				<SalesTaxCodeRef>
					<FullName>'.$tax.'</FullName>
				</SalesTaxCodeRef>
			</CustomerAdd>
		</CustomerAddRq>
	</QBXMLMsgsRq>
</QBXML>';

return $xml;
}



function _quickbooks_customer_add_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{	global $dbh;
	mysqli_query($dbh, "UPDATE wc_customer 
		SET quickbooks_listid = '" . mysqli_escape_string($dbh, $idents['ListID']) . "', 
			quickbooks_editsequence = '" . mysqli_escape_string($dbh, $idents['EditSequence']) . "'
		WHERE id = " . (int) $ID);
//////////////////////// Set in UTerminal 
	// $que1 = "INSERT INTO	qw_customer	(	
			// name, fname, lname, phone, email, quickbooks_listid, ) VALUES (
			// '" . mysqli_escape_string($dbh, $omd['_billing_company'][0] )     . "',
			// '" . mysqli_escape_string($dbh, $omd['_billing_first_name'][0] )  . "',
			// '" . mysqli_escape_string($dbh, $omd['_billing_last_name'][0] )   . "',
			// '" . mysqli_escape_string($dbh, $omd['_billing_phone'][0] )       . "',
			// '" . mysqli_escape_string($dbh, $omd['_billing_email'][0] )       . "'
		// )";
	// mysqli_query($dbh, $que1);


	mysqli_query($dbh, "UPDATE qw_customer 
		SET	quickbooks_listid =       '" . mysqli_escape_string($dbh, $idents['ListID']) . "', 
			quickbooks_editsequence = '" . mysqli_escape_string($dbh, $idents['EditSequence']) . "'
		WHERE email=(SELECT EMail FROM wc_customer WHERE id = " . (int) $ID.")");
}

function _quickbooks_error_catchall($requestID, $user, $action, $ID, $extra, &$err, $xml, $errnum, $errmsg)
{	global $dbh;
	mysql_query($dbh, "
		UPDATE wc_customer 
		SET quickbooks_errnum = '" . mysqli_escape_string($dbh, $errnum) . "', 
			quickbooks_errmsg = '" . mysqli_escape_string($dbh, $errmsg) . "'
		WHERE id = " . (int) $ID);
}



















/////////////////////////////////////////////////////////  ADD Estimate ///////////////////////////////////////////////////////////////////////////////
function _quickbooks_estimate_add_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
	{ global $dbh;
	$record = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM wc_order WHERE id = " . (int) $ID, MYSQLI_ASSOC));

	$name = ($record['CName']) ? $record['CName'] : $record['LName'] . ', ' . $record['FName'];	
	$name = spcharhndl($name);
	$bnm  = ($record['CName']) ? $record['CName'] : $record['FName'] . ' '  . $record['LName'];	
	$bnm = spcharhndl($bnm);
	$bnmAlt  = ($record['CNameAlt']) ? $record['CNameAlt'] : $record['FNameAlt'] . ' '  . $record['LNameAlt'];	
	$bnmAlt = spcharhndl($bnmAlt);	
	$tax  = ($record['StateAlt']) ? (($record['StateAlt']=='PA') ? 'Tax' : 'Non') : (($record['State']=='PA') ? 'Tax' : 'Non');	// ????
	$itax  = (1)?'TAX':'Taxable Amount';	
	$itlst = pullitems($record['OrderID'], 'Estimate');
	$phonum = ($record['ShPhone']) ? trtphonum( $record['ShPhone'], false ) : trtphonum( $record['Phone'], false );

	$toid = (preg_match('/^(T-)(.*)/', $record['OrderID'])) ? $record['OrderID'] : 'W-'.$record['OrderID'];

	$xml = '<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="'.$version.'"?>
	<QBXML>
		<QBXMLMsgsRq onError="stopOnError">
			<EstimateAddRq requestID="' . $requestID . '">
				<EstimateAdd>
					<CustomerRef>
						<ListID>' . mysqli_escape_string($dbh, $record['QBCustListID']) . '</ListID>
						<FullName>'.$name.'</FullName>
					</CustomerRef>
					<TxnDate>' . mysqli_escape_string($dbh, $record['OrderDT'] ) . '</TxnDate>
					<RefNumber>'.$toid.'</RefNumber>
					<BillAddress>
						<Addr1>'.$bnm.'</Addr1>
						<Addr2>'.$record['Street'].'</Addr2>
						<Addr3/>
						<City>'.$record['City'].'</City>
						<State>'.$record['State'].'</State>
						<PostalCode>'.$record['Zip'].'</PostalCode>
						<Country>'.$record['Country'].'</Country>
					</BillAddress>
					<ShipAddress>
						<Addr1>'.$bnmAlt.'</Addr1>
						<Addr2>'.$record['StreetAlt'].'</Addr2>
						<Addr3/>
						<City>'.$record['CityAlt'].'</City>
						<State>'.$record['StateAlt'].'</State>
						<PostalCode>'.$record['ZipAlt'].'</PostalCode>
						<Country>'.$phonum.'</Country>
					</ShipAddress>
					<ItemSalesTaxRef>
						<FullName>'.$itax.'</FullName>
					</ItemSalesTaxRef>
					<Memo>'.$record['Notes'].'</Memo>
'.$itlst.'
				</EstimateAdd>
			</EstimateAddRq>
		</QBXMLMsgsRq>
	</QBXML>';

	return $xml;
	}

function _quickbooks_estimate_add_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
    {  global $dbh;

	$rec = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM wc_order WHERE id = " . (int) $ID, MYSQLI_ASSOC));
	$res = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM qw_xt WHERE oname='Estimate' AND IID='".$rec['OrderID']."'", MYSQLI_ASSOC));
	if($res['id']){
		mysqli_query($dbh, "UPDATE qw_xt 
			SET	QBListID = '" . mysqli_escape_string($dbh, $idents['ListID']) . "'
			WHERE id = '".$res['id']."'");
		}
	else{
		mysqli_query($dbh, "INSERT INTO qw_xt ( oname, IID, QBListID ) 
			VALUES ('Estimate', 
			'" . mysqli_escape_string($dbh, $rec['OrderID'] ) . "',
			'" . mysqli_escape_string($dbh, $idents['ListID'] ) . "')"
			);
		}
	}
























/////////////////////////////////////////////////////////  ADD Sales Order ///////////////////////////////////////////////////////////////////////////////
function _quickbooks_salesorder_add_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
	{	global $dbh;
	$record = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM wc_order WHERE id = " . (int) $ID, MYSQLI_ASSOC));

	$name = ($record['CName']) ? $record['CName'] : $record['LName'] . ', ' . $record['FName'];	
	$name = spcharhndl($name);
	$bnm  = ($record['CName']) ? $record['CName'] : $record['FName'] . ' '  . $record['LName'];	
	$bnm = spcharhndl($bnm); // preg_replace('/\’/', ' ', $bnm);
	$bnmAlt  = ($record['CNameAlt']) ? $record['CNameAlt'] : $record['FNameAlt'] . ' '  . $record['LNameAlt'];	
	$bnmAlt = spcharhndl($bnmAlt); // preg_replace('/\’/', ' ', $bnmAlt);
	$tax  = ($record['StateAlt']) ? (($record['StateAlt']=='PA') ? 'Tax' : 'Non') : (($record['State']=='PA') ? 'Tax' : 'Non');	// ????
	$itax  = (1)?'TAX':'Taxable Amount';	
	$itlst = pullitems($record['OrderID'], 'Order');
	$toid = (preg_match('/^(T-)(.*)/', $record['OrderID'])) ? $record['OrderID'] : 'W-'.$record['OrderID'];
	$phonum = ($record['ShPhone']) ? trtphonum( $record['ShPhone'], false ) : trtphonum( $record['Phone'], false );


	$xml = '<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="'.$version.'"?>
	<QBXML>
		<QBXMLMsgsRq onError="stopOnError">
			<SalesOrderAddRq requestID="' . $requestID . '">
				<SalesOrderAdd>
					<CustomerRef>
						<ListID>' .     mysqli_escape_string($dbh, $record['QBCustListID']) . '</ListID>
						<FullName>' .   mysqli_escape_string($dbh, $name) . '</FullName>
					</CustomerRef>
					<TxnDate>' .        mysqli_escape_string($dbh, $record['OrderDT'] ) . '</TxnDate>
					<RefNumber>' .      mysqli_escape_string($dbh, $toid) . '</RefNumber>
					<BillAddress>
						<Addr1>' .      mysqli_escape_string($dbh, $bnm).'</Addr1>
						<Addr2>' .      mysqli_escape_string($dbh, $record['Street']) . '</Addr2>
						<Addr3/>
						<City>' .       mysqli_escape_string($dbh, $record['City']) . '</City>
						<State>' .      mysqli_escape_string($dbh, $record['State']) . '</State>
						<PostalCode>' . mysqli_escape_string($dbh, $record['Zip']) . '</PostalCode>
						<Country>' .    mysqli_escape_string($dbh, $record['Country']) . '</Country>
					</BillAddress>
					<ShipAddress>
						<Addr1>' .      mysqli_escape_string($dbh, $bnmAlt) . '</Addr1>
						<Addr2>' .      mysqli_escape_string($dbh, $record['StreetAlt']) . '</Addr2>
						<Addr3/>
						<City>' .       mysqli_escape_string($dbh, $record['CityAlt']) . '</City>
						<State>' .      mysqli_escape_string($dbh, $record['StateAlt']) . '</State>
						<PostalCode>' . mysqli_escape_string($dbh, $record['ZipAlt']) . '</PostalCode>
						<Country>' .    mysqli_escape_string($dbh, $phonum) . '</Country>
						<Note>' .       mysqli_escape_string($dbh, $record['ShPhone']) . '</Note>
					</ShipAddress>
					<ItemSalesTaxRef>
						<FullName>' .   $itax . '</FullName>
					</ItemSalesTaxRef>
					<Memo>' .           mysqli_escape_string($dbh, $record['Notes']) . '</Memo>
'.$itlst.'
				</SalesOrderAdd>
			</SalesOrderAddRq>
		</QBXMLMsgsRq>
	</QBXML>';

        return $xml;
    }

function _quickbooks_salesorder_add_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
    {  global $dbh;
	$rec = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM wc_order WHERE id = " . (int) $ID, MYSQLI_ASSOC));
	$res = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM qw_xt WHERE oname='Order' AND IID='".$rec['OrderID']."'", MYSQLI_ASSOC));
	if($res['id']){
		mysqli_query($dbh, "UPDATE qw_xt 
			SET	QBListID = '" . mysqli_escape_string($dbh, $idents['ListID']) . "'
			WHERE id = '".$res['id']."'");
		}
	else{
		mysqli_query($dbh, "INSERT INTO qw_xt ( oname, IID, QBListID ) 
			VALUES ('Order', 
			'" . mysqli_escape_string($dbh, $rec['OrderID'] ) . "',
			'" . mysqli_escape_string($dbh, $idents['ListID'] ) . "')"
			);
		}


/*
	$rec = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM wc_order WHERE id = " . (int) $ID, MYSQLI_ASSOC));
	mysqli_query($dbh, "UPDATE qw_xt 
	SET	QBListID = '" . mysqli_escape_string($dbh, $idents['ListID']) . "'
	WHERE oname = 'Order' AND  IID = '".$rec['OrderID']."'");
*/

    }

//echo pullitems('315827', 'Estimate'); ///////////////////////// Checking XML ........ //////////////
function pullitems($oid, $trg){ global $dbh;
	$items = NULL;
	$otag = ($trg=='Estimate') ? '<EstimateLineAdd>' : '<SalesOrderLineAdd>';
	$ctag = ($trg=='Estimate') ? '</EstimateLineAdd>' : '</SalesOrderLineAdd>';

	$result = mysqli_query($dbh, "SELECT * FROM wc_order_items WHERE OrderID = '$oid' ORDER BY id ASC");
	while($record = mysqli_fetch_assoc($result)){
		$qtty = ($record['Qtty']>0)?$record['Qtty']:NULL;
		$amnt = ($record['STtl']>0)?$record['STtl']:NULL;
		$ittl = preg_replace("/[–]/", '-', $record['Title']); //// Replace Hyphen (special character)
		$ittl = preg_replace("/[\/]/", '_', $ittl); //// Replace special character
		$ittl = preg_replace("/[\"]/", '', $ittl); //// Replace special character
		$ittl = preg_replace("/[\']/", '', $ittl); //// Replace special character
		$ittl = preg_replace("/[\'\']/", '', $ittl); //// Replace special character
		$items .= '
' . $otag . '
	<ItemRef>
		<FullName>'.$record['Sku'].'</FullName>
	</ItemRef>
	<Desc>'.$ittl.'</Desc>
	<Quantity>'.$qtty.'</Quantity>
	<Amount>'.$amnt.'</Amount>
' . $ctag;
		}
	mysqli_free_result($result);
	return $items;
}






















////////////////////////////////////////////////         Add Item //////////////////////////////////////////////////////////////////////////////////////
function _quickbooks_item_add_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
	{ 	global $dbh;
	$record = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM wc_item WHERE id = " . (int) $ID, MYSQLI_ASSOC));

//	$sku= encode preg_replace('/\./', '-', $record['Sku']);
//	$sku=preg_replace('/\s/', '_', $sku);

$xml = '<?xml version="1.0" encoding="utf-8"?>
	<?qbxml version="'.$version.'"?>
	<QBXML>
		<QBXMLMsgsRq onError="continueOnError">
			<ItemNonInventoryAddRq requestID="'.$requestID.'">
				<ItemNonInventoryAdd>
					<Name>' . mysqli_escape_string($dbh, $record['Sku'] ) . '</Name>
					<SalesAndPurchase>
						<SalesDesc>' . mysqli_escape_string($dbh, $record['Title'] ) . '</SalesDesc>
						<SalesPrice>' . mysqli_escape_string($dbh, $record['SPrice'] ) . '</SalesPrice>
						<IncomeAccountRef>
							<FullName>' . mysqli_escape_string($dbh, $record['IncAccRef'] ) . '</FullName>
						</IncomeAccountRef>
						<PurchaseDesc>' . mysqli_escape_string($dbh, $record['Title'] ) . '</PurchaseDesc>
						<PurchaseCost>' . mysqli_escape_string($dbh, $record['VCost'] ) . '</PurchaseCost>
						<ExpenseAccountRef>
							<FullName>' . mysqli_escape_string($dbh, $record['ExpAccRef'] ) . '</FullName>
						</ExpenseAccountRef>
					</SalesAndPurchase>
				</ItemNonInventoryAdd>
			</ItemNonInventoryAddRq>
		</QBXMLMsgsRq>
	</QBXML>';


/*
$xml = '<?xml version="1.0" encoding="utf-8"?>
	<?qbxml version="'.$version.'"?>
	<QBXML>
		<QBXMLMsgsRq onError="continueOnError">
			<ItemNonInventoryAddRq requestID="'.$requestID.'">
				<ItemNonInventoryAdd>
					<Name>'.$sku.'</Name>
					<SalesAndPurchase>
						<SalesDesc>'.$record['Title'].'</SalesDesc>
						<SalesPrice>'.$record['SPrice'].'</SalesPrice>
						<IncomeAccountRef>
							<FullName>Merchandise Sales</FullName>
						</IncomeAccountRef>
						<PurchaseDesc>'.$record['Title'].'</PurchaseDesc>
						<PurchaseCost>'.$record['VCost'].'</PurchaseCost>
						<ExpenseAccountRef>
							<FullName>Cost of Goods Sold</FullName>
						</ExpenseAccountRef>
					</SalesAndPurchase>
				</ItemNonInventoryAdd>
			</ItemNonInventoryAddRq>
		</QBXMLMsgsRq>
	</QBXML>';
*/



	return $xml;
    }

    /**
     * Receive a response from QuickBooks 
     */
    function _quickbooks_item_add_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
    {  	global $dbh;


	$xh = simplexml_load_string($xml);
	foreach($xh->QBXMLMsgsRs->ItemNonInventoryAddRs->ItemNonInventoryRet as $d){
		mysqli_query($dbh, "INSERT INTO	qw_item ( QBListID, EdSeq, Sku, Title, VCost, SPrice, IncAccRef, ExpAccRef, DTCreated, DTModified ) VALUES (
			'" . mysqli_escape_string($dbh, $d->ListID) . "',
			'" . mysqli_escape_string($dbh, $d->EditSequence) . "',
			'" . mysqli_escape_string($dbh, $d->Name) . "',
			'" . mysqli_escape_string($dbh, $d->SalesAndPurchase->SalesDesc) . "',
			'" . mysqli_escape_string($dbh, $d->SalesAndPurchase->PurchaseCost) . "',
			'" . mysqli_escape_string($dbh, $d->SalesAndPurchase->SalesPrice) . "',
			'" . mysqli_escape_string($dbh, $d->SalesAndPurchase->IncomeAccountRef->FullName) . "',
			'" . mysqli_escape_string($dbh, $d->SalesAndPurchase->ExpenseAccountRef->FullName) . "',
			'" . mysqli_escape_string($dbh, $d->TimeCreated) . "',
			'" . mysqli_escape_string($dbh, $d->TimeModified) . "'
			)");
			}
			
			



/*
	mysqli_query($dbh, "UPDATE qw_item SET quickbooks_listid='" . mysqli_escape_string($dbh, $idents['ListID']) . "' 
		WHERE email=(SELECT EMail FROM wc_customer WHERE id = " . (int) $ID.")");


		$content = $xml;
		$fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/qbw/data/sinchro/noninventoryitemsadded.xml","w") or die("Unable to open file!");
		fwrite($fp, $content);
		fclose($fp);

*/
/*
	$que = "INSERT INTO qw_item (QBListID, Sku, Title, VCost, RPrice, SPrice, DTCreated, DTModified) 
		VALUES (
			'" . mysqli_escape_string($dbh, $idents['ListID']) . "',
			'" . mysqli_escape_string($dbh, $h[0]->_sku ) . "',
			'" . mysqli_escape_string($dbh, $h[0]->post_title ) . "',
			'" . mysqli_escape_string($dbh, $h['cost'] ) . "',
			'" . mysqli_escape_string($dbh, $h[1]->regular_price ) . "',
			'" . mysqli_escape_string($dbh, $h[1]->price ) . "',
			'" . mysqli_escape_string($dbh, date('Y-m-d H:m:s', time()) ) . "',
			'" . mysqli_escape_string($dbh, date('Y-m-d H:m:s', time()) ) . "'
			)";
	mysqli_query($dbh, $que);
	*/
    }













////////////////////////////////////////////////         Modify Item //////////////////////////////////////////////////////////////////////////////////////
function _quickbooks_item_mod_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
	{ 	global $dbh; global $tsk;
	$record = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM wc_item WHERE id = " . (int) $ID, MYSQLI_ASSOC));
	$isact = 'true';

	$xml = '<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="'.$version.'"?>
	<QBXML>
		<QBXMLMsgsRq onError="continueOnError">
			<ItemNonInventoryModRq requestID="'.$requestID.'">
				<ItemNonInventoryMod>
					<ListID>'.$record['QBListID'].'</ListID>
					<EditSequence>'.$record['EdSeq'].'</EditSequence>
					<Name>' . mysqli_escape_string($dbh, $record['Sku'] ) . '</Name>
					<IsActive>'.$isact.'</IsActive>
					<SalesAndPurchaseMod>
						<SalesDesc>' . mysqli_escape_string($dbh, $record['Title'] ) . '</SalesDesc>
						<SalesPrice>' . mysqli_escape_string($dbh, $record['SPrice'] ) . '</SalesPrice>
						<IncomeAccountRef>
							<FullName>' . mysqli_escape_string($dbh, $record['IncAccRef'] ) . '</FullName>
						</IncomeAccountRef>
						<PurchaseCost>' . mysqli_escape_string($dbh, $record['VCost'] ) . '</PurchaseCost>
						<ExpenseAccountRef>
							<FullName>' . mysqli_escape_string($dbh, $record['ExpAccRef'] ) . '</FullName>
						</ExpenseAccountRef>
					</SalesAndPurchaseMod>
				</ItemNonInventoryMod>
			</ItemNonInventoryModRq>
		</QBXMLMsgsRq>
	</QBXML>';

	return $xml;
	}


   /**
     * Receive a response from QuickBooks 
     */
    function _quickbooks_item_mod_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
    {  global $dbh;

	$xh = simplexml_load_string($xml);
	foreach($xh->QBXMLMsgsRs->ItemNonInventoryModRs->ItemNonInventoryRet as $d){
		mysqli_query($dbh, "UPDATE qw_item SET 
			EdSeq     = '" . mysqli_escape_string($dbh, $d->EditSequence) . "', 
			VCost     = '" . mysqli_escape_string($dbh, $d->SalesAndPurchase->PurchaseCost) . "', 
			SPrice    = '" . mysqli_escape_string($dbh, $d->SalesAndPurchase->SalesPrice) . "',
			IncAccRef = '" . mysqli_escape_string($dbh, $d->SalesAndPurchase->IncomeAccountRef->FullName) . "',
			ExpAccRef = '" . mysqli_escape_string($dbh, $d->SalesAndPurchase->ExpenseAccountRef->FullName) . "'
		WHERE QBListID = '".$d->ListID."'");
		}
	}










//////////////////////////////////////////////////////////////////////////////////////
function _quickbooks_iitem_active_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
	{ 	global $dbh;
//	$rec = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM wc_item WHERE Sku = 'TTALAB-PAT-HFC'", MYSQLI_ASSOC));
	$rec = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM wc_item WHERE id = " . (int) $ID, MYSQLI_ASSOC));


$lid = $rec['QBListID'];
$eds = $rec['EdSeq'];
$act = ($rec['Active']==0)?'false':'true';
//$inc = $rec['IncAccRef'];
//$exp = $rec['ExpAccRef'];

	$xml = '<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="'.$version.'"?>
	<QBXML>
		<QBXMLMsgsRq onError="continueOnError">
			<ItemInventoryModRq requestID="'.$requestID.'">
				<ItemInventoryMod>
					<ListID>'.$lid.'</ListID>
					<EditSequence>'.$eds.'</EditSequence>
					<IsActive>'.$act.'</IsActive>
				</ItemInventoryMod>
			</ItemInventoryModRq>
		</QBXMLMsgsRq>
	</QBXML>';

	
	return $xml;
	}


   /**
     * Receive a response from QuickBooks 
     */
    function _quickbooks_iitem_active_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
    {  global $dbh;

	$xh = simplexml_load_string($xml);
	foreach($xh->QBXMLMsgsRs->ItemInventoryModRs->ItemInventoryRet as $d){
		$active = ($d->IsActive=='true') ? 1 : 0;
		mysqli_query($dbh, "UPDATE qw_item SET 
			EdSeq     = '" . mysqli_escape_string($dbh, $d->EditSequence) . "',
			Active    = '" . $active . "'
		WHERE QBListID = '".$d->ListID."'");
		}
	}


//////////////////////////////////////////////////////////////////////////////////////
function _quickbooks_nitem_active_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
	{ 	global $dbh;
//	$rec = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM wc_item WHERE Sku = 'TTALAB-PAT-HFC'", MYSQLI_ASSOC));
	$rec = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM wc_item WHERE id = " . (int) $ID, MYSQLI_ASSOC));


$lid = $rec['QBListID'];
$eds = $rec['EdSeq'];
$act = ($rec['Active']==0)?'false':'true';
$inc = $rec['IncAccRef'];
$exp = $rec['ExpAccRef'];

	$xml = '<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="'.$version.'"?>
	<QBXML>
		<QBXMLMsgsRq onError="continueOnError">
			<ItemNonInventoryModRq requestID="'.$requestID.'">
				<ItemNonInventoryMod>
					<ListID>'.$lid.'</ListID>
					<EditSequence>'.$eds.'</EditSequence>
					<IsActive>'.$act.'</IsActive>
					<SalesAndPurchaseMod>
						<IncomeAccountRef>
							<FullName>'.$inc.'</FullName>
						</IncomeAccountRef>
						<ExpenseAccountRef>
							<FullName>'.$exp.'</FullName>
						</ExpenseAccountRef>
					</SalesAndPurchaseMod>
				</ItemNonInventoryMod>
			</ItemNonInventoryModRq>
		</QBXMLMsgsRq>
	</QBXML>';

	
	return $xml;
	}


   /**
     * Receive a response from QuickBooks 
     */
    function _quickbooks_nitem_active_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
    {  global $dbh;

	$xh = simplexml_load_string($xml);
	foreach($xh->QBXMLMsgsRs->ItemNonInventoryModRs->ItemNonInventoryRet as $d){
		$active = ($d->IsActive=='true') ? 1 : 0;
		mysqli_query($dbh, "UPDATE qw_item SET 
			EdSeq     = '" . mysqli_escape_string($dbh, $d->EditSequence) . "',
			Active    = '" . $active . "'
		WHERE QBListID = '".$d->ListID."'");
		}
	}

///////////////////////////////////////////////////////////////

















////////////////////////////////////////////////         Add Payment //////////////////////////////////////////////////////////////////////////////////////
function _quickbooks_payment_add_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
	{ global $dbh;
	$record = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM wc_payment WHERE id = " . (int) $ID, MYSQLI_ASSOC));

	$billname = ($record['NameRes'])?$record['NameRes']:(($record['CName']) ? $record['CName'] : $record['FName'] . ' ' . $record['LName']);	
	
	// $billname = preg_replace('/[&#x2160;]/', 'I', $billname);
	// $billname = preg_replace('/[&#x2164;]/', 'V', $billname);
	// $billname = preg_replace('/[&#x2163;]/', 'IV', $billname);
	// $billname = preg_replace('/\sIV/', '', $billname);
	
	$shipname  = ($record['CNameAlt']) ? $record['CNameAlt'] : $record['FNameAlt'] . ' '  . $record['LNameAlt'];	
	$trdate = substr($record['DTLocal'], 0, 10);
	// $tax  = ($record['StateAlt']) ? (($record['StateAlt']=='PA') ? 'Tax' : 'Non') : (($record['State']=='PA') ? 'Tax' : 'Non');	// ????
	// $itax  = (1)?'TAX':'Taxable Amount';	
	// $itlst = pullitems($record['OrderID'], 'Order');

	$billname = preg_replace('/\’/',' ',$billname);
	$shipname = preg_replace('/\’/',' ',$shipname);

	$xml = '<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="'.$version.'"?>
	<QBXML>
		<QBXMLMsgsRq onError="stopOnError">
			<ReceivePaymentAddRq> <!-- Does not need request ID here   -->
				<ReceivePaymentAdd>
					<CustomerRef>
						<ListID>' . mysqli_escape_string($dbh, $record['QBCustListID']) . '</ListID>
						<FullName>' . $billname . '</FullName>
					</CustomerRef>
					<TxnDate>' . mysqli_escape_string($dbh, $trdate) . '</TxnDate>
					<RefNumber>' . mysqli_escape_string($dbh, $record['TransactionID']) . '</RefNumber>
					<TotalAmount>' . mysqli_escape_string($dbh, $record['AmountPaid']) . '</TotalAmount>
					<PaymentMethodRef>
						<FullName>' . mysqli_escape_string($dbh, $record['PayMethod']) . '</FullName>
					</PaymentMethodRef>
					<Memo>' . 
					mysqli_escape_string($dbh, $record['OrderID'] . ', ' . 
					$record['TransactionID'] . ', '. 
					$record['BatchID']       . ', ' . 
					$record['CardType']      . ', ' . 
					$record['CardNumber'])   . ' :: ' . 
					mysqli_escape_string($dbh, $record['Notes']) .'</Memo>
					<DepositToAccountRef>
						<FullName>Undeposited Funds</FullName>
					</DepositToAccountRef>
					<IsAutoApply>true</IsAutoApply>
				</ReceivePaymentAdd>
			</ReceivePaymentAddRq>
		</QBXMLMsgsRq>
	</QBXML>';
	return $xml;
    }

    /**
     * Receive a response from QuickBooks 
     */
function _quickbooks_payment_add_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
    { 	global $dbh;
	mysqli_query($dbh, "UPDATE wc_payment 
		SET quickbooks_listid = '" . mysqli_escape_string($dbh, $idents['ListID']) . "', 
			quickbooks_editsequence = '" . mysqli_escape_string($dbh, $idents['EditSequence']) . "'
		WHERE id = " . (int) $ID);
//////////////////////// Set in UTerminal 

	$rec = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM wc_payment WHERE id = " . (int) $ID, MYSQLI_ASSOC));
	$res = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM qw_xt WHERE oname='Payment' AND IID='".$rec['TransactionID']."'", MYSQLI_ASSOC));
	if($res['id']){
		mysqli_query($dbh, "UPDATE qw_xt 
			SET	QBListID = '" . mysqli_escape_string($dbh, $idents['ListID']) . "'
			WHERE id = '".$res['id']."'");
		}
	else{
		mysqli_query($dbh, "INSERT INTO qw_xt ( oname, IID, QBListID ) 
			VALUES ('Payment', 
			'" . mysqli_escape_string($dbh, $rec['TransactionID'] ) . "',
			'" . mysqli_escape_string($dbh, $idents['ListID'] ) . "')"
			);
		}
		  // $content = $xml;
            // $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/qbw/wpsdq.xml","a") or die("Unable to open file!");
            // fwrite($fp, $content);
            // fclose($fp);

    }





//////////////  ////////////////////////////////////////////////////////////////////////////////////
/////////  ///////   /////////////////////////////////////////////////////////////////////////////////
/////// ////////////  ////////////////////////////////////////////////////////////////////////////////
///// ///////////////  ///////////////////////////////////////////////////////////////////////////////
//// /////////////////  //////////////////////////////////////////////////////////////////////////////
//// /////////////////  /////////////////////////////////////////////////////////////////////////////
//// /////////////////  //////////////////////////////////////////////////////////////////////////////
////  //////////////  ////////////////////////////////////////////////////////////////////////////////
/////  ////////////  //////////////////////////////////////////////////////////////////////////////////
//////  /////////  ////////////////////////////////////////////////////////////////////////////////////
//////// /////   /////////////////////////////////////////////////////////////////////////////////////
//////////////    ////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////         Query Item //////////////////////////////////////////////////////////////////////////////////////
function _quickbooks_item_invertory_query_request_____($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
	{
	$xml = '<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="'.$version.'"?>
	<QBXML>
		<QBXMLMsgsRq onError="continueOnError">
			<ItemNonInventoryQueryRq requestID="'.$requestID.'" />
		</QBXMLMsgsRq>
	</QBXML>';

	return $xml;
    }

/////////////////////////    Receive a response from QuickBooks  /////////////////////////////////////////////////////////// 

function _quickbooks_item_invertory_query_response______($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
    {
		$content = $xml;
		$fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/qbw/data/sinchro/noninventoryitems.xml","w") or die("Unable to open file!");
		fwrite($fp, $content);
		fclose($fp);
    }


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////         Query NonInventory Item D2D //////////////////////////////////////////////////////////////////////////////////////
function _quickbooks_item_noninvertory_query_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
	{


	$xml = '<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="'.$version.'"?>
	<QBXML>
		<QBXMLMsgsRq onError="continueOnError">
			<ItemNonInventoryQueryRq requestID="'.$requestID.'">
				<ActiveStatus>All</ActiveStatus>
			</ItemNonInventoryQueryRq>
		</QBXMLMsgsRq>
	</QBXML>';  


/*
	$xml = '<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="'.$version.'"?>
	<QBXML>
		<QBXMLMsgsRq onError="continueOnError">
			<ItemNonInventoryQueryRq requestID="'.$requestID.'" />
		</QBXMLMsgsRq>
	</QBXML>';
//// */



/*
	$dfr = date("Y-m-d", strtotime("-1 week"));
	$dto = date("Y-m-d");
	
	$xml = '<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="'.$version.'"?>
	<QBXML>
		<QBXMLMsgsRq onError="continueOnError">
			<ItemNonInventoryQueryRq requestID="'.$requestID.'">
				<FromModifiedDate>' . $dfr . '</FromModifiedDate>
				<ToModifiedDate>'   . $dto . '</ToModifiedDate>
			</ItemNonInventoryQueryRq>
		</QBXMLMsgsRq>
	</QBXML>';  
//// */






/*
	$xml = '<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="'.$version.'"?>
	<QBXML>
		<QBXMLMsgsRq onError="continueOnError">
			<ItemNonInventoryQueryRq requestID="'.$requestID.'" />
		</QBXMLMsgsRq>
	</QBXML>';

	$xml = '<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="'.$version.'"?>
	<QBXML>
		<QBXMLMsgsRq onError="continueOnError">
			<ItemNonInventoryQueryRq requestID="'.$requestID.'">
				<IncludeRetElement>ListID</IncludeRetElement>
				<IncludeRetElement>TimeCreated</IncludeRetElement>
				<IncludeRetElement>TimeModified</IncludeRetElement>
				<IncludeRetElement>EditSequence</IncludeRetElement>
				<IncludeRetElement>Name</IncludeRetElement>
				<IncludeRetElement>SalesAndPurchase</IncludeRetElement>
			</ItemNonInventoryQueryRq>
		</QBXMLMsgsRq>
	</QBXML>';

	$xml = '<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="'.$version.'"?>
	<QBXML>
		<QBXMLMsgsRq onError="continueOnError">
			<ItemNonInventoryQueryRq requestID="'.$requestID.'">
       <FromModifiedDate>2020-08-01</FromModifiedDate>
       <ToModifiedDate>2020-08-13</ToModifiedDate>
				<IncludeRetElement>ListID</IncludeRetElement>
				<IncludeRetElement>EditSequence</IncludeRetElement>
				<IncludeRetElement>Name</IncludeRetElement>
				<IncludeRetElement>SalesAndPurchase</IncludeRetElement>
				<IncludeRetElement>TimeCreated</IncludeRetElement>
				<IncludeRetElement>TimeModified</IncludeRetElement>
			</ItemNonInventoryQueryRq>
		</QBXMLMsgsRq>
	</QBXML>';

	$xml = '<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="'.$version.'"?>
	<QBXML>
		<QBXMLMsgsRq onError="continueOnError">
			<ItemNonInventoryQueryRq requestID="'.$requestID.'">

       <FromModifiedDate>2020-12-31</FromModifiedDate>
       <ToModifiedDate>2021-02-25</ToModifiedDate>

			</ItemNonInventoryQueryRq>
		</QBXMLMsgsRq>
	</QBXML>';

///////////////*/


	return $xml;
    }

    /**
     * Receive a response from QuickBooks 
     */
function _quickbooks_item_noninvertory_query_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
    {   //global $dbh;

		$content = $xml;
		$fp = fopen($_SERVER['DOCUMENT_ROOT'] . '/qbw/' . NI_ITEMS_XML,"w") or die("Unable to open file!");
		fwrite($fp, $content);
		fclose($fp);

/*

	$xh = simplexml_load_string($xml);
	foreach($xh->QBXMLMsgsRs->ItemNonInventoryModRs->ItemNonInventoryRet as $d){
//		if(mysqli_fetch_array(mysqli_query($dbh, "SELECT id FROM qw_item WHERE Sku = '$d->Name'")))	{ // item exists
//		if(!	
		
		mysqli_query($dbh, "UPDATE qw_item SET 
				QBListID   = '".mysqli_escape_string($dbh, $d->ListID)."',
				EdSeq      = '".mysqli_escape_string($dbh, $d->EditSequence)."',
				Title      = '".mysqli_escape_string($dbh, $d->SalesAndPurchase->SalesDesc)."', 
				VCost      = '".mysqli_escape_string($dbh, $d->SalesAndPurchase->PurchaseCost)."', 
				SPrice     = '".mysqli_escape_string($dbh, $d->SalesAndPurchase->SalesPrice)."',
				Vendor     = '".mysqli_escape_string($dbh, $d->SalesAndPurchase->PrefVendorRef->FullName)."',
				DTCreated  = '".mysqli_escape_string($dbh, $d->TimeCreated)."', 
				DTModified = '".mysqli_escape_string($dbh, $d->TimeModified)."' 
			WHERE Sku = '".$d->Name."'");
//			)

//		WHERE QBListID = '".$d->ListID."'");
			
/*			
		{ // item does not exist
			mysqli_query($dbh, "INSERT INTO	qw_item ( QBListID, EdSeq, Sku, Title, Vendor, VCost, SPrice, DTCreated, DTModified ) 
			VALUES (
			'" . mysqli_escape_string($dbh, $d->ListID) . "',
			'" . mysqli_escape_string($dbh, $d->EditSequence) . "',
			'" . mysqli_escape_string($dbh, $d->Name) . "',
			'" . mysqli_escape_string($dbh, $d->SalesAndPurchase->SalesDesc) . "',
			'" . mysqli_escape_string($dbh, $d->SalesAndPurchase->PrefVendorRef->FullName) . "',
			'" . mysqli_escape_string($dbh, $d->SalesAndPurchase->PurchaseCost) . "',
			'" . mysqli_escape_string($dbh, $d->SalesAndPurchase->SalesPrice) . "',
			'" . mysqli_escape_string($dbh, $d->TimeCreated) . "',
			'" . mysqli_escape_string($dbh, $d->TimeModified) . "'
			)");

*/		
/*		
		mysqli_query($dbh, "UPDATE qw_item SET 
			EdSeq      ='" . mysqli_escape_string($dbh, $d->EditSequence) . "', 
			Title      ='" . mysqli_escape_string($dbh, $d->SalesAndPurchase->SalesDesc) . "', 
			VCost      ='" . mysqli_escape_string($dbh, $d->SalesAndPurchase->PurchaseCost) . "', 
			SPrice     ='" . mysqli_escape_string($dbh, $d->SalesAndPurchase->SalesPrice) . "', 
			DTModified ='" . mysqli_escape_string($dbh, $d->TimeModified) . "' 
		WHERE QBListID = '".$d->ListID."'");
*/

//			}
		
//		}

/*
$xmlstr = simplexml_load_string($xml);	
	
foreach($xmlstr->QBXMLMsgsRs->ItemNonInventoryQueryRs->ItemNonInventoryRet as $d){
 // perform check
	mysqli_query($dbh, "UPDATE qw_item SET 
		QBListID   = '$d->ListID',
		DTCreated  = '$d->TimeCreated', 
		DTModified = '$d->TimeModified', 
		Vendor     = '$d->PrefVendorRef->FullName'
	WHERE Sku = '$d->Name'");
	}	
	*/
	
	
		// mysqli_query($dbh, "INSERT INTO wc_consolisku 
		// SET quickbooks_listid = '" . mysqli_escape_string($dbh, $idents['ListID']) . "', 
			// quickbooks_editsequence = '" . mysqli_escape_string($dbh, $idents['EditSequence']) . "'
		// WHERE id = " . (int) $ID);

    }



/////////////////////////////////////////         Query Inventory Item D2D //////////////////////////////////////////////////////////////////////////////////////
function _quickbooks_item_invertory_query_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
	{

//*
	$xml = '<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="'.$version.'"?>
	<QBXML>
		<QBXMLMsgsRq onError="continueOnError">
			<ItemInventoryQueryRq requestID="'.$requestID.'">
				<ActiveStatus>All</ActiveStatus>
			</ItemInventoryQueryRq>
		</QBXMLMsgsRq>
	</QBXML>';
//// */

	return $xml;
    }

    /**
     * Receive a response from QuickBooks 
     */
function _quickbooks_item_invertory_query_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
    {   //global $dbh;

		$content = $xml;
		$fp = fopen($_SERVER['DOCUMENT_ROOT'] . '/qbw/' . I_ITEMS_XML,"w") or die("Unable to open file!");
		fwrite($fp, $content);
		fclose($fp);


    }


















////////////////////////////////////////////////         Query Customer //////////////////////////////////////////////////////////////////////////////////
function _quickbooks_customer_query_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
	{
	$xml = '<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="'.$version.'"?>
	<QBXML>
		<QBXMLMsgsRq onError="continueOnError">
			<CustomerQueryRq requestID="'.$requestID.'" />
		</QBXMLMsgsRq>
	</QBXML>';
	return $xml;
    }

    /**
     * Receive a response from QuickBooks 
     */
function _quickbooks_customer_query_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
    {   global $dbh;


		$content = $xml;
		$fp = fopen($_SERVER['DOCUMENT_ROOT'] . '/qbw/' . QB_CUSTOMER_XML,"w") or die("Unable to open file!");
		fwrite($fp, $content);
		fclose($fp);
    }

















////////////////////////////////////////////////         Query Estimate //////////////////////////////////////////////////////////////////////////////////
function _quickbooks_estimate_query_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
	{
	$xml = '<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="'.$version.'"?>
	<QBXML>
		<QBXMLMsgsRq onError="continueOnError">
			<EstimateQueryRq requestID="'.$requestID.'" />
		</QBXMLMsgsRq>
	</QBXML>';
	return $xml;
    }

function _quickbooks_estimate_query_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
	{   global $dbh;
		$content = $xml;
		$fp = fopen($_SERVER['DOCUMENT_ROOT'] . '/qbw/' . QBESTIMATE_XML,"w") or die("Unable to open file!");
		fwrite($fp, $content);
		fclose($fp);
	return;
	}
















////////////////////////////////////////////////         Query Sales Order //////////////////////////////////////////////////////////////////////////////////
function _quickbooks_salesorder_query_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
	{

	$dfr = date("Y-m-d", strtotime("-9 week"));
	$dto = date("Y-m-d");

	$xml = '<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="'.$version.'"?>
	<QBXML>
		<QBXMLMsgsRq onError="continueOnError">
			<SalesOrderQueryRq requestID="'.$requestID.'">
				<FromModifiedDate>'.$dfr.'</FromModifiedDate>
				<ToModifiedDate>'.$dto.'</ToModifiedDate>
			</SalesOrderQueryRq>
		</QBXMLMsgsRq>
	</QBXML>';

	





/*
	$xml = '<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="'.$version.'"?>
	<QBXML>
		<QBXMLMsgsRq onError="continueOnError">
			<SalesOrderQueryRq requestID="'.$requestID.'" />
		</QBXMLMsgsRq>
	</QBXML>';
	*/
	
	return $xml;
    }

function _quickbooks_salesorder_query_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
	{   global $dbh;
		$content = $xml;
		$fp = fopen($_SERVER['DOCUMENT_ROOT'] . '/qbw/' . SALES_ORDER_XML,"w") or die("Unable to open file!");
		fwrite($fp, $content);
		fclose($fp);
	return;
	}



function _quickbooks_salesorder_query_request_w_items($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
	{
	$dfr = date("Y-m-d", strtotime("-9 week"));
	$dto = date("Y-m-d");

	$xml = '<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="'.$version.'"?>
	<QBXML>
		<QBXMLMsgsRq onError="continueOnError">
			<SalesOrderQueryRq requestID="'.$requestID.'">
				<ModifiedDateRangeFilter>
					<FromModifiedDate>'.$dfr.'</FromModifiedDate>
					<ToModifiedDate>'.$dto.'</ToModifiedDate>
				</ModifiedDateRangeFilter>
				<IncludeLineItems>true</IncludeLineItems>
			</SalesOrderQueryRq>
		</QBXMLMsgsRq>
	</QBXML>';


/*
	$xml = '<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="'.$version.'"?>
	<QBXML>
		<QBXMLMsgsRq onError="continueOnError">
			<SalesOrderQueryRq requestID="'.$requestID.'">

            <IncludeLineItems>true</IncludeLineItems>

			</SalesOrderQueryRq>
		</QBXMLMsgsRq>
	</QBXML>';
*/	
	
	return $xml;
    }

function _quickbooks_salesorder_query_response_w_items($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
	{   global $dbh;
		$content = $xml;
		$fp = fopen($_SERVER['DOCUMENT_ROOT'] . '/qbw/' . SALES_ORDER_XML,"w") or die("Unable to open file!");
		fwrite($fp, $content);
		fclose($fp);
	return;
	}


////////////////////////////////////////////////         Query Purchase Order //////////////////////////////////////////////////////////////////////////////////
function _quickbooks_purchaseorder_import_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
	{
	$dfr = date("Y-m-d", strtotime("-9 week"));
	$dto = date("Y-m-d");

	$xml = '<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="'.$version.'"?>
	<QBXML>
		<QBXMLMsgsRq onError="continueOnError">
			<PurchaseOrderQueryRq requestID="'.$requestID.'">
				<ModifiedDateRangeFilter>
					<FromModifiedDate>'.$dfr.'</FromModifiedDate>
					<ToModifiedDate>'.$dto.'</ToModifiedDate>
				</ModifiedDateRangeFilter>
				<IncludeLineItems>true</IncludeLineItems>
			</PurchaseOrderQueryRq>
		</QBXMLMsgsRq>
	</QBXML>';


/*
	$xml = '<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="'.$version.'"?>
	<QBXML>
		<QBXMLMsgsRq onError="continueOnError">
			<PurchaseOrderQueryRq requestID="'.$requestID.'">

            <IncludeLineItems>true</IncludeLineItems>

			</PurchaseOrderQueryRq>
		</QBXMLMsgsRq>
	</QBXML>';
*/	
	return $xml;
    }


function _quickbooks_purchaseorder_import_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
	{   global $dbh;
		$content = $xml;
		$fp = fopen($_SERVER['DOCUMENT_ROOT'] . '/qbw/' . PURCHASE_ORDER_XML,"w") or die("Unable to open file!");
		fwrite($fp, $content);
		fclose($fp);
	return;
	}















////////////////////////////////////////////////         Query Payment //////////////////////////////////////////////////////////////////////////////////
function _quickbooks_payment_query_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
	{
	$xml = '<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="'.$version.'"?>
	<QBXML>
		<QBXMLMsgsRq onError="continueOnError">
			<ReceivePaymentQueryRq requestID="'.$requestID.'" />
		</QBXMLMsgsRq>
	</QBXML>';
	return $xml;
    }

function _quickbooks_payment_query_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
	{   global $dbh;
		$content = $xml;
		$fp = fopen($_SERVER['DOCUMENT_ROOT'] . '/qbw/' . QBPAYMENT_XML,"w") or die("Unable to open file!");
		fwrite($fp, $content);
		fclose($fp);
	return;
	}












////////////////////////////////////////////////         Add Discount //////////////////////////////////////////////////////////////////////////////////
function _quickbooks_itemDiscountAdd_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
	{ global $dbh;

	$record = mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM wc_coupon WHERE id = " . (int) $ID, MYSQLI_ASSOC));

	$xml = '<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="'.$version.'"?>
<QBXML>
	<QBXMLMsgsRq onError="continueOnError">
		<ItemDiscountAddRq>
			<ItemDiscountAdd> <!-- required -->
				<Name>' . mysqli_escape_string($dbh, $record['Code'] ) . '</Name>
				<DiscountRate>' . mysqli_escape_string($dbh, $record['Amount'] ) . '</DiscountRate>
				<AccountRef>
					<FullName>Sales Discounts</FullName>
				</AccountRef>
			</ItemDiscountAdd>
		</ItemDiscountAddRq>
	</QBXMLMsgsRq>
</QBXML>';

	return $xml;
    }

function _quickbooks_itemDiscountAdd_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
	{
	return;		
	}









////////////////////////////        Endesign // // // //



















/*


        $xml = '<?xml version="1.0" encoding="utf-8"?>
            <?qbxml version="'.$version.'"?>
 
<QBXML>
        <QBXMLMsgsRq onError="stopOnError">
				<ItemNonInventoryAddRq requestID="' . $requestID . '">
                        <ItemNonInventoryAdd> <!-- required -->
                                <Name >DBV-M372</Name> <!-- required -->
                                <BarCode> <!-- optional -->
                                        <BarCodeValue >STRTYPE</BarCodeValue> <!-- optional -->
                                        <AssignEvenIfUsed >BOOLTYPE</AssignEvenIfUsed> <!-- optional -->
                                        <AllowOverride >BOOLTYPE</AllowOverride> <!-- optional -->
                                </BarCode>
                                <IsActive >BOOLTYPE</IsActive> <!-- optional -->
                                <ParentRef> <!-- optional -->
                                        <ListID >IDTYPE</ListID> <!-- optional -->
                                        <FullName >STRTYPE</FullName> <!-- optional -->
                                </ParentRef>
                                <ClassRef> <!-- optional -->
                                        <ListID >IDTYPE</ListID> <!-- optional -->
                                        <FullName >STRTYPE</FullName> <!-- optional -->
                                </ClassRef>
                                <ManufacturerPartNumber >STRTYPE</ManufacturerPartNumber> <!-- optional -->
                                <UnitOfMeasureSetRef> <!-- optional -->
                                        <ListID >IDTYPE</ListID> <!-- optional -->
                                        <FullName >STRTYPE</FullName> <!-- optional -->
                                </UnitOfMeasureSetRef>
                                <IsTaxIncluded >BOOLTYPE</IsTaxIncluded> <!-- optional -->
                                <SalesTaxCodeRef> <!-- optional -->
                                        <ListID >IDTYPE</ListID> <!-- optional -->
                                        <FullName >STRTYPE</FullName> <!-- optional -->
                                </SalesTaxCodeRef>
                                <!-- BEGIN OR -->
                                        <SalesOrPurchase> <!-- optional -->
                                                <Desc >STRTYPE</Desc> <!-- optional -->
                                                <!-- BEGIN OR -->
                                                        <Price >PRICETYPE</Price> <!-- optional -->
                                                <!-- OR -->
                                                        <PricePercent >PERCENTTYPE</PricePercent> <!-- optional -->
                                                <!-- END OR -->
                                                <AccountRef> <!-- optional -->
                                                        <ListID >IDTYPE</ListID> <!-- optional -->
                                                        <FullName >STRTYPE</FullName> <!-- optional -->
                                                </AccountRef>
                                        </SalesOrPurchase>
                                <!-- OR -->
                                        <SalesAndPurchase> <!-- optional -->
                                                <SalesDesc >STRTYPE</SalesDesc> <!-- optional -->
                                                <SalesPrice >PRICETYPE</SalesPrice> <!-- optional -->
                                                <IncomeAccountRef> <!-- optional -->
                                                        <ListID >IDTYPE</ListID> <!-- optional -->
                                                        <FullName >STRTYPE</FullName> <!-- optional -->
                                                </IncomeAccountRef>
                                                <PurchaseDesc >STRTYPE</PurchaseDesc> <!-- optional -->
                                                <PurchaseCost >PRICETYPE</PurchaseCost> <!-- optional -->
                                                <PurchaseTaxCodeRef> <!-- optional -->
                                                        <ListID >IDTYPE</ListID> <!-- optional -->
                                                        <FullName >STRTYPE</FullName> <!-- optional -->
                                                </PurchaseTaxCodeRef>
                                                <ExpenseAccountRef> <!-- optional -->
                                                        <ListID >IDTYPE</ListID> <!-- optional -->
                                                        <FullName >STRTYPE</FullName> <!-- optional -->
                                                </ExpenseAccountRef>
                                                <PrefVendorRef> <!-- optional -->
                                                        <ListID >IDTYPE</ListID> <!-- optional -->
                                                        <FullName >STRTYPE</FullName> <!-- optional -->
                                                </PrefVendorRef>
                                        </SalesAndPurchase>
                                <!-- END OR -->
                                <ExternalGUID >GUIDTYPE</ExternalGUID> <!-- optional -->
                        </ItemNonInventoryAdd>
                        <IncludeRetElement >STRTYPE</IncludeRetElement> <!-- optional, may repeat -->
                </ItemNonInventoryAddRq>

                <ItemNonInventoryAddRs statusCode="INTTYPE" statusSeverity="STRTYPE" statusMessage="STRTYPE">
                        <ItemNonInventoryRet> <!-- optional -->
                                <ListID >IDTYPE</ListID> <!-- required -->
                                <TimeCreated >DATETIMETYPE</TimeCreated> <!-- required -->
                                <TimeModified >DATETIMETYPE</TimeModified> <!-- required -->
                                <EditSequence >STRTYPE</EditSequence> <!-- required -->
                                <Name >STRTYPE</Name> <!-- required -->
                                <FullName >STRTYPE</FullName> <!-- required -->
                                <BarCodeValue >STRTYPE</BarCodeValue> <!-- optional -->
                                <IsActive >BOOLTYPE</IsActive> <!-- optional -->
                                <ClassRef> <!-- optional -->
                                        <ListID >IDTYPE</ListID> <!-- optional -->
                                        <FullName >STRTYPE</FullName> <!-- optional -->
                                </ClassRef>
                                <ParentRef> <!-- optional -->
                                        <ListID >IDTYPE</ListID> <!-- optional -->
                                        <FullName >STRTYPE</FullName> <!-- optional -->
                                </ParentRef>
                                <Sublevel >INTTYPE</Sublevel> <!-- required -->
                                <ManufacturerPartNumber >STRTYPE</ManufacturerPartNumber> <!-- optional -->
                                <UnitOfMeasureSetRef> <!-- optional -->
                                        <ListID >IDTYPE</ListID> <!-- optional -->
                                        <FullName >STRTYPE</FullName> <!-- optional -->
                                </UnitOfMeasureSetRef>
                                <IsTaxIncluded >BOOLTYPE</IsTaxIncluded> <!-- optional -->
                                <SalesTaxCodeRef> <!-- optional -->
                                        <ListID >IDTYPE</ListID> <!-- optional -->
                                        <FullName >STRTYPE</FullName> <!-- optional -->
                                </SalesTaxCodeRef>
                                <!-- BEGIN OR -->
                                        <SalesOrPurchase> <!-- optional -->
                                                <Desc >STRTYPE</Desc> <!-- optional -->
                                                <!-- BEGIN OR -->
                                                        <Price >PRICETYPE</Price> <!-- optional -->
                                                <!-- OR -->
                                                        <PricePercent >PERCENTTYPE</PricePercent> <!-- optional -->
                                                <!-- END OR -->
                                                <AccountRef> <!-- optional -->
                                                        <ListID >IDTYPE</ListID> <!-- optional -->
                                                        <FullName >STRTYPE</FullName> <!-- optional -->
                                                </AccountRef>
                                        </SalesOrPurchase>
                                <!-- OR -->
                                        <SalesAndPurchase> <!-- optional -->
                                                <SalesDesc >STRTYPE</SalesDesc> <!-- optional -->
                                                <SalesPrice >PRICETYPE</SalesPrice> <!-- optional -->
                                                <IncomeAccountRef> <!-- optional -->
                                                        <ListID >IDTYPE</ListID> <!-- optional -->
                                                        <FullName >STRTYPE</FullName> <!-- optional -->
                                                </IncomeAccountRef>
                                                <PurchaseDesc >STRTYPE</PurchaseDesc> <!-- optional -->
                                                <PurchaseCost >PRICETYPE</PurchaseCost> <!-- optional -->
                                                <PurchaseTaxCodeRef> <!-- optional -->
                                                        <ListID >IDTYPE</ListID> <!-- optional -->
                                                        <FullName >STRTYPE</FullName> <!-- optional -->
                                                </PurchaseTaxCodeRef>
                                                <ExpenseAccountRef> <!-- optional -->
                                                        <ListID >IDTYPE</ListID> <!-- optional -->
                                                        <FullName >STRTYPE</FullName> <!-- optional -->
                                                </ExpenseAccountRef>
                                                <PrefVendorRef> <!-- optional -->
                                                        <ListID >IDTYPE</ListID> <!-- optional -->
                                                        <FullName >STRTYPE</FullName> <!-- optional -->
                                                </PrefVendorRef>
                                        </SalesAndPurchase>
                                <!-- END OR -->
                                <ExternalGUID >GUIDTYPE</ExternalGUID> <!-- optional -->
                                <DataExtRet> <!-- optional, may repeat -->
                                        <OwnerID >GUIDTYPE</OwnerID> <!-- optional -->
                                        <DataExtName >STRTYPE</DataExtName> <!-- required -->
                                        <!-- DataExtType may have one of the following values: AMTTYPE, DATETIMETYPE, INTTYPE, PERCENTTYPE, PRICETYPE, QUANTYPE, STR1024TYPE, STR255TYPE -->
                                        <DataExtType >ENUMTYPE</DataExtType> <!-- required -->
                                        <DataExtValue >STRTYPE</DataExtValue> <!-- required -->
                                </DataExtRet>
                        </ItemNonInventoryRet>
                        <ErrorRecovery> <!-- optional -->
                                <!-- BEGIN OR -->
                                        <ListID >IDTYPE</ListID> <!-- optional -->
                                <!-- OR -->
                                        <OwnerID >GUIDTYPE</OwnerID> <!-- optional -->
                                <!-- OR -->
                                        <TxnID >IDTYPE</TxnID> <!-- optional -->
                                <!-- END OR -->
                                <TxnNumber >INTTYPE</TxnNumber> <!-- optional -->
                                <EditSequence >STRTYPE</EditSequence> <!-- optional -->
                                <ExternalGUID >GUIDTYPE</ExternalGUID> <!-- optional -->
                        </ErrorRecovery>
                </ItemNonInventoryAddRs>
        </QBXMLMsgsRq>
</QBXML>





*/



/**
 * Receive a response from QuickBooks 
 * 
 * @param string $requestID					The requestID you passed to QuickBooks previously
 * @param string $action					The action that was performed (CustomerAdd in this case)
 * @param mixed $ID							The unique identifier of the record
 * @param array $extra			
 * @param string $err						An error message, assign a valid to $err if you want to report an error
 * @param integer $last_action_time			A unix timestamp (seconds) indicating when the last action of this type was dequeued (i.e.: for CustomerAdd, the last time a customer was added, for CustomerQuery, the last time a CustomerQuery ran, etc.)
 * @param integer $last_actionident_time	A unix timestamp (seconds) indicating when the combination of this action and ident was dequeued (i.e.: when the last time a CustomerQuery with ident of get-new-customers was dequeued)
 * @param string $xml						The complete qbXML response
 * @param array $idents						An array of identifiers that are contained in the qbXML response
 * @return void
 */
//function _quickbooks_customer_add_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
//{	
	// Great, customer $ID has been added to QuickBooks with a QuickBooks 
	//	ListID value of: $idents['ListID']
	// 
	// We probably want to store that ListID in our database, so we can use it 
	//	later. (You'll need to refer to the customer by either ListID or Name 
	//	in other requests, say, to update the customer or to add an invoice for 
	//	the customer. 
	
	/*
	mysql_query("UPDATE your_customer_table SET quickbooks_listid = '" . mysql_escape_string($idents['ListID']) . "' WHERE your_customer_ID_field = " . (int) $ID);
	*/
//}

/** 
 * 
 * @param string $requestID					You should include this in your qbXML request (it helps with debugging later)
 * @param string $action					The QuickBooks action being performed (CustomerAdd in this case)
 * @param mixed $ID							The unique identifier for the record (maybe a customer ID number in your database or something)
 * @param array $extra						Any extra data you included with the queued item when you queued it up
 * @param string $err						An error message, assign a value to $err if you want to report an error
 * @param integer $last_action_time			A unix timestamp (seconds) indicating when the last action of this type was dequeued (i.e.: for CustomerAdd, the last time a customer was added, for CustomerQuery, the last time a CustomerQuery ran, etc.)
 * @param integer $last_actionident_time	A unix timestamp (seconds) indicating when the combination of this action and ident was dequeued (i.e.: when the last time a CustomerQuery with ident of get-new-customers was dequeued)
 * @param float $version					The max qbXML version your QuickBooks version supports
 * @param string $locale					
 * @return string							A valid qbXML request
 */
function _quickbooks_salesreceipt_add_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	/*
		<CustomerRef>
			<ListID>80003579-1231522938</ListID>
		</CustomerRef>	
	*/
	
	$xml = '<?xml version="1.0" encoding="utf-8"?>
		<?qbxml version="2.0"?>
		<QBXML>
			<QBXMLMsgsRq onError="stopOnError">
				<SalesReceiptAddRq requestID="' . $requestID . '">
					<SalesReceiptAdd>
						<CustomerRef>
							<FullName>Keith Palmer Jr.</FullName>
						</CustomerRef>
						<TxnDate>2009-01-09</TxnDate>
						<RefNumber>16466</RefNumber>
						<BillAddress>
							<Addr1>Keith Palmer Jr.</Addr1>
							<Addr3>134 Stonemill Road</Addr3>
							<City>Storrs-Mansfield</City>
							<State>CT</State>
							<PostalCode>06268</PostalCode>
							<Country>United States</Country>
						</BillAddres>
						<SalesReceiptLineAdd>
							<ItemRef>
								<FullName>Gift Certificate</FullName>
							</ItemRef>
							<Desc>$25.00 gift certificate</Desc>
							<Quantity>1</Quantity>
							<Rate>25.00</Rate>
							<SalesTaxCodeRef>
								<FullName>NON</FullName>
							</SalesTaxCodeRef>
						</SalesReceiptLineAdd>
						<SalesReceiptLineAdd>
							<ItemRef>
								<FullName>Book</FullName>
							</ItemRef>
							<Desc>The Hitchhiker\'s Guide to the Galaxy</Desc>
							<Amount>19.95</Amount>
							<SalesTaxCodeRef>
								<FullName>TAX</FullName>
							</SalesTaxCodeRef>
						</SalesReceiptLineAdd>
					</SalesReceiptAdd>
				</SalesReceiptAddRq>
			</QBXMLMsgsRq>
		</QBXML>';
	
	return $xml;
}



/**
 * Receive a response from QuickBooks 
 * 
 * @param string $requestID					The requestID you passed to QuickBooks previously
 * @param string $action					The action that was performed (CustomerAdd in this case)
 * @param mixed $ID							The unique identifier of the record
 * @param array $extra			
 * @param string $err						An error message, assign a valid to $err if you want to report an error
 * @param integer $last_action_time			A unix timestamp (seconds) indicating when the last action of this type was dequeued (i.e.: for CustomerAdd, the last time a customer was added, for CustomerQuery, the last time a CustomerQuery ran, etc.)
 * @param integer $last_actionident_time	A unix timestamp (seconds) indicating when the combination of this action and ident was dequeued (i.e.: when the last time a CustomerQuery with ident of get-new-customers was dequeued)
 * @param string $xml						The complete qbXML response
 * @param array $idents						An array of identifiers that are contained in the qbXML response
 * @return void
 */
function _quickbooks_salesreceipt_add_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{	
	// Great, sales receipt $ID has been added to QuickBooks with a QuickBooks 
	//	TxnID value of: $idents['TxnID']
	//
	// The QuickBooks EditSequence is: $idents['EditSequence']
	// 
	// We probably want to store that TxnID in our database, so we can use it 
	//	later. You might also want to store the EditSequence. If you wanted to 
	//	issue a SalesReceiptMod to modify the sales receipt somewhere down the 
	//	road, you'd need to refer to the sales receipt using the TxnID and 
	//	EditSequence 
}

/**
 * Catch and handle a "that string is too long for that field" error (err no. 3070) from QuickBooks
 * 
 * @param string $requestID			
 * @param string $action
 * @param mixed $ID
 * @param mixed $extra
 * @param string $err
 * @param string $xml
 * @param mixed $errnum
 * @param string $errmsg
 * @return void
 */
function _quickbooks_error_stringtoolong($requestID, $user, $action, $ID, $extra, &$err, $xml, $errnum, $errmsg)
{
	mail('your-email@your-domain.com', 
		'QuickBooks error occured!', 
		'QuickBooks thinks that ' . $action . ': ' . $ID . ' has a value which will not fit in a QuickBooks field...');
}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /*-------------------------------------------------------new---------------------------------------*/

    /**
     * Generate a qbXML response to add a particular Items to QuickBooks
     */
    /**
     * Generate a qbXML response to add a particular Estimate to QuickBooks
     */


    /** 
     * 
     * @param string $requestID                 You should include this in your qbXML request (it helps with debugging later)
     * @param string $action                    The QuickBooks action being performed (CustomerAdd in this case)
     * @param mixed $ID                         The unique identifier for the record (maybe a customer ID number in your database or something)
     * @param array $extra                      Any extra data you included with the queued item when you queued it up
     * @param string $err                       An error message, assign a value to $err if you want to report an error
     * @param integer $last_action_time         A unix timestamp (seconds) indicating when the last action of this type was dequeued (i.e.: for CustomerAdd, the last time a customer was added, for CustomerQuery, the last time a CustomerQuery ran, etc.)
     * @param integer $last_actionident_time    A unix timestamp (seconds) indicating when the combination of this action and ident was dequeued (i.e.: when the last time a CustomerQuery with ident of get-new-customers was dequeued)
     * @param float $version                    The max qbXML version your QuickBooks version supports
     * @param string $locale                    
     * @return string                           A valid qbXML request
     */
    function _quickbooks_salesreceipt_add_request_________($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
    {
        /*
            <CustomerRef>
                <ListID>80003579-1231522938</ListID>
            </CustomerRef>  
        */

        $xml = '<?xml version="1.0" encoding="utf-8"?>
            <?qbxml version="2.0"?>
            <QBXML>
                <QBXMLMsgsRq onError="stopOnError">
                    <SalesReceiptAddRq requestID="' . $requestID . '">
                        <SalesReceiptAdd>
                            <CustomerRef>
                                <FullName>Keith Palmer Jr.</FullName>
                            </CustomerRef>
                            <TxnDate>2009-01-09</TxnDate>
                            <RefNumber>16466</RefNumber>
                            <BillAddress>
                                <Addr1>Keith Palmer Jr.</Addr1>
                                <Addr3>134 Stonemill Road</Addr3>
                                <City>Storrs-Mansfield</City>
                                <State>CT</State>
                                <PostalCode>06268</PostalCode>
                                <Country>United States</Country>
                            </BillAddres>
                            <SalesReceiptLineAdd>
                                <ItemRef>
                                    <FullName>Gift Certificate</FullName>
                                </ItemRef>
                                <Desc>$25.00 gift certificate</Desc>
                                <Quantity>1</Quantity>
                                <Rate>25.00</Rate>
                                <SalesTaxCodeRef>
                                    <FullName>NON</FullName>
                                </SalesTaxCodeRef>
                            </SalesReceiptLineAdd>
                            <SalesReceiptLineAdd>
                                <ItemRef>
                                    <FullName>Book</FullName>
                                </ItemRef>
                                <Desc>The Hitchhiker\'s Guide to the Galaxy</Desc>
                                <Amount>19.95</Amount>
                                <SalesTaxCodeRef>
                                    <FullName>TAX</FullName>
                                </SalesTaxCodeRef>
                            </SalesReceiptLineAdd>
                        </SalesReceiptAdd>
                    </SalesReceiptAddRq>
                </QBXMLMsgsRq>
            </QBXML>';

        return $xml;
    }

    /**
     * Receive a response from QuickBooks 
     */
    function _quickbooks_salesreceipt_add_response____________($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
    {   

    }

    //------------ INVOICE ------------------------------

    function _quickbooks_invoice_add_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
    {

        $xml = '<?xml version="1.0" encoding="utf-8"?>
                    <?qbxml version="2.0"?>
                    <QBXML>
                        <QBXMLMsgsRq onError="stopOnError">
                            <InvoiceAddRq requestID="' . $requestID . '">
                                <InvoiceAdd>
                                    <CustomerRef>
                                        <ListID>90001-1263558758</ListID>
                                        <FullName>BhagyaMazire</FullName>
                                    </CustomerRef>
                                    <TxnDate>2010-01-15</TxnDate>
                                    <RefNumber>21011</RefNumber>
                                    <BillAddress>
                                        <Addr1>ConsoliBYTE, LLC</Addr1>
                                        <Addr2>134 Stonemill Road</Addr2>
                                        <Addr3 />
                                        <City>Mansfield</City>
                                        <State>CT</State>
                                        <PostalCode>06268</PostalCode>
                                        <Country>United States</Country>
                                    </BillAddress>
                                    <ShipAddress>
                                        <Addr1>ConsoliBYTE, LLC</Addr1>
                                        <Addr2>Attn: Keith Palmer</Addr2>
                                        <Addr3>56 Cowles Road</Addr3>
                                        <City>Willington</City>
                                        <State>CT</State>
                                        <PostalCode>06279</PostalCode>
                                        <Country>United States</Country>
                                    </ShipAddress>
                                    <TermsRef>
                                        <FullName>Net 30</FullName>
                                    </TermsRef>
                                    <SalesRepRef>
                                        <FullName>KRP</FullName>
                                    </SalesRepRef>
                                    <Memo>Test memo goes here.</Memo>
                                    <InvoiceLineAdd>
                                        <ItemRef>
                                            <FullName>test</FullName>
                                        </ItemRef>
                                        <Desc>Test item description</Desc>
                                        <Quantity>1.00000</Quantity>
                                        <Rate>15.00000</Rate>
                                    </InvoiceLineAdd>
                                </InvoiceAdd>
                            </InvoiceAddRq>
                        </QBXMLMsgsRq>
                    </QBXML>';

        return $xml;
    }
    /**
     * Receive a response from QuickBooks 
     */
    function _quickbooks_invoice_add_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
    {   

    }

    //-------------- PURCHASE ORDER --------------------------------------
    function _quickbooks_purchaseorder_add_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
    {

        $xml = '<?xml version="1.0" encoding="utf-8"?>
                    <?qbxml version="2.0"?>
                    <QBXML>
                        <QBXMLMsgsRq onError="stopOnError">
                            <PurchaseOrderAddRq requestID="' . $requestID . '">
                              <PurchaseOrderAdd>
                                <VendorRef>
                                  <FullName>Test Vendor</FullName>
                                </VendorRef>
                                <TxnDate>2013-01-02</TxnDate>
                                <RefNumber>3434</RefNumber>
                                <PurchaseOrderLineAdd>
                                  <ItemRef>
                                    <FullName>My Item Name</FullName>
                                  </ItemRef>
                                  <Desc>Test description.</Desc>
                                  <Quantity>5</Quantity>
                                  <Rate>29.95</Rate>
                                </PurchaseOrderLineAdd>
                              </PurchaseOrderAdd>
                            </PurchaseOrderAddRq>
                        </QBXMLMsgsRq>
                    </QBXML>';

        return $xml;
    }
    /**
     * Receive a response from QuickBooks 
     */
    function _quickbooks_purchaseorder_add_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
    {   

    }




    /**
     * Build a request to import sales orders already in QuickBooks into our application
     */
    function _quickbooks_salesorder_import_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
    {
        // Iterator support (break the result set into small chunks)
        $attr_iteratorID = '';
        $attr_iterator = ' iterator="Start" ';
        if (empty($extra['iteratorID']))
        {
            // This is the first request in a new batch
            $last = _quickbooks_get_last_run($user, $action);
            _quickbooks_set_last_run($user, $action);           // Update the last run time to NOW()

            // Set the current run to $last
            _quickbooks_set_current_run($user, $action, $last);
        }
        else
        {
            // This is a continuation of a batch
            $attr_iteratorID = ' iteratorID="' . $extra['iteratorID'] . '" ';
            $attr_iterator = ' iterator="Continue" ';

            $last = _quickbooks_get_current_run($user, $action);
        }

        // Build the request
        $xml = '<?xml version="1.0" encoding="utf-8"?>
            <?qbxml version="' . $version . '"?>
            <QBXML>
                <QBXMLMsgsRq onError="stopOnError">
                    <SalesOrderQueryRq ' . $attr_iterator . ' ' . $attr_iteratorID . ' requestID="' . $requestID . '">
                        <MaxReturned>' . QB_QUICKBOOKS_MAX_RETURNED . '</MaxReturned>
                        <ModifiedDateRangeFilter>
                            <FromModifiedDate>' . $last . '</FromModifiedDate>
                        </ModifiedDateRangeFilter>
                        <IncludeLineItems>true</IncludeLineItems>
                        <OwnerID>0</OwnerID>
                    </SalesOrderQueryRq>    
                </QBXMLMsgsRq>
            </QBXML>';

        return $xml;
    }

    /**
     * Get the last date/time the QuickBooks sync ran
     * 
     * @param string $user      The web connector username 
     * @return string           A date/time in this format: "yyyy-mm-dd hh:ii:ss"
     */
    function _quickbooks_get_last_run($user, $action)
    {
        $type = null;
        $opts = null;
        return QuickBooks_Utilities::configRead(QB_QUICKBOOKS_DSN, $user, md5(__FILE__), QB_QUICKBOOKS_CONFIG_LAST . '-' . $action, $type, $opts);
    }

    /**
     * Set the last date/time the QuickBooks sync ran to NOW
     * 
     * @param string $user
     * @return boolean
     */
    function _quickbooks_set_last_run($user, $action, $force = null)
    {
        $value = date('Y-m-d') . 'T' . date('H:i:s');

        if ($force)
        {
            $value = date('Y-m-d', strtotime($force)) . 'T' . date('H:i:s', strtotime($force));
        }

        return QuickBooks_Utilities::configWrite(QB_QUICKBOOKS_DSN, $user, md5(__FILE__), QB_QUICKBOOKS_CONFIG_LAST . '-' . $action, $value);
    }

    /**
     * 
     * 
     */
    function _quickbooks_get_current_run($user, $action)
    {
        $type = null;
        $opts = null;
        return QuickBooks_Utilities::configRead(QB_QUICKBOOKS_DSN, $user, md5(__FILE__), QB_QUICKBOOKS_CONFIG_CURR . '-' . $action, $type, $opts);  
    }

    /**
     * 
     * 
     */
    function _quickbooks_set_current_run($user, $action, $force = null)
    {
        $value = date('Y-m-d') . 'T' . date('H:i:s');

        if ($force)
        {
            $value = date('Y-m-d', strtotime($force)) . 'T' . date('H:i:s', strtotime($force));
        }

        return QuickBooks_Utilities::configWrite(QB_QUICKBOOKS_DSN, $user, md5(__FILE__), QB_QUICKBOOKS_CONFIG_CURR . '-' . $action, $value);   
    }







?>