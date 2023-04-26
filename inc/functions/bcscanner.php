<?php

$tl   = (isset($_POST['bc'])) ? $_POST['bc'] : NULL;

if($tl){
	if( (strlen($tl)==10)or(strlen($tl)==11)){
		$td=hexdec($tl);
		
		$svd17 = intval(date('Y').$td);
		$actinfo = 'Сканирование штрих-кода процесса';
		$h = geths($svd17);
		$ended = ($h[0]['EndTime'])?'<span style="font-size:13px;color:#c60;">(Завершен - '.$h[0]['EndTime'].')</span>':NULL;
		if($h[0]['MasterID']){
			$mN = masterfn($h[0]['MasterID']);
			$txtclr =($mN)?'363':'f00';
			$mfn = ($mN)?$mN:'Нет в базе данных';
			}
		else{
			$txtclr = 'f00';
			$mfn = 'Не авторизован';
			}
		}	
	elseif( strlen($tl)==12){
		
		$svd17=(isset($_POST['svd']))?$_POST['svd']:NULL;

		$actinfo = 'Сканирование персонального штрих-кода';
		if($svd17){	
			$h = geths($svd17);
			$ended = ($h[0]['EndTime'])?'<span style="font-size:13px;color:#c60;">(Завершен - '.$h[0]['EndTime'].')</span>':NULL;

			$mstID = substr($tl, -7);
			$mN = masterfn($mstID);
			if($mN){
				if($h[0]['MasterID']){
					if($h[0]['MasterID']!=$mstID){
						$txtclr = 'f00';
						$mORN = masterfn($h[0]['MasterID']);
						$mfn = '<span style="color: #c60;">'.$mORN.'</span> - изменение невозможно';
						}
					else{ $mfn = $mN;	}
					}
				else{
					updbcg($svd17,$mstID);
//					if(1){
//						updbcgP($svd17P);
//						$svd17P = $svd17;
//						}
					$mfn = $mN;
					}
				$h = geths($svd17); 
				}
			else{
				$txtclr = 'f00';
				$mfn = 'Нет в базе данных';
				}
			}
		else{ $errtxt = 'Не определен процесс. Отсканируйте сначала штрих-код процесса.'; }
		}
	else{ $actinfo = 'Неверный формат ШК'; }
	}
else{$tl='Not defined';}


//////////////////////////////////////////////////////////////////////////////////////////

function geths($scid){
	$h=array();
	$hsh = NULL;
	$h[0] = $hsh = getrowhsh('bcg',"Id='$scid'"); 
	if($hsh['Id']){
		$h[1] = getrowhsh('item',"Id='$hsh[ItemID]'");
		$h[2] = getrowhsh('stage',"StgNum='$hsh[StgNum]' AND ItemID='$hsh[ItemID]'");
		$h[3] = getrowhsh('steproc',"Id='$hsh[SteprocID]'");
		}
	return $h;
	}

function masterfn($id){
	$h = getrowhsh('master',"Id='$id'");
	$nmd = ($h['NameF'] OR $h['NameM'] OR $h['NameL'])?1:0;
	$fn = ($nmd)?$h['NameL'].' '.$h['NameF'].' '.$h['NameM']:NULL;
	return $fn;
}


?>