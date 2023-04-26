<?php 

function inp_LName($val)
	{ return '<input type="text" name="LName" size="15" maxlength="64" value="'.$val.'">'; }	
function inp_FName($val)
	{ return '<input type="text" name="FName" size="15" maxlength="32" value="'.$val.'">'; }	
function inp_MName($val)
	{ return '<input type="text" name="MName" size="15" maxlength="32" value="'.$val.'">'; }	
////////////////////////////////////////////////////////////////////////////////////////
function inp_Gender($val)
{
if($val == 'M')
	{
	return '<select name="Gender">
	<option value="F">���.</option>
	<option value="M" selected>���.</option>
</select>';
	}
else
	{
	return '<select name="Gender">
	<option value="F" selected>���.</option>
	<option value="M">���.</option>
</select>';
	}
}
////////////////////////////////////////////////////////////////////////////////////////
function inp_Date($val)
{
$form_sDay = '
		<select name="DDay">
			<option value="01">01</option>
			<option value="02">02</option>
			<option value="03">03</option>
			<option value="04">04</option>
			<option value="05">05</option>
			<option value="06">06</option>
			<option value="07">07</option>
			<option value="08">08</option>
			<option value="09">09</option>
			<option value="10">10</option>
			<option value="11">11</option>
			<option value="12">12</option>
			<option value="13">13</option>
			<option value="14">14</option>
			<option value="15">15</option>
			<option value="16">16</option>
			<option value="17">17</option>
			<option value="18">18</option>
			<option value="19">19</option>
			<option value="20">20</option>
			<option value="21">21</option>
			<option value="22">22</option>
			<option value="23">23</option>
			<option value="24">24</option>
			<option value="25">25</option>
			<option value="26">26</option>
			<option value="27">27</option>
			<option value="28">28</option>
			<option value="29">29</option>
			<option value="30">30</option>
			<option value="31">31</option>
		</select>
';
				
$form_sMon = '
		<select name="DMon">
			<option value="01">������</option>
			<option value="02">�������</option>
			<option value="03">����</option>
			<option value="04">������</option>
			<option value="05">���</option>
			<option value="06">����</option>
			<option value="07">����</option>
			<option value="08">������</option>
			<option value="09">��������</option>
			<option value="10">�������</option>
			<option value="11">������</option>
			<option value="12">�������</option>
		</select>
';

$form_sYear = '<input type="text" name="DYear" size="2" maxlength="4" value="1900">';

if(isset($val))
	{
	$YMD = explode('-', $val);

	$pattern2 = '/(<option value=")('.$YMD[2].')(">)([\d]+)(<\/option>)/i';
	$pattern1 = '/(<option value=")('.$YMD[1].')(">)([�-��-�]+)(<\/option>)/i';

	$form_sDay  = preg_replace($pattern2, '<option value="$2" selected>$4</option>', $form_sDay);
	$form_sMon  = preg_replace($pattern1, '<option value="$2" selected>$4</option>', $form_sMon); 
	$form_sYear = '<input type="text" name="DYear" size="2" maxlength="4" value="'.$YMD[0].'">';
	}
	
return '
		<table id="datecell" cellpadding="0" cellspacing="0">
			<tr><th>�����</th>
				<th>�����</th>
				<th>���</th>
			</tr>
			<tr><td>
' . $form_sDay . '
				</td>
				<td>
' . $form_sMon . '
				</td>
				<td>
' . $form_sYear . '
				</td>
			</tr>
		</table>
';	
}
////////////////////////////////////////////////////////////////////////////////////////
function inp_Citizenship($val, $edit)
{
$form_select = '

<select name="Citizenship">
<optgroup label="���������� ������� ����">
<option value="az" >�����������</option>
<option value="am" >�������</option>
<option value="by" >����������</option>
<option value="ge" >������</option>
<option value="kz" >���������</option>
<option value="kg" >��������</option>
<option value="lv" >������</option>
<option value="lt" >�����</option>
<option value="md" >��������</option>
<option value="ru" >���������� ���������</option>
<option value="tj" >�����������</option>
<option value="tm" >���������</option>
<option value="uz" >����������</option>
<option value="ua" >�������</option>
<option value="ee" >�������</option>

</optgroup>       
<optgroup label="��� ������">
<option value="au" >���������</option>
<option value="at" >�������</option>
<option value="al" >�������</option>
<option value="dz" >�����</option>
<option value="as" >������������ �����</option>
<option value="ao" >������</option>
<option value="ai" >��������</option>
<option value="ad" >�������</option>
<option value="aq" >���������</option>
<option value="ag" >�������</option>
<option value="ar" >���������</option>
<option value="aw" >�����</option>
<option value="af" >����������</option>
<option value="bs" >������</option>
<option value="bd" >���������</option>
<option value="bb" >��������</option>
<option value="bh" >�������</option>
<option value="bz" >�����</option>
<option value="be" >�������</option>
<option value="bj" >�����</option>
<option value="ci" >����� �������� �����</option>
<option value="bm" >�������</option>
<option value="bg" >��������</option>
<option value="bo" >�������</option>
<option value="ba" >������-�����������</option>
<option value="bw" >��������</option>
<option value="br" >��������</option>
<option value="bn" >������</option>
<option value="bv" >��������� �������</option>
<option value="bf" >������� ����</option>
<option value="bi" >�������</option>
<option value="bt" >�����</option>
<option value="vu" >�������</option>
<option value="va" >�������</option>
<option value="uk" >��������������</option>
<option value="hu" >�������</option>
<option value="ve" >���������</option>
<option value="vg" >���������� �-�� (��������)</option>
<option value="vi" >���������� �-�� (���)</option>
<option value="vn" >�������</option>
<option value="ga" >�����</option>
<option value="ht" >�����</option>
<option value="gy" >������</option>
<option value="gm" >������</option>
<option value="gh" >����</option>
<option value="gt" >���������</option>
<option value="gn" >������</option>
<option value="gw" >������-�����</option>
<option value="de" >��������</option>
<option value="gi" >���������</option>
<option value="nl" >���������</option>
<option value="hn" >��������</option>
<option value="hk" >�������</option>
<option value="gd" >�������</option>
<option value="gl" >����������</option>
<option value="gr" >������</option>
<option value="gu" >���� (���)</option>
<option value="dk" >�����</option>
<option value="dj" >�������</option>
<option value="dm" >��������</option>
<option value="do" >������������� ����������</option>
<option value="eg" >������</option>
<option value="zr" >����</option>
<option value="zm" >������</option>
<option value="eh" >�������� ������</option>
<option value="zw" >��������</option>
<option value="il" >�������</option>
<option value="in" >�����</option>
<option value="id" >���������</option>
<option value="jo" >��������</option>
<option value="iq" >����</option>
<option value="ir" >����</option>
<option value="ie" >��������</option>
<option value="is" >��������</option>
<option value="es" >�������</option>
<option value="it" >������</option>
<option value="ye" >�����</option>
<option value="cv" >����-�����</option>
<option value="ky" >��������� �������</option>
<option value="kh" >��������</option>
<option value="cm" >�������</option>
<option value="ca" >������</option>
<option value="qa" >�����</option>
<option value="ke" >�����</option>
<option value="cy" >����</option>
<option value="ki" >��������</option>
<option value="cn" >�����</option>
<option value="cc" >��������� �������</option>
<option value="co" >��������</option>
<option value="km" >������</option>
<option value="cg" >�����</option>
<option value="kp" >����� ��������</option>
<option value="kr" >����� �����</option>
<option value="cr" >�����-����</option>
<option value="cx" >����������� �������</option>
<option value="cu" >����</option>
<option value="kw" >������</option>
<option value="ck" >���� �������</option>
<option value="la" >����</option>
<option value="ls" >������</option>
<option value="lr" >�������</option>
<option value="lb" >�����</option>
<option value="ly" >�����</option>
<option value="li" >�����������</option>
<option value="lu" >����������</option>
<option value="mu" >��������</option>
<option value="mr" >����������</option>
<option value="mg" >����������</option>
<option value="mo" >�����</option>
<option value="mw" >������</option>
<option value="my" >��������</option>
<option value="ml" >����</option>
<option value="mv" >��������</option>
<option value="mt" >������</option>
<option value="ma" >�������</option>
<option value="mq" >���������</option>
<option value="mh" >���������� �������</option>
<option value="mx" >�������</option>
<option value="fm" >����������</option>
<option value="mz" >��������</option>
<option value="mc" >������</option>
<option value="mn" >��������</option>
<option value="ms" >����������</option>
<option value="mm" >������</option>
<option value="na" >�������</option>
<option value="nr" >�����</option>
<option value="nt" >����������� ����</option>
<option value="np" >�����</option>
<option value="ne" >�����</option>
<option value="ng" >�������</option>
<option value="an" >������������� ���������� �������</option>
<option value="ni" >���������</option>
<option value="nu" >���</option>
<option value="nz" >����� ��������</option>
<option value="nc" >����� ���������</option>
<option value="no" >��������</option>
<option value="nf" >������� ������</option>
<option value="ae" >������������ �������� �������</option>
<option value="om" >����</option>
<option value="pk" >��������</option>
<option value="pw" >�����</option>
<option value="pa" >������</option>
<option value="pg" >�����-����� ������</option>
<option value="py" >��������</option>
<option value="pe" >����</option>
<option value="pn" >������� ������</option>
<option value="pl" >������</option>
<option value="pt" >����������</option>
<option value="pr" >������-����</option>
<option value="re" >�������</option>
<option value="rw" >������</option>
<option value="ro" >�������</option>
<option value="sv" >���������</option>
<option value="ws" >�����</option>
<option value="sm" >���-������</option>
<option value="st" >���-���� � ��������</option>
<option value="sa" >���������� ������</option>
<option value="sh" >��.����� ������</option>
<option value="sz" >���������</option>
<option value="sj" >�������� � ��� ����� �-��</option>
<option value="sc" >�������</option>
<option value="sn" >�������</option>
<option value="vc" >����-������� � ���������</option>
<option value="kn" >����-���� � �����</option>
<option value="lc" >����-�����</option>
<option value="yu" >������ � ����������</option>
<option value="sg" >��������</option>
<option value="sy" >�����</option>
<option value="sk" >��������</option>
<option value="si" >��������</option>
<option value="us" >����������� ����� ������� (���)</option>
<option value="sb" >���������� �������</option>
<option value="so" >������</option>
<option value="sd" >�����</option>
<option value="sr" >�������</option>
<option value="sl" >������-�����</option>
<option value="th" >�������</option>
<option value="tw" >�������</option>
<option value="tz" >��������</option>
<option value="tp" >����� ���������</option>
<option value="tg" >����</option>
<option value="tk" >�������</option>
<option value="to" >�����</option>
<option value="tt" >�������� � ������</option>
<option value="tv" >������</option>
<option value="tn" >�����</option>
<option value="tr" >������</option>
<option value="ug" >������</option>
<option value="wf" >������ �������</option>
<option value="uy" >�������</option>
<option value="fo" >����� �������</option>
<option value="fj" >�����</option>
<option value="ph" >���������</option>
<option value="fi" >���������</option>
<option value="fk" >������������ �������</option>
<option value="fr" >�������</option>
<option value="gp" >����������� ���������</option>
<option value="gf" >����������� ������</option>
<option value="pf" >����������� ���������</option>
<option value="hm" >���� � ������������ �������</option>
<option value="hr" >��������</option>
<option value="cf" >����������-����������� ����������</option>
<option value="td" >���</option>
<option value="cz" >�����</option>
<option value="cs" >������������</option>
<option value="cl" >����</option>
<option value="ch" >���������</option>
<option value="se" >������</option>
<option value="lk" >���-�����</option>
<option value="ec" >�������</option>
<option value="gq" >�������������� ������</option>
<option value="et" >�������</option>
<option value="za" >����-����������� ����������</option>
<option value="jm" >������</option>
<option value="jp" >������</option>
</optgroup>       
</select>

';
if(isset($val))
	{
	$pattern = '/(<option value=")('.$val.')(" >)([�-��-� .\(\)\-]+)(<\/option>)/i';
	if($edit) { return preg_replace($pattern, '<option value="$2" selected>$4</option>', $form_select); }
	else      { preg_match_all($pattern,$form_select,$matches);	return $matches[0][0]; }
	}
else{
	return preg_replace('/(<option value="ru" >���������� ���������<\/option>)/i', '<option value="ru" selected>���������� ���������</option>', $form_select);
	}
}
////////////////////////////////////////////////////////////////////////////////////////
function inp_Education($val, $edit)
{
$form_select = '
	<select name="Education" style="width: 150px;">
			<option value="prim">�������</option>
			<option value="midspec">��. �����������</option>
			<option value="high">������</option>
	</select>
';
if(isset($val))
	{
	$pattern = '/(<option value=")('.$val.')(">)([�-��-� .]+)(<\/option>)/i';
	if($edit) { $form_select = preg_replace($pattern, '<option value="$2" selected>$4</option>', $form_select); }
	else      { preg_match_all($pattern,$form_select,$matches);	$form_select = $matches[0][0]; }
	}
return $form_select;
}
////////////////////////////////////////////////////////////////////////////////////////
function inp_MEducation($val, $edit)
{
if($edit) {
	if($val) { return '<input type="checkbox" name="MEducation" value="1" checked>'; } 
	else        { return '<input type="checkbox" name="MEducation" value="1">'; }
	}
else{ 
	if($val) { return '�����������'; } 
	else        { return ''; }
	}
}
////////////////////////////////////////////////////////////////////////////////////////
function inp_Ruslang($val, $edit)
{
$form_select = '
	<select name="Ruslang" style="width: 150px;">
			<option value="1">�� ������</option>
			<option value="2">���� ��������</option>
			<option value="3">�������</option>
			<option value="4">�������</option>
			<option value="5">��������</option>
	</select>
';
if(isset($val))
	{
	$pattern = '/(<option value=")('.$val.')(">)([�-��-� ]+)(<\/option>)/i';
	if($edit) { $form_select = preg_replace($pattern, '<option value="$2" selected>$4</option>', $form_select); }
	else      { preg_match_all($pattern,$form_select,$matches);	$form_select = $matches[0][0]; }
	}
return $form_select;
}
////////////////////////////////////////////////////////////////////////////////////////
function inp_Exper_Years($val, $edit)
	{
$form_select = '
	<select name="Exper_Years" style="width: 150px;">
		<option value="0">��� �����</option>
		<option value="1">1 ���</option>
		<option value="2">2 ����</option>
		<option value="3">3 ����</option>
		<option value="4">4 ����</option>
		<option value="5">5 ���</option>
		<option value="6">6 ���</option>
		<option value="7">7 ���</option>
		<option value="8">8 ���</option>
		<option value="9">9 ���</option>
		<option value="10">10 � ����� ���</option>
	</select>
	';
if(isset($val))
	{
	$pattern = '/(<option value=")('.$val.')(">)([0-9�-��-� ]+)(<\/option>)/i';
	if($edit) { $form_select = preg_replace($pattern, '<option value="$2" selected>$4</option>', $form_select); }
	else      { preg_match_all($pattern,$form_select,$matches);	$form_select = $matches[0][0]; }
	}
return $form_select;



//	return '<input type="text" name="Exper_Years" size="1" maxlength="2" value="'.$val.'">'; 
}
function inp_Exper_details($val)
	{ return '<textarea name="Exper_details" cols="33" rows="7">'.$val.'</textarea>'; }
