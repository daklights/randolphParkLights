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
		
		exec('/sbin/ifconfig', $resultArray);
		$result = implode(",", $resultArray);
		$ip = preg_match('/eth0.*?inet\saddr:([\d]{1,3}\.[\d]{1,3}\.[\d]{1,3}\.[\d]{1,3})\s/', $result, $matches);
		$ipAddr = isset($matches[1]) ? $matches[1] : '0.0.0.0';
		
		$response = array(
			'tempC' => trim($temp),
			'serial' => trim($serial),
			'ipAddr' => trim($ipAddr),
			'variant' => $settings['Variant'],
			'fppMode' => $settings['fppMode'],
			'time' => time()
		);
		
		return $response;
	}

?>