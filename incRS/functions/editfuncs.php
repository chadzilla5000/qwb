<?php


function show_order2edit($oid){ global $dbh;
	if(evaccess(9)<2){ return '<div style="text-align: center; font-size: 15px; color: #f00;">Редактирование запрещено</div>'; }

if($oid){ $cmd='upd&oid='.$oid; }
else    { $cmd='add'; }

	$hsh = getrowhsh('order2',"Id='$oid'");
	$osttl = ($hsh['Status']=='Paused')?'Возобновить':'Остановить';
	$ostbg = ($hsh['Status']=='Paused')?'#f90':'#060';
//	$ihsh = getrowhsh('item', "Id='$hsh[ItemID]'");

	$olst1a = optionsted('brigade', $hsh['BrgID']);


	$ssum=$ttm=0;
	
	$date = new DateTime();
	$orderdate=($hsh['OrderDT'])?$hsh['OrderDT']:$date->format('Y-m-d');
	
	$stgrows = '
<script src="https'.'://'.'code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="'.ROOT_URL . '/files/js/KKM.js"></script>
	';
	$mq = "SELECT * FROM stage WHERE ItemID='$hsh[ItemID]' ORDER BY StgNum ASC"; 
	$result = mysqli_query($dbh, $mq) or die (mysqli_error());
	while ($strow = mysqli_fetch_assoc($result)) {
		
		$vo = dbstrcount('bcg', "OrderN='$hsh[OrderID]' AND ItemID='$strow[ItemID]' AND StgNum='$strow[StgNum]'");
		$dispstyle = ($vo)?'block':'none';

		$sstg = getrowhsh('substg', "Id='$strow[SubStg]' AND SubType='$strow[SubType]'");
		
		$brId = getrowhsh('bcg', "OrderN='$hsh[OrderID]' AND ItemID='$strow[ItemID]' AND StgNum='$strow[StgNum]'")['BrgID'];
		$blst = optionswcls('brigade', "StgType='$strow[StgType]'", $brId); //optionsted('brigade', $strow['BrgID']);

		$subtyptitle = getrowhsh('subtype', "Id='$strow[SubType]'")['Title'];

	/*	
		$mq1 = "SELECT * FROM subtype WHERE StgType='$strow[StgType]' ORDER BY Id ASC"; 
		$res1 = mysqli_query($dbh, $mq1) or die (mysqli_error());
		while ($strow1 = mysqli_fetch_assoc($res1)) {
		}		
		*/

		$steptlst = '<table class="StepLst" style="width: 850px;" cellpadding="0" cellspacing="0">
			<tr><th>№</th>
				<th>Операция</th>
				<th>К-во</th>
				<th>Цена</th>
				<th>Сумма</th>
				<th>Время</th>
				<th>Оборудование</th>
				<th>Время начала</th>
				<th>Время окончания</th>
				<th>Факт. время</th>
				<th>Исполнитель</th>
			</tr>';
		$q2 = "SELECT * FROM stgstp WHERE ItemID='$hsh[ItemID]' AND StgNum='$strow[StgNum]' ORDER BY Id ASC"; 
		$res2 = mysqli_query($dbh, $q2) or die (mysqli_error());
		$n=0;
		while ($ssrow = mysqli_fetch_assoc($res2)) { $n++; 

		
			$stphsh = getrowhsh('steproc', "Id='$ssrow[SteprocID]'");
			$bch = getrowhsh('bcg', "OrderN='$hsh[OrderID]' AND ItemID='$ssrow[ItemID]' AND StgNum='$ssrow[StgNum]' AND SteprocID='$ssrow[SteprocID]'");
			$actime=NULL;
			if($bch['StarTime'] AND $bch['EndTime']){
				$d1=new DateTime($bch['StarTime']);
				$d2=new DateTime($bch['EndTime']);
				$ddiff=$d1->diff($d2);
				$days    = $ddiff->format("%d"); 
				$actime= ($days>0)?$days.'дн.'.$ddiff->format("%H:%I:%S"):$ddiff->format("%H:%I:%S"); 
				}
			$mname = shortname($bch['MasterID']);
			$ttm +=$stphsh['Duration']*$stphsh['CclAmt'];
			$ssum+=$stphsh['Price']*$stphsh['CclAmt'];
			$cx  = '_Ord_'.$hsh['OrderID'].'_Itm_'.$hsh['ItemID'].'_Stg_'.$strow['StgNum'].'_Proc_'.$ssrow['SteprocID'].'_Id_'.$ssrow['Id'];
			$cx1 = '_Ord_'.$oid.'_Itm_'.$hsh['ItemID'].'_Stg_'.$strow['StgNum'].'_Proc_'.$ssrow['SteprocID'].'_Id_'.$ssrow['Id'];
			$endtime = $pauserun = NULL;
			if($bch['EndTime']){ $endtime = $bch['EndTime']; }
			else{ 
				if(checkpause($oid,$strow['StgNum'],$ssrow['SteprocID'])){
					$endtime = '<span style="color: #f60;">Paused</span>';
					$pauserun = ($bch['StarTime'] AND (!$bch['EndTime']))?'<img class="zclose" name="PauseProc'.$cx1.'" src="'.ROOT_URL.'/files/img/run.png" title="Запустить процесс" OnClick="PauseProc(this);" />':'';	
					}
				else{
					$pauserun = ($bch['StarTime'] AND (!$bch['EndTime']))?'<img class="zclose" name="PauseProc'.$cx1.'" src="'.ROOT_URL.'/files/img/pause.png" title="Остановить процесс" OnClick="PauseProc(this);" />':'';
					}
				}

			$endprocimg = ($bch['StarTime'] AND (!$bch['EndTime']))?'<img class="zclose" name="CmpltProc'.$cx.'" src="'.ROOT_URL.'/files/img/stop.png" title="Завершить процесс" OnClick="EndProc(this);" />':'';
			
			$steptlst .= '<tr>
<td>'.$n.'.</td>
<td>'.$stphsh['Title'].'</td>
<td>'.$stphsh['CclAmt'].'</td>
<td>'.number_format($stphsh['Price'],2).'</td>
<td>'.number_format(($stphsh['CclAmt']*$stphsh['Price']),2).'</td>
<td>'.$stphsh['Duration'].'</td>
<td>'.getitle('equip', $stphsh['EquipID']).'</td>
<td>'.$bch['StarTime'].'</td>
<td>'.$endtime.'</td>
<td>'.$actime.'</td>
<td>'.$mname.'</td>
<td style="width: 37px;">'.$pauserun.' '.$endprocimg.'</td>
</tr>';
			}
			
			

		$steptlst .= '</table>';
		$olst = optionsted('stgtype', $strow['StgType']);
		$sttype=getrowhsh('stgtype', "Id='$strow[StgType]'")['Title'];
		// $drn = $brgoptlst;
		// if(preg_match('/(value=\")('.$strow['BrgID'].')(\")/', $brgoptlst, $mtch)){
			// $drn = preg_replace('/(value=")('.$mtch[2].')(")/', $mtch[1].$mtch[2].$mtch[3].' selected', $brgoptlst);
			// }		

//		$nxt2 = '_tbl_stage_itid_'.$hsh['ItemID'].'_vn_'.$strow['StgNum'].'_vt_'.$strow['StgType'];
		$pbttn=(($strow['StgType']==1)OR($strow['StgType']==2))?'':'<input type="button" name="PrintBC4_ord_'.$hsh['OrderID'].'_itd_'.$hsh['ItemID'].'_stn_'.$strow['StgNum'].'" style="float: right; width: 90px;" OnClick="printPrBC(this);" value="ШК Этап '.$strow['StgNum'].'" title="Печать штрих-кодов для процессов" />';
		$stgrows .= '
<tr><td>'.$strow['StgNum'].'. <span style="font-size: 11px; color: #037" title="Название этапа">'.$strow['Title'].'</span>
	<div style="font: bold 12px \'Arial\'; color: #963" title="Тип этапа">'.$sttype.'</div><div style="font-size: 12px; text-indent: 15px; color: #999" title="Подтип этапа">'.$subtyptitle.'</div>
	<div style="font-size: 12px; color: #c93;" title="Деталь">'.$sstg['Title'].'</div>
	</td>
	<td colspan="2"><div style="float: left;">'.$steptlst.'</div>
'.$pbttn.'
<!-- div style="float: right; margin-right: 7px;">
	<select name="Brg_Assgn_ord_'.$hsh['OrderID'].'_Itm_'.$strow['ItemID'].'_Stg_'.$strow['StgNum'].'" style="width: 130px; display: '.$dispstyle.';" title="Выбрать группу исполнителей" OnChange="chngbrg(this);">
	<option value="0">Выбрать</option>
	'.$blst.'
	</select>
</div -->	
	</td>
</tr>
';
		}
	
	$ttl_cost=$ssum*$hsh['AmtOrdered'];
	$ttxt = formtime($ttm);
	$tttm=$ttm*$hsh['AmtOrdered'];
	$tttxt = formtime($tttm);
	
return '<form name="orderform" method="post" action="'.ROOT_URL.'/edit.php?subj=order&cmd='.$cmd.'" OnSubmit="return chkttl(this.OrderID);">

<table class="TBL FormTBL">
<tr><th colspan="3">Заказ №'.$hsh['OrdNum'].' Id:'.$hsh['OrderID'].'</th></tr>
<tr><th style="width: 10%;"></th>
	<th style="width: 10%;"></th>
	<th style="width: 80%;"></th>
</tr>
<tr><td>№ / Код 1С</td>
	<td>'.$hsh['OrderID'].' - '.$hsh['ClOrdcode'].'</td>
	<td rowspan="7"><div id="jxcont1"></div></td>
</tr>
<tr><td colspan="2" style="font-size: 12px; color: #044;text-align: right; font-weight: bold;">
	<!-- input type="text" name="Article" id="Article" value="'.$hsh['Article'].'" OnKeyUp="ScanItm(this);" / -->
	'.$hsh['ItemName'].'
	</td>
</tr>
<tr><td>Описание работ</td>
	<td><textarea name="Description" id="Description">'.$hsh['Description'].'</textarea></td>
</tr>
<tr><td>Дата поступления</td>
	<td><input type="date" name="OrderDT" id="OrderDT" value="'.$orderdate.'" /></td>
</tr>
<tr><td>Исполнить до</td>
	<td><input type="date" name="DeadLine" id="DeadLine" value="'.$hsh['DeadLine'].'" /></td>
</tr>
<tr><td>Стоимость</td>
	<td><input type="text" name="PriceOrdered" id="PriceOrdered" value="'.$hsh['PriceOrdered'].'" style="width: 85px;" /></td>
</tr>
<tr><td>Количество</td>
	<td><input type="text" name="AmtOrdered" id="AmtOrdered" value="'.$hsh['AmtOrdered'].'" style="width: 85px;" /></td>
</tr>
<tr><td>Коментарии</td>
	<td><textarea id="Comment" name="Comment">'.$hsh['Comment'].'</textarea></td>
	<td>Исполнители<br>
	<select name="BrgID">
	<option value="0">Выбрать</option>
	'.$olst1a.'
	</select>
	</td>
</tr>
<tr><td><input type="reset" value="Сбросить" style="width: 70px;" /></td>
	<td><input type="submit" value="Сохранить" /></td>
</tr>
<tr><td colspan="3" style="height: 40px;">
<div style="float: right;">

<table>
<tr><th></th>
	<th>Сумма (руб.)</th>
	<th>Прод-ность</th>
</tr>
<tr><td>Итог: ( за 1 изд.)</td>
	<td style="text-align:right;">'.number_format($ssum,2).'</td>
	<td style="text-align:right;">'.$ttxt.'</td>
</tr>
<tr><td>Заказ ( '.$hsh['AmtOrdered'].' шт. )</td>
	<td style="text-align:right;">'.number_format($ttl_cost,2).'</td>
	<td style="text-align:right;">'.$tttxt.'</td>
</tr>

</table>

</div>
	</td>
</tr>
<tr><td></td>
	<td>
<input type="button" name="PauseOrd_'.$oid.'" style="width: 120px; height: 19px; background: '.$ostbg.'; color: #fff;" OnClick="PauseOrder(this);" value="'.$osttl.'" title="'.$osttl.' выполнение заказа" />		
	</td>
	<td>
<input type="button" name="PrintBC4_ford_'.$hsh['OrderID'].'_itd_'.$hsh['ItemID'].'_bcg_3" style="float: right; width: 90px; height: 30px;" OnClick="printAllPrBC(this);" value="Печать ШК" title="Печать штрих-кодов для процессов" />
	</td>
</tr>
'.$stgrows.'

</table>
</form>
<div style="height: 50px;background: #ccc;"></div>';

}



