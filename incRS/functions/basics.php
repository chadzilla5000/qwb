<?php 
/*
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
*/
//include_once('inc/_init.php');

include_once 'config/basic.cfg';

global $site;
ini_set("display_errors", 1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

//if($site){ include_once('config/livebasic.cfg'); }
//if(1){ include_once('config/livebasic.cfg'); }
//else     { include_once('config/testbasic.cfg'); }

$dbh = DB_connect();
//$dbh = NULL;
//$dbadm = DB_connect();
$dbv = DB_connect();

function DB_connect() { 
	$sq = new mysqli(ServerN, MySQLID, MySQLP, DBName); 
	if($sq->connect_errno) { printf("Connect failed: %s\n", $sq->connect_error); exit(); }
	$sq->set_charset("utf8"); return $sq;
	}
function DB_connectLtd_______() { 
	$sq = new mysqli(ServerN, MySQLID_Ltd, MySQLP_Ltd, DBName); 
	if($sq->connect_errno) { printf("Connect failed: %s\n", $sq->connect_error); exit(); }
	$sq->set_charset("utf8"); return $sq;
	}
function DB_connectX________() { 
	$sq = new mysqli(ServerN, MySQLID_X, MySQLP_X, DBName); 
	if($sq->connect_errno) { printf("Connect failed: %s\n", $sq->connect_error); exit(); }
	$sq->set_charset("utf8"); return $sq;
	}

function evaccess($sct){
	global $dbh;
	global $lghsh;
	global  $accss;
	$prv =($lghsh['Prvlg'])?$lghsh['Prvlg']:'X';
	$rs = mysqli_query($dbh, "SELECT * FROM access WHERE Id='$sct'") or die (mysql_error());
	$r   = mysqli_fetch_array($rs);
	$accss = $r[$prv];
	return $r[$prv];
}

function ____dbacc($sct){
	global $dbh;
	global $dbv;
	global $lghsh;
	$prv =($lghsh['Prvlg'])?$lghsh['Prvlg']:'X';
	$rs = mysqli_query($dbv, "SELECT * FROM access WHERE Id='$sct'") or die (mysql_error());
	$r   = mysqli_fetch_array($rs);
	$dbh = ($r[$prv]==2) ? DB_connect() : (($r[$prv]==1) ? DB_connectLtd() : DB_connectX());
	// if($r[$prv] == 2)		{ $dbh = DB_connect(); }
	// elseif($r[$prv] == 1)	{ $dbh = DB_connectLtd(); }
	// else					{ $dbh = NULL; }
return;
}
	
	
function getrowhsh($tbl, $cls){ global $dbh;
$hsh = array();
if($rs=mysqli_query($dbh, "SELECT * FROM ".$tbl." WHERE ".$cls)) { 
	$hsh = mysqli_fetch_assoc($rs); 
	$rs->free(); 
	}
return $hsh;
}

function dbstrcount($tbl, $cls){ global $dbh;
$ct = 0;
if($rs = mysqli_query($dbh, "SELECT COUNT(*) FROM $tbl WHERE $cls")){ 
$ct = mysqli_fetch_row($rs)[0]; 
$rs->free(); 
}
return $ct;
}


function ins_tbl($tbl){ global $dbh; ////////////////////////////////////////////////////////////

	$keys = array();
	$vals = array();
	foreach($_POST as $rk => $rv){ 
		if($rk){ 
			$chc = mysqli_query($dbh, "SELECT COUNT(*) FROM information_schema.COLUMNS
			WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='$tbl' AND COLUMN_NAME='$rk';");
			if(mysqli_fetch_row($chc)[0]>0) { 
				if($rk=='Pass'){ $rv = sha1($rv . SWORD, FALSE); }
				array_push($keys,$rk);
				array_push($vals,'\''.$rv.'\'');
				}
			}
		}
	$kstr = join(', ', $keys);
	$vstr = join(', ', $vals);

	$q = "INSERT INTO $tbl ($kstr) VALUES ($vstr)"; 
	
	$dbh->query("LOCK TABLES $tbl WRITE");
	mysqli_query($dbh, $q); echo mysqli_error($dbh);
	$lid = $dbh->insert_id;
	if($tbl=='order2'){
		$adup = "UPDATE order2 SET OrderID=Id, OrdNum=IF(OrdNum>0,OrdNum,Id), AmtOrdered=IF(AmtOrdered>0,AmtOrdered,1) WHERE Id='$lid'";	
		mysqli_query($dbh, $adup); echo mysqli_error($dbh);
		}
	$dbh->query("UNLOCK TABLES");
	
	return $lid; //////////////////////////////////////////////////////////////////////////
} ///////////////////////////////////////////////////////////////////////////////////

function rcrd_tbl($tbl, $id){ global $dbh; ////////////////////////////////////////////////////////////

	$kvs = array();
	foreach($_POST as $rk => $rv){ 
		if($rk){ $chc = mysqli_query($dbh, "SELECT COUNT(*) FROM information_schema.COLUMNS
			WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='$tbl' AND COLUMN_NAME='$rk';");
			if(mysqli_fetch_row($chc)[0]>0) { 
				if($rk=='Pass'){ 
					if($rv != ""){ $rv = sha1($rv . SWORD, FALSE); }
					else                { continue; }
					}
				array_push($kvs, $rk.'=\''.$rv.'\''); 
				}
			}
		}
	$qstr = join(', ', $kvs);
	$q = "UPDATE $tbl SET $qstr WHERE Id='$id'"; 

	$dbh->query("LOCK TABLES $tbl WRITE");
	mysqli_query($dbh, $q); echo mysqli_error($dbh);
	$dbh->query("UNLOCK TABLES");
return; ///////////////////////////////////////////////////////////////////////////////
} ////////////////////////////////////////////////////////////////////////////////////

function deleterec($tbl, $id){ global $dbh; ////////////////////////////////////////////////////////////
	global $lghsh;
	switch($lghsh['Prvlg']){
		case 'A': break;
		case 'B': break;
		case 'C': return; break;
		case 'D': return; break;
		case 'E': return; break;
		case 'F': return; break;
		default : return; break;
	}
	$q = "DELETE FROM $tbl WHERE Id='$id'"; 

	$dbh->query("LOCK TABLES $tbl WRITE");
	mysqli_query($dbh, $q); echo mysqli_error($dbh);
	$dbh->query("UNLOCK TABLES");
return; ///////////////////////////////////////////////////////////////////////////////
} ////////////////////////////////////////////////////////////////////////////////////

function dltails($id){ 	global $dbh;

	$q1 = "DELETE FROM stage WHERE ItemID='$id'"; 
	$q2 = "DELETE FROM stgstp WHERE ItemID='$id'"; 

	$dbh->query("LOCK TABLES stage WRITE, stgstp WRITE");
	mysqli_query($dbh, $q1); echo mysqli_error($dbh);
	mysqli_query($dbh, $q2); echo mysqli_error($dbh);
	$dbh->query("UNLOCK TABLES");
return; ///////////////////////////////////////////////////////////////////////////////
} ////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////
	
	
function get_session()
	{
//	session_start();
//	$_SESSION[id] = md5(rand());
	return;
	}

function ____get_member()
	{
	$row;
//	$mquery  = "SELECT * FROM sitter WHERE SessId = '$_COOKIE[UNISessId]'"; 
	$mquery  = "SELECT * FROM sitter WHERE SessId = '$_SESSION[id]'"; 
	$dbh     = connect_DB();
//	mysql_query($mquery);// or die (mysql_error());
	$result  = mysql_query($mquery) or die (mysql_error());
	if($result){ $row = mysql_fetch_array($result); }
	mysql_close($dbh);
	return $row;
	}

function hint($title, $hint, $wdth){
	return		'<span class="ntxt">'.$title.' 
			<div class="infobox" style="min-width: '.$wdth.'px;">'.$hint.'</div>
		</span>';
	}
function display_txt($lnk){
    $content = file_get_contents($lnk);
    return mb_convert_encoding($content, 'windows-1251', mb_detect_encoding($content, 'UTF-8, windows-1251', true));
	}

function logged(){ global $dbv;

	$row = NULL;
	$ssh = session_id();
	$mquery = "SELECT * FROM user WHERE SessH = '$ssh'"; 
	$result = mysqli_query($dbv, $mquery) or die (mysqli_error($dbv));
	if($result){ $row = mysqli_fetch_array($result); }
return $row;
}

function getitle($tbl, $id){ global $dbh;

$rsl = mysqli_query($dbh, "SELECT Title FROM $tbl WHERE Id='$id'");
$row = mysqli_fetch_row($rsl);
return $row[0];
}

function getopts($optlist, $active){
	$opts = NULL;
	foreach($optlist as $op){
		$pattern = '/value="'.$active.'"/';
		$opts .= preg_replace($pattern, $pattern.' selected', $op);
		}
	return $opts;
	}



function optionsted($src, $active){
	$optlist = optlist($src);
	$pattern = '/value="'.$active.'"/';
	return preg_replace($pattern, $pattern.' selected', $optlist);
	}

function optlist($tbl){ global $dbh;

$optstr = NULL;
$rsl = mysqli_query($dbh, "SELECT * FROM $tbl");
while($row = mysqli_fetch_assoc($rsl)) { 
	$optstr .= '
	<option value="'.$row['Id'].'">'.$row['Title'].'</option>';
	}
return $optstr;
}

function optionswcls($tbl, $cls, $active){ global $dbh;

	$optlist = NULL;
	$rsl = mysqli_query($dbh, "SELECT * FROM $tbl WHERE $cls");
	while($row = mysqli_fetch_assoc($rsl)) { 
		$optlist .= '
		<option value="'.$row['Id'].'">'.$row['Title'].'</option>';
		}
	$pattern = '/value="'.$active.'"/';
	return preg_replace($pattern, $pattern.' selected', $optlist);
	}

function dynoptsstg($st, $active){ 	global $dbh;

	$sel = '<select name="SubStg">
	<option value="0">Выбрать</option>';
	$optlist = NULL;
	$rsl = mysqli_query($dbh, "SELECT * FROM substg WHERE SubType='$st'");
	while($row = mysqli_fetch_assoc($rsl)) { 
		$optlist .= '
		<option value="'.$row['Id'].'">'.$row['Title'].'</option>';
		}
	$pattern = '/value="'.$active.'"/';
	$ropt = preg_replace($pattern, $pattern.' selected', $optlist);
	
	$sel .= $ropt . '</select>';
	return $sel;
	}
	
	
function formtime($t){
	$ts=sprintf('%02d', ($t%60));
	$tm=sprintf('%02d', ((($t-$ts)/60)%60));
	$th=sprintf('%02d', (((($t-$ts)/60)-$tm)/60));
return $th.':'.$tm.':'.$ts;
}

function filltmpl8($tmpl, $hsh){
foreach($hsh as $id=>$val) { $tmpl = preg_replace("/(<!--<)($id)(>-->)/i", $val, $tmpl); }
	return $tmpl;
}

function getrowid($tbl,$cls){ global $dbh;
	$rsl = mysqli_query($dbh, "SELECT Id FROM $tbl WHERE $cls");
	$rid = mysqli_fetch_row($rsl); 
	return $rid[0];
}


?>