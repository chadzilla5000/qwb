<?php 

ini_set("display_errors", 1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

include_once('basics.php'); 

//$dbh     = connect_DB();

function login(){ global $dbv;

//echo $_POST[login];// . ' - ' . $_REQUEST['login'];
	$row = NULL;
	$passhsh = sha1($_POST['password'] . SWORD, FALSE);
	$Sid = session_id(); echo 'Sess ID - '.$Sid;
// echo $passhsh;	exit;  /////  Get passhsh ///////
	$mquery  = "SELECT * FROM user WHERE Login = '$_POST[login]' AND Pass = '$passhsh'"; 
	$result  = mysqli_query($dbv, $mquery) or die (mysql_error());
	if($result){
		$row = mysqli_fetch_array($result);
		get_session();
		//setcookie('UNISessId', $_SESSION["id"]);
		$bquery = "UPDATE user SET SessH = '$Sid' WHERE Id = '$row[Id]'";
		mysqli_query($dbv, $bquery) or die (mysql_error());
		}
	return $row;
	}

function logout(){ global $dbv;
	$lgh = logged();
	$mquery = "UPDATE user SET SessH = 'b83youtes334' WHERE Id = '$lgh[Id]'";
	mysqli_query($dbv, $mquery) or die (mysql_error());

	$_SESSION = array();
//	session_destroy();
//	$lghsh=NULL;
	return;
	}
	
//////////////////////////////////////////////////////
function logform(){ //////////////////////////////////
$login = (isset($_POST['login']))?$_POST['login']:NULL;
	return '
<div id="LogForm">	
<form action="index.php?cmd=login" method="post">
<table cellspacing="1">
<tr><td colspan="2" style="text-align: center; background: #999; color: #fff; font-weight: bold;">Authorization</td></tr>
<tr><td>User name &nbsp;</td>
	<td><input type="text" name="login" value="'.$login.'" /></td>
</tr>
<tr><td>Password &nbsp;</td>
	<td><input type="password" name="password" size="20" maxlength="20" /></td>
</tr>
<tr><td style="vertical-align: bottom; font-size: 13px;"></td>
	<td><input type="submit" value="Login" style="padding: 2px 10px;" /> &nbsp;
		<a href="account.php" style="font: normal 12px \'Arial\'; color: #f00;">Forgot password</a>
	</td>
</tr>
</table>

</form>
</div>';
	}
////////////////////////////////////////////////////////
	
?>




