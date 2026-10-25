<?php

	include_once "/home/fpp/media/plugins/randolphParkLights/rpl_common.php";
	
	$doLoop = true;
	logEntry("Randolph Park Lights Initializing...");
	
	$url = $GLOBALS['pluginBaseUrl'];
	logEntry("Base Url: " . $url);
	
	while ($doLoop) {
		// Reload plugin settings on every iteration
		clearstatcache(true, $pluginConfigFile);
		$pluginSettings = parse_ini_file($pluginConfigFile);

		// Sync is enabled by default if setting doesn't exist
		$syncEnabled = true;
		if (isset($pluginSettings['syncEnabled'])) {
			$syncValue = trim((string)$pluginSettings['syncEnabled'], " \t\n\r\0\x0B\"'");
			$syncEnabled = ($syncValue === '1');
		}

		if (!$syncEnabled) {
			WriteSettingToFile("skippingThisRun",time() . " > true",$pluginName);
			sleep(60);
			continue;
		} else {
			WriteSettingToFile("skippingThisRun",time() . " > false",$pluginName);
		}

		$deviceData = getDeviceData();
		$playingData = json_decode(getCurrentPlayingData(),true);
		$combined = json_encode(array_merge(json_decode($deviceData,true),$playingData));
		
		if ($playingData['mode'] == "player") {
			// master/player mode
			$reportedSequenceName = $pluginSettings['reportedSequenceName'];
			$reportedPlaylistName = $pluginSettings['reportedPlaylistName'];
			$reportedSequenceStarted = $pluginSettings['reportedSequenceStarted'];
			
			$loopTrigger = false;
			if (trim($playingData['sequenceName']) != "") {
				if (abs($reportedSequenceStarted - $playingData['sequenceStarted']) > 2) {
					$loopTrigger = true;
				}
			}
			
			if ($reportedSequenceName != $playingData['sequenceName'] || $reportedPlaylistName != $playingData['playlistName'] || $loopTrigger) {
				// song or playlist has changed (or looped if non-standard show)
				logEntry("Change Detected: " . $playingData['sequenceName'] . " | " . $playingData['playlistName']);
				$options = array (
					'http' => array (
						'method' => 'POST',
						'content' => $combined,
						'header' => "Content-Type: application/json; charset=UTF-8\r\n" .
									"Accept: application/json\r\n",
						'timeout' => 5,
						'ignore_errors' => true
					)
				);
				$context = stream_context_create($options);
				
				// Suppress PHP warnings and capture the error ourselves
				$errorBefore = error_get_last();
				$startTime = microtime(true);

				$result = @file_get_contents($url, false, $context);
				$elapsed = round(microtime(true) - $startTime, 2);
				if ($result === false) {
					$error = error_get_last();
					$errorMessage = ($error !== null && $error !== $errorBefore) ? $error['message'] : 'Unknown connection error';
					if (stripos($errorMessage, 'timed out') !== false) {
						logEntry("Remote Sync TIMEOUT after {$elapsed} seconds: {$url}");
					} else {
						logEntry("Remote Sync FAILED after {$elapsed} seconds: {$errorMessage}");
					}
					$result = "ERROR " . time();
				}

				if (stripos($result,'SUCCESS') !== false) {
					// remote success
					WriteSettingToFile("latestRemoteSyncResult", $result, $pluginName);
					WriteSettingToFile("reportedSequenceName", $playingData['sequenceName'], $pluginName);
					WriteSettingToFile("reportedPlaylistName", $playingData['playlistName'], $pluginName);
					WriteSettingToFile("reportedSequenceStarted", $playingData['sequenceStarted'], $pluginName);
					$sleepDuration = 2;
				} else {
					// remote error
					logEntry("Remote Sync FAILED: " . $result);
					WriteSettingToFile("latestRemoteSyncResult", "ERROR " . time(), $pluginName);
					$sleepDuration = 60;
				}
				logEntry("Remote Sync: " . $result);
			} else {
				// song has not changed
				$sleepDuration = 2;
			}
		} else {
			// remote mode
			$options = array (
				'http' => array (
					'method' => 'POST',
					'content' => $combined,
					'header' => "Content-Type: application/json; charset=UTF-8\r\n" .
								"Accept: application/json\r\n"
				)
			);
			$context = stream_context_create($options);
			$result = file_get_contents($url, false, $context);
			WriteSettingToFile("latestRemoteSyncResult",$result,$pluginName);
			$sleepDuration = 60;
		}
		
		sleep($sleepDuration);

	}
	
?>