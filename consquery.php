<?phpinclude_once('inc/functions/general.php');include_once 'inc/functions.php';error_reporting(E_ALL | E_STRICT);include_once 'inc/functions.php';require_once 'wconfig.php';	if(isset($_GET['wcs'])){	$cls = "Sku_2020='$_GET[ds]' AND Style_2020='$_GET[stl]'";	if($_GET['wcs']){			$que = NULL;		$res = mysqli_query($dbh, "SELECT * FROM wc_consolisku WHERE $cls");		if(mysqli_fetch_assoc($res)['id']){			$que = "UPDATE wc_consolisku SET 				Sku_WC  = '$_GET[wcs]', 				Vendor  = '$_GET[vnd]'			WHERE $cls";			}		else{			$que = "INSERT INTO wc_consolisku ( Sku_WC, Vendor, Sku_2020, Style_2020 ) 				VALUES ('$_GET[wcs]', '$_GET[vnd]',	'$_GET[ds]', '$_GET[stl]')";			}		if(mysqli_query($dbh, $que)){ echo $_GET['ds'].' assigned succesfully.'; }		else { echo 'ERROR: '.$_GET['ds'].' not assigned'; }		}	else{ 		$que = "DELETE FROM wc_consolisku WHERE $cls";		if(mysqli_query($dbh, $que)){ echo 'Assignment of '.$_GET['ds'].' removed succesfully.'; }		else { echo 'ERROR: '.$_GET['ds'].' not removed'; }		}	}exit;?>