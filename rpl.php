<?php

	include_once "rpl_common.php";
	
	$r = getDeviceData();	
	echo "<pre>";
	print_r($r);
	echo "</pre>";
	
	//echo "<br /><br />";
	
	//echo "<pre>";
	//print_r($settings);
	//echo "</pre>";
	
	echo "<br /><br />";
	
	echo "<pre>";
	print_r($GLOBALS);
	echo "</pre>";
	
	$url = $GLOBALS['pluginBaseUrl'] . "index.php";
	$data = getDeviceData();
	$options = array(
		'http' => array(
			'method'  => 'POST',
			'content' => json_encode($data),
			'header'=>  "Content-Type: application/json; charset=UTF-8\r\n" .
						"Accept: application/json\r\n"
			)
	);
	$context = stream_context_create($options);
	$result = file_get_contents($url, false, $context);
	
	echo "result -----<br />";
	echo $result . "<br /><br />";

?>