<?php
require('inc/PdfToText.phpclass');

extract($_POST);
if(isset($readpdf)){
    if($_FILES['file']['type']=="application/pdf") {

		$pdf =  new PdfToText ($_FILES['file']['tmp_name']) ;
		$vf = $pdf -> Text; 		// or you could also write : echo ( string ) $pdf ;





echo $vf; exit;
        // $a->setFilename($_FILES['file']['tmp_name']); 
        // $a->decodePDF();
		// $vf = $a->output();


//$vr = explode("\n", $vf);


// //$vf = preg_replace('/\W/','',$vf);
$pr = array();
$ti=0;

$p_str=NULL;







foreach(explode("\n", $vf) as $r){

$p_str .= preg_replace('/[\x00-\x1F\x80-\xFF]/','',$r).'
';

//	array_push($pr, preg_replace('/[\x00-\x1F\x80-\xFF]/','',$r));
//	echo ++$ti.'. '.$r.'<br>';	
	}


$addr_sh2 = prspdf_str($p_str, 'ship2');


echo $addr_sh2;



exit;


// foreach($pr as $r){
// echo ++$ti.'. '.$r . '<br>';
// }
//		echo $vf;
    }
       
    else {
        echo "<p style='color:red;text-align:center'>
            Wrong file format</p>";
    }
}    



function prspdf_str($str, $sc){
	return substr($str, (strpos($str, 'Ship To')+8), (strpos($str, 'Terms')-strpos($str, 'Ship To')-8));
	
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
</body>
  
</html>