function show_item2edit($itn){ 
	if(evaccess(12)<2){ return '<div style="text-align: center; font-size: 15px; color: #f00;">Редактирование запрещено</div>'; }
	global $dbh;
	global $lghsh;
	$cmd = NULL;
	$ssum = $ttm = 0;
	if($itn){ $cmd='upd&iid='.$itn; }
	else    { $cmd='add'; }

	$maxstg = getmax('stage','StgNum',"ItemID='$itn'");
	$ihsh = getrowhsh('item', "Id='$itn'"); 
	$steptlst = $stgrows = NULL;

	$stgrows = NULL;
	$mq = "SELECT * FROM stage WHERE ItemID='$itn' ORDER BY StgNum ASC"; 
	$result = mysqli_query($dbh, $mq) or die (mysqli_error());
	while ($strow = mysqli_fetch_assoc($result)) {

	$steptlst = NULL;



	$subtypehsh = getrowhsh('subtype', "StgType='$strow[StgType]'"); 

	$substg = ($strow['StgType']==5)?'Sborka i poshiv':'';
	
	
	$q2 = "SELECT * FROM stgstp WHERE ItemID='$itn' AND StgNum='$strow[StgNum]' ORDER BY Id ASC"; 
	$res2 = mysqli_query($dbh, $q2) or die (mysqli_error());
	$n=0;
	while ($ssrow = mysqli_fetch_assoc($res2)) { $n++; 
	
		$stphsh = getrowhsh('steproc', "Id='$ssrow[SteprocID]'");
		$ssum+=$stphsh['Price']*$stphsh['CclAmt'];
		$ttm +=$stphsh['Duration']*$stphsh['CclAmt'];
		$nxt3 = $ssrow['Id'].'_StgN_'.$strow['StgNum'].'_RN_'.$n;
		$steptlst .= '<div>'.$n.'. '.$stphsh['Title'].' 
		<span class="SpStyle3">
	<!-- img class="zclose" name="mvup_'.$nxt3.'" src="'.ROOT_URL.'/files/img/arrup.png" OnClick="movestep(this);" title="Сдвинуть вверх" />
	<img class="zclose" name="mvdn_'.$nxt3.'" src="'.ROOT_URL.'/files/img/arrdn.png" OnClick="movestep(this);" title="Сдвинуть вниз" / -->
	<img class="zclose" name="rmstp_'.$ssrow['Id'].'" src="'.ROOT_URL.'/files/img/btnclosehover.png" OnClick="removestep(this);" title="Удалить процесс" />
		</span></div>';
		}
		
		
$olst1 = optionsted('stgtype', $strow['StgType']);
$olst2 = optionswcls('subtype', "StgType='$strow[StgType]'", $strow['SubType']);
$olst3 = optionswcls('substg', "SubType='$strow[SubType]'", $strow['SubStg']);

		// $drn = $brgoptlst;
		// if(preg_match('/(value=\")('.$strow['BrgID'].')(\")/', $brgoptlst, $mtch)){
			// $drn = preg_replace('/(value=")('.$mtch[2].')(")/', $mtch[1].$mtch[2].$mtch[3].' selected', $brgoptlst);
			// }		

$nxt2 = '_tbl_stage_itid_'.$itn
	.'_vn_'.$strow['StgNum']
	.'_vt_'.$strow['StgType']
	.'_vs_'.$strow['SubType']
	.'_vss_'.$strow['SubStg'];
		
		$stgup = ($strow['StgNum']<=1)?'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;':'<img class="zclose" name="stgmvup__itid_'.$itn.'_vn_'.$strow['StgNum'].'" src="'.ROOT_URL.'/files/img/arrup.png" OnClick="movestg(this);" title="Сдвинуть вверх" style="height: 15px;" />';		
		$stgdn = ($strow['StgNum']>=$maxstg)?'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;':'<img class="zclose" name="stgmvdn__itid_'.$itn.'_vn_'.$strow['StgNum'].'" src="'.ROOT_URL.'/files/img/arrdn.png" OnClick="movestg(this);" title="Сдвинуть вниз" style="height: 15px;" />';		
		
		
//		$pbttn=(1)?'<input type="button" name="PrintBC_'.$nxt2.'" class="SpStyle3" OnClick="preprnt(this);" value="Печать ШК" />':
		'';
		$stgrows .= '
<tr><td style="width: 620px">'.$strow['StgNum'].'
	<select name="StgType'.$nxt2.'" style="width: 150px;margin-left: 7px;" OnChange="StgType(this);">
	<option value="0">Выбрать</option>
	'.$olst1.'
	</select>
	
	<select name="SubType'.$nxt2.'" style="width: 200px;margin-left: 7px;" OnChange="SubType(this);">
	<option value="0">Выбрать</option>
	'.$olst2.'
	</select>
	
	<select name="SubStg'.$nxt2.'" style="width: 200px;margin-left: 7px;" OnChange="SubStg(this);">
	<option value="0">Выбрать</option>
	'.$olst3.'
	</select>
	
	</td>
	<td style="width: 300px"><input type="text" name="Title'.$nxt2.'" id="Title'.$nxt2.'" value="'.$strow['Title'].'" OnChange="svstgttl(this);" />
<img class="zclose" name="TtlSv'.$nxt2.'" src="'.ROOT_URL.'/files/img/cnfrm.png" title="Сохранить" />&nbsp;&nbsp;&nbsp;

'.$stgup.'
'.$stgdn.'

<img class="zclose" name="rmstg_'.$strow['StgNum'].'_Itm_'.$itn.'" src="'.ROOT_URL.'/files/img/btnclosehover.png" OnClick="removestage(this);" title="Удалить этап" />		
	</td>
	<td colspan="2">'.$steptlst.'</td>
	<td style="width: 70px">
	<input type="button" name="SelProc_'.$nxt2.'" class="SpStyle3" style="width: 40px" OnClick="opup(this);" value="- п -" title="Добавить процесс" />
	</td>
</tr>
';
		}

$stgsct = (isset($_GET['iid']))?'
<form name="f5" method="post" action="" OnSubmit="return false">
<tr><th colspan="5">Этапы сборки</th></tr>
'.$stgrows.'
<tr><td></td>
	<td>&nbsp;</td>
	<td></td>
</tr>
<tr><td></td>
	<td><input type="button" name="AddStg" value="Добавить этап" OnClick="addstg('.$ihsh['Id'].');" /></td>
	<td colspan="3"></td>
</tr>
</form>
':NULL;

$ttxt = formtime($ttm);

return '
<form name="f4" method="post" action="'.ROOT_URL.'/edit.php?subj=item&cmd='.$cmd.'" OnSubmit="return chkttl(this.Article);">
<input type="hidden" name="UserID" value="'.$lghsh['Id'].'" />
<table class="TBL FormTBL">
<tr><th colspan="5">Изделие</th></tr>
<tr><td colspan="3"></td>
	<td style="font: bold 15px \'Arial\'">Работа</td>
	<td></td>
</tr>
<tr><td>Артикул</td>
	<td><input type="text" name="Article" id="Article" value="'.$ihsh['Article'].'" /></td>
	<td></td>
	<td>Стоймость (руб.) - <b>'.number_format($ssum,2).'</b></td>
	<td></td>
</tr>
<tr><td>Наименование</td>
	<td><input type="text" name="Title" id="Title" value="'.$ihsh['Title'].'" /></td>
	<td></td>
	<td>Время - <b>'.$ttxt.'</b></td>
	<td></td>
</tr>
<tr><td>Описание работ</td>
	<td><textarea name="Description" id="Description">'.$ihsh['Description'].'</textarea></td>
	<td></td>
	<td></td>
</tr>

<tr><td></td>
	<td>&nbsp;</td>
	<td></td>
	<td></td>
	<td></td>
</tr>
<tr><td><input type="reset" style="width: 70px;" value="Сбросить" /></td>
	<td><input type="submit" value="Сохранить" /></td>
	<td></td>
	<td></td>
	<td></td>
</tr>
</form>
'.$stgsct.'
</table>
<div style="height: 50px;"></div>
';
}












