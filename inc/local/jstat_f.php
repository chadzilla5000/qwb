<?php
function chckdb_jstt($oid){ global $dbh;
		$q = "SELECT id FROM st_jobstat WHERE OID='$oid'";
		$result = mysqli_query($dbh, $q);
		if($result->num_rows){ return 1; }
	return NULL;
}


function fup_t($r, &$i){
	if($r['qtty'] AND $r['sku']!='Freight'){++$i;
		return '
<input type="hidden" id="id_'.$i.'" name="id_'.$i.'" value="'.$r['id'].'" />
<input type="hidden" id="isku_'.$i.'" name="isku_'.$i.'" value="'.$r['sku'].'" />
<input type="hidden" id="qtty_'.$i.'" name="qtty_'.$i.'" value="'.$r['qtty'].'" />
<input type="hidden" id="hstt_'.$i.'" name="hstt_'.$i.'" value="'.$r['hstt'].'" />
<input type="hidden" id="hend_'.$i.'" name="hend_'.$i.'" value="'.$r['hend'].'" />
<tr><td style="height: 50px;">'.$i.'</td>
	<td style="width: 130px; color: #999; cursor: pointer;" id="stt_'.$i.'" title="Click to put start time" OnClick="puTS(this);">'.$r['tstt'].'</td>
	<td style="text-align: center;">'.$r['qtty'].'</td>
	<td style="cursor: pointer;" id="sku_'.$i.'" title="Add Note" OnClick="putnote(\''.$i.'\');">'.$r['sku'].'</td>
	<td style="width: 130px; color: #999; cursor: pointer;" id="end_'.$i.'" title="Click to put end time" OnClick="puTS(this);">'.$r['tend'].'</td>
</tr>

<div id="notediv_'.$i.'" style="position: absolute; right: 5px; padding: 5px; display: none; z-index: 400'.$i.';">
<div id="txacl_'.$i.'" style="position: absolute; right: 5px; width: 50px; height: 20px; cursor: pointer;" OnClick="txtareacloseall();">Close '.$i.'</div>
<textarea id="txtr_'.$i.'" name="txtr_'.$i.'" style="width: 200px; height: 140px; padding: 5px;">'.$r['note'].'</textarea>
</div>
';
		}
	return NULL;
}

?>