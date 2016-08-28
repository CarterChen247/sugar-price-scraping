<?php
require("db_config.php");

$query = "SELECT * FROM price";

try{

	$data = array();

	$stmt = $db->prepare($query);
	$result = $stmt->execute();
	$rows = $stmt->fetchAll();

	if ($rows){

		foreach ($rows as $row){
			$price[0] = strtotime($row["time"])*1000;
			$price[1] = intval($row['bag']);
			array_push($data, $price);
		}

	}

}catch(Exception $ex){

}
?>

<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/foundation.css">
	<link rel="stylesheet" href="css/app.css">
	<script language="javascript" type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
	<script language="javascript" type="text/javascript" src="js/flot/jquery.flot.js"></script>
	<script language="javascript" type="text/javascript" src="js/flot/jquery.flot.time.js"></script>
	<script language="javascript" type="text/javascript" src="js/flot/jquery.flot.symbol.js"></script>
	<script language="javascript" type="text/javascript" src="js/flot/jquery.flot.axislabels.js"></script>
	<script src="js/vendor/foundation.js"></script>
	<script src="js/app.js"></script>
	<script type="text/javascript">

	$(function() {

		var data = <?php echo json_encode($data); ?>;

		var options = {
			lines: {
				show: true
			},
			points: {
				show: true
			},
			xaxis: {
				mode: "time",
				tickSize: [6, "month"]
			},
			grid: { hoverable: true}
		};


		$.plot("#placeholder", [data], options);

		// 节点提示
		function showTooltip(x, y, contents) {
			$('<div id="tooltip">' + contents + '</div>').css( {
				position: 'absolute',
				display: 'none',
				top: y + 10,
				left: x + 10,
				border: '1px solid #fdd',
				padding: '2px',
				'background-color': '#dfeffc',
				opacity: 0.80
			}).appendTo("body").fadeIn(200);
		}

		var previousPoint = null;

		$("#placeholder").bind("plothover", function (event, pos, item) {
			if (item) {
				if (previousPoint != item.dataIndex) {
					previousPoint = item.dataIndex;
					$("#tooltip").remove();
					var price = item.datapoint[1].toFixed(0);

					showTooltip(item.pageX, item.pageY,price+" per bag");
				}
			}
			else {
				$("#tooltip").remove();
				previousPoint = null;
			}
		});

	});
	</script>
</head>
<body>
	<div class="row">
		<h2>Sugar Price(50kg)</h2>
		<p>Reference: <a href="http://g1.taisugar.com.tw/Sugar/Sugar_show_His.asp">History of Sugar Price, Taiwan Sugar Corporation.</a></p>
	</div>
	<div class="row">
		<div class="large-16 columns">
			<div id="placeholder" style="width:100%;height:60%"></div>
		</div>
	</div>

	<div class="row">

		</body>
		</html>