function show_client2edit($cl_n){
	if(evaccess(5)<2){ return '<div style="text-align: center; font-size: 15px; color: #f00;">Редактирование запрещено</div>'; }
$hshf = getblhsh($cl_n, 'clients');

return '<form name="orderform" method="post" action="edit.php?subj=updbcl&cnsn='.$cl_n.'" OnSubmit="return chkttl(this.clname);">
<table class="TBL FormTBL">
<tr><th colspan="3">Клиент</th></tr>
<tr><td style="min-width: 140px;">Ф. И. О.</td>
	<td><input type="text" name="clname" id="clname" value="'.$hshf[FullName].'" /></td>
	<td rowspan="5" width="70%"></td>
</tr>
<tr><td>Телефон(ы)</td>
	<td><input type="text" name="phone" id="phone" value="'.$hshf[Phone].'" /></td>

</tr>
<tr><td>Эл. почта</td>
	<td><input type="text" name="email" id="email" value="'.$hshf[Email].'" /></td>
</tr>
<tr><td>Адрес (улица)</td>
	<td><input type="text" name="addr1" id="addr1" value="'.$hshf[Addr1].'" /></td>
</tr>
<tr><td></td>
	<td><input type="text" name="addr2" id="addr2" value="'.$hshf[Addr2].'" /></td>
</tr>

<tr><td>Район</td>
	<td>'.selform_1().'</td>
	<td style="width: 95%;"><input type="text" name="region" id="region" value="'.$hshf[Region].'" /></td>
</tr>

<tr><td>Ст. Метро</td>
	<td>'.selform_2().'</td>
	<td><input type="text" name="metro" id="metro" value="'.$hshf[Metro].'" /></td>
</tr>
<tr><td>Город</td>
	<td>'.selform_3().'</td>
	<td><input type="text" name="city" id="city" value="'.$hshf[City].'" /></td>
</tr>
<tr><td>Откуда узнали про нас?</td>
	<td>'.selform_4().'</td>
	<td><input type="text" name="lrnfrom" id="lrnfrom" value="'.$hshf[ReferFrom].'" /></td>
</tr>
<tr><td>Комментарии</td>
	<td colspan="2"><textarea name="clcomment">'.$hshf[ClComment].'</textarea></td>
</tr>
<tr><td>&nbsp;</td>
	<td></td>
	<td></td>
</tr>
<tr><td><input type="reset" value="Сбросить" style="width: 70px;" /></td>
	<td><input type="submit" value="Изменить" /></td>
</tr>
</table>

</form>
<div style="height: 50px;"></div>
<script>
function placem(selm)
{
var plhid = selm.name.replace("sel", "");
var dm = document.getElementsByName(plhid)[0]; //document.getElementById(plhid);
	dm.value = selm.value;
}
</script>
';

}

