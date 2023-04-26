<?php 
$Err = array(
		"email"		   => array("_msg" => "E-mail address has an incorrect format",
//								"_fld" => "EMail",
								"_rgx" => '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/'),
		"pass"		   => array("_msg" => "Password is incorrect",
								"_rgx" => '/^[\d\w.-]{5,20}$/'),
		"pass_confirm" => array("_msg" => "Password confirmation failed"),
		"captcha"	   => array("_msg" => "Captcha symbols do not match",
								"_rgx" => '/^[\d\w]{4}$/'),
		);




function check_($step)
	{
	$chk_result = '';
	$err_cnt = 0;

	GLOBAL $Err;


	$ErrHL = 'class="ErrInput"';
	if($step == 2) { 
		if(chk_email($Err["email"]["_rgx"]))     { $err_cnt++; $chk_result .= $err_cnt . '. ' . $Err["email"]["_msg"]    . '<br>'; }
		if(chk_pass($Err["pass"]["_rgx"]))       { $err_cnt++; $chk_result .= $err_cnt . '. ' . $Err["pass"]["_msg"]     . '<br>'; }
		if(chk_pass_confirm())                   { $err_cnt++; $chk_result .= $err_cnt . '. ' . $Err["pass_confirm"]["_msg"]     . '<br>'; }
		if(chk_captcha($Err["captcha"]["_rgx"])) { $err_cnt++; $chk_result .= $err_cnt . '. ' . $Err["captcha"]["_msg"]  . '<br>'; }
		}
	if($step == 3) { 

		}
	return $chk_result;	
	}

	
function chk_email($rgx) {
	if(preg_match($rgx, $_POST["EMail"])) { return false; }
	else                                  { return true; }
	}
function chk_pass($rgx) {
	if(preg_match($rgx, $_POST["password"])) { return false; }
	else                                     { return true; }
	}
function chk_pass_confirm() {
	if($_POST["password_confirm"] == $_POST["password"]) { return false; }
	else                                                 { return true; }
	}
function chk_captcha($rgx) {
	session_start();
	if((preg_match($rgx, $_POST["captcha_code"]))    AND
	   (preg_match($rgx, $_SESSION["captcha_code"])) AND
	   ($_SESSION["captcha_code"] == $_POST["captcha_code"])) { return false; }
//	if(!preg_match($rgx, $_POST["captcha_code"])) { return true; }
	else                                                      { return true; }
	}
	
?>




