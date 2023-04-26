<html>
    <head>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
<script>
    window.onload = function () {
    
    var chart = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,
        theme: "light2",
        title:{
            text: "Simple Line Chart"
        },
        axisY:{
            includeZero: false
        },
        data: [{        
            type: "line",       
            dataPoints: [
                { y: 450 },
                { y: 414},
                { y: 520, indexLabel: "highest",markerColor: "red", markerType: "triangle" },
                { y: 460 },
                { y: 450 },
                { y: 500 },
                { y: 480 },
                { y: 480 },
                { y: 410 , indexLabel: "lowest",markerColor: "DarkSlateGrey", markerType: "cross" },
                { y: 500 },
                { y: 480 },
                { y: 510 }

            ]
        }]
    });
    chart.render();
    
    }
</script>
</head>

<body bgcolor="black">
<div id="wholebody">  
<a href="javascript:genScreenshotgraph()"><button style="background:aqua; cursor:pointer">Get Screenshot of Cars onl </button> </a>

	<div id="car" style="float: right; width:53%; min-height: 95%; padding: 7px;">
		<table class="TList" cellspacing="1">
		
			<tr><th colspan="2" style="height: 30px; vertical-align: middle; font-size: 17px;">'.$vendor.'</th><th colspan="2" style="height: 30px; vertical-align: middle; font-size: 17px;">Confirmation # '.$r1[0]['ocn'].'</th></tr>

			<tr><td colspan="6" style="padding: 0px;">
				<table style="width: 100%;">
				<tr><th style="width: 20%; vertical-align: top;">Bill to address</th>
					<td style="width: 30%; height: 200px; vertical-align: top; '.$addrstyle.'"><input type="checkbox" name="" value="1" style="float: right;" />
<div style="float: left; width: 90px; height: 5px; '.$shphonestyle.'"></div></td>
					<th style="width: 20%; vertical-align: top;">Ship to address</th>
					<td style="width: 30%; height: 200px; vertical-align: top; '.$addrstyle.'"><input type="checkbox" name="" value="1" style="float: right;" />
<div style="float: left; width: 90px; height: 5px; '.$shphonestyle.'"></div></td>
				</tr>
				</table>
			</td></tr>

			<tr><th style="width: 5%; height: 30px;">Qty</th>
				<th>Item Code</th>
				<th>Remark</th>
				<th>Description</th>
				<th>Cost</th>
				<th>Total</th>
			</tr>

			<tr><td colspan="4" style="height: 50px; vertical-align: middle;"><span style="border-radius: 50%; padding: 9px; '.$lrowstyle.'">Rows - '.$lrows1.'</span>&nbsp;&nbsp;&nbsp; <span style="border-radius: 50%; padding: 9px; '.$qtitstyle.'">Items - '.$qtit1.'</span></td>
				<td colspan="2" style="vertical-align: middle; text-align: right; font-weight: bold;">'.$ttbl.'</td>
			</tr>
		</table>
	</div>
<br>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

<div id="box1">
</div>
</div>>
</body>

<script>

function genScreenshotgraph() 
{
    html2canvas($('#car'), {
        
      onrendered: function(canvas) {

        var imgData = canvas.toDataURL("image/jpeg");
        var pdf = new jsPDF();
        pdf.addImage(imgData, 'JPEG', 0, 0, -180, -180);
        pdf.save("download.pdf");
        
      
      
      }
     });

}

</script>

</html>