function show_master2edit($mr_n){
	if(evaccess(8)<2){ return '<div style="text-align: center; font-size: 15px; color: #f00;">Редактирование запрещено</div>'; }
	
if($mr_n){ $cmd='upd&mstn='.$mr_n; }
else    { $cmd='add'; }
	
	$hsh = getblhsh($mr_n, 'master');

	$brglst = optionsted('brigade', $hsh['BrgID']);
	
	$cats='';
	$cnt=0;
	
return '<form name="mstrform" method="post" action="'.ROOT_URL.'/edit.php?subj=master&cmd='.$cmd.'" OnSubmit="return chkttl(this.NameL);">
<table class="TBL FormTBL">
<tr><th colspan="3">Мастер</th></tr>
<tr><td style="min-width: 140px;">Фамилия</td>
	<td><input type="text" name="NameL" id="NameL" value="'.$hsh['NameL'].'" /></td>
	<td rowspan="16" width="90%">'.$cats.'</td>
</tr>
<tr><td>Имя</td>
	<td><input type="text" name="NameF" id="NameF" value="'.$hsh['NameF'].'" /></td>
	<td rowspan="5" width="70%"></td>
</tr>
<tr><td>Отчество</td>
	<td><input type="text" name="NameM" id="NameM" value="'.$hsh['NameM'].'" /></td>
	<td rowspan="5" width="70%"></td>
</tr>
<tr><td>Телефон(ы)</td>
	<td><input type="text" name="Phone" id="Phone" value="'.$hsh['Phone'].'" /></td>
</tr>
<tr><td>Эл. почта</td>
	<td><input type="text" name="Email" id="Email" value="'.$hsh['Email'].'" /></td>
</tr>
<tr><td>What\'s up</td>
	<td><input type="text" name="Wsup" id="Wsup" value="'.$hsh['Wsup'].'" /></td>
</tr>
<tr><td>Автомобиль</td>
	<td><input type="text" name="Vhc" id="Vhc" value="'.$hsh['Vhc'].'" /></td>
</tr>
<tr><td>Гос. номер</td>
	<td><input type="text" name="VhcPlate" id="VhcPlate" value="'.$hsh['VhcPlate'].'" /></td>
</tr>
<tr><td>Категория</td>
	<td><input type="text" name="Category" id="category" value="'.$hsh['Category'].'" /></td>
</tr>
<tr><td>Группа/Бригада</td>
	<td><select name="BrgID">
	<option value="0">Выбрать</option>
	'.$brglst.'
	</select></td>
</tr>
<tr><td>Номер карты</td>
	<td><input type="text" name="CardN" id="CardN" value="'.$hsh['CardN'].'" /></td>
</tr>
<tr><td>Город</td>
	<td><input type="text" name="City" id="city" value="'.$hsh['City'].'" /></td>
</tr>
<tr><td>Комментарии</td>
	<td><textarea name="Comment">'.$hsh['Comment'].'</textarea></td>
</tr>
<tr><td>&nbsp;</td>
	<td></td>
</tr>
<tr><td><input type="reset" value="Сбросить" style="width: 70px;" /></td>
	<td><input type="submit" value="Изменить" /></td>
</tr>
</table>


</form>
<div style="height: 50px;"></div>
<script>
function placem(selm)
{
var plhid = selm.name.replace("sel", "");
var dm = document.getElementsByName(plhid)[0]; //document.getElementById(plhid);
	dm.value = selm.value;
}
</script>
';

}


