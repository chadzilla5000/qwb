<?php
require_once('PHPMailer-master/src/PHPMailer.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$file = NULL;



/*
if($_GET['file'])
{
 $file=$_GET['file'];
 if (file_exists($file))
 {
  header('Content-Description: File Transfer');
  header('Content-Type: image/png');
  header('Content-Disposition: attachment; filename='.basename($file));
  header('Content-Transfer-Encoding: binary');
  header('Expires: 0');
  header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
  header('Pragma: public');
  header('Content-Length: ' . filesize($file));
  ob_clean();
  flush();
  readfile($file);
  unlink($file);
//  exit;
 }
}
*/



if($_POST['data'])
{
  header('Content-Description: File Transfer');
  header('Content-Type: image/png');
  header('Content-Disposition: attachment; filename='.basename($file));
  header('Content-Transfer-Encoding: binary');
  header('Expires: 0');
  header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
  header('Pragma: public');
  header('Content-Length: ' . filesize($file));
  ob_clean();
  flush();


 $data = $_POST['data'];
 $file = md5(uniqid()) . '.png';
 $uri =  substr($data,strpos($data,",")+1);
 file_put_contents('./'.$file, base64_decode($uri));
// echo $file;
// exit();
}




//$file_to_attach = (isset($_POST['data']))?$_POST['data']:NULL;


$email = new PHPMailer();
$email->SetFrom('custserv@waverlycabinets.com', 'Zst'); //Name is optional
$email->Subject   = 'Send image attached';
$email->Body      = 'Shoudl be file attached';
$email->AddAddress( 'zaza@waverlycabinets.com' );

//$email->AddEmbeddedImage($file, 'oc.png');
$email->AddAttachment( $file, 'sdk.png' );

return $email->Send();


?>