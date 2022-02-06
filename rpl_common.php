<?php

	include_once "/opt/fpp/www/common.php";
	$pluginBaseUrl = "https://daklights.com/api/";

	function getDeviceData() {
		global $settings;
		
		// temperature
		$f = fopen("/sys/class/thermal/thermal_zone0/temp","r");
		$temp = fgets($f);
		fclose($f);
		
		// serial number
		$output = shell_exec('cat /proc/cpuinfo');
		$serial = substr($output, (strpos($output, 'Serial'))+9, 17);
		
		$response = array(
			'tempC' => $temp,
			'serial' => $serial,
			'ipAddr' => $_SERVER['SERVER_ADDR'],
			'variant' => $settings['Variant'],
			'fppMode' => $settings['fppMode'],
			'time' => time()
		);
		
		return $response;
	}

?>