////////////////////////////////////////////////////////////////////////////////////////
function inp_Worktype($val, $edit)
{
$form_select = '
	<select name="Worktype" style="width: 150px;">
		<option value="any">�����</option>
		<option value="livin">� �����������</option>
		<option value="coming">��� ����������</option>
	</select>
';
if(isset($val))
	{
	$pattern = '/(<option value=")('.$val.')(">)([�-��-� ]+)(<\/option>)/i';
	if($edit) { $form_select = preg_replace($pattern, '<option value="$2" selected>$4</option>', $form_select); }
	else      { preg_match_all($pattern,$form_select,$matches);	$form_select = $matches[0][0]; }
	}
return $form_select;
}
////////////////////////////////////////////////////////////////////////////////////////
function inp_Wage($val)
	{ return '<input type="text" name="Wage" size="4" maxlength="7" value="'.$val.'">'; }
////////////////////////////////////////////////////////////////////////////////////////
function inp_Currency($val, $edit)
{
$form_select = '
		<select name="Currency" style="width: 96px;">
			<option value="rub">������</option>
			<option value="usd">��������</option>
			<option value="euro">����</option>
		</select>
';
if(isset($val))
	{
	$pattern = '/(<option value=")('.$val.')(">)([�-��-� ]+)(<\/option>)/i';
	if($edit) { $form_select = preg_replace($pattern, '<option value="$2" selected>$4</option>', $form_select); }
	else      { preg_match_all($pattern,$form_select,$matches);	$form_select = $matches[0][0]; }
	}
return $form_select;
}	
////////////////////////////////////////////////////////////////////////////////////////
function inp_TFrame($val, $edit)
{
$form_select = '
		<select name="TFrame" style="width: 96px;">
			<option value="hour">� ���</option>
			<option value="day">� ����</option>
			<option value="month">� �����</option>
			<option value="wday">�� �����</option>
		</select>
';
if(isset($val))
	{
	$pattern = '/(<option value=")('.$val.')(">)([�-��-� ]+)(<\/option>)/i';
	if($edit) { $form_select = preg_replace($pattern, '<option value="$2" selected>$4</option>', $form_select); }
	else      { preg_match_all($pattern,$form_select,$matches);	$form_select = $matches[0][0]; }
	}
return $form_select;
}	
////////////////////////////////////////////////////////////////////////////////////////
function inp_TPeriod_amt($val)
{
return '<input type="text" name="TPeriod_amt" size="1" maxlength="2" value="'.$val.'">';
}	
////////////////////////////////////////////////////////////////////////////////////////
function inp_TPeriod_item($val, $edit)
{
$form_select = '
		<select name="TPeriod_item" style="width: 70px;">
			<option value="hour">���</option>
			<option value="day">����</option>
			<option value="month">�����</option>
		</select>
';
if(isset($val))
	{
	$pattern = '/(<option value=")('.$val.')(">)([�-��-� ]+)(<\/option>)/i';
	if($edit) { $form_select = preg_replace($pattern, '<option value="$2" selected>$4</option>', $form_select); }
	else      { preg_match_all($pattern,$form_select,$matches);	$form_select = $matches[0][0]; }
	}
return $form_select;
}	
////////////////////////////////////////////////////////////////////////////////////////
function inp_Available($val, $edit)
{
if($edit) {
	if($val) { return '<input type="radio" name="Available" value="1" checked> - ��� ������<br><input type="radio" name="Available" value="0"> - �������� �����(�)'; } 
	else     { return '<input type="radio" name="Available" value="1"> - ��� ������<br><input type="radio" name="Available" value="0" checked> - �������� �����(�)'; }
	}
else{ 
	if($val) { return '��� ������'; } 
	else     { return '�������� �����(�)'; }
	}
}
////////////////////////////////////////////////////////////////////////////////////////
function inp_Addinfo($val)
	{ return '<textarea name="Addinfo" cols="33" rows="7">'.$val.'</textarea>'; }
