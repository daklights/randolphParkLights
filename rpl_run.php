<?php

	include_once "/home/fpp/media/plugins/randolphParkLights/rpl_common.php";
	
	$doLoop = true;
	logEntry("Randolph Park Lights Initializing...");
	
	while ($doLoop) {
		$pluginSettings = parse_ini_file($pluginConfigFile);
		$url = $GLOBALS['pluginBaseUrl'];
		logEntry("Base Url: " . $url);
		$deviceData = getDeviceData();
		$playingData = json_decode(getCurrentPlayingData(),true);
		$combined = json_encode(array_merge(json_decode($deviceData,true),$playingData));
		$reportedSequenceName = $pluginSettings['reportedSequenceName'];
		
		if ($reportedSequenceName != $playingData['sequenceName']) {
			// song has changed
			logEntry("New Song Detected: " . $playingData['sequenceName']);
			$options = array(
				'http' => array(
					'method'  => 'POST',
					'content' => $combined,
					'header'=>  "Content-Type: application/json; charset=UTF-8\r\n" .
								"Accept: application/json\r\n"
					)
			);
			$context = stream_context_create($options);
			$result = file_get_contents($url, false, $context);
			if (stripos($result,'SUCCESS') !== false) {
				// remote success
				WriteSettingToFile("reportedSequenceName",$playingData['sequenceName'],$pluginName);
				$sleepDuration = 2;
			} else {
				// remote error
				$sleepDuration = 1;
			}
			logEntry("Remote Save: " . $result);
		} else {
			// song has not changed
			$sleepDuration = 2;
		}	
		
		sleep($sleepDuration);

	}
	
?>