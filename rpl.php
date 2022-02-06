<?php

	include_once "rpl_common.php";
	
	$r = getDeviceData();	
	echo "<pre>";
	print_r($r);
	echo "</pre>";
	
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
	echo $result;

?>