<?php
//header("Content-Type: text/html;charset=windows-1251");
$dbh = connect_DB();

//mysql_query("INSERT INTO sitter (FName, LName) VALUES ('JJ','Now')") or die (mysql_error());
//mysql_query("set character_set_results='cp1251'");
//mysql_query("SET NAMES cp1251");
//mysql_set_charset( 'cp1251' );


$clause = true;
	//declare the SQL statement that will query the database
	$query = "SELECT * FROM sitter WHERE '$clause'"; 

	//execute the SQL query and return records
	$result = mysql_query($query);

	$numRows = mysql_num_rows($result); 

$mtxt = $numRows . " Row" . ($numRows == 1 ? "" : "s") . " Returned<br><br>";
$mtxt .= '<table class="tblsitter" cellpadding="0" cellspacing="0">
	<tr><th></th>
	
	</tr>';

	//display the results 
	while($row = mysql_fetch_array($result))
	{
	$age = '';
	$picpath = '';
	if($row["DoB"] != 0){
		$diff = abs(time(now) - strtotime($row["DoB"]));
		$age = floor($diff / (365*60*60*24));
		}
		$picpath = 'stpics/m' . $row["Id"] . '.jpg';
	if(!file_exists($picpath))	{ $picpath = 'stpics/m' . $row["Id"] . '.png'; }	
	if(!file_exists($picpath))	{ $picpath = 'stpics/m' . $row["Id"] . '.gif'; }	
	if(!file_exists($picpath))	{ $picpath = 'stpics/m' . $row["Id"] . '.bmp'; }	
	if(!file_exists($picpath))	{ $picpath = 'files/img/nopic.jpg'; }	
	
//	if(!(isset($picpath)){
//	$picpath = 'files/img/nopic.jpg';
//	}



		$mtxt .= '
<tr><td rowspan="4" style="width: 100px; text-align: center; color: #ccc; font-size: 11px;">ID: ' . $row["Id"] . '<br><img src="'.$picpath.'" style="width: 75px; max-height: 120px;"></td>
	<td colspan="2">'.$row["FName"]. ' ' .$row["MName"].' ('.$age.')</td>
</tr>	
<tr>
	<td> Гражданство </td>
	<td>'.$row["Citizenship"].'</td>
</tr>	
<tr>
	<td>Образование</td>
	<td>'.$row["Education"].' '.($row["MEducation"] ? "Мед." : "").'</td>
</tr>	
<tr>
	<td></td>
	<td></td>
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
	}

	
$mtxt .= '</table>';

//close the connection
mysql_close($dbh);

/*
$pgcontent        = <<<EOD__
<!-- Page content start -->


connect_DB();

<!-- Page content end -->
EOD__;
*/

$pgcontent        = $mtxt;



?>