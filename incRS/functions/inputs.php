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
	<option value="F">Жен.</option>
	<option value="M" selected>Муж.</option>
</select>';
	}
else
	{
	return '<select name="Gender">
	<option value="F" selected>Жен.</option>
	<option value="M">Муж.</option>
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
			<option value="01">Январь</option>
			<option value="02">Февраль</option>
			<option value="03">Март</option>
			<option value="04">Апрель</option>
			<option value="05">Май</option>
			<option value="06">Июнь</option>
			<option value="07">Июль</option>
			<option value="08">Август</option>
			<option value="09">Сентябрь</option>
			<option value="10">Октябрь</option>
			<option value="11">Ноябрь</option>
			<option value="12">Декабрь</option>
		</select>
';

$form_sYear = '<input type="text" name="DYear" size="2" maxlength="4" value="1900">';

if(isset($val))
	{
	$YMD = explode('-', $val);

	$pattern2 = '/(<option value=")('.$YMD[2].')(">)([\d]+)(<\/option>)/i';
	$pattern1 = '/(<option value=")('.$YMD[1].')(">)([а-яА-Я]+)(<\/option>)/i';

	$form_sDay  = preg_replace($pattern2, '<option value="$2" selected>$4</option>', $form_sDay);
	$form_sMon  = preg_replace($pattern1, '<option value="$2" selected>$4</option>', $form_sMon); 
	$form_sYear = '<input type="text" name="DYear" size="2" maxlength="4" value="'.$YMD[0].'">';
	}
	
return '
		<table id="datecell" cellpadding="0" cellspacing="0">
			<tr><th>Число</th>
				<th>Месяц</th>
				<th>Год</th>
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
<optgroup label="Республики бывшего СССР">
<option value="az" >Азербайджан</option>
<option value="am" >Армения</option>
<option value="by" >Белоруссия</option>
<option value="ge" >Грузия</option>
<option value="kz" >Казахстан</option>
<option value="kg" >Киргизия</option>
<option value="lv" >Латвия</option>
<option value="lt" >Литва</option>
<option value="md" >Молдавия</option>
<option value="ru" >Российская Федерация</option>
<option value="tj" >Таджикистан</option>
<option value="tm" >Туркмения</option>
<option value="uz" >Узбекистан</option>
<option value="ua" >Украина</option>
<option value="ee" >Эстония</option>

</optgroup>       
<optgroup label="Все страны">
<option value="au" >Австралия</option>
<option value="at" >Австрия</option>
<option value="al" >Албания</option>
<option value="dz" >Алжир</option>
<option value="as" >Американская Самоа</option>
<option value="ao" >Ангола</option>
<option value="ai" >Ангуилла</option>
<option value="ad" >Андорра</option>
<option value="aq" >Антартика</option>
<option value="ag" >Антигуа</option>
<option value="ar" >Аргентина</option>
<option value="aw" >Аруба</option>
<option value="af" >Афганистан</option>
<option value="bs" >Багамы</option>
<option value="bd" >Бангладеш</option>
<option value="bb" >Барбадос</option>
<option value="bh" >Бахрейн</option>
<option value="bz" >Белиз</option>
<option value="be" >Бельгия</option>
<option value="bj" >Бенин</option>
<option value="ci" >Берег слоновой кости</option>
<option value="bm" >Бермуды</option>
<option value="bg" >Болгария</option>
<option value="bo" >Боливия</option>
<option value="ba" >Босния-Герцеговина</option>
<option value="bw" >Ботсвана</option>
<option value="br" >Бразилия</option>
<option value="bn" >Бруней</option>
<option value="bv" >Буветские острова</option>
<option value="bf" >Буркина фасо</option>
<option value="bi" >Бурунди</option>
<option value="bt" >Бутан</option>
<option value="vu" >Вануату</option>
<option value="va" >Ватикан</option>
<option value="uk" >Великобритания</option>
<option value="hu" >Венгрия</option>
<option value="ve" >Венесуэла</option>
<option value="vg" >Виргинские о-ва (британия)</option>
<option value="vi" >Виргинские о-ва (сша)</option>
<option value="vn" >Вьетнам</option>
<option value="ga" >Габон</option>
<option value="ht" >Гаити</option>
<option value="gy" >Гайана</option>
<option value="gm" >Гамбия</option>
<option value="gh" >Гана</option>
<option value="gt" >Гватемала</option>
<option value="gn" >Гвинея</option>
<option value="gw" >Гвинея-Бисау</option>
<option value="de" >Германия</option>
<option value="gi" >Гибралтар</option>
<option value="nl" >Голландия</option>
<option value="hn" >Гондурас</option>
<option value="hk" >Гонконг</option>
<option value="gd" >Гренада</option>
<option value="gl" >Гренладния</option>
<option value="gr" >Греция</option>
<option value="gu" >Гуам (США)</option>
<option value="dk" >Дания</option>
<option value="dj" >Джибути</option>
<option value="dm" >Доминика</option>
<option value="do" >Доминиканская республика</option>
<option value="eg" >Египет</option>
<option value="zr" >Заир</option>
<option value="zm" >Замбия</option>
<option value="eh" >Западная Сахара</option>
<option value="zw" >Зимбабве</option>
<option value="il" >Израиль</option>
<option value="in" >Индия</option>
<option value="id" >Индонезия</option>
<option value="jo" >Иордания</option>
<option value="iq" >Ирак</option>
<option value="ir" >Иран</option>
<option value="ie" >Ирландия</option>
<option value="is" >Исландия</option>
<option value="es" >Испания</option>
<option value="it" >Италия</option>
<option value="ye" >Йемен</option>
<option value="cv" >Кабо-Верде</option>
<option value="ky" >Каймановы острова</option>
<option value="kh" >Камбоджа</option>
<option value="cm" >Камерун</option>
<option value="ca" >Канада</option>
<option value="qa" >Катар</option>
<option value="ke" >Кения</option>
<option value="cy" >Кипр</option>
<option value="ki" >Кирибати</option>
<option value="cn" >Китай</option>
<option value="cc" >Кокосовые острова</option>
<option value="co" >Колумбия</option>
<option value="km" >Коморы</option>
<option value="cg" >Конго</option>
<option value="kp" >Корея Северная</option>
<option value="kr" >Корея Южная</option>
<option value="cr" >Коста-Рика</option>
<option value="cx" >Кристмасовы острова</option>
<option value="cu" >Куба</option>
<option value="kw" >Кувейт</option>
<option value="ck" >Кука острова</option>
<option value="la" >Лаос</option>
<option value="ls" >Лесото</option>
<option value="lr" >Либерия</option>
<option value="lb" >Ливан</option>
<option value="ly" >Ливия</option>
<option value="li" >Лихтенштейн</option>
<option value="lu" >Люксембург</option>
<option value="mu" >Маврикий</option>
<option value="mr" >Мавритания</option>
<option value="mg" >Мадагаскар</option>
<option value="mo" >Макао</option>
<option value="mw" >Малави</option>
<option value="my" >Малайзия</option>
<option value="ml" >Мали</option>
<option value="mv" >Мальдивы</option>
<option value="mt" >Мальта</option>
<option value="ma" >Марокко</option>
<option value="mq" >Мартиника</option>
<option value="mh" >Маршалловы острова</option>
<option value="mx" >Мексика</option>
<option value="fm" >Микронезия</option>
<option value="mz" >Мозамбик</option>
<option value="mc" >Монако</option>
<option value="mn" >Монголия</option>
<option value="ms" >Монтесерат</option>
<option value="mm" >Мьянма</option>
<option value="na" >Намибия</option>
<option value="nr" >Науру</option>
<option value="nt" >Нейтральная Зона</option>
<option value="np" >Непал</option>
<option value="ne" >Нигер</option>
<option value="ng" >Нигерия</option>
<option value="an" >Нидерландские Антильские острова</option>
<option value="ni" >Никарагуа</option>
<option value="nu" >Ниу</option>
<option value="nz" >Новая Зеландия</option>
<option value="nc" >Новая Каледония</option>
<option value="no" >Норвегия</option>
<option value="nf" >Норфолк остров</option>
<option value="ae" >Объединенные Арабские Эмираты</option>
<option value="om" >Оман</option>
<option value="pk" >Пакистан</option>
<option value="pw" >Палау</option>
<option value="pa" >Панама</option>
<option value="pg" >Папуа-Новая Гвинея</option>
<option value="py" >Парагвай</option>
<option value="pe" >Перу</option>
<option value="pn" >Питкерн остров</option>
<option value="pl" >Польша</option>
<option value="pt" >Португалия</option>
<option value="pr" >Пуэрто-Рико</option>
<option value="re" >Реюньон</option>
<option value="rw" >Руанда</option>
<option value="ro" >Румыния</option>
<option value="sv" >Сальвадор</option>
<option value="ws" >Самоа</option>
<option value="sm" >Сан-Марино</option>
<option value="st" >Сан-Томе и Принсипи</option>
<option value="sa" >Саудовская Аравия</option>
<option value="sh" >Св.Елены остров</option>
<option value="sz" >Свазиленд</option>
<option value="sj" >Свалбард и Жан Майен о-ва</option>
<option value="sc" >Сейшелы</option>
<option value="sn" >Сенегал</option>
<option value="vc" >Сент-Винсент и Гренадины</option>
<option value="kn" >Сент-Китс и Невис</option>
<option value="lc" >Сент-люсия</option>
<option value="yu" >Сербия и Черногория</option>
<option value="sg" >Сингапур</option>
<option value="sy" >Сирия</option>
<option value="sk" >Словакия</option>
<option value="si" >Словения</option>
<option value="us" >Соединенные Штаты Америки (США)</option>
<option value="sb" >Соломоновы острова</option>
<option value="so" >Сомали</option>
<option value="sd" >Судан</option>
<option value="sr" >Суринам</option>
<option value="sl" >Сьерра-Леоне</option>
<option value="th" >Таиланд</option>
<option value="tw" >Тайвань</option>
<option value="tz" >Танзания</option>
<option value="tp" >Тимор Восточный</option>
<option value="tg" >Того</option>
<option value="tk" >Токелау</option>
<option value="to" >Тонга</option>
<option value="tt" >Тринидад и Тобаго</option>
<option value="tv" >Тувалу</option>
<option value="tn" >Тунис</option>
<option value="tr" >Турция</option>
<option value="ug" >Уганда</option>
<option value="wf" >Уоллис острова</option>
<option value="uy" >Уругвай</option>
<option value="fo" >Фарое острова</option>
<option value="fj" >Фиджи</option>
<option value="ph" >Филиппины</option>
<option value="fi" >Финляндия</option>
<option value="fk" >Фолклендские острова</option>
<option value="fr" >Франция</option>
<option value="gp" >Французская Гваделупа</option>
<option value="gf" >Французская Гвинея</option>
<option value="pf" >Французская Полинезия</option>
<option value="hm" >Херд и Мкдональдовы острова</option>
<option value="hr" >Хорватия</option>
<option value="cf" >Центрально-Африканская Республика</option>
<option value="td" >Чад</option>
<option value="cz" >Чехия</option>
<option value="cs" >Чехословакия</option>
<option value="cl" >Чили</option>
<option value="ch" >Швейцария</option>
<option value="se" >Швеция</option>
<option value="lk" >Шри-Ланка</option>
<option value="ec" >Эквадор</option>
<option value="gq" >Экваториальная Гвинея</option>
<option value="et" >Эфиопия</option>
<option value="za" >Южно-Африканская Республика</option>
<option value="jm" >Ямайка</option>
<option value="jp" >Япония</option>
</optgroup>       
</select>

';
if(isset($val))
	{
	$pattern = '/(<option value=")('.$val.')(" >)([а-яА-Я .\(\)\-]+)(<\/option>)/i';
	if($edit) { return preg_replace($pattern, '<option value="$2" selected>$4</option>', $form_select); }
	else      { preg_match_all($pattern,$form_select,$matches);	return $matches[0][0]; }
	}
else{
	return preg_replace('/(<option value="ru" >Российская Федерация<\/option>)/i', '<option value="ru" selected>Российская Федерация</option>', $form_select);
	}
}
////////////////////////////////////////////////////////////////////////////////////////
function inp_Education($val, $edit)
{
$form_select = '
	<select name="Education" style="width: 150px;">
			<option value="prim">Среднее</option>
			<option value="midspec">Ср. специальное</option>
			<option value="high">Высшее</option>
	</select>
';
if(isset($val))
	{
	$pattern = '/(<option value=")('.$val.')(">)([а-яА-Я .]+)(<\/option>)/i';
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
	if($val) { return 'Медицинское'; } 
	else        { return ''; }
	}
}
////////////////////////////////////////////////////////////////////////////////////////
function inp_Ruslang($val, $edit)
{
$form_select = '
	<select name="Ruslang" style="width: 150px;">
			<option value="1">Не владею</option>
			<option value="2">Ниже среднего</option>
			<option value="3">Среднее</option>
			<option value="4">Хорошее</option>
			<option value="5">Отличное</option>
	</select>
';
if(isset($val))
	{
	$pattern = '/(<option value=")('.$val.')(">)([а-яА-Я ]+)(<\/option>)/i';
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
		<option value="0">Без опыта</option>
		<option value="1">1 год</option>
		<option value="2">2 года</option>
		<option value="3">3 года</option>
		<option value="4">4 года</option>
		<option value="5">5 лет</option>
		<option value="6">6 лет</option>
		<option value="7">7 лет</option>
		<option value="8">8 лет</option>
		<option value="9">9 лет</option>
		<option value="10">10 и более лет</option>
	</select>
	';
if(isset($val))
	{
	$pattern = '/(<option value=")('.$val.')(">)([0-9а-яА-Я ]+)(<\/option>)/i';
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
		<option value="any">Любой</option>
		<option value="livin">С проживанием</option>
		<option value="coming">Без проживания</option>
	</select>
';
if(isset($val))
	{
	$pattern = '/(<option value=")('.$val.')(">)([а-яА-Я ]+)(<\/option>)/i';
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
			<option value="rub">Рублей</option>
			<option value="usd">Долларов</option>
			<option value="euro">Евро</option>
		</select>
';
if(isset($val))
	{
	$pattern = '/(<option value=")('.$val.')(">)([а-яА-Я ]+)(<\/option>)/i';
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
			<option value="hour">В час</option>
			<option value="day">В день</option>
			<option value="month">В месяц</option>
			<option value="wday">За выход</option>
		</select>
';
if(isset($val))
	{
	$pattern = '/(<option value=")('.$val.')(">)([а-яА-Я ]+)(<\/option>)/i';
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
			<option value="hour">Час</option>
			<option value="day">День</option>
			<option value="month">Месяц</option>
		</select>
';
if(isset($val))
	{
	$pattern = '/(<option value=")('.$val.')(">)([а-яА-Я ]+)(<\/option>)/i';
	if($edit) { $form_select = preg_replace($pattern, '<option value="$2" selected>$4</option>', $form_select); }
	else      { preg_match_all($pattern,$form_select,$matches);	$form_select = $matches[0][0]; }
	}
return $form_select;
}	
////////////////////////////////////////////////////////////////////////////////////////
function inp_Available($val, $edit)
{
if($edit) {
	if($val) { return '<input type="radio" name="Available" value="1" checked> - Ищу работу<br><input type="radio" name="Available" value="0"> - Временно занят(а)'; } 
	else     { return '<input type="radio" name="Available" value="1"> - Ищу работу<br><input type="radio" name="Available" value="0" checked> - Временно занят(а)'; }
	}
else{ 
	if($val) { return 'Ищу работу'; } 
	else     { return 'Временно занят(а)'; }
	}
}
////////////////////////////////////////////////////////////////////////////////////////
function inp_Addinfo($val)
	{ return '<textarea name="Addinfo" cols="33" rows="7">'.$val.'</textarea>'; }
////////////////////////////////////////////////////////////////////////////////////////
function inp_Region($val)
{
$form_select = '
&nbsp;&nbsp; Ст. Метро: 
<select name="RegionMetro" onChange="metroselect(this.value);">
<option value=""></option>
<option value="Авиамоторная">Авиамоторная</option>
<option value="Автозаводская">Автозаводская</option>
<option value="Академическая">Академическая</option>
<option value="Александровский сад">Александровский сад</option>
<option value="Алексеевская">Алексеевская</option>
<option value="Алма-Атинская">Алма-Атинская</option>
<option value="Алтуфьево">Алтуфьево</option>
<option value="Аннино">Аннино</option>
<option value="Арбатская">Арбатская</option>
<option value="Аэропорт">Аэропорт</option>
<option value="Бабушкинская">Бабушкинская</option>
<option value="Багратионовская">Багратионовская</option>
<option value="Баррикадная">Баррикадная</option>
<option value="Бауманская">Бауманская</option>
<option value="Беговая">Беговая</option>
<option value="Белорусская">Белорусская</option>
<option value="Белорусская">Белорусская</option>
<option value="Беляево">Беляево</option>
<option value="Биберево">Биберево</option>
<option value="Библиотека им. Ленина">Библиотека им. Ленина</option>
<option value="Борисово">Борисово</option>
<option value="Боровицкая">Боровицкая</option>
<option value="Ботанический сад">Ботанический сад</option>
<option value="Братиславская">Братиславская</option>
<option value="Бульвар Дмитрия Донского">Бульвар Дмитрия Донского</option>
<option value="Варшавская">Варшавская</option>
<option value="ВДНХ">ВДНХ</option>
<option value="Владыкино">Владыкино</option>
<option value="Водный стадион">Водный стадион</option>
<option value="Войковская">Войковская</option>
<option value="Волгоградский проспект">Волгоградский проспект</option>
<option value="Волжская">Волжская</option>
<option value="Волоколамская">Волоколамская</option>
<option value="Воробьевы горы">Воробьевы горы</option>
<option value="Выставочная">Выставочная</option>
<option value="Выхино">Выхино</option>
<option value="Динамо">Динамо</option>
<option value="Дмитровская">Дмитровская</option>
<option value="Добрынинская">Добрынинская</option>
<option value="Домодедовская">Домодедовская</option>
<option value="Достоевская">Достоевская</option>
<option value="Дубровка">Дубровка</option>
<option value="Жулебино">Жулебино</option>
<option value="Зябликово">Зябликово</option>
<option value="Измайловская">Измайловская</option>
<option value="Калужская">Калужская</option>
<option value="Кантемировская">Кантемировская</option>
<option value="Каховская">Каховская</option>
<option value="Каширская">Каширская</option>
<option value="Киевская">Киевская</option>
<option value="Китай Город">Китай Город</option>
<option value="Кожуховская">Кожуховская</option>
<option value="Коломенская">Коломенская</option>
<option value="Комсомольская">Комсомольская</option>
<option value="Коньково">Коньково</option>
<option value="Красногвардейская">Красногвардейская</option>
<option value="Краснопресненская">Краснопресненская</option>
<option value="Красносельская">Красносельская</option>
<option value="Красные ворота">Красные ворота</option>
<option value="Крестьянская застава">Крестьянская застава</option>
<option value="Кропоткинская">Кропоткинская</option>
<option value="Крылатское">Крылатское</option>
<option value="Кузнецкий мост">Кузнецкий мост</option>
<option value="Кузьминки">Кузьминки</option>
<option value="Кунцевская">Кунцевская</option>
<option value="Курская">Курская</option>
<option value="Кутузовская">Кутузовская</option>
<option value="Ленинский проспект">Ленинский проспект</option>
<option value="Лермонтовский проспект">Лермонтовский проспект</option>
<option value="Лубянка">Лубянка</option>
<option value="Люблино">Люблино</option>
<option value="Марксистская">Марксистская</option>
<option value="Марьина Роща">Марьина Роща</option>
<option value="Марьино">Марьино</option>
<option value="Маяковская">Маяковская</option>
<option value="Медведково">Медведково</option>
<option value="Международная">Международная</option>
<option value="Менделеевская">Менделеевская</option>
<option value="Митино">Митино</option>
<option value="Молодежная">Молодежная</option>
<option value="Мякинино">Мякинино</option>
<option value="Нагатинская">Нагатинская</option>
<option value="Нагорная">Нагорная</option>
<option value="Нахимовский проспект">Нахимовский проспект</option>
<option value="Новогиреево">Новогиреево</option>
<option value="Новокосино">Новокосино</option>
<option value="Новокузнецкая">Новокузнецкая</option>
<option value="Новослободская">Новослободская</option>
<option value="Новоясеневская">Новоясеневская</option>
<option value="Новые Черемушки">Новые Черемушки</option>
<option value="Октябрьская">Октябрьская</option>
<option value="Октябрьское поле">Октябрьское поле</option>
<option value="Орехово">Орехово</option>
<option value="Отрадное">Отрадное</option>
<option value="Охотный ряд">Охотный ряд</option>
<option value="Павелецкая">Павелецкая</option>
<option value="Парк Культуры">Парк Культуры</option>
<option value="Парк Победы">Парк Победы</option>
<option value="Партизанская">Партизанская</option>
<option value="Первомайская">Первомайская</option>
<option value="Перово">Перово</option>
<option value="Петровско-Разумовская">Петровско-Разумовская</option>
<option value="Печатники">Печатники</option>
<option value="Пионерская">Пионерская</option>
<option value="Планерная">Планерная</option>
<option value="Площадь Ильича">Площадь Ильича</option>
<option value="Площадь революции">Площадь революции</option>
<option value="Полежаевская">Полежаевская</option>
<option value="Полянка">Полянка</option>
<option value="Пражская">Пражская</option>
<option value="Преображенская площадь">Преображенская площадь</option>
<option value="Пролетарская">Пролетарская</option>
<option value="Проспект Вернадского">Проспект Вернадского</option>
<option value="Проспект Мира">Проспект Мира</option>
<option value="Проспект Мира">Проспект Мира</option>
<option value="Профсоюзная">Профсоюзная</option>
<option value="Пушкинская">Пушкинская</option>
<option value="Речной вокзал">Речной вокзал</option>
<option value="Рижская">Рижская</option>
<option value="Римская">Римская</option>
<option value="Рязанский проспект">Рязанский проспект</option>
<option value="Савеловская">Савеловская</option>
<option value="Свиблово">Свиблово</option>
<option value="Севастопольская">Севастопольская</option>
<option value="Семеновская">Семеновская</option>
<option value="Серпуховская">Серпуховская</option>
<option value="Славянский бульвар">Славянский бульвар</option>
<option value="Смоленская">Смоленская</option>
<option value="Сокол">Сокол</option>
<option value="Сокольники">Сокольники</option>
<option value="Спортивная">Спортивная</option>
<option value="Сретенский бульвар">Сретенский бульвар</option>
<option value="Строгино">Строгино</option>
<option value="Студенческая">Студенческая</option>
<option value="Сухаревская">Сухаревская</option>
<option value="Сходненская">Сходненская</option>
<option value="Таганская">Таганская</option>
<option value="Тверская">Тверская</option>
<option value="Театральная">Театральная</option>
<option value="Текстильщики">Текстильщики</option>
<option value="Тёплый стан">Тёплый стан</option>
<option value="Тимирязевская">Тимирязевская</option>
<option value="Третьяковская">Третьяковская</option>
<option value="Трубная">Трубная</option>
<option value="Тульская">Тульская</option>
<option value="Тургеневская">Тургеневская</option>
<option value="Тушинская">Тушинская</option>
<option value="Ул. Академика Янгеля">Ул. Академика Янгеля</option>
<option value="Улица 1905 года">Улица 1905 года</option>
<option value="Улица Подбельского">Улица Подбельского</option>
<option value="Университет">Университет</option>
<option value="Филевский парк">Филевский парк</option>
<option value="Фили">Фили</option>
<option value="Фрунзенская">Фрунзенская</option>
<option value="Царицыно">Царицыно</option>
<option value="Цветной бульвар">Цветной бульвар</option>
<option value="Черкизовская">Черкизовская</option>
<option value="Чертановская">Чертановская</option>
<option value="Чеховская">Чеховская</option>
<option value="Чистые пруды">Чистые пруды</option>
<option value="Чкаловская">Чкаловская</option>
<option value="Шаболовская">Шаболовская</option>
<option value="Шипиловская">Шипиловская</option>
<option value="Шоссе Энтузиастов">Шоссе Энтузиастов</option>
<option value="Щелковская">Щелковская</option>
<option value="Щукинская">Щукинская</option>
<option value="Электрозаводская">Электрозаводская</option>
<option value="Юго-Западная">Юго-Западная</option>
<option value="Южная">Южная</option>
<option value="Ясенево">Ясенево</option>
</select>	
<br>		
Или другое (город, село, населенный пункт...)<br>
<input type="text" name="Region" size="43" maxlength="128" value="'.$val.'">
';

$form_select .= <<<EODJ_1
<script type="Text/Javascript" language="Javascript">

function metroselect(mval)
	{ addsitter.Region.value = 'м. ' + addsitter.RegionMetro.value;	}

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
	{ return '<input type="file" name="upimg" title="Обзор" style="background: #fff; padding: 1px;" donChange="picpreview(this);">'; }
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
	<tr><td><input type="file" name="upimg" value="Обзор" style="padding: 2px 10px;">
';
$retxt .= '<input type="hidden" name="imname" value="' . $pname . '">';
$retxt .= '<input name="Submit" type="submit" value="Загрузить фото" style="padding: 2px 10px;">
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
		{ return 'Фотография превышает размер ' . $max_size . 'кб.';	}
	if (!array_key_exists($imgtype, $file_types))
		{ return 'Несоответствующий тип графического файла - ' . $imgtype; }

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
