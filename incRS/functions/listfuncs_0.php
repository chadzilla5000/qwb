<?php

function get_sitterow($row){
	$picpath = '';
	if($row["DoB"] != 0){
		$diff = abs(time(now) - strtotime($row["DoB"]));
		$row["age"] = floor($diff / (365*60*60*24));
		}

	$picgender = ($row["Gender"] == 'M' ? "files/img/nopicM.jpg" : "files/img/nopic.jpg");	
	$picpath = 'stpics/m' . $row["Id"] . '.jpg';
	if(!file_exists($picpath))	{ $picpath = 'stpics/m' . $row["Id"] . '.png'; }	
	if(!file_exists($picpath))	{ $picpath = 'stpics/m' . $row["Id"] . '.gif'; }	
	if(!file_exists($picpath))	{ $picpath = 'stpics/m' . $row["Id"] . '.bmp'; }	
	if(!file_exists($picpath))	{ $picpath = $picgender; }	
	$row["picpath"]     = $picpath;
	$row["Citizenship"] = inp_Citizenship($row["Citizenship"], 0);
	$row["Education"]   = inp_Education($row["Education"], 0);
	$row["MEducation"]  = inp_MEducation($row["MEducation"], 0);
	$row["Ruslang"]     = inp_Ruslang($row["Ruslang"], 0);
	$row["Currency"]    = inp_Currency($row["Currency"], 0);
	$row["TFrame"]      = inp_TFrame($row["TFrame"], 0);

	$ms = '
<tr><td rowspan="5" style="width: 100px; text-align: center; color: #ccc; font-weight: normal; font-size: 11px;">
	 ID: <!--<Id>--><br><img src="<!--<picpath>-->" style="width: 75px; max-height: 120px;"><br><!--<ApplyDT>-->
	</td>
	<td colspan="2" style="padding-left: 15px; padding-top: 7px; font-size: 14px; font-weight: bold; font-style: italic; color: #070;"><!--<FName>--> <!--<MName>--> (<!--<age>-->)</td>
	<td style="font-weight: normal; vertical-align: bottom;"><span class="LCell">Оплата:</span> от <b><!--<Wage>--></b> <!--<Currency>--> <!--<TFrame>--></td>
	<td style="font-weight: normal; text-align: right;">';
	if($_GET['cmd'] != 'list_selected'){
	$ms .= '<input type="checkbox" name="sitter_chosen[]" value="<!--<Id>-->" title="Отметить" />';
		}
	$ms .= '</td>

	
	
</tr>	
<tr><td class="LCell" width="85"> Гражданство </td>
	<td><!--<Citizenship>--></td>
	<td width="50%" colspan="2" rowspan="3" style="font-weight: normal; color: #666;"> <span style="font-weight: bold; color: #070;">Опыт: (<!--<Exper_Years>--> лет).</span> <!--<Exper_details>--></td>
</tr>	
<tr><td class="LCell">Образование</td>
	<td>
		<!--<Education>-->&nbsp;&nbsp; <!--<MEducation>-->
	</td>
</tr>
<tr><td class="LCell">Язык (русский)</td>
	<td>
		<!--<Ruslang>-->
	</td>
</tr>	
<tr><td class="LCell">Место жит-ва</td>
	<td>
		<!--<Region>-->
	</td>
	<td>Rating and feedbacks</td>
	<td>Оценка <!--<Rating>--></td>
</tr>	

	
<tr><td style="background: none; border: none; height: 15px;"></td></tr>	
<!-- tr><td>' . $row["Id"]          . '</td>
	<td>' . $row["FName"]       . '</td>
	<td>' . $row["MName"]       . '</td>
	<td>' . $row["LName"]       . '</td>
	<td>' . $row["DoB"]  . '</td>
	<td>' . $row["Citizenship"] . '</td>
	<td>' . $row["Education"]   . '</td>
	<td>' .($row["MEducation"] ? "Есть" : "Нет")  . '</td>
	<td>' . $row["Ruslang"]     . '</td>
	<td>' . $row["Exper_Years"] . '</td>
	<td>' . $row["Wage"]        . '</td>
	<td>' . $row["Currency"]    . '</td>
	<td>' . $row["TFrame"]   . '</td>
	<td>' . $row["Region"]   . '</td>
	<td>' . $row["Available"]   . '</td>
	<td>' . $row["Rating"]   . '</td>
</tr -->

';
$ms = mb_convert_encoding($ms, 'windows-1251');
$ms = filltmpl8($ms, $row);

return $ms;
}

function filltmpl8($tmpl, $hsh){
foreach($hsh as $id=>$val) { $tmpl = preg_replace("/(<!--<)($id)(>-->)/i", $val, $tmpl); }
	return $tmpl;
}

?>