function show_brg2edit($brid){
	if(evaccess(4)<2){ return '<div style="text-align: center; font-size: 15px; color: #f00;">Редактирование запрещено</div>'; }
if($brid){ $cmd='upd&bid='.$brid; }
else    { $cmd='add'; }
	
	$hsh = getrowhsh('brigade', "Id='$brid'");
	$olst = optionsted('stgtype', $hsh['StgType']);
	
return '<form name="mstrform" method="post" action="edit.php?subj=brg&cmd='.$cmd.'" OnSubmit="return chkttl(this.Title);">
<table class="TBL FormTBL">
<tr><th colspan="3">Бригада '.$hsh['Title'].'</th></tr>
<tr><td>Тип этапа</td>
	<td><select name="StgType">
	<option value="0">Выбрать</option>
	'.$olst.'
	</select></td>
	<td></td>
	<td></td>
	<td></td>
</tr>
<tr><td>Название</td>
	<td><input type="text" name="Title" id="Title" value="'.$hsh['Title'].'" /></td>
</tr>
<tr><td>Описание</td>
	<td><textarea name="Description">'.$hsh['Description'].'</textarea></td>
</tr>
<tr><td>&nbsp;</td>
	<td></td>
</tr>
<tr><td><input type="reset" value="Сбросить" style="width: 70px;" /></td>
	<td><input type="submit" value="Изменить" /></td>
</tr>
</table>

</form>
<div style="height: 50px;"></div>
';
}



