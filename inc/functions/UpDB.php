<?php 

function add_sitter()
	{
	session_start();
	$passhsh = sha1($_POST[password] . $sword, TRUE);
	$dbh = connect_DB();
	$mquery = "INSERT INTO sitter (EMail, PassHsh, SessId)
	VALUES ('$_POST[EMail]', '$passhsh', '$_SESSION[cid]')";

	mysql_query($mquery) or die (mysql_error());
	$id = mysql_insert_id();
	
	mysql_close($dbh);
	$_SESSION[id] = $_SESSION[cid];

	return $id;
	}

function edit_sitter()
	{
//return $_POST["SessId"];
	
	session_start();

$now = new DateTime();
$now->setTimezone(new DateTimeZone('Europe/Moscow'));
$dt = $now->format('Y-m-d H:i:s');

	$dob    = $_POST["DYear"].'-'.$_POST["DMon"].'-'.$_POST["DDay"];
	$dbh    = connect_DB();
	$mquery = "UPDATE sitter 
		SET FName         = '$_POST[FName]',
			MName         = '$_POST[MName]',
			LName         = '$_POST[LName]',
			Gender        = '$_POST[Gender]',
			DoB           = '$dob',
			Citizenship   = '$_POST[Citizenship]',
			Education     = '$_POST[Education]',
			MEducation    = '$_POST[MEducation]',
			Ruslang       = '$_POST[Ruslang]',
			Exper_Years   = '$_POST[Exper_Years]',
			Exper_details = '$_POST[Exper_details]',
			Worktype      = '$_POST[Worktype]',
			Wage          = '$_POST[Wage]',
			Currency      = '$_POST[Currency]', 
			TFrame        = '$_POST[TFrame]',
			Addinfo       = '$_POST[Addinfo]',
			Region        = '$_POST[Region]',
			Phone         = '$_POST[Phone]', 
			ApplyDT       = '$dt', 
			Available     = '$_POST[Available]'
		WHERE SessId      = '$_SESSION[cid]'";

//	$mquery = "UPDATE sitter SET FName = '$_POST[FName]' WHERE SessId = '$_POST[SessId]'";
		
	mysql_query($mquery) or die (mysql_error());
	
//	$mquery = "SELECT * FROM sitter WHERE SessId = '$_POST[SessId]'"; 
	$mquery = "SELECT * FROM sitter WHERE SessId = '$_SESSION[cid]'"; 


	$result = mysql_query($mquery);
	$arrrow = mysql_fetch_array($result);
	
	mysql_close($dbh);
	
	upload_pic($arrrow["Id"]);
	
	return $arrrow;
	}

function confirm_sitter()
	{
	session_start();
	$dbh    = connect_DB();
	$mquery = "UPDATE sitter 
		SET Confirm       = '1'
		WHERE SessId      = '$_SESSION[cid]'";
//		WHERE SessId      = '$_POST[SessId]'";

	mysql_query($mquery) or die (mysql_error());
	mysql_close($dbh);
	return;
	}

	
	
////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////



function add_sitter_prev()
	{
	session_start();
	if($_POST["captcha_code"] == $_SESSION["captcha_code"]) {
		$dbh = connect_DB();
		$dob    = $_POST["DYear"].'-'.$_POST["DMon"].'-'.$_POST["DDay"];
		$passhsh = sha1($_POST[password], TRUE);
		$mquery = "INSERT INTO sitter (FName, MName, LName, Gender, DoB, Citizenship, Education, MEducation, Ruslang, Exper_Years, Exper_details, Worktype, Wage, Currency, TFrame, Addinfo, Region, EMail, Phone, Available, PassHsh)
	VALUES ('$_POST[FName]', '$_POST[MName]', '$_POST[LName]', '$_POST[Gender]', '$dob', '$_POST[Citizenship]', '$_POST[Education]', '$_POST[MEducation]', '$_POST[Ruslang]', '$_POST[Exper_Years]', '$_POST[Exper_details]', '$_POST[Worktype]', '$_POST[Wage]', '$_POST[Currency]', '$_POST[TFrame]', '$_POST[Addinfo]', '$_POST[Region]', '$_POST[EMail]', '$_POST[Phone]', '$_POST[Available]', '$passhsh')";

//	mysql_query($mquery) or die (mysql_error());

		$id = mysql_insert_id();
//	$clause = 'Id = $id';
	$mquery = "SELECT * FROM sitter WHERE Id = '$id'"; 


//	$result = mysql_query($mquery);
//	$arrrow = mysql_fetch_array($result);
	
		mysql_close($dbh);


		$uptxt = 'OK';
//	$upresult = upload_pic($arrrow["Id"]);
		$upresult = upload_pic('123');
		if($upresult != '')
			{
//		$uptxt = $upresult . '<br>' . upl_other($arrrow["Id"]);
		$uptxt = $upresult . '<br>' . upl_other('234');
			}

		}
	}
function upphoto()
	{
	$uptxt = 'OK';
	$upresult = upload_pic($_POST['imname']);
	if($upresult != '')
		{
		$uptxt = $upresult . '<br>' . upl_other($_POST['imname']);
		$pgcontent .= '<br><br>' . $uptxt;
		}
	else{
		$pgcontent .= 'Фотография загружена - ' . $uptxt;
		}

	}

?>




