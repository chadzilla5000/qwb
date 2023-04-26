<?php
//require_once('basics.php');

//get_session();
session_start();
$_SESSION["cid"] = md5(rand());
$_SESSION["captcha_code"] = substr($_SESSION["cid"], 0, 4);

$target_layer             = imagecreatetruecolor(30,15);
$captcha_background       = imagecolorallocate($target_layer, 238, 221, 153);
imagefill($target_layer, 0, 2, $captcha_background);
$captcha_text_color       = imagecolorallocate($target_layer, 192, 154, 98);
imagestring($target_layer, 2, 3, 1, $_SESSION["captcha_code"], $captcha_text_color);
header("Content-type: image/jpeg");
imagejpeg($target_layer);


//session_start();
//$random_alpha             = md5(rand());
//$_SESSION["captcha_code"] = substr($random_alpha, 0, 4);
//$captcha_code = $_SESSION["captcha_code"];

//$captcha_code             = substr($_SESSION["id"], 0, 4);
//$_SESSION["id"]           = $random_alpha;

/*
$target_layer             = imagecreatetruecolor(30,15);
$captcha_background       = imagecolorallocate($target_layer, 238, 221, 153);
imagefill($target_layer, 0, 2, $captcha_background);
$captcha_text_color       = imagecolorallocate($target_layer, 192, 154, 98);
imagestring($target_layer, 2, 3, 1, $captcha_code, $captcha_text_color);
header("Content-type: image/jpeg");
imagejpeg($target_layer);
*/

?>