function show_st2edit($stid){ global $dbh;
	if(evaccess(17)<2){ return '<div style="text-align: center; font-size: 15px; color: #f00;">Редактирование запрещено</div>'; }
global $lghsh;
$cmd = NULL;
	
if($stid){ $cmd='upd&st='.$stid; }
else    { $cmd='add'; }

$shsh = getrowhsh('subtype', "Id='$stid'"); 

$olst = optionsted('stgtype', $shsh['StgType']);

//$nxt1 = '_tbl_item_Id_'.$stid.'_vnd';

return '
<form name="f4" method="post" action="'.ROOT_URL.'/edit.php?subj=stype&cmd='.$cmd.'" OnSubmit="return chkttl(this.Title);">
<input type="hidden" name="UserID" value="'.$lghsh['Id'].'" />
<table class="TBL FormTBL">
<tr><th colspan="5">Подтип</th></tr>
<tr><td>Наименование</td>
	<td><input type="text" name="Title" id="Title" value="'.$shsh['Title'].'" /></td>
	<td></td>
	<td></td>
	<td></td>
</tr>
<tr><td>Описание</td>
	<td><textarea name="Description" id="Description">'.$shsh['Description'].'</textarea></td>
	<td></td>
	<td></td>
	<td></td>
</tr>
<tr><td>Тип этапа</td>
	<td><select name="StgType">
	<option value="0">Выбрать</option>
	'.$olst.'
	</select></td>
	<td></td>
	<td></td>
	<td></td>
</tr>

<tr><td></td>
	<td>&nbsp;</td>
	<td></td>
	<td></td>
	<td></td>
</tr>
<tr><td><input type="reset" value="Сбросить" style="width: 70px;" /></td>
	<td><input type="submit" value="Изменить" /></td>
</tr>
</table>
</form>
<div style="height: 50px;"></div>
';
}



function show_sstg2edit($stid){ global $dbh;
	if(evaccess(16)<2){ return '<div style="text-align: center; font-size: 15px; color: #f00;">Редактирование запрещено</div>'; }
global $lghsh;
$cmd = NULL;
	
if($stid){ $cmd='upd&st='.$stid; }
else    { $cmd='add'; }

$shsh = getrowhsh('substg', "Id='$stid'"); 

$olst = optionsted('subtype', $shsh['SubType']);

//$nxt1 = '_tbl_item_Id_'.$stid.'_vnd';

return '
<form name="f4" method="post" action="'.ROOT_URL.'/edit.php?subj=sstg&cmd='.$cmd.'" OnSubmit="return chkttl(this.Title);">
<input type="hidden" name="UserID" value="'.$lghsh['Id'].'" />
<table class="TBL FormTBL">
<tr><th colspan="5">Подтип</th></tr>
<tr><td>Наименование</td>
	<td><input type="text" name="Title" id="Title" value="'.$shsh['Title'].'" /></td>
	<td></td>
	<td></td>
	<td></td>
</tr>
<tr><td>Описание</td>
	<td><textarea name="Description" id="Description">'.$shsh['Description'].'</textarea></td>
	<td></td>
	<td></td>
	<td></td>
</tr>
<tr><td>Подтип</td>
	<td><select name="SubType">
	<option value="0">Выбрать</option>
	'.$olst.'
	</select></td>
	<td></td>
	<td></td>
	<td></td>
</tr>

<tr><td></td>
	<td>&nbsp;</td>
	<td></td>
	<td></td>
	<td></td>
</tr>
<tr><td><input type="reset" value="Сбросить" style="width: 70px;" /></td>
	<td><input type="submit" value="Изменить" /></td>
</tr>
</table>
</form>
<div style="height: 50px;"></div>
';
}




function show_proc2edit($procn){ global $dbh;
	if(evaccess(13)<2){ return '<div style="text-align: center; font-size: 15px; color: #f00;">Редактирование запрещено</div>'; }
global $lghsh;
$cmd = NULL;
	
if($procn){ $cmd='upd&pid='.$procn; }
else    { $cmd='add'; }

$phsh = getrowhsh('steproc', "Id='$procn'"); 
$stype = (isset($_GET['stp']))?$_GET['stp']:$phsh['SubType'];
$sstg = (isset($_GET['sstg']))?$_GET['sstg']:$phsh['SubStg'];

$olst = optstype($stype);
$ols2 = dynoptsstg($stype, $sstg);  //optionswcls('substg', "SubType='$stype'", $sstg); // optionsted('substg', $sstg);
$eqlst= optionsted('equip', $phsh['EquipID']);

$nxt1 = '_tbl_item_Id_'.$procn.'_vnd';

return '
<form name="f4" method="post" action="'.ROOT_URL.'/edit.php?subj=proc&cmd='.$cmd.'" OnSubmit="return chkttl(this.Title);">
<input type="hidden" name="UserID" value="'.$lghsh['Id'].'" />
<table class="TBL FormTBL">
<tr><th colspan="5">Процесс</th></tr>
<tr><td>Наименование</td>
	<td><input type="text" name="Title" id="Title" value="'.$phsh['Title'].'" style="width: 90%;" /></td>
	<td></td>
	<td></td>
	<td></td>
</tr>
<tr><td>Описание</td>
	<td><textarea name="Description" id="Description">'.$phsh['Description'].'</textarea></td>
	<td></td>
	<td></td>
	<td></td>
</tr>
<tr><td>Подтип этапа</td>
	<td><select name="SubType" OnChange="dynoptsstg(this);">
	<option value="0">Выбрать</option>
	'.$olst.'
	</select></td>
	<td></td>
	<td></td>
	<td></td>
</tr>
<tr><td>Подэтап</td>
	<td id="dynoptsstg">
	'.$ols2.'
	</td>
	<td></td>
	<td></td>
	<td></td>
</tr>
<tr><td>Продолжительность</td>
	<td><input type="text" name="Duration" id="Article" value="'.$phsh['Duration'].'" style="width: 61px;" /></td>
	<td></td>
	<td></td>
	<td></td>
</tr>
<tr><td>Стоймость (руб.)</td>
	<td><input type="text" name="Price" id="Price" value="'.$phsh['Price'].'" style="width: 61px;" /></td>
	<td></td>
	<td></td>
	<td></td>
</tr>
<tr><td>Количество</td>
	<td><input type="text" name="CclAmt" id="CclAmt" value="'.$phsh['CclAmt'].'" style="width: 61px;" /></td>
	<td></td>
	<td></td>
	<td></td>
</tr>

<tr><td>Размеры (мм) Д x Ш x В</td>
	<td><input type="text" name="SizeL" id="SizeL" value="'.$phsh['SizeL'].'" style="width: 61px;" />x
		<input type="text" name="SizeW" id="SizeW" value="'.$phsh['SizeW'].'" style="width: 61px;" />x
		<input type="text" name="SizeH" id="SizeH" value="'.$phsh['SizeH'].'" style="width: 61px;" />
	</td>
	<td></td>
	<td></td>
	<td></td>
</tr>
<tr><td>Оборудование</td>
	<td><select name="EquipID">
	<option value="0">Выбрать</option>
	'.$eqlst.'
	</select></td>
	<td></td>
	<td></td>
	<td></td>
</tr>


<tr><td></td>
	<td>&nbsp;</td>
	<td></td>
	<td></td>
	<td></td>
</tr>
<tr><td><input type="reset" value="Сбросить" style="width: 70px;" /></td>
	<td><input type="submit" value="Изменить" /></td>
</tr>
</table>
</form>
<div style="height: 50px;"></div>
';
}

