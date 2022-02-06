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
	
	//echo "<br /><br />";
	
	//echo "<pre>";
	//print_r($GLOBALS);
	//echo "</pre>";
	
	$url = "http://127.0.0.1/api/fppd/status";
	$options = array(
		'http' => array(
			'method' => 'GET'
		)
	);	
	$context = stream_context_create($options);	
	$result = file_get_contents($url, false, $context);
	
	echo "local result -----<br />";
	echo $result . "<br /><br />";
	
	$url = $GLOBALS['pluginBaseUrl'] . "index.php";
	$data = getDeviceData();
	$options = array(
		'http' => array(
			'method' => 'POST',
			'content' => json_encode($data)
		)
	);	
	$context = stream_context_create($options);	
	$result = file_get_contents($url, false, $context);
	
	echo "remote result -----<br />";
	echo $result . "<br /><br />";

?>