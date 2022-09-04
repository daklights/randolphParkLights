<?php

	include_once "/home/fpp/media/plugins/randolphParkLights/rpl_common.php";
	
	$doLoop = true;
	logEntry("Randolph Park Lights Initializing...");
	logEntry("Base Url: " . $url);
	
	while ($doLoop) {
		$url = $GLOBALS['pluginBaseUrl'];
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
		
		if (strpos($result,'ABORT') === false) {
			sleep(60);
		} else {
			logEntry("Randolph Park Lights Aborting...");
			$doLoop = false;
		}
	}
	
?>