<?php

function getcoords($arr, $trm, $stv=0, $sth=0){
	foreach($arr as $vk=>$vv){
		foreach($vv as $hk=>$hv){
			if($vk>=$stv AND $hk>=$sth AND preg_match("/$trm/", $hv)){ return array($vk, $hk); }
			}
		}
	return NULL;
	}


function getVcoord($arr, $trm, $rshift=0, $stv=0){
	if($rshift){ return $arr[getVcoord($arr, $trm, 0, $stv)][$rshift]; }	
	else{
		foreach($arr as $rk=>$rv){
			if($rk>=$stv AND $rv[0]==$trm){ return $rk; }
			}
		return NULL;
		}
}

function lk4cl($arr, $coords, $rshift=0, $dshift=0){
	return $arr[$coords[0]+$dshift][$coords[1]+$rshift];
}

function getWCSku($sku, $stl, $a=NULL){ global $dbh;
//	$acl=($a)?'Asm=\''.$a.'\'':'1';
//	return mysqli_fetch_assoc(mysqli_query($dbh, "SELECT * FROM wc_consolisku WHERE Sku_2020='$sku' AND Style_2020='$stl' AND ".$acl))['Sku_WC'];
	return mysqli_fetch_assoc(mysqli_query($dbh, "SELECT * FROM wc_consolisku WHERE Sku_2020='$sku' AND Style_2020='$stl' AND Asm='$a'"))['Sku_WC'];
}

function orscan($o){
	$i = $ii = 0;
	$nr = array();
	if($o){
	foreach ( $o->get_items() as $iid => $io ) { //global $mfactor;
		$pm = $io->get_product();
		
		$fctxtn = 'f_'.$pm->sku;

		$nr[$i]['sku'] = $pm->sku;
		$nr[$i]['fct'] = getfactor($pm->sku);
		$nr[$i]['fct'] = (isset($_POST[$fctxtn]))?$_POST[$fctxtn]:$nr[$i]['fct'];
		$nr[$i]['id']  = getidbysku($pm->sku);;
		$nr[$i]['qty'] = $io->get_quantity();
		$nr[$i]['ttl'] = $io->get_name();
		$nr[$i]['spr'] = $pm->price;
//		$nr[$i]['lpr'] = getlistpricebyid($nr[$i]['id']);;
		$nr[$i]['lpr'] = calclistpricebysku($pm->sku);
//		$nr[$i]['ppr'] = calcostbyid($nr[$i]['id']);
//		$nr[$i]['ppr'] = ($factor)?round($nr[$i]['lpr']*$factor, 2):calcostbysku($pm->sku);
		$nr[$i]['ppr'] = round($nr[$i]['lpr']*$nr[$i]['fct'], 2);
//		$nr[$i]['ppr'] = calcostbysku($pm->sku);
		$nr[$i]['amt'] = $pm->price * $nr[$i]['qty'];
		$i++;
		
//		echo $iid .' - '.$iobj->get_product_id() .' - ' .$pm->sku . '<br>';
		
		}
	}
	return $nr;
}

function soscan($o){
	$i = 0;
	$nr = array();
	if($o){
		foreach ( $o->SalesOrderLineRet as $ip ) {
			$nr[$i]['sku'] = $ip->ItemRef->FullName;
			if(	preg_match('/Subtot/', $nr[$i]['sku'])){
				$nr[$i]['sbttl'] = floatval($ip->Amount);
				}
			elseif(preg_match('/Discou/', $nr[$i]['sku'])){
				$nr[$i]['dscnt'] = floatval($ip->Amount);
				}
			elseif(preg_match('/Freigh/', $nr[$i]['sku'])){
				$nr[$i]['frght'] = floatval($ip->Amount);
				}
			else{
				$nr[$i]['fct'] = getfactor($nr[$i]['sku']);
				$nr[$i]['lpr'] = calclistpricebysku($nr[$i]['sku']);
				$nr[$i]['ppr'] = round($nr[$i]['lpr']*$nr[$i]['fct'], 2);
				}
			$nr[$i]['qty'] = $ip->Quantity;
			$nr[$i]['ttl'] = $ip->Desc;
			$nr[$i]['spr'] = floatval($ip->Rate);
			$nr[$i]['amt'] = floatval($ip->Amount);
			$i++;
			}
		}
	return $nr;
}

function esscan($o){
	$i = 0;
	$nr = array();
	if($o){
		foreach ( $o->TxnID as $ip ) {
			$nr[$i]['sku'] = $ip->ItemRef->FullName;
			if(	preg_match('/Subtot/', $nr[$i]['sku'])){
				$nr[$i]['sbttl'] = floatval($ip->Amount);
				}
			elseif(preg_match('/Discou/', $nr[$i]['sku'])){
				$nr[$i]['dscnt'] = floatval($ip->Amount);
				}
			elseif(preg_match('/Freigh/', $nr[$i]['sku'])){
				$nr[$i]['frght'] = floatval($ip->Amount);
				}
			else{
				$nr[$i]['fct'] = getfactor($nr[$i]['sku']);
				$nr[$i]['lpr'] = calclistpricebysku($nr[$i]['sku']);
				$nr[$i]['ppr'] = round($nr[$i]['lpr']*$nr[$i]['fct'], 2);
				}
			$nr[$i]['qty'] = $ip->Quantity;
			$nr[$i]['ttl'] = $ip->Desc;
			$nr[$i]['spr'] = floatval($ip->Rate);
			$nr[$i]['amt'] = floatval($ip->Amount);
			$i++;
			}
		}
	return $nr;
}


function poscan($o){
	$i = $ii = 0;
	$nr = array();
	if($o){
		foreach ( $o->PurchaseOrderLineRet as $ip ) {
			$nr[$i]['sku'] = $ip->ItemRef->FullName;
			$nr[$i]['qty'] = $ip->Quantity;
			$nr[$i]['fct'] = getfactor($nr[$i]['sku']);
			$nr[$i]['ttl'] = $ip->Desc;
			$nr[$i]['ppr'] = floatval($ip->Rate);
			$nr[$i]['amt'] = floatval($ip->Amount);
			$i++;
			}
		}
	return $nr;
}


