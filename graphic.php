<?php
require("common.php");
/*
     $xdata = array(
          0 => array(1,5),
          1 => array(2,7),
		  2 => array(3,0),
		  3 => array(4,7)
     );
	 */

$query = "SELECT * FROM price2013";
				
try{
	
	$data = array();
	
	
	$stmt = $db->prepare($query);
	$result = $stmt->execute();
	
	$rows = $stmt->fetchAll();
	
	if ($rows){
		
		$holdon = ""; // postpone the display of price
		
		foreach ($rows as $row){
			
			$id = $row["id"];
			$bag = $row['bag'];
			if ($bag == null){ // the field in database
				if ($holdon == null){
					$holdon = 1000;  // the first node in the graph
				}
				
				$bag = $holdon;
				
			}
			//echo $id . " " . $bag . "</br>";
			
			$holdon = $bag;
		
			$point = array();	
			$point[0] = $id;
			$point[1] = $bag;
		
			array_push($data, $point);	
			
		}
		
		// check
		//print_r($data);
		
		
	} // end if rows
}catch(Exception $ex){
	
}
?>

<html>
<head>
<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="js/flot/jquery.flot.js"></script>
<script type="text/javascript" src="/js/flot/jquery.flot.time.js"></script>    
<script type="text/javascript" src="/js/flot/jquery.flot.symbol.js"></script>
<script type="text/javascript" src="/js/flot/jquery.flot.axislabels.js"></script>
<script type="text/javascript">
		
		var data = <?php echo json_encode($data); ?>;
 
        var dataset = [{label: "line1",data: data}];
		

 
        var options = {
            series: {
                lines: { show: true },
                points: {
                    radius: 3,
                    show: true
                }
            },
			xaxis: {
				axisLabel: "2014",
				axisLabelUseCanvas: true,
				axisLabelFontSizePixels: 12,
				axisLabelPadding: 3
			},
			yaxis: {
		        axisLabel: "Price",
				axisLabelUseCanvas: true,
				axisLabelFontSizePixels: 12,
				axisLabelPadding: 3
			}    
			
        };
 
        $(document).ready(function () {
            $.plot($("#flot-placeholder"), dataset, options);
			 $("#flot-placeholder").UseTooltip();
        });
		

		
</script>
</head>
<body>
<div id="flot-placeholder" style="width:1200px;height:600px"></div>
</body>
</html>

