<?php


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head><title><?php echo $page_title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<meta name="Content-Language"   content="russian, english" />
<meta http-equiv="Page-Enter"   content="BlendTrans(Duration=0.2)" />
<meta name="description"        content="<?php echo $page_description; ?>" />
<meta name="keywords"           content="<?php echo $keywords; ?>" />
<meta name="author"             content="Zaza G. Kandelaki" />
<meta name="copyright"          content="Copyright ©, 2017 Power of Grace Inc." />
<meta name="home_url"           content="<?php echo ROOT_URL ?>" />
<meta name="robots"             content="index,all" />
<!-- base href="<?php echo ROOT_URL ?>/" / -->
<link href="files/css/Shell.css" type="text/css"     rel="stylesheet" />
<link href="files/favicon.ico"   type="image/x-icon" rel="shortcut icon" />
<?php echo $head_ext ?>
</head>

<?php

$logmsg = '';
$loginf = '';
$tmenu  = '';

if($_GET[cmd]=='login'){
	require_once('inc/functions/authorization.php'); 
//$usn = login();
	if(login()) {$logmsg = '<div style="color: green;">Вход выполнен</div>';}
	else {$logmsg = '<div style="color: red;">Неверный логин/пароль</div>';}
	}

if($_GET[cmd]=='logout'){
	require_once('inc/functions/authorization.php'); 
	logout();
//	$lghsh = NULL;
	}

//	$lghsh = logged();

?> 
<body>
<div id="container">
	<div id="Top">
<?php 
if($lghsh[Prvlg]){
switch ($lghsh[Prvlg]){
	case 'A': 
	$tmenu = '
<li><a href="index.php">Главная</a></li>
<li><a href="orders.php">Заказы</a></li>
<li><a href="masters.php">Мастера</a></li>
'; 
break;
	case 'B': 	$tmenu = '
<li><a href="index.php">Главная</a></li>
<li><a href="orders.php">Заказы</a></li>
<li><a href="masters.php">Мастера</a></li>
'; 
break;
	default: 
break;
}
$loginf = '
	<div><h3>UMS</h3></div>
	<div>'.$lghsh[Login].' - <a href="'.$_PHP_SELF.'?cmd=logout">Выход</a></div>
';
echo $loginf; ?>

	</div>
<div id="TMenu"><ul><?php echo $tmenu; ?></ul></div>
<div style="clear: both;"><?php echo $logmsg; ?></div>
<div id="MContent">
<!-- Main content  -->

<?php echo $pgcontent; ?>

<!-- End main content -->	
</div>

<div style="clear: both;"></div>

<div class="Footer"></div>

</div>

</body>
</html>
<!-- End --><!-- BODY ENDS HERE -->
<?php  return; } 
else{
$loginf = '
	<div><h3>Unified Management System</h3></div>';
echo $loginf; ?>
	</div>
<?php
	require_once('inc/functions/authorization.php'); 
	echo logform(); 
?>

</div>

</body>
</html>

<?php
	return; 
} 
?>
