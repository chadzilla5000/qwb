<?php

function ordawaitbrg($bid){ global $dbh;
$pt = NULL;	

$qy = "SELECT 
### Cols to display
t1.OID,
t1.ItemID,
t0.OrdNum,
t0.AmtOrdered,
### Cols for other purposes
t2a.StgNum AS stg1,
t2b.StgNum AS stg2,
t0.TmpBrgID,
# -----------------------------
t1.Id ### Dummy

FROM bcg AS t1
### Jointables
JOIN brigade AS tb ON 
	(tb.Id='$bid')
JOIN order2 AS t0 ON 
	(t0.OrderID=t1.OID)
JOIN stage AS t2a ON 
	(t2a.ItemID=t0.ItemID AND t2a.StgType=tb.StgType)
JOIN stage AS t2b ON 
	(t2b.ItemID=t0.ItemID AND t2b.StgType<>tb.StgType AND t2b.StgNum=t2a.StgNum-1)
	
WHERE ################################################
	t1.StarTime IS NOT NULL 
AND t1.StgNum = t2b.StgNum 
AND t1.StgNum <(SELECT MAX(StgNum) FROM stage WHERE ItemID=t0.ItemID AND StgType=tb.StgType)
AND(t1.EndTime  IS NULL OR (t0.TmpBrgID IS NULL OR t0.TmpBrgID <> '$bid'))

GROUP BY t1.OID
";

$rst = mysqli_query($dbh, $qy) or die (mysql_error());
while($row = mysqli_fetch_assoc($rst)) {

$lst = lastg($row['ItemID'], $bid);
$fst = firstg($row['ItemID'], $row['TmpBrgID']);

if($lst<$fst){continue;}

	$prg1 = endegree1($row['OID']);
	$prg2 = endegree2($row['OID'], $row['stg2']);
	$prcbar1 = prgbar($prg1);
	$prcbar2 = prgbar($prg2);
	$stgtl     = getrowhsh('stage', "ItemID='$row[ItemID]' AND StgNum='$row[stg1]'")['Title'];
	$ittle     = getrowhsh('item', "Id='$row[ItemID]'")['Title'];
	$pt .=  '
<div class="BrgOrd">
<div style="float: left; font-weight: bold;">'.$row['ClOrdcode'].'</div>
<div style="font-size: 11px;" title="'.$stgtl.'">'.$prcbar2.'</div>
&nbsp;- '.$ittle.' - '.$row['AmtOrdered'].' шт.
<div style="clear: both;"></div>
</div>';
	}
	
return $pt;	
}	

//////////////////////////////////////////////////////////////////
function ordawait($bid){ global $dbh;
$pt = NULL;	

$qy   = "SELECT 
### Cols to display
t0.OrderID,
t0.ItemID,
t0.OrdNum,
t0.AmtOrdered,
#------------------------

### Cols for informational purposes
t2a.StgNum AS stg1,
t2b.StgNum AS stg2,

t1.BrgID As b1,
t1.Id As b0,
#-----------------
t0.Id #Dummy

FROM order2 AS t0
### Jointables
JOIN brigade AS tb ON 
	(tb.Id='$bid')
JOIN stage AS t2a ON 
	(t2a.StgType=tb.StgType AND t2a.ItemID=t0.ItemID)
JOIN stage AS t2b ON 
	(t2b.StgNum=t2a.StgNum-1 AND t2b.ItemID=t0.ItemID AND t2b.StgType<>tb.StgType)
JOIN bcg   AS t1  ON 
	(t0.OrderID=t1.OID AND t0.ItemID=t1.ItemID AND t1.StgNum=t2b.StgNum AND t1.StarTime IS NOT NULL AND 
	(t1.EndTime IS NULL OR (t0.TmpBrgID IS NULL OR t0.TmpBrgID<>'$bid')))
#-----------------
GROUP BY t0.OrderID
";

$rst = mysqli_query($dbh, $qy) or die (mysql_error());
while($row = mysqli_fetch_assoc($rst)) {

//$lst = lastg($row['ItemID'], $bid);

	$prg1 = endegree1($row['OrderID']);
	$prg2 = endegree2($row['OrderID'], $row['stg2']);
	$prcbar1 = prgbar($prg1);
	$prcbar2 = prgbar($prg2);
	$stgtl     = getrowhsh('stage', "ItemID='$row[ItemID]' AND StgNum='$row[stg1]'")['Title'];
//	$ittle     = getrowhsh('item', "Id='$row[ItemID]'")['Title'];
	$pt .=  '
<div class="BrgOrd">
<div style="float: left; font-weight: bold;">'.$row['ClOrdcode'].'</div>
<div style="font-size: 11px;" title="'.$stgtl.'">'.$prcbar2.'</div>
&nbsp;- '.$row['ItemName'].' - '.$row['AmtOrdered'].' шт.
<div style="clear: both;"></div>
</div>';
	}
	
return $pt;	
}	


	
function formordiv($bid){ global $dbh;
$pt = NULL;	
$actcls = "StarTime IS NOT NULL AND EndTime IS NULL";
$qy  = "SELECT * FROM order2 WHERE Id IN(SELECT OID FROM bcg 
	WHERE $actcls AND BrgID>0 AND BrgID='$bid')"; 
$rst = mysqli_query($dbh, $qy) or die (mysql_error());
while($row = mysqli_fetch_assoc($rst)) {
	$prg1 = endegree1($row['OrderID']);
	$sh = getrowhsh('bcg',"OrderN='$row[OrderID]' AND BrgID='$bid' AND $actcls");
	$prg2 = endegree2($row['OrderID'], $sh['StgNum']);
	$prcbar1 = prgbar($prg1);
	$prcbar2 = prgbar($prg2);
	$stgtl     = getrowhsh('stage', "ItemID='$row[ItemID]' AND StgNum='$sh[StgNum]'")['Title'];
//	$ittle     = getrowhsh('item', "Id='$row[ItemID]'")['Title'];
	$pt .=  '
<div class="BrgOrd">
<div style="float: left; font-weight: bold;">'.$row['ClOrdcode'].'</div>
<div style="font-size: 11px;">'.$prcbar1.'</div>
&nbsp;- '.$row['ItemName'].' - '.$row['AmtOrdered'].' шт.
<div style="clear: both;"></div>
</div>';
	}
return $pt;	
}	

/////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
function firstg($itm, $bid){ global $dbh;
$qy  = "SELECT MIN(StgNum) AS firstg FROM stage WHERE (StgType = (SELECT StgType FROM brigade WHERE Id='$bid')) AND ItemID='$itm'"; 
$rst = mysqli_query($dbh, $qy) or die (mysql_error());
$r = mysqli_fetch_assoc($rst);
return $r['firstg'];
}
//////////////////////////////////////
function lastg($itm, $bid){ global $dbh;
$qy  = "SELECT MAX(StgNum) AS lastg FROM stage WHERE (StgType = (SELECT StgType FROM brigade WHERE Id='$bid')) AND ItemID='$itm'"; 
$rst = mysqli_query($dbh, $qy) or die (mysql_error());
$r = mysqli_fetch_assoc($rst);
return $r['lastg'];
}
///////////////////////////////////////////////////
//////////////////////////////////////////////////
/////////////////////////////////////////////////



?>