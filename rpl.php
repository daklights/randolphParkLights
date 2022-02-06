<?php

	include_once "rpl_common.php";
	
	$r = getDeviceData();	
	echo "<pre>";
	print_r($r);
	echo "</pre>";

	$ipAddr = "0.0.0.0";
	exec("/sbin/ifconfig eth0 | grep 'inet '", $resultArray);
	$ipLine = explode(' ',$resultArray[1]);
	$ipAddr = $ipLine[1];
	echo "IP Address: " . $ipAddr . "<br /><br /><pre>";
	print_r($resultArray);
	echo " ----- ";
	print_r($ipLine);
	echo "</pre><br /><br />";
	
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