function ttscan_fm($r){
	$nr = array();
	
	// $poc = getcoords($r, 'P.O. Number');
	// $nr[0]['pon']  = $r[$poc[0]+2][$poc[1]];
	// $occ = getcoords($r, 'Order No.');
	// $nr[0]['ocn']  = $r[$occ[0]+2][$occ[1]+1];
	
	$i = $ii = 0;
	$qty_index = $sku_index = $rmk_index = $dsc_index = $prc_index = $amt_index = NULL;

	while(	$r[$ii][0]  !='' OR 
			$r[$ii+1][0]!='' OR 
			$r[$ii+2][0]!='' OR 
			$r[$ii+3][0]!='' OR 
			$r[$ii][2]  !='' OR 
			$r[$ii+1][2]!='' OR 
			$r[$ii+2][2]!='' OR 
			$r[$ii+3][2]!='' OR 
			$r[$ii][4]  !='' OR 
			$r[$ii+1][4]!='' OR 
			$r[$ii+2][4]!='' OR 
			$r[$ii+3][4]!=''){

		
//		if(preg_match('/B\/O ETA 1/', $r[$ii][0])){
//		if($r[$ii][0]=='B/0 ETA 1' OR $r[$ii][0]=='B/0 ETA I' OR preg_match('/B\/O ETA 1/', $r[$ii][0])){
		if(preg_match('/ETA/', $r[$ii][0])){
//		if(preg_match('/B\/O ETA/', $r[$ii][0])){
			for($in=0; $in<15; $in++){
				if($r[$ii][$in]=='Qty')			{ $qty_index = $in; }
				if($r[$ii][$in]=='Item')		{ $sku_index = $in; }
				if($r[$ii][$in]=='Remark')		{ $rmk_index = $in; }
				if(preg_match('/Descripti/', $r[$ii][$in]))	{ $dsc_index = $in; }
				if($r[$ii][$in]=='Unit $')		{ $prc_index = $in; }
				if($r[$ii][$in]=='Amount')		{ $amt_index = $in; }
				}
			}
		// if($r[$ii][0]=='B/O ETA 1' AND $r[$ii][1]=='Qty'){
			// for($in=0; $in<15; $in++){
				// if($r[$ii][$in]=='Qty')			{ $qty_index = $in; }
				// if($r[$ii][$in]=='Item')		{ $sku_index = $in; }
				// if($r[$ii][$in]=='Description')	{ $dsc_index = $in; }
				// if($r[$ii][$in]=='Unit $')		{ $prc_index = $in; }
				// if($r[$ii][$in]=='Amount')		{ $amt_index = $in; }
				// }
			// }
		if($r[$ii][0]=='#' AND $r[$ii][1]=='Qty'){
			for($in=0; $in<15; $in++){
				if($r[$ii][$in]=='Qty')			{ $qty_index = $in; }
				if($r[$ii][$in]=='User code')	{ $sku_index = $in; }
				if($r[$ii][$in]=='Manuf. code')	{ $dsc_index = $in; }
				if($r[$ii][$in]=='Description')	{ $prc_index = $in; }
				}
			}
		
		if(($r[$ii][$qty_index]>0 OR $r[$ii][$qty_index+1]>0)   AND 
			$r[$ii][$sku_index]!='' AND $r[$ii][$sku_index]!='Residential/Jobsite' AND 
//			$r[$ii][$dsc_index]!='' AND 
			$r[$ii][$prc_index]!='' ){
			$nr[$i]['sku'] = $r[$ii][$sku_index];
			$nr[$i]['rmk'] = $r[$ii][$rmk_index];
			$nr[$i]['qty'] = ($r[$ii][$qty_index])?$r[$ii][$qty_index]:$r[$ii][$qty_index+1];
			$nr[$i]['ttl'] = $r[$ii][$dsc_index];
			$nr[$i]['ppr'] = $r[$ii][$prc_index];
			$nr[$i]['amt'] =($r[$ii][$amt_index])?$r[$ii][$amt_index]:$r[$ii][$prc_index]*$r[$ii][$qty_index];
			$i++;
			}
		if(preg_match('/Shipping Cost/', $r[$ii][$sku_index])){
			$nr[$i]['sku'] = $r[$ii][$sku_index];
			$nr[$i]['rmk'] = $r[$ii][$rmk_index];
//			$nr[$i]['qty'] = ($r[$ii][$qty_index])?$r[$ii][$qty_index]:$r[$ii][$qty_index+1];
//			$nr[$i]['ttl'] = $r[$ii][$dsc_index];
			$nr[$i]['ppr'] = $r[$ii][$dsc_index];
			$nr[$i]['amt'] = $r[$ii][$dsc_index];
			$i++;
			}
		$ii++;
		}
	return $nr;
}


function qbpscan_ghi($r){
$gr = array();

return $gr;
}

function ttscan_ghi($r){
	$i = $ii = 0;
	$nr = array();
	$strght=NULL;

	$poc = getcoords($r, 'P.O. Number');
	$nr[0]['pon']  = $r[$poc[0]+2][$poc[1]];
	$occ = getcoords($r, 'Order Number:');
	$nr[0]['ocn']  = $r[$occ[0]][$occ[1]+1];
	
	$qty_index = $sku_index = $dsc_index = $prc_index = $amt_index = 0;
	while(steppin($r, $ii, 0, 9)){
		if($r[$ii][0]=='Line Number'){
			for($in=0; $in<15; $in++){
				if($r[$ii][$in]=='Ordered')	{ $qty_index = $in; }
				if($r[$ii][$in]=='Price')	{ $prc_index = $in; }
				if($r[$ii][$in]=='Amount')	{ $amt_index = $in; }
				}
			}

		if( (is_numeric($r[$ii][$qty_index]) AND $r[$ii][$qty_index]>0) OR preg_match('/^(B\/O)/', $r[$ii][0], $m)){
			$r[$ii][$dsc_index] = preg_replace('/\"/','',$r[$ii][$dsc_index]);
			
//			echo $r[$ii][$dsc_index];
			
			if( preg_match('/^(GHI\s)([\/\-\+\w]*)(\s)(.*)/', $r[$ii][$dsc_index], $m)){
				$nr[$i]['sku'] = 'G'.$m[2];

				if(preg_match('/MULL DR/',                     $m[4])){$nr[$i]['sku'] .='MD';}
				if(preg_match('/STONE HARBOR GRAY/',           $m[4])){$nr[$i]['sku'] .='-SHG';}
				if(preg_match('/(ARCADIA )(WH.*)( SHAKER)/',   $m[4])){$nr[$i]['sku'] .='-ACW';}
				if(preg_match('/(ARCADIA )(L.*)( SHAKER)/',    $m[4])){$nr[$i]['sku'] .='-ACL';}
				if(preg_match('/(Charleston )(T.*)( Cognac)/', $m[4])){$nr[$i]['sku'] .='-CTC';}
	//			if(preg_match('/Lancaster Shaker/', $m[4])){$nr[$i]['sku'] .='-CTC';}
				if(preg_match('/NANTUCKET LIN/',               $m[4])){$nr[$i]['sku'] .='-NTL';}
				if(preg_match('/Nantucket Linen/',             $m[4])){$nr[$i]['sku'] .='-NTL';}
				if(preg_match('/New Castle Gray/',             $m[4])){$nr[$i]['sku'] .='-NCG';}
				if(preg_match('/Regal Oak/',                   $m[4])){$nr[$i]['sku'] .='-RGO';}
				if(preg_match('/Richmond Auburn/',             $m[4])){$nr[$i]['sku'] .='-RMA';}
				if(preg_match('/Sedona Chestnut/',             $m[4])){$nr[$i]['sku'] .='-SDC';}
				if(preg_match('/Stone Harbor Gray/',           $m[4])){$nr[$i]['sku'] .='-SHG';}
				
				$nr[$i]['ttl'] = $r[$ii][$dsc_index];
				$nr[$i]['qty'] = $r[$ii][$qty_index];
				$nr[$i]['ppr'] = $r[$ii][$prc_index];
				$nr[$i]['amt'] = $r[$ii][$amt_index]; if(!$nr[$i]['amt']>0){ $nr[$i]['amt'] = $r[$ii][$amt_index+1]; }
				$i++;
				}

			if( preg_match('/^(FL\s)([\/\w]*)(\s)(\w.*)/', $r[$ii][$dsc_index], $m) ){
				$nr[$i]['sku'] = 'F'.$m[2];
				if(preg_match('/NEWPORT WHITE/', $m[4])){$nr[$i]['sku'] .='-NPW';}
				if(preg_match('/GRAND RESERVE CHERRY/', $m[4])){$nr[$i]['sku'] .='-GRC';}
				
				$nr[$i]['ttl'] = $r[$ii][$dsc_index];
				$nr[$i]['qty'] = $r[$ii][$qty_index];
				$nr[$i]['ppr'] = $r[$ii][$prc_index];
				$nr[$i]['amt'] = $r[$ii][$amt_index]; if(!$nr[$i]['amt']>0){ $nr[$i]['amt'] = $r[$ii][$amt_index+1]; }
				$i++;
				}

			if( preg_match('/^(JIFFY KIT - )(\w+)( - # )(\w.*)/', $r[$ii][0], $m) OR  
				preg_match('/^(JIFFY KIT- )(\w+)( - # )(\w.*)/', $r[$ii][0], $m) OR
				preg_match('/^(JIFFYKIT- )(\w+)( - # )(\w.*)/', $r[$ii][0], $m) OR
				preg_match('/^JIFFYKIT/', $r[$ii][0], $m)){
				$nr[$i]['sku'] = 'JK'.$m[2];

				$nr[$i]['ttl'] = $r[$ii][$dsc_index];  //JIFFY KIT- ACW - # M883-2901
				$nr[$i]['qty'] = $r[$ii][$qty_index];
				$nr[$i]['ppr'] = $r[$ii][$prc_index];
				$nr[$i]['amt'] = $r[$ii][$amt_index]; if(!$nr[$i]['amt']>0){ $nr[$i]['amt'] = $r[$ii][$amt_index+1]; }
				$i++;
				}
			if( preg_match('/^(B\/O)([-\s\/\w+].*)/', $r[$ii][0], $m) ){
				$nr[$i]['sku'] = 'B/O';
				$nr[$i]['ttl'] = $r[$ii][0];  //JIFFY KIT- ACW - # M883-2901
				// $nr[$i]['qty'] = $r[$ii][$qty_index];
				// $nr[$i]['ppr'] = $r[$ii][$prc_index];
				// $nr[$i]['amt'] = $r[$ii][$amt_index]; if(!$nr[$i]['amt']>0){ $nr[$i]['amt'] = $r[$ii][$amt_index+1]; }
				$i++;
				}
			}
		$ii++;
		}
	return $nr;
}

