<?php

	include_once "/home/fpp/media/plugins/randolphParkLights/rpl_common.php";
	
	$doLoop = true;
	logEntry("Starting Plugin");
	
	while ($doLoop) {
		$url = $GLOBALS['pluginBaseUrl'];
		logEntry("Base Url: " . $url);
		$data = getDeviceData();
		$options = array(
			'http' => array(
				'method'  => 'POST',
				'content' => $data,
				'header'=>  "Content-Type: application/json; charset=UTF-8\r\n" .
							"Accept: application/json\r\n"
				)
		);
		$context = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		logEntry("RPL Response Result: " . $result);
		
		if (strpos($result,'ABORT') === false) {
			logEntry("Sleep 60");
			sleep(60);
		} else {
			logEntry("Abort");
			$doLoop = false;
		}
	}
	
?>