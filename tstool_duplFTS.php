<?phprequire_once('inc/_init.php');require_once('inc/functions/general.php');if($lghsh['Id'] == 0){	require_once('inc/functions/authorization.php'); 	$pgcontent = logform(); 	}include_once 'inc/functions.php';include_once 'wconfig.php';setlocale(LC_MONETARY, 'en_US');error_reporting(E_ALL | E_STRICT);//error_reporting(E_ERROR | E_WARNING | E_PARSE);///////////////////////////////////////////////////////////////////////////////$brand = 'ghi'; //(isset($_GET['br']))?$_GET['br']:NULL;$postp = NULL;$dflag = true;ini_set('memory_limit', '2048M');set_time_limit ( 1500 );$ofs = 0;$ppp = -1;$ofs_1 = 0;                  /////////  Start from$ppp_1 = 200;              /////////  Amount listed$msuff = 'FTS';$dpl_fr = 'ACW';$wc_adp = new WC_Admin_Duplicate_Product;// $probj = $wc_adp->product_duplicate( wc_get_product($oldid) );$wsiarr = getwsitems2($brand, $ppp, $ofs); /// Get items array from website by brand name (Sku, Title, Regular price, Sale price) /////////////////$mcls = (isset($_GET['sel']))?$_GET['sel']:0;//drc($wsiarr);$sbm_bttn = NULL;if($mcls==2){ $sbm_bttn = '<input type="submit" name="sku_update" value="Update Sku" style="padding: 3px 9px;" />'; }$vnd2 = NULL;$vnd = 'GHI';	$mcls=0;		$inc = $inc_all = $in_1 = $in_2 = 0;$bdy = NULL;$chbchk = '';$chbdsb = 'disabled="disabled"';$buttoname = NULL;$vw=array();	$vw[0] = 'Vendor'; $vw[1] = 'WCWeb';	if($mcls==1){ $sbm_bttn = '<input type="submit" name="sku_create" value="Create Sku" style="padding: 3px 9px;" />'; }	$tmquery = "SELECT * FROM cr_vndpl WHERE Vnd='$vnd' AND Ln='$msuff' ORDER BY ID ASC LIMIT ".$ofs_1.','.$ppp_1; 	$tresult = mysqli_query($dbh, $tmquery) or die (mysqli_error($dbh));	if($brand){		while ($tr = mysqli_fetch_array($tresult)) { $inc_all++;//if($inc_all>4100){break;}//			if(!$tr['VPr']>0) {continue;}			$fsku = preg_replace("/-$msuff$/", "-$dpl_fr", $tr['SkuWC']);//echo $fsku.'<br>';			//			$ws = get_wsku($wsiarr, $fsku);			$ws = get_wsku($wsiarr, $fsku);//			if(!$ws AND !$dflag){continue;}			$vs = (isset($ws['ptype']))?(($ws['ptype'])?'Variable':'Simple'):NULL;			$st1 = (!$tr['Sku'])?'background: #fc9;':'';			$st2 = (isset($ws['sku']) AND ($tr['VPr']>0) AND ($ws['sku']) AND ($tr['VPr'] != $ws['rprice'] AND $tr['VPr'] != $ws['price']))?'background: #fc9;':'';			if($ws){ if($mcls==1){continue;} }			else{ $in_1++; }			if(!$ws OR $tr['VPr']==$ws['rprice'] OR $tr['VPr']==$ws['price']){ if($mcls==2){continue;}	}			else{ $in_2++; }			if($mcls==1 OR $mcls==2){$chbdsb='';}			//			if($ws){			if(isset($_POST['duplicate_sku'])){								$probj = $wc_adp->product_duplicate( wc_get_product($ws['id']) );//				echo $probj->id.'<br>';$r4 = array('ttl' => 'Arcadia White Shaker','slg' => 'arcadia-white-shaker','prf' => '-ACW','cat' => 'GHI Arcadia White Shaker','tag' => 'arcadia-white-shaker');$r2 = array('ttl' => 'Frontier Shaker','slg' => 'frontier-shaker','prf' => '-FTS','cat' => 'GHI Frontier Shaker','tag' => 'frontier-shaker','rpr' => $tr['VPr']);lstdpl($probj, $r4, $r2);			}						$bdy.='<tr><td style="color: #ccc;">'.++$inc.'</td>	<td><input type="checkbox" name="vn_skubx[]" value="'.$tr['Sku'].'::'.$tr['Ln'].'::'.$tr['Title'].'::'.$tr['VPr'].'::'.$tr['UData'].'" '.$chbchk.' '.$chbdsb.' /></td>	<td>'.$tr['Sku'].'</td>	<td>'.$tr['Ln'].'</td>	<td style="text-align: right;">'.$tr['VPr'].'</td>	<td style="width: 150px; border-right: solid 2px #f00;">'.$tr['UData'].'</td>	<td style="width: 200px;color: #999;">'.$vs.'</td>	<td>'.((isset($ws))?$ws['id']:NULL).'</td>	<td><a target="_blank" href="'.SITE_URL.'/wp-admin/post.php?post='.$ws['id'].'&action=edit">'.((isset($ws))?$ws['sku']:NULL).'</a></td>	<td><a target="_blank" href="'.SITE_URL.'/product/'.$ws['slug'].'/">'.((isset($ws))?$ws['title']:NULL).'</a></td>	<td style="text-align: right; '.$st2.'">'.((isset($ws))?(($ws['rprice'])?$ws['rprice']:$ws['price']):NULL).'</td></tr>';				}  // Cycle end  ////////////////////////////////////////////////////////////////////		}		$subcontent = ($dflag)?'<form method="post" name="bfmm31" action="#"><table id="LTbl" cellspacing="1"><tr><th><input type="number" style="width: 50px" name="numslct" value="16" /></th>	<th style="border: solid 1px #f60; border-width: 1px 0px 0px 2px; color: #f60;"><input type="checkbox" name="chckall" id="chckall" value="1" OnChange="chckallbx(this);" /></th>	<th style="border: solid 1px #f60; border-width: 1px 2px 0px 0px; color: #f60;" colspan="4">'.$vw[0].'</th>	<th><input type="submit" name="duplicate_sku" value="Duplicate Sku" style="padding: 3px 9px;" /></th>	<th style="border: solid 1px #f60; border-width: 1px 2px 0px 2px; color: #f60;" colspan="4">'.$vw[1].'</th></tr><tr><th></th>	<th></th>	<th></th>	<th></th>	<th></th>	<th></th>	<th>'.$sbm_bttn.'</th>	<th></th>	<th></th>	<th></th>	<th></th></tr>'.$bdy.'</table></form>':'';$chkdirection_optlist = array(	'<option value="1">Vendor -> Website</option>',	'<option value="0">Website -> Vendor</option>',);$br_optlist = array(	'<option value="">Select Vendor</option>',	'<option value="aurafina">Aurafina</option>',	'<option value="cnc">CNC</option>',	'<option value="cubitac">Cubitac</option>',	'<option value="feather-lodge">Feather Lodge</option>',	'<option value="forevermark">Forevermark</option>',	'<option value="ghi">GHI</option>',	'<option value="msi">MSI</option>',	'<option value="us-cabinet-depot">USCD</option>',);$pgtitle = '<h4>Prices synchronization</h4>';//$pgcontent = <<<EOD__$pgcontent = '<!-- Page content start --><style>#TopLn{	width: 100%;	height: 20px;	background: #009; 	background-image: linear-gradient(to left, #012, #09c); 	padding: 3px 0px; 	color: #fff;}.vcell{ padding: 1px 3px; background: #fff;}.vcell:first-child {	background: #555; color: #aa9; text-align: right;	min-width: 140px;			}#LTbl td{	padding: 2px 5px;	font: normal 11px "Tahoma";}#LTbl tr:nth-child(odd){  background-color: #eee;}#rsrchdirection:hover{	text-decoration: underline;}</style><div id="TopLn"><div style="float: left;"><a style="margin-left: 35px; color: #fee;" OnClick="return pickurl(0);" href="cmpUVW.php?dm=1&sel=0">All ('.$inc_all.')</a><a style="margin-left: 35px; color: #fee;" OnClick="return pickurl(1);" href="cmpUVW.php?dm=1&sel=1">Not Avail. ('.$in_1.')</a><a style="margin-left: 35px; color: #fee;" OnClick="return pickurl(2);" href="cmpUVW.php?dm=1&sel=2">Price MSM. ('.$in_2.')</a><select style="margin-left: 35px;" name="parsebrand" id="parsebrand" OnChange="prsbrand(this);">'.getopts($br_optlist, $brand).'</select></div><div style="float: left; margin-left: 35px; cursor: pointer" title="Direction switch" id="rsrchdirection" DOnClick="sw_direction();">Vendor-Website</div><div style="float: right; margin-right: 35px;"><a href="unipricel.php">U-Price</a></div><div style="clear: both;"></div></div>'.(($postp)?'<div style="margin-top: 20px;">Items posted:</div>'.$postp:'').''.((isset($reqntnt))?$reqntnt:'').''.$subcontent.''.(($postp)?'<div style="margin-top: 50px;">Items posted:</div>'.$postp:'').'<script>function pickurl(s){var hr = window.location.href;	hr = hr.replace(/\&sel=\d/g, "\&sel="+s);	window.location.href = hr;return false;	}function prsdirection_______(o){	var hr = window.location.href;	if(hr.match(/\?dm=\d/)){ hr = hr.replace(/\?dm=\d/, "\?dm="+o.value); }	window.location.href = hr;}function sw_direction(){var hr = window.location.href;var d = document.getElementById("rsrchdirection");var val;	if(d.innerHTML == "Vendor-Website") { val=0; d.innerHTML = "Website-Vendor"; }	else                                { val=1; d.innerHTML = "Vendor-Website"; }	if(hr.match(/\?dm=\d/))	{ hr = hr.replace(/\?dm=\d/, "\?dm="+val); }	else 					{ hr = hr+"?dm=0"; }	window.location.href = hr;}function prsbrand(o){	var hr = window.location.href;	if(hr.match(/\&br=[\w\-].*/)){ hr = hr.replace(/\&br=[\w\-].*/, "\&br="+o.value); }	else { hr += "\&br="+o.value;}	window.location.href = hr;}document.getElementById("chckall").onclick = function() {var checkboxes = document.querySelectorAll(\'input[type="checkbox"]\');var bi = 0;var b2 = bfmm31.numslct.value;for(var checkbox of checkboxes) { 	if(b2>0 && bi>b2){ return; } 	bi++;	if(!checkbox.disabled){		checkbox.checked = this.checked;		}	}}</script><!-- Page content end -->';$page_title			= 'Unified Management System - Prices';$hsct				= 'pl2snch';$page_description	= '';$keywords			= '';$head_ext			= '';require_once('inc/_shell.php'); ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////function get_wsku($wsiarr, $sku) {	foreach($wsiarr as $wi){		$skt = preg_replace('/ NEW/','',$sku);		if($skt == $wi['sku']){	return $wi;	}		}	return null;}function getvendorptbl($s){ global $dbh;	return mysqli_fetch_assoc(mysqli_query($dbh, "SELECT Sku, VPr FROM cr_vndpl WHERE SkuWC='$s'"));}function lstdpl($prod, $rp4, $rp2){$pid = $prod->id;$pcat = wc_get_product_terms($pid, 'product_cat');$ptag = wc_get_product_terms($pid, 'product_tag');$catarr = replarray($pcat, $rp4['cat'], $rp2['cat']);$tagarr = replarray($ptag, $rp4['tag'], $rp2['tag']);$ir['ttl'] = preg_replace("/$rp4[ttl]/", $rp2['ttl'], preg_replace('/ \(Copy\)/','', $prod->name)); // nameoff($pobj->name);$ir['ttl'] = preg_replace('/ \(PCG\)-/','', $ir['ttl']);$ir['slg'] = preg_replace('/[-]{2,7}/', '-', strtolower( preg_replace('/\s/','-', $ir['ttl']))) ; // slugoff($arr['ttl']);$ir['sku'] = preg_replace("/$rp4[prf]$/", $rp2['prf'], preg_replace('/-\d$/', '', $prod->sku)); //skuoff($pobj->sku);$ir['catarr']  = $catarr;$ir['tagarr']  = $tagarr;$ir['rprice']  = $rp2['rpr'];echo 	$ir['sku'] . ' : ' . 	$ir['ttl'] . ' : ' . 	$ir['slg'] . ' : ' .'<br>';	chngrplmnt($prod, $ir);		foreach($catarr as $ct){ echo $ct.', ';} echo '<br>';	foreach($tagarr as $ft){ echo $ft.', ';} echo '<br>';			return;}function chngrplmnt($prod, $ir){$pid = $prod->id;wp_set_object_terms( $pid, $ir['catarr'], 'product_cat' );wp_set_object_terms( $pid, $ir['tagarr'], 'product_tag' );$prod->set_name($ir['ttl']);$prod->set_sku($ir['sku']);$prod->set_regular_price($ir['rprice']);$prod->set_price(NULL);$prod->save();//chngvrplmnt($pid, $ir);$uargs = array(	'ID' => $pid, 	'post_slug'     => $ir['slg'],	'post_content'  => $ir['ttl'],	'post_status'   => 'publish',	);		wp_update_post( $uargs ); return;	}function replarray($parr, $nsrch, $nrplc){	$r = array();	// $key = array_search($nsrch, $parr);	// echo $parr['name'];	// if($key){ $parr[$key] = $nrplc; } foreach($parr as $k=>$v){	if($v->name==$nsrch){ array_push($r, $nrplc); }	else { array_push($r, $v->name); }	}	return $r;}?>