////////////////////////////////////////////////////////////////////////////////////////
function inp_Region($val)
{
$form_select = '
&nbsp;&nbsp; ��. �����: 
<select name="RegionMetro" onChange="metroselect(this.value);">
<option value=""></option>
<option value="������������">������������</option>
<option value="�������������">�������������</option>
<option value="�������������">�������������</option>
<option value="��������������� ���">��������������� ���</option>
<option value="������������">������������</option>
<option value="����-��������">����-��������</option>
<option value="���������">���������</option>
<option value="������">������</option>
<option value="���������">���������</option>
<option value="��������">��������</option>
<option value="������������">������������</option>
<option value="���������������">���������������</option>
<option value="�����������">�����������</option>
<option value="����������">����������</option>
<option value="�������">�������</option>
<option value="�����������">�����������</option>
<option value="�����������">�����������</option>
<option value="�������">�������</option>
<option value="��������">��������</option>
<option value="���������� ��. ������">���������� ��. ������</option>
<option value="��������">��������</option>
<option value="����������">����������</option>
<option value="������������ ���">������������ ���</option>
<option value="�������������">�������������</option>
<option value="������� ������� ��������">������� ������� ��������</option>
<option value="����������">����������</option>
<option value="����">����</option>
<option value="���������">���������</option>
<option value="������ �������">������ �������</option>
<option value="����������">����������</option>
<option value="������������� ��������">������������� ��������</option>
<option value="��������">��������</option>
<option value="�������������">�������������</option>
<option value="��������� ����">��������� ����</option>
<option value="�����������">�����������</option>
<option value="������">������</option>
<option value="������">������</option>
<option value="�����������">�����������</option>
<option value="������������">������������</option>
<option value="�������������">�������������</option>
<option value="�����������">�����������</option>
<option value="��������">��������</option>
<option value="��������">��������</option>
<option value="���������">���������</option>
<option value="������������">������������</option>
<option value="���������">���������</option>
<option value="��������������">��������������</option>
<option value="���������">���������</option>
<option value="���������">���������</option>
<option value="��������">��������</option>
<option value="����� �����">����� �����</option>
<option value="�����������">�����������</option>
<option value="�����������">�����������</option>
<option value="�������������">�������������</option>
<option value="��������">��������</option>
<option value="�����������������">�����������������</option>
<option value="�����������������">�����������������</option>
<option value="��������������">��������������</option>
<option value="������� ������">������� ������</option>
<option value="������������ �������">������������ �������</option>
<option value="�������������">�������������</option>
<option value="����������">����������</option>
<option value="��������� ����">��������� ����</option>
<option value="���������">���������</option>
<option value="����������">����������</option>
<option value="�������">�������</option>
<option value="�����������">�����������</option>
<option value="��������� ��������">��������� ��������</option>
<option value="������������� ��������">������������� ��������</option>
<option value="�������">�������</option>
<option value="�������">�������</option>
<option value="������������">������������</option>
<option value="������� ����">������� ����</option>
<option value="�������">�������</option>
<option value="����������">����������</option>
<option value="����������">����������</option>
<option value="�������������">�������������</option>
<option value="�������������">�������������</option>
<option value="������">������</option>
<option value="����������">����������</option>
<option value="��������">��������</option>
<option value="�����������">�����������</option>
<option value="��������">��������</option>
<option value="����������� ��������">����������� ��������</option>
<option value="�����������">�����������</option>
<option value="����������">����������</option>
<option value="�������������">�������������</option>
<option value="��������������">��������������</option>
<option value="��������������">��������������</option>
<option value="����� ���������">����� ���������</option>
<option value="�����������">�����������</option>
<option value="����������� ����">����������� ����</option>
<option value="�������">�������</option>
<option value="��������">��������</option>
<option value="������� ���">������� ���</option>
<option value="����������">����������</option>
<option value="���� ��������">���� ��������</option>
<option value="���� ������">���� ������</option>
<option value="������������">������������</option>
<option value="������������">������������</option>
<option value="������">������</option>
<option value="���������-�����������">���������-�����������</option>
<option value="���������">���������</option>
<option value="����������">����������</option>
<option value="���������">���������</option>
<option value="������� ������">������� ������</option>
<option value="������� ���������">������� ���������</option>
<option value="������������">������������</option>
<option value="�������">�������</option>
<option value="��������">��������</option>
<option value="�������������� �������">�������������� �������</option>
<option value="������������">������������</option>
<option value="�������� �����������">�������� �����������</option>
<option value="�������� ����">�������� ����</option>
<option value="�������� ����">�������� ����</option>
<option value="�����������">�����������</option>
<option value="����������">����������</option>
<option value="������ ������">������ ������</option>
<option value="�������">�������</option>
<option value="�������">�������</option>
<option value="��������� ��������">��������� ��������</option>
<option value="�����������">�����������</option>
<option value="��������">��������</option>
<option value="���������������">���������������</option>
<option value="�����������">�����������</option>
<option value="������������">������������</option>
<option value="���������� �������">���������� �������</option>
<option value="����������">����������</option>
<option value="�����">�����</option>
<option value="����������">����������</option>
<option value="����������">����������</option>
<option value="���������� �������">���������� �������</option>
<option value="��������">��������</option>
<option value="������������">������������</option>
<option value="�����������">�����������</option>
<option value="�����������">�����������</option>
<option value="���������">���������</option>
<option value="��������">��������</option>
<option value="�����������">�����������</option>
<option value="������������">������������</option>
<option value="Ҹ���� ����">Ҹ���� ����</option>
<option value="�������������">�������������</option>
<option value="�������������">�������������</option>
<option value="�������">�������</option>
<option value="��������">��������</option>
<option value="������������">������������</option>
<option value="���������">���������</option>
<option value="��. ��������� ������">��. ��������� ������</option>
<option value="����� 1905 ����">����� 1905 ����</option>
<option value="����� ������������">����� ������������</option>
<option value="�����������">�����������</option>
<option value="��������� ����">��������� ����</option>
<option value="����">����</option>
<option value="�����������">�����������</option>
<option value="��������">��������</option>
<option value="������� �������">������� �������</option>
<option value="������������">������������</option>
<option value="������������">������������</option>
<option value="���������">���������</option>
<option value="������ �����">������ �����</option>
<option value="����������">����������</option>
<option value="�����������">�����������</option>
<option value="�����������">�����������</option>
<option value="����� �����������">����� �����������</option>
<option value="����������">����������</option>
<option value="���������">���������</option>
<option value="����������������">����������������</option>
<option value="���-��������">���-��������</option>
<option value="�����">�����</option>
<option value="�������">�������</option>
</select>	
<br>		
��� ������ (�����, ����, ���������� �����...)<br>
<input type="text" name="Region" size="43" maxlength="128" value="'.$val.'">
';

$form_select .= <<<EODJ_1
<script type="Text/Javascript" language="Javascript">

function metroselect(mval)
	{ addsitter.Region.value = '�. ' + addsitter.RegionMetro.value;	}

function picpreview(obj)
	{
	document.getElementById("uppic").src = obj.value;
	return;
	}

</script>
EODJ_1;

return $form_select;
}
////////////////////////////////////////////////////////////////////////////////////////
function inp_upimg()
	{ return '<input type="file" name="upimg" title="�����" style="background: #fff; padding: 1px;" donChange="picpreview(this);">'; }