function ttscan_uscd($r){
	$i = $ii = 0;
	$nr = array();

	$qty_index = $sku_index = $dsc_index = $prc_index = $amt_index = NULL;

//	while($r[$ii][0]!='' OR $r[$ii+1][0]!='' OR $r[$ii+2][0]!='' OR $r[$ii+3][0]!='' OR $r[$ii+4][0]!='' OR $r[$ii+5][0]!=''){
	while(steppin($r, $ii, 0, 9)){

		if($r[$ii][0]=='Quantity'){
			for($in=0; $in<15; $in++){ // $qty_index = 0;
				if($r[$ii][$in]=='Quantity')		{ $qty_index = $in; }
				if($r[$ii][$in]=='Item')			{ $sku_index = $in; }
				if($r[$ii][$in]=='Description')		{ $dsc_index = $in; }
				if($r[$ii][$in]=='Price Each')	{ $prc_index = $in; }
				if($r[$ii][$in]=='Amount')			{ $amt_index = $in; }
				}
			}
		if($r[$ii][0]=='Product Name'){
			for($in=0; $in<15; $in++){
				if($r[$ii][$in]=='Product Name')	{ $sku_index = $in; }
				if($r[$ii][$in]=='Price')			{ $prc_index = $in; }
				if($r[$ii][$in]=='Qty')				{ $qty_index = $in; }
				if($r[$ii][$in]=='Subtotal')		{ $amt_index = $in; }
				}
			}

		$r[$ii][$qty_index] = preg_replace('/Ordered: /','',$r[$ii][$qty_index]);
		$r[$ii][$prc_index] = preg_replace('/\$/','',$r[$ii][$prc_index]);
		$r[$ii][$amt_index] = preg_replace('/\$/','',$r[$ii][$amt_index]);

		if( $r[$ii][$qty_index]!='' AND $r[$ii][$sku_index]!='' AND is_numeric($r[$ii][$prc_index]) AND is_numeric($r[$ii][$amt_index])){
//			$nr[$i]['sku'] = 'G'.$m[2];

			$nr[$i]['sku'] = 'U-'.$r[$ii][$sku_index];
			$nr[$i]['qty'] = $r[$ii][$qty_index];
			$nr[$i]['ttl'] = $r[$ii][$dsc_index];
			$nr[$i]['ppr'] = $r[$ii][$prc_index];
			$nr[$i]['amt'] = $r[$ii][$amt_index];
			$i++;
			}

		$ii++;
		}
	return $nr;
}

function ttscan_ctc($r){
	$i = $ii = 0;
	$nr = array();

	$qty_index = $sku_index = $dsc_index = $prc_index = $amt_index = NULL;

	while(steppin($r, $ii, 0, 9)){
		if($r[$ii][0]=='Order Qty'){
			for($in=0; $in<15; $in++){ // $qty_index = 0;
				if($r[$ii][$in]=='Apr. Qty' OR $r[$ii][$in]=='Open Qty')	{ $qty_index = $in; }
				if($r[$ii][$in]=='Item Number' OR $r[$ii][$in]=='Item')						{ $sku_index = $in; }
				if(preg_match('/Description/', $r[$ii][$in])) 		{ $dsc_index = $in; }
				if(preg_match('/Unit Price/', $r[$ii][$in]))  		{ $prc_index = $in; }
				if(preg_match('/Extended Price/', $r[$ii][$in]))	{ $amt_index = $in; }
				}
			}
		elseif($r[$ii][1]=='QTY'){
			for($in=0; $in<15; $in++){ // $qty_index = 0;
				if($r[$ii][$in]=='QTY')		{ $qty_index = $in; }
				if($r[$ii][$in]=='ITEM')	{ $sku_index = $in; }
				if(preg_match('/DESCRIPTION/', $r[$ii][$in])) 		{ $dsc_index = $in; }
				if(preg_match('/PRICE/', $r[$ii][$in]))  		{ $prc_index = $in; }
				if(preg_match('/AMOUNT/', $r[$ii][$in]))	{ $amt_index = $in; }
				}
			}
		else{}
	
		if( is_numeric($r[$ii][$qty_index]) AND is_numeric($r[$ii][$prc_index]) AND is_numeric($r[$ii][$amt_index])){

			for($in1=0; $in1<15; $in1++){ // $qty_index = 0;
				$nr[$i]['frow'] .= $r[$ii][$in1].' ';
				}
//		if( is_numeric($r[$ii][$qty_index])){
			// $nr[$i]['sku'] = $r[$ii][$sku_index]; 
			// if(!$nr[$i]['sku']) { $nr[$i]['sku'] = $r[$ii+1][$sku_index]; };
			// if(!$nr[$i]['sku']) { $nr[$i]['sku'] = $r[$ii+1][$sku_index]; };

			$nr[$i]['qty'] = $r[$ii][$qty_index];
			$nr[$i]['sku'] = $r[$ii][$qty_index+1]; 
			if(!$nr[$i]['sku']) { $nr[$i]['sku'] = $r[$ii+1][$qty_index+2]; };

			$nr[$i]['ttl'] = $r[$ii][$dsc_index];
			$nr[$i]['ppr'] = $r[$ii][$prc_index];
			$nr[$i]['amt'] = $r[$ii][$amt_index];
			$i++;
			}
		$ii++;
		}
	return $nr;
}


function steppin($r, $ii, $col=0, $dsh=3){
for($i=0;$i<=$dsh;$i++){
	if($r[$ii+$i][$col]!=''){return true;}
	}
	return false;
}