function show_eqp2edit($eqn){ global $dbh;
	if(evaccess(6)<2){ return '<div style="text-align: center; font-size: 15px; color: #f00;">Редактирование запрещено</div>'; }

global $lghsh;
$cmd = NULL;
	
if($eqn){ $cmd='upd&eid='.$eqn; }
else    { $cmd='add'; }

$phsh = getrowhsh('equip', "Id='$eqn'"); 

$nxt1 = '_tbl_item_Id_'.$eqn.'_vnd';

return '
<form name="f4" method="post" action="'.ROOT_URL.'/edit.php?subj=eqp&cmd='.$cmd.'" OnSubmit="return chkttl(this.Title);">
<input type="hidden" name="UserID" value="'.$lghsh['Id'].'" />
<table class="TBL FormTBL">
<tr><th colspan="5">Оборудование</th></tr>
<tr><td>Кодовое название</td>
	<td><input type="text" name="TCode" id="TCode" value="'.$phsh['TCode'].'" /></td>
	<td></td>
	<td></td>
	<td></td>
</tr>
<tr><td>Серийный №</td>
	<td><input type="text" name="SN" id="SN" value="'.$phsh['SN'].'" /></td>
	<td></td>
	<td></td>
	<td></td>
</tr>
<tr><td>Наименование</td>
	<td><input type="text" name="Title" id="Title" value="'.$phsh['Title'].'" /></td>
	<td></td>
	<td></td>
	<td></td>
</tr>
<tr><td>Описание</td>
	<td><textarea name="Description" id="Description">'.$phsh['Description'].'</textarea></td>
	<td></td>
	<td></td>
	<td></td>
</tr>

<tr><td></td>
	<td>&nbsp;</td>
	<td></td>
	<td></td>
	<td></td>
</tr>
<tr><td><input type="reset" value="Сбросить" style="width: 70px;" /></td>
	<td><input type="submit" value="Изменить" /></td>
</tr>
</table>
</form>
<div style="height: 50px;"></div>
';
}



function show_usr2edit($usn){ global $dbh;
	if(evaccess(19)<2){ return '<div style="text-align: center; font-size: 15px; color: #f00;">Редактирование запрещено</div>'; }
global $lghsh;
$cmd = NULL;

$phsh = $editxt = NULL;

	
if($usn){ $cmd='upd&uid='.$usn; 
	$phsh = getrowhsh('user', "Id='$usn'"); 
//	$editxt =  '<a href="'.ROOT_URL.'/list.php?sbj=acctbl&prv='.$phsh['Prvlg'].'">Редактировать</a>' ;
	}
else    { $cmd='add'; }


$olist = array(
'<option value="X">--</option>',
'<option value="A">A</option>',
'<option value="B">B</option>',
'<option value="C">C</option>',
'<option value="D">D</option>',
'<option value="E">E</option>',
'<option value="F">F</option>'
);

$editxt = ($usn) ? '<a href="'.ROOT_URL.'/list.php?sbj=acctbl&prv='.$phsh['Prvlg'].'" title="Редактировать уровни доступа">Уровни доступа</a>' : '';

return '
<form name="f4" method="post" action="'.ROOT_URL.'/edit.php?subj=usr&cmd='.$cmd.'">
<input type="hidden" name="UserID" value="'.$lghsh['Id'].'" />
<table class="TBL FormTBL">
<tr><th colspan="5">Пользователь</th></tr>
<tr><td>Логин</td>
	<td><input type="text" name="Login" id="Login" value="'.$phsh['Login'].'" /></td>
	<td></td>
	<td></td>
	<td></td>
</tr>
<tr><td>Пароль (минимум 3 символа)</td>
	<td><input type="password" name="Pass" id="Pass" value="" /></td>
	<td></td>
	<td></td>
	<td></td>
</tr>
<tr><td>Имя</td>
	<td><input type="text" name="Name" id="Name" value="'.$phsh['Name'].'" /></td>
	<td></td>
	<td></td>
	<td></td>
</tr>
<tr><td>Уровень доступа</td>
	<td>
<select name="Prvlg" style="width: 35px;">
'.getopts($olist, $phsh['Prvlg']).'
</select>
&nbsp;
&nbsp;
&nbsp;
'.$editxt.'
	</td>
	<td></td>
	<td></td>
	<td></td>
</tr>

<tr><td></td>
	<td>&nbsp;</td>
	<td></td>
	<td></td>
	<td></td>
</tr>
<tr><td><input type="reset" value="Сбросить" style="width: 70px;" /></td>
	<td><input type="submit" value="Изменить" /></td>
</tr>
</table>
</form>
<div style="height: 50px;"></div>
';
}



