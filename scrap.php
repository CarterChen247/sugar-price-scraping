<?php

require("db_config.php"); // import DB settings
ini_set('max_execution_time', 0);

$retrieveYear = 2016;
// run each day in a year
for ($y = $retrieveYear; $y < ($retrieveYear + 1); $y++){

	// run each day in each month
	for ($m = 1; $m <= 12; $m++){

		//
		$mappend = "";

		if ($m < 10){

			$mappend = ",".$m;

		}else{

			$mappend = ",".$m;

		}

		if ($m ==1 || $m ==3 || $m ==5 || $m ==7 || $m ==8 || $m ==10 || $m ==12){

			for ($d = 1; $d <= 31; $d++){

				$dappend="";

				if ($d < 10){

					$dappend = ",".$d;
				}else{
					$dappend = ",".$d;
				}

				$time = $y.$mappend.$dappend;
				scrap($db, $y, $m, $d, $time);
			}

		}else if ( $m ==4 || $m ==6 || $m ==9 || $m ==11){

			for ($d = 1; $d <= 30; $d++){

				$dappend="";

				if ($d < 10){

					$dappend = ",".$d;
				}else{
					$dappend = ",".$d;
				}

				$time= $y.$mappend.$dappend;
				scrap($db, $y, $m, $d, $time);
			}
		}else if ( $m ==2){

			for ($d = 1; $d <= 28; $d++){

				$dappend="";

				if ($d < 10){

					$dappend = ",".$d;
				}else{
					$dappend = ",".$d;
				}

				$time= $y.$mappend.$dappend;
				scrap($db, $y, $m, $d, $time);
			}
		}
	}
}

function scrap($db, $y, $m, $d, $time){
	echo $time;

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

		}

	}catch(Exception $ex){

		echo "failed </br>";
	}
}

function insertDB($db, $pid, $kg, $bag, $time){


	$query = "INSERT  INTO price(pid,kg,bag,`time`) VALUES (:pid, :kg, :bag, :time)";

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
