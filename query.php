<?php
if (!empty($_GET['y'])&&!empty($_GET['m'])&&!empty($_GET['d'])){

	require("db_config.php");
	ini_set('safe_mode', false);
	date_default_timezone_set('Asia/Taipei');

	scrap($db, $_GET['y'], $_GET['m'], $_GET['d'],  date($_GET['y']."-".$_GET['m']."-".$_GET['d']));

}else{

	echo 'please input query year, month and day';

}

function scrap($db, $y, $m, $d, $time){

	$postFields = array(
		'strYear' => $y,
		'strMonth' => $m,
		'strDay' => $d
	);

	try{
		$url = 'http://g1.taisugar.com.tw/Sugar/Sugar_show_His.asp';
		$sugar = curl($url, $postFields);

		$packtSugarXpath = returnXPathObject($sugar);

		$td = $packtSugarXpath->query('//td'); // return DOMNodeList
		$td_title = $td->length;

		$td_first =13;
		$td_second = 14;
		$td_third = 15;
		$td_diff = 7;

		if ($td_title>13){

			$pid = filter_var($td->item($td_first)->nodeValue, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
			$kg = filter_var($td->item($td_second)->nodeValue, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
			$bag = filter_var($td->item($td_third)->nodeValue, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

			$result['pid'] =  clean($pid);
			$result['pricePerKg'] =  $kg;
			$result['pricePerBag'] =  $bag;
			$result['time'] =  $time;


			while( $result['pid'] != '01021050'){

				$td_first = $td_first + $td_diff;
				$td_second = $td_second + $td_diff;
				$td_third = $td_third + $td_diff;

				$pid = filter_var($td->item($td_first)->nodeValue, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
				$kg = filter_var($td->item($td_second)->nodeValue, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
				$bag = filter_var($td->item($td_third)->nodeValue, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

				$result['pid'] =  clean($pid);
				$result['pricePerKg'] =  $kg;
				$result['pricePerBag'] =  $bag;
				$result['time'] =  $time;

			}
			insertDB($db,$result['pid'] ,$result['pricePerKg'], $result['pricePerBag'],  $result['time'] );
			print_r($result);

		}else{

			echo 'no data to be displayed today';
		}

	}catch(Exception $ex){

		echo "failed </br>";
	}
}

function insertDB($db, $pid, $kg, $bag, $time){

	$query = "INSERT INTO
	price(pid,kg,bag,`time`)
	SELECT
	:pid, :kg, :bag, :time
	FROM
	DUAL
	WHERE NOT EXIST
	(SELECT
		1
		FROM
		price
		WHERE
		`time` = :time
		LIMIT
		1)";

		try{

			$stmt = $db->prepare($query);
			$stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
			$stmt->bindParam(':kg', $kg, PDO::PARAM_STR);
			$stmt->bindParam(':bag', $bag, PDO::PARAM_STR);
			$stmt->bindParam(':time', $time, PDO::PARAM_STR);
			$result = $stmt->execute();

		}catch(PDOException $ex){

			die(json_encode($ex));
		}

	}

	function clean($string) {
		$string = str_replace('-', '', $string);

		return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
	}

	function curl($url, $postFields) {

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));

		$results = curl_exec($ch);
		curl_close($ch);
		return $results;

	}

	function returnXPathObject($item) {

		$xmlPageDom = new DomDocument();
		@$xmlPageDom->loadHTML($item);
		$xmlPageXPath = new DOMXPath($xmlPageDom);
		return $xmlPageXPath; // Returning XPath object

	}

?>
