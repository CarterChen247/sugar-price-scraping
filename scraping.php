<?php

require("common.php"); // import DB settings
$retrieveYear = 2013;


$time = "";

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
				insertDB($db, $y, $m, $d, $time);
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
				insertDB($db, $y, $m, $d, $time);
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
				insertDB($db, $y, $m, $d, $time);
			}
		}
	}
}

function insertDB($db, $y, $m, $d, $time){
	
	$postFields = array(
	'strYear' => $y,
	'strMonth' => $m,
	'strDay' => $d
	);
	
	try{
		$url = ''; // the web page you want to scrap
		$sugar = curl($url, $postFields); 
	
		$packtSugarXpath = returnXPathObject($sugar); // 

		$td = $packtSugarXpath->query('//td'); // Querying for <tr> (title of book)

		$pid = filter_var($td->item(27)->nodeValue, FILTER_SANITIZE_NUMBER_FLOAT);
		$kg = filter_var($td->item(28)->nodeValue, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
		$bag = filter_var($td->item(29)->nodeValue, FILTER_SANITIZE_NUMBER_FLOAT);

		$spider['pid'] =  $pid;
		$spider['kg'] =  $kg;
		$spider['bag'] =  $bag;

		print_r($spider);
		
	}catch(Exception $ex){
		
		echo "failed </br>";
	}
		
	$query = "INSERT  INTO test(pid,kg,bag,`time`) VALUES (:pid, :kg, :bag, :time)";
				
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
<!DOCTYPE html>
<html>
<head>
<meta name="generator" content=
"HTML Tidy for HTML5 (experimental) for Windows https://github.com/w3c/tidy-html5/tree/c63cc39">
<title></title>
</head>
<body>
</body>
</html>
