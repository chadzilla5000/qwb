<?php


?>


<html>
<head><title>QB WC Master</title>
</head>
<body>


<h1>QBW Tools</h1>

<div><a href="xchng.php" target="_blank">XChange</a></div>
<div><a href="wc_queueing.php" target="_blank">WC Queueing</a></div>
<div><a href="docs/web_connector/example_web_connector_import.php" target="_blank">WC Import</a></div>
<div><a href="docs/web_connector/example_app_web_connector/form.php" target="_blank">Add Customer</a></div>



<form action="#" method="post" name="fg7">
<div style="margin-top: 45px;"><label style="font: bold 29px 'Arial'"> Log </label>
<input style="float: right;" type="submit" name="fsbm" value="Clear" />
<div>
</form>

<?php

if(isset($_POST['fsbm'])){
$fh = fopen( 'mzdebug.log', 'w' );
fclose($fh);
}



$myfile = fopen('mzdebug.log', "r") or die("Unable to open file!");
$fcnt = (filesize('mzdebug.log')>0)?fread($myfile,filesize('mzdebug.log')): 'Log file is empty';
fclose($myfile);

?>
<textarea style="width: 100%; height: 350px; background: #030; color: #cfc; "><?=$fcnt;?> </textarea>
<div style="padding: 25px; background: #000; color: #fff;><?=$fcnt;?></div>
</div>




</div>






</body>

</html>


