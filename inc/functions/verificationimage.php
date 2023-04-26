<?php
header('Content-type: image/jpeg');

$width = 50;
$height = 15;

$my_image = imagecreatetruecolor($width, $height);

imagefill($my_image, 0, 0, 0xffcc00);

// add noise
for ($c = 0; $c < 75; $c++){
	$x = rand(0,$width);
	$y = rand(0,$height);
	imagesetpixel($my_image, $x, $y, 0x000099);
	}

$x = rand(1,5);
$y = rand(1,1);

$rand_string = rand(10000,99999);
imagestring($my_image, 5, $x, $y, $rand_string, 0x000099);

//setcookie('tntcon',(md5($rand_string).'a4xn'));
//setcookie('tntcon', $rand_string);
setcookie('tntcon','___');

imagejpeg($my_image);
imagedestroy($my_image);
?>