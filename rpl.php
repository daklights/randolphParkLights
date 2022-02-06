<?php

	include_once "rpl_common.php";
	
	$r = getDeviceData();	
	echo "<pre>";
	print_r($r);
	echo "</pre>";
	
	exec('/sbin/ifconfig', $resultArray);
	echo "ifconfig result=====<br /><pre>";
	print_r($resultArray);
	echo "</pre><br /><br />";
	$result = implode(",", $resultArray);
	echo $result . "<br /><br />";
	$ip = preg_match('/eth0.*?inet\saddr:([\d]{1,3}\.[\d]{1,3}\.[\d]{1,3}\.[\d]{1,3})\s/', $result, $matches);
	echo "matches result=====<br /><pre>";
	print_r($matches);
	echo "</pre><br /><br />";
	$ipAddr = isset($matches[1]) ? $matches[1] : '0.0.0.0';
	
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