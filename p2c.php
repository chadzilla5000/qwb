<?php
require('inc/pdf2text.php');

//echo 'VvV'; exit;


extract($_POST);
if(isset($readpdf)){
    if($_FILES['file']['type']=="application/pdf") {
        $a = new PDF2Text();
        $a->setFilename($_FILES['file']['tmp_name']); 
        $a->decodePDF();
		$vf = $a->output();


//$vf = preg_replace('/^[?]{1,5}/', '', $vf);

//echo $vf; exit;


// $vr = explode("\n", $vf);



// $ti=0;
// foreach($vr as $r){
	
// $f = preg_replace('/[\x00-\x1F\x80-\xFF]/','',$r);
// echo $f;	
// }


$p_str=NULL;


foreach(explode("\n", $vf) as $r){

$p_str .= preg_replace('/[\x00-\x1F\x80-\xFF]/','',$r).'
';

//	array_push($pr, preg_replace('/[\x00-\x1F\x80-\xFF]/','',$r));
//	echo ++$ti.'. '.$r.'<br>';	
	}


$p_inc=2;


//echo $p_str; exit;


$addr_bl2 = prspdf_str($p_str, 'bill2', $p_inc);
$addr_sh2 = prspdf_str($p_str, 'ship2', $p_inc);


echo $addr_bl2.'<br><br>';
echo $addr_sh2.'<br><br>';




$itset_1 = prspdf_str($p_str, 'item', $p_inc);

//echo $itset_1.'<br><br>';
$dn = 0;
$rowcount=0;

$p_contnt = '<table class="TBL" width="100%" cellspacing="1" style="margin-top: 25px;">';
$p_contnt .= '<tr>';





foreach(explode("\n", $itset_1) as $v){
	
	$p_contnt .= '<td>'.$v.'</td>';
	// if($v=='Item'){ $p_contnt .= '<th>'.$v.'</th>'; }
	// if($v=='Remark'){ $p_contnt .= '<th>'.$v.'</th>'; }
	// if($v=='Description'){ $p_contnt .= '<th>'.$v.'</th>'; }
	// if($v=='Unit $'){ $p_contnt .= '<th>'.$v.'</th>'; }
	// if($v=='Amount'){ $p_contnt .= '<th>'.$v.'</th></tr>'; }
//	$p_contnt .=  $v . '<br>';
$dn++;
}

$p_contnt .= '</tr>';

$p_contnt .= '</table>';













//		echo $vf;
		}       
	else { echo "<p style='color:red;text-align:center'>Wrong file format</p>"; }
	}    


function prspdf_str($str, $s, &$p_inc){
	$spos=$epos=0;
	
	switch($s){
		case 'bill2':
			$spos = strpos($str, 'Bill To', $p_inc);	
			$epos = strpos($str, 'Ship To', $p_inc);	
			$p_inc = $epos;	
			return substr($str, $spos+9, $epos-$spos-9); break;
		case 'ship2':
			$spos = strpos($str, 'Ship To', $p_inc);	
			$epos = strpos($str, 'Terms', $p_inc);	
			$p_inc = $epos;	
			return substr($str, $spos+9, $epos-$spos-9); break;
		case 'item':
			$spos = strpos($str, "B/O ETA 1", $p_inc);	
			$epos = strpos($str, 'TSG
Bill To', $p_inc);	
			$p_inc = $epos;	
			return substr($str, $spos+11, $epos-$spos-13); break;
		}
		
	return NULL;
}


function splitem_str($str, $s, &$p_inc){
	$spos=$epos=0;
	
	switch($s){
		case 1:
			$spos = strpos($str, 'Qty', $p_inc);	
			$epos = strpos($str, 'Item', $p_inc);	
			$p_inc = $epos;	
			return substr($str, $spos+9, $epos-$spos-9); break;
		}
		
	return NULL;
}

   
?>
  
<html>
  
<head>
    <title>Read pdf php</title>
</head>
  
<body>
    <form method="post" enctype="multipart/form-data">
        Choose Your File
        <input type="file" name="file" />
        <br>
        <input type="submit" value="Read PDF" name="readpdf" />
    </form>
	
	
	<?php echo $p_contnt; ?>
</body>
  
</html>