<?php

	include_once "rpl_common.php";
	
	//$r = getDeviceData();	
	//echo "<pre>";
	//print_r($r);
	//echo "</pre>";
	
	//echo "<br /><br />";
	
	//echo "<pre>";
	//print_r($settings);
	//echo "</pre>";
	
	//echo "<br /><br />";
	
	//echo "<pre>";
	//print_r($GLOBALS);
	//echo "</pre>";
	
	$url = $GLOBALS['pluginBaseUrl'] . "index.php";
	$data = getDeviceData();
	$options = array(
		'http' => array(
			'method' => 'POST',
			'content' => json_encode($data)
		)
	);	
	echo "<pre>";
	print_r($data);
	echo "</pre><br /><br />";
	
	echo "<pre>";
	print_r($options);
	echo "</pre><br /><br />";
	
	echo "<pre>";
	print_r($url);
	echo "</pre><br /><br />";
	
	$context = stream_context_create($options);
	var_dump($context);
	echo "<br /><br />";
	
	$result = file_get_contents($url, false, $context);
	
	echo "result -----<br />";
	echo $result . "<br /><br />";

?>