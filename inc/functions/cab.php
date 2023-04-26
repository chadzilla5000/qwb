<?php

function cabinet_page($m) {

	$picgender = ($m["Gender"] == 'M' ? "files/img/nopicM.jpg" : "files/img/nopic.jpg");	
	$picpath = 'stpics/m' . $m["Id"] . '.jpg';
	if(!file_exists($picpath))	{ $picpath = 'stpics/m' . $m["Id"] . '.png'; }	
	if(!file_exists($picpath))	{ $picpath = 'stpics/m' . $m["Id"] . '.gif'; }	
	if(!file_exists($picpath))	{ $picpath = 'stpics/m' . $m["Id"] . '.bmp'; }	
	if(!file_exists($picpath))	{ $picpath = $picgender; }	
	$m["picpath"]     = $picpath;

	$content .= '<br><br>
<style>
.PersDTbl{
width: 100%;
background: #eec;
border: solid 1px #333;
order-collapse: collapse;
}
.PersDTbl th{
padding: 5px;
background: #555;
color: #aaa;
}
.PersDTbl td{
padding: 5px;
background: #fff;
font-weight: bold;
color: #333;
}
.PersDTbl .LCell{
text-align: right;
font-weight: normal;
font-size: 12px;
color: #999;
}
</style>	
';

	if($_GET["edit"] == 'pset1')
		{
		$content .= '<br><br>
<form method="post" action="?cmd=psetupdt&updt=pset1">		
<table class="PersDTbl" cellspacing="1">
<tr><th colspan="3">������������ ������</td></tr>
<tr><td class="LCell" style="width: 180px;">������� ��� ��������</td>
	<td>' . $m['LName'] . ' ' . $m['FName'] . ' ' . $m['MName'] . '</td>
	<td rowspan="3" align="center"><a href="">��������</a></td>
</tr>
<tr><td class="LCell">���� ��������</td>
	<td>' . $m['DoB'] .'</td>

</tr>
<tr><td class="LCell">�����������</td>
	<td>' . inp_Citizenship($m['Citizenship'], 0) .'</td>

</tr>
<tr><td class="LCell"><img src="' . $m["picpath"] . '" style="max-width: 180px; max-height: 240px;"></td>
	<td colspan="2" style="font-weight: normal; vertical-align: top;"><div style="color: #999;">������������� � ����:</div><textarea name="Addinfo">' . $m['Addinfo'] .'</textarea></td>
</tr>
</table>
<input type="submit" name="submit" value="���������">
</form>
';
		return $content;
		}

	if($_GET["edit"] == 'pset2')
		{
		$content .= '<br><br>
<form method="post" action="?cmd=psetupdt&updt=pset2">		
<table class="PersDTbl" cellspacing="1">
<tr><th colspan="3">����������� � ����</td></tr>
<tr><td class="LCell" style="width: 180px;">����������� </td>
	<td>' . inp_Education($m['Education'], 0) . ' &nbsp; ' . inp_MEducation($m['MEducation'], 0) . '</td>
	<td rowspan="4" align="center"><a href="?cmd=psetedit&edit=pset2">��������</a></td>
</tr>
<tr><td class="LCell">�������� ������� ������</td>
	<td>' . inp_Ruslang($m['Ruslang'], 0) . '</td>

</tr>
<tr><td class="LCell">���� ������ ��������</td>
	<td>' . inp_Exper_Years($m['Exper_Years'], 0) . '</td>

</tr>
<tr><td class="LCell">���� ������ ��������</td>
	<td>' . $m['Exper_details'] . '</td>

</tr>
</table>
<input type="submit" name="submit" value="���������">
</form>
		';
		return $content;
		}

	if($_GET["edit"] == 'pset3')
		{
		$content .= '<br><br>
<form method="post" action="?cmd=psetupdt&updt=pset3">		
<table class="PersDTbl" cellspacing="1">
<tr><th colspan="3">������� ������</td></tr>
<tr><td class="LCell" style="width: 180px;">������ ������</td>
	<td>' . inp_Worktype($m['Worktype'], 0) . '</td>
	<td rowspan="4" align="center"><a href="?cmd=psetedit&edit=pset3">��������</a></td>
</tr>
<tr><td class="LCell">������</td>
	<td>' . $m['Wage'] . ' ' . inp_Currency($m["Currency"], 0) . ' ' . inp_TFrame($m["TFrame"], 0) . '</td>

</tr>
<tr><td class="LCell">�����</td>
	<td>' . $m["Region"] . '</td>

</tr>
<tr><td class="LCell">� ����. �����</td>
	<td>' . inp_Available($m["Available"], 0) . '</td>

</tr>
</table>
<input type="submit" name="submit" value="���������">
</form>
		';
		return $content;
		}

	if($_GET["edit"] == 'pset4')
		{
		$content .= '<br><br>
<form method="post" action="?cmd=psetupdt&updt=pset4">		
<table class="PersDTbl" cellspacing="1">
<tr><th colspan="3">�������� � �����������</td></tr>
<tr><td class="LCell" style="width: 180px;">����� ��. �����</td>
	<td>' . $m["EMail"] . '</td>
	<td rowspan="4" align="center"><a href="?cmd=psetedit&edit=pset4">��������</a></td>
</tr>
<tr><td class="LCell">�������(�)</td>
	<td>' . $m['Phone'] . '</td>

</tr>
<tr><td class="LCell">�����</td>
	<td>' . $m["Region"] . '</td>

</tr>
<tr><td class="LCell">� ����. �����</td>
	<td>' . inp_Available($m["Available"], 0) . '</td>

</tr>
</table>
<input type="submit" name="submit" value="���������">
</form>
		';
		return $content;
		}

		
	$content .= '<br><br>
<table class="PersDTbl" cellspacing="1">
<tr><th colspan="3">������������ ������</td></tr>
<tr><td class="LCell" style="width: 180px;">������� ��� ��������</td>
	<td>' . $m['LName'] . ' ' . $m['FName'] . ' ' . $m['MName'] . '</td>
	<td rowspan="3" align="center"><a href="?cmd=psetedit&edit=pset1">��������</a></td>
</tr>
<tr><td class="LCell">���� ��������</td>
	<td>' . $m['DoB'] .'</td>

</tr>
<tr><td class="LCell">�����������</td>
	<td>' . inp_Citizenship($m['Citizenship'], 0) .'</td>

</tr>
<tr><td class="LCell"><img src="' . $m["picpath"] . '" style="max-width: 180px; max-height: 240px;"></td>
	<td colspan="2" style="font-weight: normal; vertical-align: top;"><div style="color: #999;">������������� � ����:</div>' . $m['Addinfo'] .'</td>

</tr>
</table>

<br>
<table class="PersDTbl" cellspacing="1">
<tr><th colspan="3">����������� � ����</td></tr>
<tr><td class="LCell" style="width: 180px;">����������� </td>
	<td>' . inp_Education($m['Education'], 0) . ' &nbsp; ' . inp_MEducation($m['MEducation'], 0) . '</td>
	<td rowspan="4" align="center"><a href="?cmd=psetedit&edit=pset2">��������</a></td>
</tr>
<tr><td class="LCell">�������� ������� ������</td>
	<td>' . inp_Ruslang($m['Ruslang'], 0) . '</td>

</tr>
<tr><td class="LCell">���� ������ ��������</td>
	<td>' . inp_Exper_Years($m['Exper_Years'], 0) . '</td>

</tr>
<tr><td class="LCell">���� ������ ��������</td>
	<td>' . $m['Exper_details'] . '</td>

</tr>
</table>

<br>
<table class="PersDTbl" cellspacing="1">
<tr><th colspan="3">������� ������</td></tr>
<tr><td class="LCell" style="width: 180px;">������ ������</td>
	<td>' . inp_Worktype($m['Worktype'], 0) . '</td>
	<td rowspan="4" align="center"><a href="?cmd=psetedit&edit=pset3">��������</a></td>
</tr>
<tr><td class="LCell">������</td>
	<td>' . $m['Wage'] . ' ' . inp_Currency($m["Currency"], 0) . ' ' . inp_TFrame($m["TFrame"], 0) . '</td>

</tr>
<tr><td class="LCell">�����</td>
	<td>' . $m["Region"] . '</td>

</tr>
<tr><td class="LCell">� ����. �����</td>
	<td>' . inp_Available($m["Available"], 0) . '</td>

</tr>
</table>

<br>
<table class="PersDTbl" cellspacing="1">
<tr><th colspan="3">�������� � �����������</td></tr>
<tr><td class="LCell" style="width: 180px;">����� ��. �����</td>
	<td>' . $m["EMail"] . '</td>
	<td rowspan="4" align="center"><a href="?cmd=psetedit&edit=pset4">��������</a></td>
</tr>
<tr><td class="LCell">�������(�)</td>
	<td>' . $m['Phone'] . '</td>

</tr>
<tr><td class="LCell">�����</td>
	<td>' . $m["Region"] . '</td>

</tr>
<tr><td class="LCell">� ����. �����</td>
	<td>' . inp_Available($m["Available"], 0) . '</td>

</tr>
</table>




<br>
<br>
<br>

';

	reset($m);
	$key = '';
	foreach($m as $key=>$value)	{
		if(preg_match('/[a-z,A-Z]+/', $key)) {
		$content .= $key . ' - ' . $m[$key] . '<br>';
			}
		}

	
	return $content;
	}
?>