function scantotals($r, $vnd){
	$tt = array();
	if($vnd=='ghi'){
		$vhx = getcoords($r, 'Net Order:');
		if($r[$vhx[0]][$vhx[1]+1]!=''){ $tt['onet'] = $r[$vhx[0]][$vhx[1]+1]; }
		if($r[$vhx[0]][$vhx[1]+2]!=''){ $tt['onet'] = $r[$vhx[0]][$vhx[1]+2]; }
		if($r[$vhx[0]][$vhx[1]+3]!=''){ $tt['onet'] = $r[$vhx[0]][$vhx[1]+3]; }

		$vhx = getcoords($r, 'Less Discount:');
		if($r[$vhx[0]][$vhx[1]+1]!=''){ $tt['dcnt'] = $r[$vhx[0]][$vhx[1]+1]; }
		if($r[$vhx[0]][$vhx[1]+2]!=''){ $tt['dcnt'] = $r[$vhx[0]][$vhx[1]+2]; }
		if($r[$vhx[0]][$vhx[1]+3]!=''){ $tt['dcnt'] = $r[$vhx[0]][$vhx[1]+3]; }

		$vhx = getcoords($r, 'Freight:');
		if($r[$vhx[0]][$vhx[1]+1]!=''){ $tt['frgt'] = $r[$vhx[0]][$vhx[1]+1]; }
		if($r[$vhx[0]][$vhx[1]+2]!=''){ $tt['frgt'] = $r[$vhx[0]][$vhx[1]+2]; }
		if($r[$vhx[0]][$vhx[1]+3]!=''){ $tt['frgt'] = $r[$vhx[0]][$vhx[1]+3]; }

		$vhx = getcoords($r, 'Shipping Cost (Carrier)');
		if($r[$vhx[0]][$vhx[1]+1]!=''){ $tt['frgt'] = $r[$vhx[0]][$vhx[1]+1]; }
		if($r[$vhx[0]][$vhx[1]+2]!=''){ $tt['frgt'] = $r[$vhx[0]][$vhx[1]+2]; }
		if($r[$vhx[0]][$vhx[1]+3]!=''){ $tt['frgt'] = $r[$vhx[0]][$vhx[1]+3]; }

		$vhx = getcoords($r, 'Sales Tax:');
		if($r[$vhx[0]][$vhx[1]+1]!=''){ $tt['sltx'] = $r[$vhx[0]][$vhx[1]+1]; }
		if($r[$vhx[0]][$vhx[1]+2]!=''){ $tt['sltx'] = $r[$vhx[0]][$vhx[1]+2]; }
		if($r[$vhx[0]][$vhx[1]+3]!=''){ $tt['sltx'] = $r[$vhx[0]][$vhx[1]+3]; }

		$vhx = getcoords($r, 'Order Tota:');
		if($r[$vhx[0]][$vhx[1]+1]!=''){ $tt['ottl'] = $r[$vhx[0]][$vhx[1]+1]; }
		if($r[$vhx[0]][$vhx[1]+2]!=''){ $tt['ottl'] = $r[$vhx[0]][$vhx[1]+2]; }
		if($r[$vhx[0]][$vhx[1]+3]!=''){ $tt['ottl'] = $r[$vhx[0]][$vhx[1]+3]; }
		if(!$tt['ottl']){
			$vhx = getcoords($r, 'Order Total:');
			if($r[$vhx[0]][$vhx[1]+1]!=''){ $tt['ottl'] = $r[$vhx[0]][$vhx[1]+1]; }
			if($r[$vhx[0]][$vhx[1]+2]!=''){ $tt['ottl'] = $r[$vhx[0]][$vhx[1]+2]; }
			if($r[$vhx[0]][$vhx[1]+3]!=''){ $tt['ottl'] = $r[$vhx[0]][$vhx[1]+3]; }
			}
		}

	if($vnd=='ctc'){
		$vhx = getcoords($r, 'SubTotal');
		if($r[$vhx[0]][$vhx[1]]!='')  { $tt['sbtt_lbl'] = $r[$vhx[0]][$vhx[1]]; }
		if($r[$vhx[0]][$vhx[1]+1]!=''){ $tt['onet'] = floatval(preg_replace('/[\$\,]/','',$r[$vhx[0]][$vhx[1]+1])); }
		// if($r[$vhx[0]][$vhx[1]+2]!=''){ $tt['onet'] = floatval(preg_replace('/\$/','',$r[$vhx[0]][$vhx[1]+2])); }
		// if($r[$vhx[0]][$vhx[1]+3]!=''){ $tt['onet'] = floatval(preg_replace('/\$/','',$r[$vhx[0]][$vhx[1]+3])); }
		// if($r[$vhx[0]][$vhx[1]+4]!=''){ $tt['onet'] = floatval(preg_replace('/\$/','',$r[$vhx[0]][$vhx[1]+4])); }
		// if($r[$vhx[0]][$vhx[1]+5]!=''){ $tt['onet'] = floatval(preg_replace('/\$/','',$r[$vhx[0]][$vhx[1]+5])); }

		$vhx = getcoords($r, 'Shipping');
		if($r[$vhx[0]][$vhx[1]]!='')  { $tt['shp_lbl'] = $r[$vhx[0]][$vhx[1]]; }
		if($r[$vhx[0]][$vhx[1]+1]!=''){ $tt['shp'] = floatval(preg_replace('/[\$\,]/','',$r[$vhx[0]][$vhx[1]+1])); }
		// if($r[$vhx[0]][$vhx[1]+2]!=''){ $tt['shp'] = floatval(preg_replace('/\$/','',$r[$vhx[0]][$vhx[1]+2])); }
		// if($r[$vhx[0]][$vhx[1]+3]!=''){ $tt['shp'] = floatval(preg_replace('/\$/','',$r[$vhx[0]][$vhx[1]+3])); }

		$vhx = getcoords($r, 'Charge');
		if($r[$vhx[0]][$vhx[1]]!='')  { $tt['chrg_lbl'] = $r[$vhx[0]][$vhx[1]]; }
		if($r[$vhx[0]][$vhx[1]+1]!=''){ $tt['chrg'] = floatval(preg_replace('/[\$\,]/','',$r[$vhx[0]][$vhx[1]+1])); }
		// if($r[$vhx[0]][$vhx[1]+2]!=''){ $tt['chrg'] = floatval(preg_replace('/\$/','',$r[$vhx[0]][$vhx[1]+2])); }
		// if($r[$vhx[0]][$vhx[1]+3]!=''){ $tt['chrg'] = floatval(preg_replace('/\$/','',$r[$vhx[0]][$vhx[1]+3])); }

		$vhx = getcoords($r, 'Freight'); 
		if($r[$vhx[0]][$vhx[1]]!='')  { $tt['fr_lbl'] = $r[$vhx[0]][$vhx[1]]; }
		if($r[$vhx[0]][$vhx[1]+1]!=''){ $tt['fr'] = floatval(preg_replace('/[\$\,]/','',$r[$vhx[0]][$vhx[1]+1])); }
		// if($r[$vhx[0]][$vhx[1]+2]!=''){ $tt['frgt'] = floatval(preg_replace('/\$/','',$r[$vhx[0]][$vhx[1]+2])); }
		// if($r[$vhx[0]][$vhx[1]+3]!=''){ $tt['frgt'] = floatval(preg_replace('/\$/','',$r[$vhx[0]][$vhx[1]+3])); }
//		$tt['shpttl'] = $tt['shp'] + $tt['chrg'] + $tt['frgt']; ////////////////////////////////////////////////////

		
//		$vhx = getcoords($r, 'Order dTotal');
//		if($r[$vhx[0]][$vhx[1]+1]!=''){ $tt['ottl'] = floatval(preg_replace('/[\$\,]/','',$r[$vhx[0]][$vhx[1]+1])); }
		// if($r[$vhx[0]][$vhx[1]+2]!=''){ $tt['ottl'] = floatval(preg_replace('/\$/','',$r[$vhx[0]][$vhx[1]+2])); }
		// if($r[$vhx[0]][$vhx[1]+3]!=''){ $tt['ottl'] = floatval(preg_replace('/\$/','',$r[$vhx[0]][$vhx[1]+3])); }
		// if($r[$vhx[0]][$vhx[1]+4]!=''){ $tt['ottl'] = floatval(preg_replace('/\$/','',$r[$vhx[0]][$vhx[1]+4])); }
		// if($r[$vhx[0]][$vhx[1]+5]!=''){ $tt['ottl'] = floatval(preg_replace('/\$/','',$r[$vhx[0]][$vhx[1]+5])); }

		$vhx = getcoords($r, 'Grand Total');
		if($r[$vhx[0]][$vhx[1]]!='')  { $tt['grttl_lbl'] = $r[$vhx[0]][$vhx[1]]; }
		if($r[$vhx[0]][$vhx[1]+1]!=''){ $tt['grttl'] = floatval(preg_replace('/[\$\,]/','',$r[$vhx[0]][$vhx[1]+1])); }
		// if($r[$vhx[0]][$vhx[1]+2]!=''){ $tt['grttl'] = floatval(preg_replace('/\$/','',$r[$vhx[0]][$vhx[1]+2])); }
		// if($r[$vhx[0]][$vhx[1]+3]!=''){ $tt['grttl'] = floatval(preg_replace('/\$/','',$r[$vhx[0]][$vhx[1]+3])); }
		$tt['ottl'] = ($tt['grttl']) ? $tt['grttl'] : $tt['ottl'];
	
		$tt['frgt'] = floatval($tt['shp']) + floatval($tt['chrg']) + floatval($tt['fr']);
		}

	if($vnd=='fm'){
		$vhx = getcoords($r, 'Subtotal');
		if($r[$vhx[0]][$vhx[1]+1]!=''){ $tt['onet'] = $r[$vhx[0]][$vhx[1]+1]; }
		if($r[$vhx[0]][$vhx[1]+2]!=''){ $tt['onet'] = $r[$vhx[0]][$vhx[1]+2]; }
		if($r[$vhx[0]][$vhx[1]+3]!=''){ $tt['onet'] = $r[$vhx[0]][$vhx[1]+3]; }
		if($r[$vhx[0]][$vhx[1]+4]!=''){ $tt['onet'] = $r[$vhx[0]][$vhx[1]+4]; }
		if($r[$vhx[0]][$vhx[1]+5]!=''){ $tt['onet'] = $r[$vhx[0]][$vhx[1]+5]; }


		$vhx = getcoords($r, 'Shipping Cost');
		if($r[$vhx[0]][$vhx[1]+1]!=''){ $tt['frgt'] = $r[$vhx[0]][$vhx[1]+1]; }
		if($r[$vhx[0]][$vhx[1]+2]!=''){ $tt['frgt'] = $r[$vhx[0]][$vhx[1]+2]; }
		if($r[$vhx[0]][$vhx[1]+3]!=''){ $tt['frgt'] = $r[$vhx[0]][$vhx[1]+3]; }


		$vhx = getcoords($r, 'Total');
		if($r[$vhx[0]][$vhx[1]+1]!=''){ $tt['ottl'] = floatval(preg_replace('/\$/','',$r[$vhx[0]][$vhx[1]+1])); }
		if($r[$vhx[0]][$vhx[1]+2]!=''){ $tt['ottl'] = floatval(preg_replace('/\$/','',$r[$vhx[0]][$vhx[1]+2])); }
		if($r[$vhx[0]][$vhx[1]+3]!=''){ $tt['ottl'] = floatval(preg_replace('/\$/','',$r[$vhx[0]][$vhx[1]+3])); }
		if($r[$vhx[0]][$vhx[1]+4]!=''){ $tt['ottl'] = floatval(preg_replace('/\$/','',$r[$vhx[0]][$vhx[1]+4])); }
		if($r[$vhx[0]][$vhx[1]+5]!=''){ $tt['ottl'] = floatval(preg_replace('/\$/','',$r[$vhx[0]][$vhx[1]+5])); }
		}

	if($vnd=='uscd'){


		}
		
		
	// $tt = array();
	// if($vnd=='ghi'){
		// while(steppin($r, $ii, 0, 9)){
			// if( $r[$ii][7]=='Net Order:' )		{ $tt['onet'] = $r[$ii][9]; }
			// if( $r[$ii][7]=='Less Discount:' )	{ $tt['dcnt'] = $r[$ii][9]; }
			// if( $r[$ii][7]=='Freight:' )		{ $tt['frgt'] = $r[$ii][9]; }
			// if( $r[$ii][7]=='Sales Tax:' )		{ $tt['sltx'] = $r[$ii][9]; }
			// if( $r[$ii][7]=='Order Total:' ) 	{ $tt['ottl'] = $r[$ii][9]; }
			// $ii++;
			// }
		// }
	return $tt;
}





