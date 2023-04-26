<?php
include_once('inc/functions/general.php');
include_once 'inc/functions.php';
error_reporting(E_ALL | E_STRICT);

include_once 'inc/functions.php';
require_once 'wconfig.php';


	$optitlist = allqbitems();
	if(isset($_GET['dv'])){	
		$ds = $_GET['dv'];
		echo '
<select id="massign2020_'.$ds.'" name="massign2020_'.$ds.'"><option value="">Select WC Sku</option>
'.$optitlist.'
</select>
<input type="button" name="massignbttn_'.$ds.'" title="'.$ds.'" value="Assign" OnClick="assign2020(this);" />';
		}
exit;






?>