<html>
<head><title><?php echo $page_title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<meta name="Content-Language"   content="russian, english" />
<meta http-equiv="Page-Enter"   content="BlendTrans(Duration=0.2)" />
<meta name="description"        content="<?php echo $page_description; ?>" />
<meta name="keywords"           content="<?php echo $keywords; ?>" />
<meta name="author"             content="Zaza G. Kandelaki" />
<meta name="copyright"          content="Copyright ©, 2018 Power of Grace Inc." />
<meta name="home_url"           content="<?php echo ROOT_URL ?>" />
<meta name="robots"             content="index,all" />
<!-- base href="<?php echo ROOT_URL ?>/" / -->
<link href="files/css/Shell.css"  type="text/css" rel="stylesheet" />
<link href="files/css/Shell2.css" type="text/css" rel="stylesheet" />
<script src="files/js/Base.js" type="text/javascript"></script>
<!-- link href="files/favicon.ico"   type="image/x-icon" rel="shortcut icon" / -->
<?php echo $head_ext ?>
</head>

<body>
<div id="container" tyle="position: absolute; left: 0p; bottom: 0px; height: 100%;">
	<div id="Top">
	<div><h3><?php echo $page_title; ?></h3></div>
	<?php echo $inftop; ?>
	</div>
	<div id="MContent">
	<!-- Main content  -->

<?php echo $pgcontent; ?>

	<!-- End main content -->	
	</div>
	<div style="clear: both;"></div>
	<div class="Footer" style="position: absolute; left: 0p; bottom: 0px; height: 20px; width: 99%;">Copyright &copy; VERONA LLC (2018-2019)&nbsp;&nbsp;&nbsp;All rights reserved</div>
</div>
</body>
</html>
<!-- End --><!-- BODY ENDS HERE -->
<!-- a padding to disable MSIE and Chrome friendly error page -->