function getordn($arr){
	foreach($arr as $vk=>$vv){
		foreach($vv as $hk=>$hv){
			if(preg_match('/(OC-)([\d]{4,7})/', $hv)){ return preg_replace('/OC-/','',$hv); }
			}
		}
	return NULL;
}



function look2sku($r, $sku){
	foreach ($r as $v){
		if(preg_match("/^$sku$/", $v['sku'])){ return $v; }
		}
	return 0;
}

function look4sku($r, $sku){
	foreach ($r as $v){
		if(preg_match("/$sku/", $v['sku'])){ return $v; }
		}
	return 0;
}

function skutrim0($sku){
	$tsku = preg_replace('/-RTA/',   '', $sku);
	$tsku = preg_replace('/-ASM/',   '', $tsku);
	$tsku = preg_replace('/-LEFT/',  '', $tsku);
	$tsku = preg_replace('/-RIGHT/', '', $tsku);
	$tsku = preg_replace('/\//', '\/'  , $tsku);
	$tsku = preg_replace('/\(/', '\('  , $tsku);
	$tsku = preg_replace('/\)/', '\)'  , $tsku);
return $tsku;	
}

function skutrim1($sku){
	$tsku = preg_replace('/-SOL.*$/', '', $sku);
	$tsku = preg_replace('/\“.*$/',   '', $tsku);
	$tsku = preg_replace('/\"/',   '', $tsku);
	$tsku = preg_replace('/\'/',   '', $tsku);
//	$tsku = preg_replace('/\“/',      '', $tsku);
	$tsku = preg_replace('/\‘.*$/',   '', $tsku);
//	$tsku = preg_replace('/\‘/',      '', $tsku);
	$tsku = preg_replace('/-SP$/',    '', $tsku);
	$tsku = preg_replace('/\s\(2\)/', '', $tsku);
	$tsku = preg_replace('/\//',    '\/', $tsku);
	$tsku = preg_replace('/\(/',    '\(', $tsku);
	$tsku = preg_replace('/\)/',    '\)', $tsku);
return $tsku;	
}




function getqw_item($sku){ global $dbh;
	return mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM qw_item WHERE Sku = '$sku'", MYSQLI_ASSOC));
}


