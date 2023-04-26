<?php 

ini_set("display_errors", 1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

require_once('inc/_init.php');


function ystart(){ global $dbv;

	$mv = intval(date('Y').'1000000000000');

	$res12i = mysqli_query($dbv, "SELECT Id FROM bcg WHERE Id='$mv'");
	$bcid = $res12i->fetch_row()[0];
	if(!$bcid){ $q12i="INSERT INTO bcg (Id)	VALUES('$mv')";
		$dbv->query("LOCK TABLES bcg WRITE");
		mysqli_query($dbv, $q12i); echo mysqli_error($dbv);
		$bcid = $dbv->insert_id;
		$dbv->query("UNLOCK TABLES");
		}
return 0;	
}

function getblhsh($id, $tbl){ global $dbh;
$mquery = "SELECT * FROM $tbl WHERE Id='$id'"; 
	$row = mysqli_query($dbh, $mquery) or die (mysql_error());	
	$hsh = mysqli_fetch_array($row);	
return $hsh;
}


function getblhshbysku($tbl, $fv){ global $dbh;
//	$h = array();
	$res = mysqli_query($dbh, "SELECT * FROM $tbl WHERE Sku='$fv'") or die (mysqli_error($dbh));
	return mysqli_fetch_array($res);
}


function loadorders($wr){ global $dbh;
//	echo (isset($wr[1]))?$wr[0] . '<br>':'';
if(isset($wr[2])){ 	
	$rxstId = getrowid('order2',"UniKey='$wr[0]'");
	$itid     = getItemID($wr);
	
	$dbh->query("LOCK TABLES order2 WRITE");
	if($rxstId){
		$qwi="UPDATE order2 SET
			Article    = '$wr[1]', 
			ItemID     = '$itid', 
			ItemName   = '$wr[5]', 
			AmtOrdered = '$wr[4]', 
			OrderDT    = '$wr[2]', 
			DeadLine   = '$wr[3]', 
			ClOrdcode  = '$wr[8]', 
			ClName     = '$wr[9]', 
			PiCode     = '$wr[10]', 
			BrgID      = '$wr[6]'
		WHERE UniKey   = '$wr[0]'"; 
		mysqli_query($dbh, $qwi); echo mysqli_error($dbh);
		}
	else{	
		$qwi="INSERT INTO order2 (UniKey, Article, ItemID, ItemName, AmtOrdered, OrderDT, DeadLine, ClOrdcode, ClName, PiCode, BrgID)	
			VALUES('$wr[0]', '$wr[1]', '$itid', '$wr[5]', '$wr[4]', '$wr[2]', '$wr[3]', '$wr[8]', '$wr[9]', '$wr[10]', '$wr[6]')";
		mysqli_query($dbh, $qwi); echo mysqli_error($dbh);
		$adup = "UPDATE order2 SET OrderID=Id, OrdNum=Id WHERE Id='$dbh->insert_id'";	
		mysqli_query($dbh, $adup); echo mysqli_error($dbh);
		}
	$dbh->query("UNLOCK TABLES");
	}
return NULL;
}







/////////// List functions ////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////
function list_orders(){ global $dbh;
	if(evaccess(9)<1){ return '<div style="text-align: center; font-size: 15px; color: #f00;">Доступ запрещен</div>'; }

	$sw = (isset($_POST['srch']))?$_POST['srch']:NULL;

	$date = new DateTime();
	$dto   = (isset($_POST['dto']))?$_POST['dto']:$date->format('Y-m-d');
	$date->sub(new DateInterval("P6M"));
	$dfr = (isset($_POST['dfr']))?$_POST['dfr']:$date->format('Y-m-d');


// $today = new DateTime();
// $prevm = new DateTime();
// $prevm->modify('-1 month');
// $d0 =  new DateTime();
// $d1 = (isset($_POST['dstart']))?$_POST['dstart']:$prevm->modify('-1 month');
// $d2 = (isset($_POST['dend']))?$_POST['dend']:$today->format('Y-m-d');

$listxt = '
<div style="float: left; border-radius: 3px; text-align: center; width: 150px; margin: 15px 35px; padding: 2px; border: solid 1px #999;"><a href="'.ROOT_URL.'/edit.php?subj=loadorders">Загрузить из 1С</a></div>
<!-- div style="float: right; border-radius: 3px; text-align: center; width: 100px; margin: 15px 35px; padding: 2px; border: solid 1px #999;"><a href="'.ROOT_URL.'/edit.php?subj=order">Новый</a></div -->
<form name="srchform" method="post" action="'.ROOT_URL.'/list.php?sbj=order2">
<div id="SrchSrt">
<input type="date" name="dfr" value="'.$dfr.'" title="Дата начала отчета" />
<input type="date" name="dto" value="'.$dto.'" title="Конец отчета" />
<input type="text" name="srch" value="'.$sw.'" />
<input type="submit" name="submit" value="Поиск" /> 
</div>
</form>
<table class="TBL" cellspacing="1">
	<tr><th>Заказ</th>
		<th>Изделие</th>
		<th>Кол-во</th>
		<th>Дата поступления</th>
		<th>Заказчик</th>
		<th>Дата исполнения до</th>
		<th>Цена руб.</th>
		<th>Статус / Степень вып.%</th>
		<th>Этапы</th>
		<th>Комментарий</th>
	</tr>
';

	$dtcls = "OrderDT>='$dfr' AND OrderDT<='$dto'";
	
	$clause = ($sw)?"(ClOrdcode LIKE '%$sw%' OR Article LIKE '%$sw%' OR ClName LIKE '%$sw%')":1;
//	$clause = ($sw)?"(OrderID='$sw' OR Ordcode LIKE '%$sw%' OR Article LIKE '%$sw%' OR ClName LIKE '%$sw%')":1;
	$mquery = "SELECT * FROM order2 WHERE $clause AND ($dtcls) ORDER BY Id DESC"; 
	$result = mysqli_query($dbh, $mquery) or die (mysql_error());
	while ($row = mysqli_fetch_array($result)) {
	$prg = 0;
	
	$prg1 = endegree1($row['Id']);
	$prcbar = prgbar($prg1);
	
	
	$user = getrowhsh('user', "Id='$row[UserID]'")['Login'];
//	$item = ($row['Article'])?$row['Article']:getrowhsh('item', "Id='$row[ItemID]'")['Article'];

	
	$nxt4 = $row['Id'].'_Itm_'.$row['ItemID'].'_Ord_'.$row['OrderID'];
		$listxt .= '<tr>
	<td><a href="edit.php?subj=order&oid='.$row['Id'].'" title="Данные заказа"><span style="font-size: 10px; color: #999;">'.$row['Id'].'</span>&nbsp;'.$row['ClOrdcode'].'</a></td>
	<td>'.$row['ItemName'].'</td>
	<td>'.$row['AmtOrdered'].'</td>
	<td>'.$row['OrderDT'].'</td>
	<td>'.$row['ClName'].'</td>
	<td>'.$row['DeadLine'].'</td>
	<td>'.$row['PriceOrdered'].'</td>
	<td>'.$row['Status'].' '.$prcbar.'</td>
	<td><div class="SpStyle4" style="width: 30px;float: left;" OnClick="spreadstg(this);">
		<img class="zclose" id="StgLst_'.$nxt4.'" src="'.ROOT_URL.'/files/img/spread.png" 
		OnClick="spreadstg(this);" title="Раскрыть" />
		</div><div style="float: left;"><div class="Trg1" id="TrgSpr_'.$nxt4.'"></div></div>
	</td>
	<td>'.$row['Comment'].'</td>
</tr>';  
		}

	$listxt .=	'</table>';
return $listxt;
}

/////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////
function list_cnsms(){ global $dbh;
	if(evaccess(5)<1){ return '<div style="text-align: center; font-size: 15px; color: #f00;">Доступ запрещен</div>'; }
$listxt = '
<table class="TBL" cellspacing="1">
	<tr><th>ID</th>
		<th>Ф.И.О</th>
		<th>Город</th>
		<th>Район</th>
		<th>Станция метро</th>
		<th>Телефон(ы)</th>
		<th>Эл. почта</th>
		<th>Пришел</th>
		<th>Менеджер</th>
		<th>Заказы</th>
		<th>Комментарий</th>
	</tr>
';
	$mquery  = "SELECT * FROM clients ORDER BY Id DESC"; 
//	$dbh = connect_DB();
	$result = mysql_query($mquery) or die (mysql_error());
while($row = mysql_fetch_array($result)) {
	$r2 = mysql_query("SELECT Login FROM user WHERE Id='$row[Mngr]'");
	$user = mysql_fetch_row($r2);
	$r3 = mysql_query("SELECT * FROM orders WHERE ClientId='$row[Id]'");
	$cntord = mysql_num_rows($r3);
	
		$listxt .= '<tr>
	<td><a href="edit.php?subj=client&cnsn='.$row[Id].'" title="Данные клиента">'.(int)$row[Id].'</a></td>
	<td>'.$row[FullName].'</td>
	<td>'.$row[City].'</td>
	<td>'.$row[Region].'</td>
	<td>'.$row[Metro].'</td>
	<td>'.$row[Phone].'</td>
	<td>'.$row[Email].'</td>
	<td>'.$row[ReferFrom].'</td>
	<td>'.$user[0].'</td>
	<td><a href="list.php?subj=orders&cnsn='.$row[Id].'" title="Заказы от клиента">'.$cntord.'</a></td>
	<td>'.$row[ClComment].'</td>
	</tr>';  
		}
//	mysql_close($dbh);
//		$listxt .= '<tr><td>sho'.$row[Id].'</td><td>'.$row[Mngr].'</td><td>'.$row[Category].'</td><td>'.$row[OrderDate].'</td></tr>';  

$listxt .=	'</table>';
return $listxt;
}


function list_brg(){ global $dbh;
	if(evaccess(4)<1){ return '<div style="text-align: center; font-size: 15px; color: #f00;">Доступ запрещен</div>'; }

$listxt = '<div style="float: right; border-radius: 3px; text-align: center; width: 100px; margin: 15px 35px; padding: 2px; border: solid 1px #999;"><a href="'.ROOT_URL.'/edit.php?subj=brg">Добавить</a></div>
<table class="TBL" cellspacing="1">
	<tr><th>ID</th>
		<th>Группа / Бригада</th>
		<th>Описание</th>
		<th>Тип этапа</th>
		<th></th>
	</tr>
';

	$mquery  = "SELECT * FROM brigade ORDER BY Id ASC"; 
	$result = mysqli_query($dbh, $mquery) or die (mysql_error());
while($row = mysqli_fetch_array($result)) {
	$stype = getrowhsh('stgtype', "Id='$row[StgType]'")['Title'];
	$listxt .= '<tr>
<td>'.(int)$row['Id'].'</td>
<td><a href="edit.php?subj=brg&bid='.$row['Id'].'" title="Редактировать группу">'.$row['Title'].'</a></td>
<td>'.$row['Description'].'</td>
<td>'.$stype.'</td>
<td style="background: #fff;">
<a style="background: #fff;" href="'.ROOT_URL.'/list.php?sbj=brg&cmd=dlt&id='.$row['Id'].'" OnClick="return ask4confirm();" title="Удалить запись"><img class="zclose" src="'.ROOT_URL.'/files/img/btnclosehover.png" /></a></td>
</tr>';  
	}

$listxt .=	'</table>';
return $listxt;
}

/////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////
function list_mstrs(){ global $dbh;
	if(evaccess(8)<1){ return '<div style="text-align: center; font-size: 15px; color: #f00;">Доступ запрещен</div>'; }
$listxt = '
<script src="https'.'://'.'code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="'.ROOT_URL . '/files/js/KKM.js"></script>

<div><input type="button" name="PrintBC4_msts" style="float:right;width:90px;margin:15px;" OnClick="chooseprint(0);" value="Печать ШК" />
<input type="checkbox" name="RegPrint" id="RegPrint" value="1" style="float: right; margin: 15px;" title="Вывести страницу для печати" />
<div style="float: right; border-radius: 3px; text-align: center; width: 100px; margin: 15px 35px; padding: 2px; border: solid 1px #999;"><a href="'.ROOT_URL.'/edit.php?subj=master">Добавить</a></div></div>
<table class="TBL" cellspacing="1">
	<tr><th>ID</th>
		<th>Ф.И.О</th>
		<th>Бригада</th>
		<th>Город</th>
		<th>Район</th>
		<th>Телефон(ы)</th>
		<th>Эл. почта</th>
		<th>What\'sUp</th>
		<th>Автомобиль</th>
		<th>Гос. номер</th>
		<th>Номер карты</th>
		<th>Комментарий</th>
		<th></th>
		<th></th>
	</tr>
';
	$mquery  = "SELECT * FROM master ORDER BY Id ASC"; 
	$result = mysqli_query($dbh, $mquery) or die (mysql_error());
while($row = mysqli_fetch_array($result)) {
//	$r2 = mysqli_query($dbh, "SELECT Login FROM user WHERE Id='$row[Mngr]'");
//	$user = mysqli_fetch_row($r2);
//	$r3 = mysqli_query($dbh, "SELECT * FROM orders WHERE MasterId='$row[Id]'");
//	$cntord = mysqli_num_rows($r3);
	$brgh = getrowhsh('brigade', "Id='$row[BrgID]'");
	
	
		$listxt .= '<tr>
	<td>'.(int)$row['Id'].'</td>
	<td><a href="edit.php?subj=master&mstn='.$row['Id'].'" title="Данные мастера">'.$row['NameL'].' '.$row['NameF'].' '.$row['NameM'].'</a></td>
	<td>'.$brgh['Title'].'</td>
	<td>'.$row['City'].'</td>
	<td>'.$row['Region'].'</td>
	<td>'.$row['Phone'].'</td>
	<td>'.$row['Email'].'</td>
	<td>'.$row['Wsup'].'</td>
	<td>'.$row['Vhc'].'</td>
	<td>'.$row['VhcPlate'].'</td>
	<td>'.$row['CardN'].'</td>
	<td>'.$row['Comment'].'</td>
	<td><input type="button" name="PrintBC4_msts" style="width: 30px;" OnClick="chooseprint('.$row['Id'].');"  value="ШК" /></td>
	<td style="background: #fff;">
	<a style="background: #fff;" href="'.ROOT_URL.'/list.php?sbj=mstr&cmd=dlt&id='.$row['Id'].'" OnClick="return ask4confirm();" title="Удалить запись">
	<img class="zclose" src="'.ROOT_URL.'/files/img/btnclosehover.png" /></a></td>
	</tr>';  
		}

$listxt .=	'</table>';
return $listxt;
}

function mstr_bc() { global $dbh;
	if(evaccess(8)<1){ return '<div style="text-align: center; font-size: 15px; color: #f00;">Доступ запрещен</div>'; }

$listxt = '';
	$mquery  = "SELECT * FROM master ORDER BY Id DESC"; 
	$result = mysqli_query($dbh, $mquery) or die (mysql_error());
while($row = mysqli_fetch_array($result)) {
//	$r2 = mysqli_query($dbh, "SELECT Login FROM user WHERE Id='$row[Mngr]'");
//	$user = mysqli_fetch_row($r2);
//	$r3 = mysqli_query($dbh, "SELECT * FROM orders WHERE MasterId='$row[Id]'");
//	$cntord = mysqli_num_rows($r3);
	$brgname = getrowhsh('brigade', "Id='$row[BrgID]'")['Title'];
	$m2 = sprintf('%04d', $row['BrgID']);
	$m3 = sprintf('%07d', $row['Id']);
	$ms = $row['Category'].$m2.$m3;

	$listxt .='
<div class="Empl1">'.$row['NameL'].' '.$row['NameF'].' '.$row['NameM'].'<br>
	<img src="'.ROOT_URL.'/inc/bcode.php?c='.$ms.'" />
	<div class="cdg" style="">
		<div style="float: left;">'.$row['Category'].'</div>
		<div style="float: left; margin:0px 10px;">'.$m2.'</div>
		<div style="float: right;">'.$m3.'</div>
	</div>
</div>';  
		}

		
return $listxt;

}





/////////////////////////////////////////////////////////////////////
function lst_item(){ global $dbh;
	if(evaccess(7)<1){ return '<div style="text-align: center; font-size: 15px; color: #f00;">Доступ запрещен</div>'; }
$listxt = '<div style="float: right; border-radius: 3px; text-align: center; width: 100px; margin: 15px 35px; padding: 2px; border: solid 1px #999;"><a href="'.ROOT_URL.'/edit.php?subj=item">Добавить</a></div>
<table class="TBL" cellspacing="1">
	<tr><th>№</th>
		<th>Артикул</th>
		<th>Наименование</th>
		<th>Продолжительность (ЧЧ:ММ:СС)</th>
		<th>Себестоимость (руб.)</th>
		<th>Дата</th>
		<th>Автор</th>
		<th></th>
	</tr>
';

	$clause = 1;
//if($_GET[cnsn]!= ''){$clause = 'ClientId='.$_GET[cnsn];}
//if($_GET[mstn]!= ''){$clause = 'MasterId='.$_GET[mstn];}


	$mquery = "SELECT * FROM item WHERE $clause ORDER BY Id ASC"; 
	$result = mysqli_query($dbh, $mquery) or die (mysql_error());
	while ($row = mysqli_fetch_array($result)) {
		$ssum=$ttm=0;
		$q2 = "SELECT * FROM stgstp WHERE ItemID='$row[Id]'"; 
		$res2 = mysqli_query($dbh, $q2) or die (mysqli_error());
		while ($ssrow = mysqli_fetch_assoc($res2)) { 
			$stphsh = getrowhsh('steproc', "Id='$ssrow[SteprocID]'");
			$ttm +=$stphsh['Duration']*$stphsh['CclAmt'];
			$ssum+=$stphsh['Price']*$stphsh['CclAmt'];
			}

	
		$user = getrowhsh('user', "Id='$row[UserID]'")['Login'];

	// $r2 = mysql_query("SELECT Login FROM user WHERE Id='$row[Mngr]'");	
	// $user = mysql_fetch_assoc($r2);

		$listxt .= '<tr>
	<td>'.(int)$row['Id'].'</td>
	<!-- td><a href="'.ROOT_URL.'/disp.php?sbj=itm&id='.$row['Id'].'" title="Редактировать">'.$row['Article'].'</a></td -->
	<td><a href="'.ROOT_URL.'/edit.php?subj=item&iid='.$row['Id'].'" title="Редактировать">'.$row['Article'].'</a></td>
	<td>'.$row['Title'].'</td>
	<td>'.formtime($ttm).'</td>
	<td>'.number_format($ssum,2).'</td>
	<td>'.$row['DT'].'</td>
	<td>'.$user.'</td>
	<td style="background: #fff;"><a style="background: #fff;" href="'.ROOT_URL.'/list.php?sbj=asmbl&cmd=dlt&id='.$row['Id'].'" OnClick="return ask4confirm();" title="Удалить запись '.$row['Id'].' - Артикул '.$row['Article'].'">
	<img class="zclose" src="'.ROOT_URL.'/files/img/btnclosehover.png" />
	</a></td>
</tr>';  
		}

$listxt .=	'</table>';
return $listxt;
}


/////////////////////////////////////////////////////////////////////
function lst_subtype(){ global $dbh;
	if(evaccess(17)<1){ return '<div style="text-align: center; font-size: 15px; color: #f00;">Доступ запрещен</div>'; }
$listxt = '<div style="float: right; border-radius: 3px; text-align: center; width: 100px; margin: 15px 35px; padding: 2px; border: solid 1px #999;"><a href="'.ROOT_URL.'/edit.php?subj=stype" title="Добавить подтип">Добавить</a></div>
<table class="TBL" cellspacing="1">
	<tr><th>№</th>
		<th>Наименование</th>
		<th>Описание</th>
		<th>Этап (тип)</th>
		<th></th>
	</tr>
';

	$clause = 1;

	$mquery = "SELECT * FROM subtype WHERE $clause ORDER BY Id ASC"; 
	$result = mysqli_query($dbh, $mquery) or die (mysql_error());
	while ($row = mysqli_fetch_array($result)) {

//		$user = getrowhsh('user', "Id='$row[UserID]'")['Login'];
//		$sthsh = getrowhsh('subtype', "Id='$row[SubType]'");
		$stgtype = getrowhsh('stgtype', "Id='$row[StgType]'")['Title'];

	// $r2 = mysql_query("SELECT Login FROM user WHERE Id='$row[Mngr]'");	
	// $user = mysql_fetch_assoc($r2);

		$listxt .= '<tr>
<td>'.(int)$row['Id'].'</td>
<td><a href="'.ROOT_URL.'/edit.php?subj=stype&st='.$row['Id'].'" title="Редактировать подтип">'.$row['Title'].'</a></td>
<td>'.$row['Description'].'</td>
<td>'.$stgtype.'</td>
<td style="background: #fff;"><a style="background: #fff;" href="'.ROOT_URL.'/list.php?sbj=subtype&cmd=dlt&id='.$row['Id'].'" OnClick="return ask4confirm();" title="Удалить запись '.$row['Id'].'">
<img class="zclose" src="'.ROOT_URL.'/files/img/btnclosehover.png" />
</a></td>
</tr>';  
		}

$listxt .=	'</table>';
return $listxt;
}


/////////////////////////////////////////////////////////////////////
function lst_substg(){ global $dbh;
	if(evaccess(16)<1){ return '<div style="text-align: center; font-size: 15px; color: #f00;">Доступ запрещен</div>'; }
$listxt = '<div style="float: right; border-radius: 3px; text-align: center; width: 100px; margin: 15px 35px; padding: 2px; border: solid 1px #999;"><a href="'.ROOT_URL.'/edit.php?subj=sstg" title="Добавить подэтап">Добавить</a></div>
<table class="TBL" cellspacing="1">
	<tr><th>№</th>
		<th>Наименование</th>
		<th>Описание</th>
		<th>Подтип этапа</th>
		<th></th>
	</tr>
';

	$clause = 1;

	$mq32 = "SELECT * FROM substg WHERE $clause ORDER BY Id ASC"; 
	$result = mysqli_query($dbh, $mq32) or die (mysql_error());
	while ($row = mysqli_fetch_array($result)) {

//		$user = getrowhsh('user', "Id='$row[UserID]'")['Login'];
//		$sthsh = getrowhsh('subtype', "Id='$row[SubType]'");
		$subtype = getrowhsh('subtype', "Id='$row[SubType]'")['Title'];

	// $r2 = mysql_query("SELECT Login FROM user WHERE Id='$row[Mngr]'");	
	// $user = mysql_fetch_assoc($r2);

		$listxt .= '<tr>
<td>'.(int)$row['Id'].'</td>
<td><a href="'.ROOT_URL.'/edit.php?subj=sstg&st='.$row['Id'].'" title="Редактировать подэтап">'.$row['Title'].'</a></td>
<td>'.$row['Description'].'</td>
<td>'.$subtype.'</td>
<td style="background: #fff;"><a style="background: #fff;" href="'.ROOT_URL.'/list.php?sbj=substg&cmd=dlt&id='.$row['Id'].'" OnClick="return ask4confirm();" title="Удалить запись '.$row['Id'].'">
<img class="zclose" src="'.ROOT_URL.'/files/img/btnclosehover.png" />
</a></td>
</tr>';  
		}

$listxt .=	'</table>';
return $listxt;
}



/////////////////////////////////////////////////////////////////////
function lst_procs(){ global $dbh;
	if(evaccess(15)<1){ return '<div style="text-align: center; font-size: 15px; color: #f00;">Доступ запрещен</div>'; }
$listxt = '<div style="float: right; border-radius: 3px; text-align: center; width: 100px; margin: 15px 35px; padding: 2px; border: solid 1px #999;"><a href="'.ROOT_URL.'/edit.php?subj=proc">Добавить</a></div>
<table class="TBL" cellspacing="1">
	<tr><th>№</th>
		<th>Наименование</th>
		<th>Время (сек.)</th>
		<th>Цена руб.</th>
		<th>Автор</th>
		<th>Дата</th>
		<th></th>
	</tr>
';

	$clause = 1;
//if($_GET[cnsn]!= ''){$clause = 'ClientId='.$_GET[cnsn];}
//if($_GET[mstn]!= ''){$clause = 'MasterId='.$_GET[mstn];}

	$mq = "SELECT * FROM stgtype ORDER BY Id ASC"; 
	$rs = mysqli_query($dbh, $mq) or die (mysql_error());
	while ($r = mysqli_fetch_array($rs)) {
		$listxt .= '<tr>
<td style="background: #7cc;"></td>
<td colspan="6" style="background: #050; font: bold 15px \'Arial\'; color: #cff;">'.$r['Title'].'</td>
		</tr>
		';

		$mq0 = "SELECT * FROM subtype WHERE StgType='$r[Id]'"; 
		$rs0 = mysqli_query($dbh, $mq0) or die (mysql_error());
		while ($r0 = mysqli_fetch_array($rs0)) {
			$listxt .= '<tr>
<td colspan="8" style="background: #ccc; font-weight: bold; font-size: 15px; color: #f60;">'.$r0['Title'].'</td>
			</tr>		
			';

			$mquery = "SELECT * FROM steproc WHERE SubType='$r0[Id]' AND (SubStg IS NULL OR SubStg='0') ORDER BY SubType ASC"; 
			$result = mysqli_query($dbh, $mquery) or die (mysql_error());
			while ($row = mysqli_fetch_array($result)) {

				$user = getrowhsh('user', "Id='$row[UserID]'")['Login'];
				$listxt .= '<tr>
<td>'.(int)$row['Id'].'</td>
<td><a href="'.ROOT_URL.'/edit.php?subj=proc&pid='.$row['Id'].'" title="Редактировать процесс">'.$row['Title'].'</a></td>
<td>'.$row['Duration'].'</td>
<td>'.$row['Price'].'</td>
<td>'.$user.'</td>
<td>'.$row['DT'].'</td>
<td style="background: #fff;"><a style="background: #fff;" href="'.ROOT_URL.'/list.php?sbj=procs&cmd=dlt&id='.$row['Id'].'" OnClick="return ask4confirm();" title="Удалить запись '.$row['Id'].'">
<img class="zclose" src="'.ROOT_URL.'/files/img/btnclosehover.png" />
</a></td>
</tr>';  
				}

			$mq01 = "SELECT * FROM substg WHERE SubType='$r0[Id]'"; 
			$rs01 = mysqli_query($dbh, $mq01) or die (mysql_error());
			while ($r01 = mysqli_fetch_array($rs01)) {
				$listxt .= '<tr>
<td colspan="2" style="font-weight: bold; text-align: center; color: #960;">'.$r01['Title'].'</td>
				</tr>		
				';
				
				$mquery = "SELECT * FROM steproc WHERE SubType='$r0[Id]' AND SubStg='$r01[Id]' ORDER BY SubType ASC"; 
				$result = mysqli_query($dbh, $mquery) or die (mysql_error());
				while ($row = mysqli_fetch_array($result)) {

					$user = getrowhsh('user', "Id='$row[UserID]'")['Login'];
					$listxt .= '<tr>
<td>'.(int)$row['Id'].'</td>
<td><a href="'.ROOT_URL.'/edit.php?subj=proc&pid='.$row['Id'].'" title="Редактировать процесс">'.$row['Title'].'</a></td>
<td>'.$row['Duration'].'</td>
<td>'.$row['Price'].'</td>
<td>'.$user.'</td>
<td>'.$row['DT'].'</td>
<td style="background: #fff;"><a style="background: #fff;" href="'.ROOT_URL.'/list.php?sbj=procs&cmd=dlt&id='.$row['Id'].'" OnClick="return ask4confirm();" title="Удалить запись '.$row['Id'].'">
<img class="zclose" src="'.ROOT_URL.'/files/img/btnclosehover.png" />
</a></td>
</tr>';  
					}
				}				
			}
		}		
	$ptxt = NULL;		
	$mqr = "SELECT * FROM steproc WHERE SubType=0 OR SubType IS NULL ORDER BY Id ASC"; 
	$result = mysqli_query($dbh, $mqr) or die (mysql_error());
	while ($rr = mysqli_fetch_array($result)) {
		$user = getrowhsh('user', "Id='$row[UserID]'")['Login'];
		$ptxt .= '<tr>
<td>'.(int)$rr['Id'].'</td>
<td><a href="'.ROOT_URL.'/edit.php?subj=proc&pid='.$rr['Id'].'" title="Редактировать процесс">'.$rr['Title'].'</a></td>
<td>'.$rr['Duration'].'</td>
<td>'.$rr['Price'].'</td>
<td>'.$user.'</td>
<td>'.$rr['DT'].'</td>
<td style="background: #fff;"><a style="background: #fff;" href="'.ROOT_URL.'/list.php?sbj=procs&cmd=dlt&id='.$rr['Id'].'" OnClick="return ask4confirm();" title="Удалить запись '.$rr['Id'].'">
<img class="zclose" src="'.ROOT_URL.'/files/img/btnclosehover.png" />
</a></td>
</tr>';  
		}
	$listxt .= ($ptxt)?'<tr>
<td style="background: #7cc;"></td>
<td colspan="6" style="background: #730; font: bold 15px \'Arial\'; color: #cff;">Процессы без групп</td>
</tr>'.$ptxt:'';
		
	$listxt .=	'</table>';

return $listxt;
}

/////////////////////////////////////////////////////////////////////
function lst_eqp(){ global $dbh;
	if(evaccess(6)<1){ return '<div style="text-align: center; font-size: 15px; color: #f00;">Доступ запрещен</div>'; }

$listxt = '<div style="float: right; border-radius: 3px; text-align: center; width: 100px; margin: 15px 35px; padding: 2px; border: solid 1px #999;"><a href="'.ROOT_URL.'/edit.php?subj=eqp">Добавить</a></div>
<table class="TBL" cellspacing="1">
	<tr><th>№</th>
		<th>Код.н</th>
		<th>Сер. №</th>
		<th>Наименование</th>
		<th>Описание</th>
		<th>Автор</th>
		<th></th>
	</tr>
';

	$clause = 1;
//if($_GET[cnsn]!= ''){$clause = 'ClientId='.$_GET[cnsn];}
//if($_GET[mstn]!= ''){$clause = 'MasterId='.$_GET[mstn];}

	$mquery = "SELECT * FROM equip WHERE $clause ORDER BY Id ASC"; 
	$result = mysqli_query($dbh, $mquery) or die (mysql_error());
	while ($row = mysqli_fetch_array($result)) {

	$user = getrowhsh('user', "Id='$row[UserID]'")['Login'];

	// $r2 = mysql_query("SELECT Login FROM user WHERE Id='$row[Mngr]'");	
	// $user = mysql_fetch_assoc($r2);

		$listxt .= '<tr>
	<td>'.(int)$row['Id'].'</td>
	<td>'.$row['TCode'].'</td>
	<td>'.$row['SN'].'</td>
	<td><a href="'.ROOT_URL.'/edit.php?subj=eqp&eid='.$row['Id'].'" title="Редактировать">'.$row['Title'].'</a></td>
	<td>'.$row['Description'].'</td>
	<td>'.$user.'</td>
	<td style="background: #fff;"><a style="background: #fff;" href="'.ROOT_URL.'/list.php?sbj=eqp&cmd=dlt&id='.$row['Id'].'" OnClick="return ask4confirm();" title="Удалить запись '.$row['Id'].'">
	<img class="zclose" src="'.ROOT_URL.'/files/img/btnclosehover.png" />
	</a></td>
	</tr>';  
		}

$listxt .=	'</table>';
return $listxt;
}

/////////////////////////////////////////////////////////////////////
function lst_usr(){ global $dbh;
	if(evaccess(19)<1){ return '<div style="text-align: center; font-size: 15px; color: #f00;">Доступ запрещен</div>'; }

$listxt = '<div style="float: right; border-radius: 3px; text-align: center; width: 100px; margin: 15px 35px; padding: 2px; border: solid 1px #999;"><a href="'.ROOT_URL.'/edit.php?subj=usr">Добавить</a></div>
<table class="TBL" cellspacing="1">
	<tr><th>ID</th>
		<th>Логин</th>
		<th>Имя</th>
		<th>Уровень доступа</th>
		<th></th>
	</tr>
';

	$clause = 1;
//if($_GET[cnsn]!= ''){$clause = 'ClientId='.$_GET[cnsn];}
//if($_GET[mstn]!= ''){$clause = 'MasterId='.$_GET[mstn];}

	$mquery = "SELECT * FROM user WHERE $clause ORDER BY Id ASC"; 
	$result = mysqli_query($dbh, $mquery) or die (mysql_error());
	while ($row = mysqli_fetch_array($result)) {


	// $r2 = mysql_query("SELECT Login FROM user WHERE Id='$row[Mngr]'");	
	// $user = mysql_fetch_assoc($r2);

		$listxt .= '<tr>
	<td>'.(int)$row['Id'].'</td>
	<td><a href="'.ROOT_URL.'/edit.php?subj=usr&uid='.$row['Id'].'" title="Редактировать">'.$row['Login'].'</a></td>
	<td>'.$row['Name'].'</td>
	<td>'.$row['Prvlg'].'</td>
	<td style="background: #fff;"><a style="background: #fff;" href="'.ROOT_URL.'/list.php?sbj=usr&cmd=dlt&id='.$row['Id'].'" OnClick="return ask4confirm();" title="Удалить запись '.$row['Id'].'">
	<img class="zclose" src="'.ROOT_URL.'/files/img/btnclosehover.png" />
	</a></td>
	</tr>';  
		}

$listxt .=	'</table>';
return $listxt;
}

function lst_prv($prv){  global $dbh;
	if(evaccess(1)<2){ return '<div style="text-align: center; font-size: 15px; color: #f00;">Редактирование запрещено</div>'; }

$bv = array('A','B','C','D','E','F');	

$tm1 = $tm2 = NULL;


foreach($bv as $v){
	$dc = ($prv==$v)?'color: #ff0;':'';
$tm1 .= '<th colspan="3" style="'.$dc.'">'.$v.'</th>';
$tm2 .= '
<th style="width: 25px;">X</th>
<th style="width: 25px;">R</th>
<th style="width: 25px;">W</th>
';
}

	
$listxt = '
<table class="TBL" cellspacing="1">
	<tr><th colspan="2"></th>
		'.$tm1.'
		<th style="width: 40px;"></th>
	</tr>
	<tr><th>ID</th>
		<th>Название раздела</th>
		'.$tm2.'
		<th></th>
	</tr>
';

	$clause = 1;
//if($_GET[cnsn]!= ''){$clause = 'ClientId='.$_GET[cnsn];}
//if($_GET[mstn]!= ''){$clause = 'MasterId='.$_GET[mstn];}

	$mquery = "SELECT * FROM access WHERE $clause ORDER BY Id ASC"; 
	$result = mysqli_query($dbh, $mquery) or die (mysql_error());
	while ($row = mysqli_fetch_array($result)) {

		$listxt .= '<tr>
<td style="width: 150px;">'.(int)$row['Id'].' ('.$row['Table'].')</td>
<td style="padding: 2px 5px;"><input type="text" name="prttl_'.$row['Id'].'" value="'.$row['Title'].'" style="width: 100%; background: transparent; border: none; outline: none;" OnChange="updatePrvlttl(this);" /></td>';

foreach($bv as $v){
	
	// $r2 = mysql_query("SELECT Login FROM user WHERE Id='$row[Mngr]'");	
	// $user = mysql_fetch_assoc($r2);
	$chckd0 = ($row[$v]==0) ? 'checked':'';
	$chckd1 = ($row[$v]==1) ? 'checked':'';
	$chckd2 = ($row[$v]==2) ? 'checked':'';
	
		$listxt .= '
<td><input type="radio" name="prrb_'.$row['Id'].'_prvlg_'.$v.'" value="0" '.$chckd0.' OnChange="updatePrvaccs(this);" /></td>
<td><input type="radio" name="prrb_'.$row['Id'].'_prvlg_'.$v.'" value="1" '.$chckd1.' OnChange="updatePrvaccs(this);" /></td>
<td style="border-right: solid 1px #999;"><input type="radio" name="prrb_'.$row['Id'].'_prvlg_'.$v.'" value="2" '.$chckd2.' OnChange="updatePrvaccs(this);" /></td>
	';  

}
		$listxt .= '<td></td></tr>';

		}

$listxt .=	'</table>';
return $listxt;
	
}

/////////////////////////////////////////////////////////
////////////     End list functions ////////////////////
///////////////////////////////////////////////////////






function updbcg($s17,$mID){ global $dbh;
	$bID = getblhsh($mID, 'master')['BrgID'];
	$qa="UPDATE bcg SET EndTime=NOW() WHERE MasterID='$mID' AND StarTime IS NOT NULL AND EndTime IS NULL AND Id!='$s17'"; 
	$qb="UPDATE bcg SET MasterID='$mID', BrgID='$bID', StarTime=NOW() WHERE Id='$s17' AND MasterID IS NULL"; 
	$qc="UPDATE order2 SET Status='P', TmpMasterID='$mID', TmpBrgID='$bID' WHERE OrderID=(SELECT OID FROM bcg WHERE Id='$s17')"; 
	$dbh->query("LOCK TABLES bcg WRITE, order2 WRITE");
	mysqli_query($dbh, $qa); echo mysqli_error($dbh);
	mysqli_query($dbh, $qb); echo mysqli_error($dbh);
	mysqli_query($dbh, $qc); echo mysqli_error($dbh);
	$dbh->query("UNLOCK TABLES");
return;	
}
function updbcgP($s17p){ global $dbh;

	$qa="UPDATE bcg SET EndTime=NOW() WHERE Id='$s17p' AND StarTime IS NOT NULL AND EndTime IS NULL"; 
	$dbh->query("LOCK TABLES bcg WRITE");
	mysqli_query($dbh, $qa); echo mysqli_error($dbh);
	$dbh->query("UNLOCK TABLES");
return;	
}

function shortname($h){ 
if(!is_array($h)){ global $dbh;
	$mquery = "SELECT * FROM master WHERE Id='$h'"; 
	$rs = mysqli_query($dbh, $mquery) or die (mysql_error());	
	$h = mysqli_fetch_array($rs);
	}
	$nf = mb_substr($h['NameF'],0,1,"utf-8");
	$nm = mb_substr($h['NameM'],0,1,"utf-8");
	$nf = ($nf!='')?$nf.'.':'';
	$nm = ($nm!='')?$nm.'.':'';
	
return $h['NameL'].' '.$nf.' '.$nm;
}
/////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////
function endegree1($oid){ global $dbh;

	$clause1 = "ItemID=(SELECT ItemID FROM order2 WHERE Id='$oid')";
	$clause2 = "OrderN='$oid'";

	$ttlos = dbstrcount('stgstp', $clause1);
	$ttlob = dbstrcount('bcg'    ,   $clause2);

	$ttlop =($ttlos>=$ttlob)?$ttlos:$ttlob;
	$ttloc = dbstrcount('bcg', "$clause2 AND EndTime IS NOT NULL");
	return($ttlop)?floor($ttloc/$ttlop*100*2)/2:0;
}

function endegree2($oid, $stg){ global $dbh;

	$clause1 = "ItemID=(SELECT ItemID FROM order2 WHERE OrderID='$oid') AND StgNum='$stg'";
	$clause2 = "OrderN='$oid' AND StgNum='$stg'";

	$ttlss = dbstrcount('stgstp', $clause1);
	$ttlsb = dbstrcount('bcg'    ,   $clause2);

	$ttstp =($ttlss>=$ttlsb)?$ttlss:$ttlsb;
	$ttlsc = dbstrcount('bcg', "$clause2 AND EndTime IS NOT NULL");
	return($ttstp)?floor($ttlsc/$ttstp*100*2)/2:0;
}

function  prgbar($p){ global $dbh;
	return '
	<div style="float: right; width: 100px;height: 12px; border: solid 1px #ccc;"> 
	<div style="width: 100px; height: 12px; background: #c00; z-index: 300;">
	<div style="position: absolute;width: '.$p.'px; height: 12px; background: #090; z-index: 400;">&nbsp;</div>
	<div style="position: absolute; width: 100px; color: #fff;text-align: center; z-index: 500;">'.$p.'%</div>
	</div>
	</div>';
}

function  prgbarV($p){ global $dbh;
	return '
	<div style="float: right; width: 100px;height: 7px; border: solid 1px #ccc;"> 
	<div style="width: 100px; height: 7px; background: #c00; z-index: 300;" title="'.$p.'%">
	<div style="width: '.$p.'px; height: 7px; background: #090; z-index: 400;">&nbsp;</div>
	</div>
	</div>';
}


function  prcbar3($oid, $stg, $proc){ global $dbh;
$mq2 = "SELECT EndTime FROM bcg WHERE OrderN='$oid' AND StgNum='$stg' AND SteprocID='$proc'"; 
$rs2 = mysqli_query($dbh, $mq2) or die (mysql_error());
$r2 = $rs2->fetch_row()[0];

	$bg=($r2)?'090':'c00';
	return '<div style="float: right; width: 11px;height: 11px; border-radius:50%; background: #'.$bg.';"></div>';
}

function getNItemID($a){ global $dbh;
	$nid=NULL;
	$rs = mysqli_query($dbh, "SELECT Id FROM item WHERE Article='$a'") or die (mysql_error());
	$r = $rs->fetch_row()[0];
	if($r){ $nid=$r; }
	else{
		$dbh->query("LOCK TABLES item WRITE");
		mysqli_query($dbh, "INSERT INTO item (Article) VALUES('$a')"); echo mysqli_error($dbh);
		$nid = $dbh->insert_id;
		$dbh->query("UNLOCK TABLES");
		}
return $nid;	
}

function getItemID($w){ global $dbh;
	$nid=NULL;
	$rs = mysqli_query($dbh, "SELECT Id FROM item WHERE Article='$w[1]'") or die (mysql_error());
	$r = $rs->fetch_row()[0];
	if($r){ $nid=$r; }
	else{
		$dbh->query("LOCK TABLES item WRITE");
		mysqli_query($dbh, "INSERT INTO item (Article, Title) VALUES('$w[1]', '$w[5]')"); echo mysqli_error($dbh);
		$nid = $dbh->insert_id;
		$dbh->query("UNLOCK TABLES");
		}
return $nid;	
}


function checkpause($oid,$stg,$stp){ global $dbh;
$rs = mysqli_query($dbh, "SELECT Id FROM pause WHERE OrderID='$oid' AND StageID='$stg' AND ProcID='$stp' AND PauStop IS NULL") or die (mysql_error());
//$r = $rs->fetch_row()[0];
if($rs->fetch_row()[0]) {return true;}	
else {return false;}	
}

function getmax($tbl, $col, $cls){ global $dbh;
$mq = "SELECT MAX($col) FROM $tbl WHERE $cls"; 
$rs = mysqli_query($dbh, $mq) or die (mysql_error());
$r   = $rs->fetch_row()[0];
	
return $r;	
}

function fillcontent($cnt){ 
global $accss;
global $pgcontent;

switch($accss){	case 1:		$acbgr='#f90'; $acttl='Доступен только просмотр';			break;
				case 2:		$acbgr='#0c0'; $acttl='Доступны просмотр и редактирование';	break;
				default:	$acbgr='#f00'; $acttl='Доступ к разделу запрещен';			break;
}

$accln = '<div style="float: right; margin-right: 5px; width: 13px;height: 13px; background: '.$acbgr.'; padding: 0px; border-radius: 50%;" title="'.$acttl.'"></div>';

$pgcontent = <<<EOD__
$accln
<!-- Page content start -->
$cnt
<script>
function ask4confirm(){
	alert('Удаление временно невозможно'); return false;
	if(confirm('Уверены, что хотите удалить запись?')){ return true; }
	return false;
	}
</script>
<!-- Page content end -->
EOD__;

return;	
}


///////////////////////////////////////////////////////
//////////////////////////////////////////////////////
/////////////////////////////////////////////////////
////////////////////////////////////////////////////
///////////////////////////////////////////////////
//////////////////////////////////////////////////
/////////////////////////////////////////////////

function currstg($oid){ global $dbh;
$cstg=array();
$qy  = "SELECT * FROM bcg WHERE OID='$oid' AND StarTime IS NOT NULL AND EndTime IS NULL"; 
$rst = mysqli_query($dbh, $qy) or die (mysql_error());
while($row = mysqli_fetch_assoc($rst)) {
	
	}	
return $pstg;	
}


function priorstg($bid){ global $dbh;
$pstgarr=NULL; //array();

$qy21  = "SELECT * FROM bcg 
JOIN order2 ON (bcg.ItemID = order2.ItemID AND (order2.TmpBrgID='$bid'))
WHERE StarTime IS NOT NULL AND EndTime IS NULL"; 

$qy   = "SELECT 
order2.OrderID AS i1

FROM order2
JOIN bcg   ON (bcg.OID=order2.OrderID AND order2.ItemID=bcg.ItemID)

WHERE 
 (order2.BrgID='$bid' OR order2.TmpBrgID='$bid') AND
 bcg.StarTime IS NOT NULL AND bcg.EndTime IS NULL
";


//JOIN order2 ON (stage.ItemID = order2.ItemID AND (order2.BrgID='$bid' OR order2.TmpBrgID='$bid'))
//JOIN brigade ON (stage.StgType = brigade.StgType)
//JOIN bcg   ON (bcg.OID=order2.OrderID AND order2.ItemID=bcg.ItemID)





//$qy  = "SELECT * FROM order2 WHERE (BrgID='$bid' OR TmpBrgID='$bid')"; 



$rst = mysqli_query($dbh, $qy) or die (mysql_error());
while($r0 = mysqli_fetch_assoc($rst)) {

//$qy1  = "SELECT * FROM stage WHERE (StgType IN (SELECT StgType FROM brigade WHERE Id='$bid')) AND StgNum='$r0[StgNum]+1' AND ItemID='$r0[ItemID]'"; 

//$rst1 = mysqli_query($dbh, $qy1) or die (mysql_error());
//$r1 = mysqli_fetch_assoc($rst1);

$pstgarr .= $r0['i1']  . '<br>';

// if($r1['Id'])
// {
	// $pstgarr .= $r0['StgNum'] . '<br>';
// //array_push($pstgarr, $r0['StgNum']);
// }	

	}	
return $pstgarr;	
}




/////////////////////////////////////////
function nexstg($itm, $cstg){ global $dbh;
$nstg=NULL;
$qy  = "SELECT * FROM order2 WHERE WHState='6' OR  WHState='2'"; 
$rst = mysqli_query($dbh, $qy) or die (mysql_error());
while($row = mysqli_fetch_assoc($rst)) {
	
	}	
return $nstg;	
}




function trtphonum_________($phn){
	return substr(preg_replace('/[\D]/', '', $phn), -10);
}


function trtphonum($ph, $f=0){
	$ph = preg_replace('/\D/', '', $ph);
	if(strlen($ph)<10){ return false; }
	$ph = substr($ph, -10);
	if($f){ return $ph; }
	$p1 = substr($ph, 0,3);
	$p2 = substr($ph, 3,3);
	$p3 = substr($ph, -4);
	return $p1.'-'.$p2.'-'.$p3;
}

function spcharhndl($trm){
//	return preg_replace('/[\’\″\W]{1,5}/', ' ', $trm);
	$vv = preg_replace('/\//', ' ', $trm);
	$vv = preg_replace('/[\W]{1,5}/', ' ', $vv);
//	$vv = preg_replace('/[\']{1,2}/', ' ', $vv);
	$vv = preg_replace('/[\s]{2,4}/', ' ', $vv);
	return $vv;
}

function upstrhndl($trm){
	return preg_replace('/[\']{1,2}/', ' ', $trm);
}


function drc($o){
	echo '<pre>';
	print_r($o);
	echo '</pre>';
return;
}

?>