<?php

	include_once "/opt/fpp/www/common.php";
	$pluginBaseUrl = "https://daklights.com/api/";
	$pluginName = basename(dirname(__FILE__));
	$pluginPath = $settings['pluginDirectory']."/".$pluginName."/";
	$logFile = $settings['logDirectory']."/".$pluginName.".log";
	$pluginConfigFile = $settings['configDirectory'] . "/plugin." .$pluginName;
	$pluginSettings = parse_ini_file($pluginConfigFile);

	function getDeviceData() {
		
		// temperature
		$f = fopen("/sys/class/thermal/thermal_zone0/temp","r");
		$temp = fgets($f);
		fclose($f);
		
		// serial number
		$output = shell_exec('cat /proc/cpuinfo');
		$serial = substr($output, (strpos($output, 'Serial'))+9, 17);
		
		// eth0 ip address
		$eth0Addr = "0.0.0.0";
		exec("/sbin/ifconfig eth0 | grep 'inet '", $resultArrayE);
		$ipLineE = explode(' ',trim($resultArrayE[0]));
		$eth0Addr = $ipLineE[1];
		
		// wlan0 ip address
		$wlan0Addr = "0.0.0.0";
		exec("/sbin/ifconfig wlan0 | grep 'inet '", $resultArrayW);
		$ipLineW = explode(' ',trim($resultArrayW[0]));
		$wlan0Addr = $ipLineW[1];
		
		$response = array(
			'tempC' => trim($temp),
			'serial' => trim($serial),
			'eth0Addr' => trim($eth0Addr),
			'wlan0Addr' => trim($wlan0Addr),
			'time' => time()
		);
		
		return json_encode($response);
	}
	
	function getDeviceStatus() {
		$url = "http://127.0.0.1/api/fppd/status";
		$options = array(
			'http' => array(
				'method'  => 'GET'
			)
		);
		$context = stream_context_create( $options );
		$result = file_get_contents( $url, false, $context );		
		return $result;
	}
	
	function logEntry($data) {
		global $logFile;
		$data = $_SERVER['PHP_SELF']." : ".$data;		
		$logWrite= fopen($logFile, "a") or die("Unable to open file!");
		fwrite($logWrite, date('Y-m-d h:i:s A',time()).": ".$data."\n");
		fclose($logWrite);		
	}
	
	function getCurrentPlayingData() {
		$ds = getDeviceStatus();
		$j = json_decode($ds,true);
		$response = array(
			'mode' => trim($j['mode']),
			'playlistName' => trim($j['current_playlist']['playlist']),
			'sequenceName' => trim($j['current_sequence']),
			'secondsElapsed' => trim($j['seconds_elapsed']),
			'secondsRemaining' => trim($j['seconds_remaining']),
			'sequenceStarted' => trim($j['scheduler']['currentPlaylist']['currentTime'] - $j['seconds_elapsed']),
			'time' => time()
		);
		return json_encode($response);
	}

?>