function getoidmax($px){ global $dbh;
	return mysqli_fetch_array(mysqli_query($dbh, "SELECT MAX(IID) FROM qw_xt WHERE (oname = 'Order' OR oname = 'Estimate') AND IID LIKE '$px%'", MYSQLI_ASSOC))[0];
}




function getpfx($r){
	$px = NULL;
	$sn = getVcoord($r, 'Supplier');
if($r[$sn][1] == 'US Cabinets'){
	$vc = getVcoord($r, 'Door style', 1);
	if(preg_match('/^(.*)(\()([\w]{2})(\))/', $vc, $m)){ $px = 'U-'.$m[3]; }
	}

if( ($r[$sn][1] == 'FOREVERMARK CABINETRY (TSG)') OR
	($r[$sn][1] == '20-20 Technologies')
	){
	$vc = getVcoord($r, 'Wall Door', 1);
	if(preg_match('/^([\w]{1,4})(\s\-)(.*)/', $vc, $m)){ $px = $m[1]; }
	if(!$px){ $vc = getVcoord($r, 'Door style', 1);
		if(preg_match('/^([\w]{1,4})(\s\-)(.*)/', $vc, $m)){ $px = $m[1]; }
		}	
	}


if($r[$sn][1] == 'Cubitac Cabinetry'){
	$vc = getVcoord($r, 'Door style', 1);
	if(preg_match('/^(Milan Shale)(.*)/', $vc)){ $px = 'SM'; }
	}

	return $px;
}


function getvendor($r){
	foreach($r as $rk=>$rv){
		foreach($rv as $ik=>$iv){
			if(preg_match('/TSG/', $iv) OR preg_match('/FOREVERMARK/', $iv)){ return 'fm'; }
			if(preg_match('/Horning/', $iv)){ return 'ghi'; }
			if(preg_match('/cubitac/', $iv)){ return 'ctc'; }
			if(preg_match('/US Cabinets/', $iv) OR preg_match('/(Order #)([\d]{7,10})/', $iv)){ return 'uscd'; }
			}
		}
	return NULL;
}



function getaddress($r, $s){
//$stend = array('Customer','Ship to address','DESIGN DETAILS');
$addr = array();
$i = 0;
if($s){
	$vc = getVcoord($r, 'Ship to address');
	if($vc){
		while(($r[++$vc][0] != 'DESIGN DETAILS')  AND 
			  ($r[$vc][0] != '')){
			$addr[$i++] = $r[$vc][0];
			if($r[$vc][1]){ $addr[$i++] = preg_replace('/^\s/', '', $r[$vc][1]); }
			}
		}
	}
else{
	$vc = getVcoord($r, 'Customer');
	if($vc){
		while(($r[++$vc][0] != 'Ship to address')  AND 
			  ($r[$vc][0] != 'Note')  AND
			  ($r[$vc][0] != 'Fax:')  AND
			  ($r[$vc][0] != 'Home:')  AND
			  ($r[$vc][0] != 'Email:')  AND
			  ($r[$vc][0] != 'DESIGN DETAILS')  AND
			  ($r[$vc][0] != '')){
			$addr[$i++] = $r[$vc][0];
			if($r[$vc][1]){ $addr[$i++] = preg_replace('/^\s/', '', $r[$vc][1]); }
			}
		}
	}
	
	
//$addr[3] = preg_replace('/\s/', '', $addr[3]);	
return $addr;
}

function displaycsvarr($r){
	$cnt='<table class="TBL" width="100%" cellspacing="1" style="margin-top: 25px;">
<tr><th colspan="10"></th></tr>
	';
	foreach($r as $trow) {
		$cnt .= '<tr>';
		foreach($trow as $tcoldt){
			$cnt .= '<td>'.$tcoldt.'</td>';
			}
		$cnt .= '</tr>';
		}
	$cnt .= '</table>';
	return $cnt;
}


function chkcustqb($name, $em, $ph){ global $dbh;
	
//	return mysqli_fetch_array(mysqli_query($dbh, "SELECT * FROM qw_customer WHERE (lname = 'Order' OR oname = 'Estimate') AND IID LIKE '$px%'", MYSQLI_ASSOC))[0];
	
	return;
}

