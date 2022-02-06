<?php

	include_once "/home/fpp/media/plugins/randolphParkLights/rpl_common.php";
	
	/* while(true) {
		$url = $GLOBALS['pluginBaseUrl'];
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
		sleep(60);
	} */
	
?>