function inp_Phone($val)
	{ return '<input type="text" name="Phone" size="42" maxlength="64" value="'.$val.'">'; }
function inp_EMail($val)
	{ return '<input type="text" name="EMail" size="25" maxlength="64" value="'.$val.'">'; }
function inp_password()
	{ return '<input type="password" name="password" size="20" maxlength="20" value="">'; }
function inp_password_confirm()
	{ return '<input type="password" name="password_confirm" size="20" maxlength="20" value="">'; }
function inp_cpcha()
	{ return '<input type="text" name="captcha_code" size="2" maxlength="4" style="float: left; margin-top: 13px;" />
<img src="inc/functions/verifimage.php" style="float: left; width: 92px; margin-left: 5px;" />'; }
function inp_tnc()
	{ return '<input type="checkbox" name="termsagree" value="1" style="float: left;" checked />'; }

////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////


function upl_other($pname)
{
$retxt = '
<form name="newad" method="post" enctype="multipart/form-data" action="mngdb.php?cmd=up_img">
<table class="SitterForm" cellspacing="1">
	<tr><td><input type="file" name="upimg" value="�����" style="padding: 2px 10px;">
';
$retxt .= '<input type="hidden" name="imname" value="' . $pname . '">';
$retxt .= '<input name="Submit" type="submit" value="��������� ����" style="padding: 2px 10px;">
		</td>
	</tr>
</table>
</form>';
return $retxt;
}

function upload_pic($pname)
{
	$max_size = 500;
	$max_w    = 300;
	$max_h    = 400;
	$file_types = array(
	'image/pjpeg' => 'jpg',
	'image/jpeg' => 'jpg',
	'image/gif' => 'gif',
	'image/png' => 'png',
	'image/bmp' => 'bmp',
	);       
	  
	//	$img = $_POST[FName];
	$imgsize = $_FILES['upimg']['size'];
	$imgtype = $_FILES['upimg']['type'];
	$imgname = 'm' . $pname . '.' . $file_types[$imgtype];
	$upload_dir = $_SERVER['DOCUMENT_ROOT'] . "/stpics/";

	if ($imgsize > ($max_size * 1024))
		{ return '���������� ��������� ������ ' . $max_size . '��.';	}
	if (!array_key_exists($imgtype, $file_types))
		{ return '����������������� ��� ������������ ����� - ' . $imgtype; }

	$imgfpath = $upload_dir . $imgname;
	
//	list($w, $h) = getimagesize($imgfpath);
//	if(($h > $max_h) OR ($w > $max_w)){ ImgResize($max_w, $max_h, $imgfpath); }
//	ImgResize($max_w, $max_h, $imgfpath);
	move_uploaded_file($_FILES['upimg']['tmp_name'], $imgfpath); 

	return;
}

/**
         * Image re-size
         * @param int $width
         * @param int $height

*/		 
function ImgResize($width, $height, $img_name)
{
	list($w, $h) = getimagesize($_FILES['upimg']['tmp_name']);

	$ratio = max($width/$w, $height/$h);
	$h = ceil($height / $ratio);
	$x = ($w - $width / $ratio) / 2;
	$w = ceil($width / $ratio);
	$path = $img_name;

	if($_FILES['upimg']['type']=='image/jpeg')
		{
		$imgString = file_get_contents($_FILES['upimg']['tmp_name']);
		$image = imagecreatefromstring($imgString);
		$tmp = imagecreatetruecolor($width, $height);
		imagecopyresampled($tmp, $image, 0, 0, $x, 0, $width, $height, $w, $h);
		imagejpeg($tmp, $path, 100);
		}
	else if($_FILES['upimg']['type']=='image/png')
		{
		$image = imagecreatefrompng($_FILES['upimg']['tmp_name']);
		$tmp = imagecreatetruecolor($width,$height);
		imagealphablending($tmp, false);
		imagesavealpha($tmp, true);
		imagecopyresampled($tmp, $image,0,0,$x,0,$width,$height,$w, $h);
		imagepng($tmp, $path, 0);
		}
	else if($_FILES['upimg']['type']=='image/gif')
		{
		$image = imagecreatefromgif($_FILES['upimg']['tmp_name']);
		$tmp = imagecreatetruecolor($width,$height);
		$transparent = imagecolorallocatealpha($tmp, 0, 0, 0, 127);
		imagefill($tmp, 0, 0, $transparent);
		imagealphablending($tmp, true); 
		imagecopyresampled($tmp, $image,0,0,0,0,$width,$height,$w, $h);
		imagegif($tmp, $path);
		}
	else
		{
		return false;
		}
	return true;
	imagedestroy($image);
	imagedestroy($tmp);
}

?>
