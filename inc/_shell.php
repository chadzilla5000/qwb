<html>
<head><title><?php echo $page_title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<meta name="Content-Language"   content="russian, english" />
<meta http-equiv="Page-Enter"   content="BlendTrans(Duration=0.2)" />
<meta name="description"        content="<?php echo $page_description; ?>" />
<meta name="keywords"           content="<?php echo $keywords; ?>" />
<meta name="author"             content="Zaza G. Kandelaki" />
<meta name="copyright"          content="Copyright ©, 2020 Waverly Cabinets Inc." />
<meta name="home_url"           content="<?php echo ROOT_URL ?>" />
<meta name="robots"             content="NoIndex,NoFollow" />
<!-- base href="<?php echo ROOT_URL ?>/" / -->
<link href="files/css/Shell.css" type="text/css"     rel="stylesheet" />
<script src="files/js/Base.js" type="text/javascript"></script>
<link href="https://waverlycabinets.com/qbw/favicon.ico"   type="image/x-icon" rel="shortcut icon" / >
<?php echo $head_ext ?>
</head>

<body style="margin: 0px; padding: 0px;">
<div id="container">
	<div id="Top">
	<div><h3 style="float: left;">Waverly Terminal</h3></div>
	<?php echo $loginf; ?>
	<div style="float: right;"><a href="#" title="Context Help" OnClick="return HelpConsole('<?php echo $hsct ?>');">Help</a></div>
	</div>
	<div id="TMenu"><ul><?php echo $tmenu; ?></ul></div>
	<div style="float: right;"></div>
	<div style="clear: both;"><?php echo $logmsg; ?></div>
	<div id="MContent">
	<!-- Main content  -->

<?php echo $pgcontent; ?>

	<!-- End main content -->	
	</div>
	<div style="clear: both; height: 40px;"></div>
	<div class="Footer">Copyright &copy; WAVERLY CABINETS Inc. (2018-2023)<br>All rights reserved</div>
</div>
<?php
global $msg;
$vsbl=($msg)?'block':'none';
?>
<div id="Shed"></div>
<div id="popup">
<div id="pupttl" style="float: left; font: bold 14px 'Tahoma'">PopUp</div>
<div style="float: right;"><img class="zclose" src="<?=ROOT_URL?>/files/img/btnclosehover.png" OnClick="cpup();" title="Close" /></div>
<div style="clear: both; border-bottom: solid 1px #999; height: 8px;">&nbsp;</div>
<div style="float: right;"><input style="border: none; outline: none; padding: 3px;" type="text" name="srchproc" value="" placeholder="Search process" OnKeyUp="dynsrchproc(this);" /></div>
<div id="pupcnt">&nbsp;</div>
</div>
<div id="RSlider" style="display: <?php echo $vsbl; ?>;"><?php echo $msg; ?></div>

<div id="CConsole" OnMouseOver="bgtrrmv(this);" OnMouseOut="bgtrput(this);" 
style="position: fixed; width: 80%; bottom: 0px; left: 200px; height: 90%; background: #fff; border: solid 1px #333; border-radius: 17px 17px 0px 0px; box-shadow: 10px 15px 15px #555; display: none; z-index: 233;">
<div style="text-align: right; padding: 9px;">
<a id="consolink" href="#" target="_blank"><img class="zclose" src="files/img/printicon.jpg" title="Switch to printer friendly page" /></a> &nbsp; &nbsp;
<img class="zclose" src="files/img/btnclosehover.png" OnClick="ClsConsole();" style="cursor: pointer;" title="Close Console" /></div>
<div id="InConsole" style="height: 90%; overflow: auto;">&nbsp;

</div>
</div>

<div id="<?php echo $hsct; ?>_Prt" style="position: fixed; width: 45%; top: 42px; right: 20px; height: 75%; background: #fff; border: solid 1px #333; border-radius: 0px 0px 17px 17px; box-shadow: 10px 15px 15px #555; display: none; z-index: 235;">
<div style="text-align: right; padding: 9px;">
<a id="consolink" href="#" target="_blank" OnClick="return PrintMConsole('<?php echo $hsct; ?>')"><img class="zclose" src="files/img/printicon.jpg" title="Switch to printer friendly page" /></a>&nbsp;&nbsp;
<img class="zclose" id="PPConsHelp_Chb" src="files/img/btnclosehover.png" OnClick="HelpConsole('<?php echo $hsct; ?>')" style="cursor: pointer;" title="Close Console" /></div>
<div id="<?php echo $hsct; ?>_Chd" style="height: 85%; margin: 25px; overflow: auto;">&nbsp;

</div>
</div>

</body>
</html>
<!-- End --><!-- BODY ENDS HERE -->
<!-- a padding to disable MSIE and Chrome friendly error page -->