function ___updb_order($ordn){ global $dbh;




	$mquery = "UPDATE orders
	SET OrDescription = '$_POST[description]',
		OrderDate     = '$_POST[orderdate]',
		DeadLine      = '$_POST[deadline]',
		PriceMin      = '$_POST[prcmin]',
		PriceNom      = '$_POST[prcnom]',
		PriceMax      = '$_POST[prcmax]',
		Status        = '$_POST[status]',
		Comment       = '$_POST[comments]'
	WHERE Id         = '$ordn'";
	mysqli_query($dbh, $mquery);
	return $ordn;
}

function updb_client($clid){ global $dbh;
	$dbh = connect_DB();
	$mquery = "UPDATE clients 
	SET FullName = '$_POST[clname]',
		Addr1     = '$_POST[addr1]',
		Addr2     = '$_POST[addr2]',
		Region    = '$_POST[region]',
		Metro     = '$_POST[metro]',
		City      = '$_POST[city]',
		Phone     = '$_POST[phone]',
		Email     = '$_POST[email]',
		ReferFrom = '$_POST[lrnfrom]',
		ClComment = '$_POST[clcomment]'
	WHERE Id     = '$clid'";
	mysql_query($mquery, $dbh) or die (mysql_error());
	mysql_close($dbh);
return $clid;
}

function updb_master($mrid){
	$profs = implode(':', $_REQUEST['procat']);
	$dbh = connect_DB();
	$mquery = "UPDATE masters 
	SET NameF      = '$_POST[mrnamef]',
		NameM       = '$_POST[mrnamem]',
		NameL       = '$_POST[mrnamel]',
		Profess     = '$profs',
		Region      = '$_POST[region]',
		City        = '$_POST[city]',
		Phone       = '$_POST[phone]',
		Email       = '$_POST[email]',
		Wsup        = '$_POST[wsup]',
		LimitOrders = '$_POST[limitorders]',
		Vhc         = '$_POST[vhc]',
		VhcPlate    = '$_POST[vhcplate]',
		Category    = '$_POST[category]',
		CardN       = '$_POST[cardn]',
		MrComment   = '$_POST[mrcomment]'
	WHERE Id       = '$mrid'";
	mysql_query($mquery, $dbh) or die (mysql_error());
	mysql_close($dbh);
return $mrid;
}

function _______insertorder($mngr){
$dnflg = 0;
$last_id = $_POST[clid];
	$dbh = connect_DB();
	
if($last_id != 0){
	$mquery = "UPDATE clients 
	SET FullName = '$_POST[clname]',
		Addr1     = '$_POST[addr1]',
		Addr2     = '$_POST[addr2]',
		Region    = '$_POST[region]',
		Metro     = '$_POST[metro]',
		City      = '$_POST[city]',
		Phone     = '$_POST[phone]',
		Email     = '$_POST[email]',
		ReferFrom = '$_POST[lrnfrom]',
		ClComment = '$_POST[clcomment]'
	WHERE Id     = '$last_id'";
	mysql_query($mquery, $dbh);
	}
else{	
	$mquery = "INSERT INTO clients(FullName,Addr1,Addr2,Region,Metro,City,Phone,Email,ReferFrom,Mngr,ClComment) 
VALUES ('$_POST[clname]','$_POST[addr1]','$_POST[addr2]','$_POST[region]','$_POST[metro]','$_POST[city]','$_POST[phone]','$_POST[email]','$_POST[lrnfrom]','$mngr','$_POST[clcomment]')";
	if(mysql_query($mquery, $dbh)) { $last_id = mysql_insert_id($dbh); }
}
	$mquery = "INSERT INTO orders(Mngr,Category,OrDescription,OrderDate,DeadLine,PriceMin,PriceNom,PriceMax,Status,Comment,ClientId) 
VALUES ('$mngr', '$_POST[category]','$_POST[description]','$_POST[orderdate]','$_POST[deadline]','$_POST[prcmin]','$_POST[prcnom]','$_POST[prcmax]','$_POST[status]','$_POST[comments]','$last_id')";

	if(mysql_query($mquery, $dbh)) { $dnflg=1; }

	mysql_close($dbh);
return dnflg;
}

function optstype($active){ global $dbh;
	$optlist = NULL;
	$rsl = mysqli_query($dbh, "SELECT * FROM subtype GROUP BY StgType");
	while($row = mysqli_fetch_assoc($rsl)) { 
	$sttl = getitle('stgtype', $row['StgType']);
		$optlist .= '
<optgroup label="'.$sttl.'">';
		$rsl2 = mysqli_query($dbh, "SELECT * FROM subtype WHERE StgType='$row[StgType]'");
		while($row2 = mysqli_fetch_assoc($rsl2)) { 
			$optlist .= '
<option value="'.$row2['Id'].'">'.$row2['Title'].'</option>';
			}
		$optlist .= '
</optgroup>';
		}
	$pattern = '/value="'.$active.'"/';
	return preg_replace($pattern, $pattern.' selected', $optlist);
	}



?>