function getcsvarr(){
	$varr = array();
		$csvfname = $_FILES['csv']['name'];
		$ext     = strtolower(end(explode('.', $csvfname)));
		$type    = $_FILES['csv']['type'];
		$tmpName = $_FILES['csv']['tmp_name'];
		if($ext === 'csv'){
			if(($handle = fopen($tmpName, 'r')) !== FALSE) {
				// necessary if a large csv file
				set_time_limit(0);
				$row = 0;
				while(($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
					$col_count = count($data);
					for($col=0; $col<$col_count; $col++){
						$varr[$row][$col]=$data[$col];
						}
					$row++;
					}
				fclose($handle);
				}
			}

	return $varr;
}


function ioscan($r){
	$nr = array();
	
	$poc = getcoords($r, 'P.O. Number');
	$nr[0]['pon']  = $r[$poc[0]+2][$poc[1]];
	$occ = getcoords($r, 'Order No.');
	$nr[0]['ocn']  = $r[$occ[0]+2][$occ[1]+1];
	
	$i = $ii = 0;
	$qty_index = $sku_index = $dsc_index = $prc_index = $amt_index = NULL;

	while(	$r[$ii][0]  !='' OR 
			$r[$ii+1][0]!='' OR 
			$r[$ii+2][0]!='' OR 
			$r[$ii+3][0]!='' OR 
			$r[$ii][4]  !='' OR 
			$r[$ii+1][4]!='' OR 
			$r[$ii+2][4]!='' OR 
			$r[$ii+3][4]!=''){
		
		if($r[$ii][0]=='B/O ETA 1'){
			for($in=0; $in<15; $in++){
				if($r[$ii][$in]=='Qty')			{ $qty_index = $in; }
				if($r[$ii][$in]=='Item')		{ $sku_index = $in; }
				if($r[$ii][$in]=='Description')	{ $dsc_index = $in; }
				if($r[$ii][$in]=='Unit $')		{ $prc_index = $in; }
				if($r[$ii][$in]=='Amount')		{ $amt_index = $in; }
				}
			}
		if($r[$ii][0]=='#' AND $r[$ii][1]=='Qty'){
			for($in=0; $in<15; $in++){
				if($r[$ii][$in]=='Qty')			{ $qty_index = $in; }
				if($r[$ii][$in]=='User code')	{ $sku_index = $in; }
				if($r[$ii][$in]=='Manuf. code')	{ $dsc_index = $in; }
				if($r[$ii][$in]=='Description')	{ $prc_index = $in; }
				}
			}
		
		if(	$r[$ii][$qty_index]>0   AND 
			$r[$ii][$sku_index]!='' AND $r[$ii][$sku_index]!='Residential/Jobsite' AND 
			$r[$ii][$dsc_index]!='' AND 
			$r[$ii][$prc_index]!='' ){
			$nr[$i]['sku'] = $r[$ii][$sku_index];
			$nr[$i]['qty'] = $r[$ii][$qty_index];
			$nr[$i]['ttl'] = $r[$ii][$dsc_index];
			$nr[$i]['ppr'] = $r[$ii][$prc_index];
			$nr[$i]['amt'] =($r[$ii][$amt_index])?$r[$ii][$amt_index]:$r[$ii][$prc_index]*$r[$ii][$qty_index];
			$i++;
			}
		$ii++;
		}
	return $nr;
	
}

function group_sims($r){
return $r;


$nr = array();
$i = 0;
foreach ($r as $k=>$v){
	$itex = true;
	$qty = $r[$k]['qty'];
	$sku = preg_replace('/^\s/', '', $r[$k]['sku']);
	$sku = preg_replace('/\s$/', '', $sku);
	$ttl = $r[$k]['ttl'];
	$cst = $r[$k]['ppr'];
	$amt = $r[$k]['amt'];
	
	foreach($nr as $nk=>$nv){
		if( $nr[$nk]['sku'] == $sku){ 
			$itex = false;
			$nr[$nk]['qty'] += $qty;
			$nr[$nk]['amt'] = $nr[$nk]['qty']*$cst;
			}
		}
		
	if($itex){ 
		$nr[$i]['ttl'] = $ttl;
		$nr[$i]['qty'] = $qty;
		$nr[$i]['sku'] = $sku;
		$nr[$i]['ppr'] = $cst;
		$nr[$i]['amt'] = $amt;
		$i++;
		}
	}
return $nr;
}



function getarr_htm($r){ //  Getting data from HTML confirmation file (Forevermark)
	$df = 0;	
	$nr = array();
	$html = str_get_html($r); // Parse the HTML, stored as a string in $html
	$rows = ($html)?$html->find('tr'):array(); // Find all rows in the table

	$nr['dt']['vnd']     = 'TSG';
	$nr['addr']['bill2'] = getbillingaddr($r);
	$nr['addr']['shp2w'] = getshippingaddr($r);

	foreach ($rows as $row) {
		if(preg_match('/(.*)(Order #&nbsp;)([\d]{7,9})(.*)/', $row->children()[1]->plaintext, $m)){ $nr['n']['ocn']=$m[3]; }
		if(preg_match('/(.*)(PO[&nbsp;\s]*#[&nbsp;\s]*)([\w\s]*)(Shipping Method)(.*)/', $row->children()[1]->plaintext, $m)){ $nr['n']['pon']= preg_replace('/\s/','',$m[3]); }

		if( $row->children()[0]->plaintext=='Subtotal')						{ $nr['tt']['onet'] = floatval(preg_replace('/[\$,]*/','',$row->children()[1]->plaintext)); $df = 0; }
		if(	$row->children()[0]->plaintext=='Shipping Cost (Freight - MF)' OR
			$row->children()[0]->plaintext=='Shipping Cost (Carrier)'	)	{ $nr['tt']['frgt'] = floatval(preg_replace('/[\$,]*/','',$row->children()[1]->plaintext)); }
		if( $row->children()[0]->plaintext=='Total')						{ $nr['tt']['ottl'] = floatval(preg_replace('/[\$,]*/','',$row->children()[1]->plaintext)); }
		if( $df ){
			$nr['it'][$fi]['qty'] = $row->children()[1]->plaintext;
			$nr['it'][$fi]['sku'] = $row->children()[3]->plaintext;
			$nr['it'][$fi]['rmk'] = $row->children()[4]->plaintext;
			$nr['it'][$fi]['ttl'] = $row->children()[5]->plaintext;
			$nr['it'][$fi]['ppr'] = $row->children()[6]->plaintext;
			$nr['it'][$fi]['amt'] = $row->children()[7]->plaintext;
			$fi++;
			}
		if($row->children()[0]->plaintext=='B/O ETA 1'){ $df = 1; }
		}
	return $nr;
}


function getarr_htmlf($tnm){ 
	$nr['dt']['vnd'] = 'US Cabinet Depot';
	$nr['n']['ocn']  = '- - -';
	$hobj = file_get_html($tnm); // Get tnm file content object 
	$nr['n']['pon']  = (isset($_POST['srchiorder']))?$_POST['srchiorder']:getpon($r);
	$addrv = $hobj->find('.shipping-address-item', 0);
	$nr['addr']['shp2w'] = preg_replace('/<div class="edit-address-popup">[\w\s\=\">].*<\/div>/','',$addrv->outertext);
	$nr['tt']['frgt'] = $hobj->find('1_freight_EXPRESS_ADVANTAGE', 0);
	$nr['it'] = getUSCDr($hobj);
	
	foreach ($nr['it'] as $amt){ $nr['tt']['onet'] += $amt['ppr']*$amt['qty']; }
	
	$nr['tt']['ottl'] = $nr['tt']['onet']; //scantotals($hobj, 'uscd');
	
return $nr;
}






function ____getUSCDr($robj){ //  Getting data from HTML confirmation file (Forevermark)


$code = $robj->find('.minicart-items', 0);
$vdiv = str_get_html($code->outertext);
$rows = $vdiv->find('.product-item-inner');
$i = 0;
$darr = array();
foreach($rows as $rowV){ 
	$vdv1 = str_get_html($rowV);
	preg_match('/(<strong .*>)(\w.*)(<\/strong>)/', $vdv1->find('.product-item-name')[0], $m); $darr[$i]['name'] = $m[2];
	preg_match('/(<span .*>)(\w.*)(<\/span>)/',     $vdv1->find('.value')[0], $m);             $darr[$i]['qty']  = $m[2];
	preg_match('/(<span .*>\$)(\w.*)(<\/span>)/',   $vdv1->find('.price')[0], $m);             $darr[$i]['prc']  = $m[2]/$darr[$i]['qty'];
//	$darr[$i]['qty']  = preg_replace('/(<[.*]>)/','',$vdv1->find('.value')[0]);
//	$darr[$i]['prc']  = preg_replace('/(<[.*]>)/','',$vdv1->find('.price')[0]);
	$i++;
	}
return $darr;


	$df = 0;	
	$nr = array();
	$html = str_get_html($r); // Parse the HTML, stored as a string in $html
	$rows = ($html)?$html->find('div'):array(); // Find all rows in the table

	$nr['dt']['vnd']     = 'USCD';
	$nr['addr']['bill2'] = getbillingaddr($r);
	$nr['addr']['shp2w'] = getshippingaddr($r);

	foreach ($rows as $row) {
		
//		echo $row . '<br>';
		
		
		if(preg_match('/(.*)(Order #&nbsp;)([\d]{7,9})(.*)/', $row->children()[1]->plaintext, $m)){ $nr['n']['ocn']=$m[3]; }
		if(preg_match('/(.*)(PO[&nbsp;\s]*#[&nbsp;\s]*)([\w\s]*)(Shipping Method)(.*)/', $row->children()[1]->plaintext, $m)){ $nr['n']['pon']= preg_replace('/\s/','',$m[3]); }

		if( $row->children()[0]->plaintext=='Subtotal')						{ $nr['tt']['onet'] = floatval(preg_replace('/[\$,]*/','',$row->children()[1]->plaintext)); $df = 0; }
		if(	$row->children()[0]->plaintext=='Shipping Cost (Freight - MF)' OR
			$row->children()[0]->plaintext=='Shipping Cost (Carrier)'	)	{ $nr['tt']['frgt'] = floatval(preg_replace('/[\$,]*/','',$row->children()[1]->plaintext)); }
		if( $row->children()[0]->plaintext=='Total')						{ $nr['tt']['ottl'] = floatval(preg_replace('/[\$,]*/','',$row->children()[1]->plaintext)); }
		if( $df ){
			$nr['it'][$fi]['qty'] = $row->children()[1]->plaintext;
			$nr['it'][$fi]['sku'] = $row->children()[3]->plaintext;
			$nr['it'][$fi]['rmk'] = $row->children()[4]->plaintext;
			$nr['it'][$fi]['ttl'] = $row->children()[5]->plaintext;
			$nr['it'][$fi]['ppr'] = $row->children()[6]->plaintext;
			$nr['it'][$fi]['amt'] = $row->children()[7]->plaintext;
			$fi++;
			}
		if($row->children()[0]->plaintext=='B/O ETA 1'){ $df = 1; }
		}
	return $nr;
}


function getUSCDr($robj){ //  Getting data from HTML confirmation file (Forevermark)


//$robj = str_get_html($fcnt);
//var_dump($robj);
$code = $robj->find('.minicart-items', 0);
//$code = $robj->find('.modal-inner-wrap', 0);
$vdiv = str_get_html($code->outertext);

//$shaddr = $robj->find('.shipping-information-content', 0);
//$shaddr = $robj->find('.shipping-address-item', 0);
//var_dump($code->outertext);
//echo '<pre>'; print_r($shaddr->outertext); echo '</pre>';

//echo $shaddr->outertext;


$rows = $vdiv->find('.product-item-inner');
//$rows = $vdiv->find('.product-item-details');

$darr = array();
$i = 0;

foreach($rows as $rowV){ 
	$vdv1 = str_get_html($rowV);
	if(preg_match('/(<strong .*>)(\w.*)(<\/strong>)/', $vdv1->find('.product-item-name')[0], $m)) { $darr[$i]['sku']  = 'U-'.$m[2]; }
	if(preg_match('/(<span .*>)(\w.*)(<\/span>)/',     $vdv1->find('.value')[0], $m))             { $darr[$i]['qty']  = $m[2]; }
	if(preg_match('/(<span .*>\$)(\w.*)(<\/span>)/',   $vdv1->find('.price')[0], $m))             { $darr[$i]['ppr']  = $m[2]/$darr[$i]['qty']; }
	$i++;
	}
return $darr;
}






function getarr_csv($r){ /// Getting information from CSV files Vendor confirmation
	$nr = array();
	$vnd = getvendor($r);
	switch ($vnd){
		case 'ctc':
			$nr['dt']['vnd'] = 'CUBITAC';
			$occ = getcoords($r, 'Quote #');
			$nr['n']['ocn']  = $r[$occ[0]][$occ[1]+1];
			if(!$nr['n']['ocn']){
				$nr['n']['ocn']  = $r[$occ[0]][$occ[1]+2];
				}
			if(!$nr['n']['ocn']){
				$occ = getcoords($r, 'SO #');
				$nr['n']['ocn']  = $r[$occ[0]][$occ[1]+2];
				}
			if(!$nr['n']['ocn']){
				$occ = getcoords($r, 'SALES QUOTE');
				$nr['n']['ocn']  = $r[$occ[0]+2][$occ[1]+1];
				}

			$nr['n']['pon']  = (isset($_POST['srchiorder']))?$_POST['srchiorder']:getpon($r);
//			$nr['n']['pon']  = getpon($r);
			$nr['addr']['bill2']  = getaddrcsv($r, 'BILL TO', 'Contact:');
			$nr['addr']['shp2w']  = getaddrcsv($r, 'SHIP TO', 'Contact:');
			$nr['phone']['shp2w'] = getphonecsv_cbt($r, 'Phone:', 0, 1);

			$nr['it'] = ttscan_ctc($r);
			$nr['tt'] = scantotals($r, 'ctc');

		break;
		case 'fm':
			$nr['dt']['vnd'] = 'TSG';
			$occ = getcoords($r, 'Order No.');
			$nr['n']['ocn']  = $r[$occ[0]+2][$occ[1]+1];
			$pon = getcoords($r, 'P.O. Number');
			$nr['n']['pon']  = (isset($_POST['srchiorder']))?$_POST['srchiorder']:$r[$pon[0]+2][$pon[1]];
			// $tel = getcoords($r, 'Tel');
			// $nr['n']['pon']  = $r[$pon[0]+2][$pon[1]];
			$nr['addr']['bill2'] = getaddrcsv($r, 'Bill To', 'Memo');
			$nr['addr']['shp2w'] = getaddrcsv($r, 'Ship To', 'Door Style');
			$nr['phone']['shp2w'] = getphonecsv($r, 'Tel', 3);


			$nr['it'] = ttscan_fm($r);
			$nr['tt'] = scantotals($r, 'fm');

		break;
		case 'ghi': 
			$nr['dt']['vnd'] = 'HORNING';
			$occ = getcoords($r, 'Order Number:');
			$nr['n']['ocn']  = $r[$occ[0]][$occ[1]+1];
//	$poc = getcoords($r, 'P.O. Number');
			$nr['n']['pon']  = getpon($r);
			$nr['addr']['bill2'] = getaddrcsv($r, 'Sold To:', 'Confirm To:');
			$nr['addr']['shp2w'] = getaddrcsv($r, 'Ship To:', 'F.O.B.');
			
			$nr['it'] = ttscan_ghi($r);
			$nr['tt'] = scantotals($r, 'ghi');
		break;
		case 'uscd':    /////                  USCD uses HTML (not here)
			$nr['dt']['vnd'] = 'USCD';

			$occ = getcoords($r, 'Quote #');
			$nr['n']['ocn']  = $r[$occ[0]][$occ[1]+2];
			$nr['n']['pon']  = 'hintze0120';//getpon($r);
			$nr['addr']['bill2']  = getaddrcsv( $r, 'Customer', 'Ship to address');
			$nr['addr']['shp2w']  = getaddrcsv( $r, 'Ship to address', 'DESIGN DETAILS');
			$nr['phone']['shp2w'] = getphonecsv($r, 'Home:', 1);

			$nr['it'] = ttscan_uscd($r);
			$nr['tt'] = scantotals($r, 'uscd');

			
		break;
		default: break;
	}
	
	return $nr;
} 

function getpon($r){
	$pon = NULL;
	$vhx = getcoords($r, 'Customer PO Number');
	if($r[$vhx[0]][$vhx[1]+1]!=''){ $pon = preg_replace('/\s/','',$r[$vhx[0]][$vhx[1]+1]); }
	if($r[$vhx[0]][$vhx[1]+2]!=''){ $pon = preg_replace('/\s/','',$r[$vhx[0]][$vhx[1]+2]); }
	if($pon){ return $pon; }
	return (isset($_POST['srchiorder']))?$_POST['srchiorder']:NULL;
}

function getaddrcsv($r, $phst, $phnd){
	$addr = NULL;
	$vi=0;
	$ln = array();
	$crd = getcoords($r, $phst);
	while($vi<7){
		$bln[$vi] = lk4cl($r, $crd, 0, $vi+1);
		if($bln[$vi]==$phnd){break;}
		$addr .= $bln[$vi].'<br>'; //array_push($sold2arr, $bln[$bi]);
		$vi++;
		}
	return $addr;
}

function getphonecsv($r, $phst, $hshft){
	$ph = NULL;
	$vi=0;
	$ln = array();
	$vhx = getcoords($r, $phst, 3, $hshft);
	if($r[$vhx[0]][$vhx[1]+1]!=''){ $ph = $r[$vhx[0]][$vhx[1]+1]; }
	if($r[$vhx[0]][$vhx[1]+2]!=''){ $ph = $r[$vhx[0]][$vhx[1]+2]; }
	if($r[$vhx[0]][$vhx[1]+3]!=''){ $ph = $r[$vhx[0]][$vhx[1]+3]; }

	return $ph;
}

function getphonecsv_cbt($r, $phst, $v, $h){
	$ph = NULL;
	$vi=0;
	$ln = array();
	$vhx = getcoords($r, $phst, $v, $h);
	return $r[$vhx[0]][$vhx[1]];
}

?>