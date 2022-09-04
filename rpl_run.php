<?php

	include_once "/home/fpp/media/plugins/randolphParkLights/rpl_common.php";
	
	$doLoop = true;
	logEntry("Randolph Park Lights Initializing...");
	logEntry("Base Url: " . $url);
	
	while ($doLoop) {
		$pluginSettings = parse_ini_file($pluginConfigFile);
		$url = $GLOBALS['pluginBaseUrl'];
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
			if (strpos($result,'SUCCESS') === true) {
				// remote success
				WriteSettingToFile("reportedSequenceName",$playingData['sequenceName'],$pluginName);
				$sleepDuration = 5;
				logEntry("Remote Save Success");
			} else {
				// remote error
				$sleepDuration = 2;
				logEntry("Remote Save Error");
			}
		} else {
			// song has not changed
			$sleepDuration = 5;
		}	
		
		sleep($sleepDuration);

	}
	
?>