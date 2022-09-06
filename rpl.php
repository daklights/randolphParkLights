<?php

	include_once "rpl_common.php";
	
	$pluginName = basename(dirname(__FILE__));
	$pluginConfigFile = $settings['configDirectory'] . "/plugin." .$pluginName;
	$pluginSettings = parse_ini_file($pluginConfigFile);
	
	$deviceData = getDeviceData();
	$playingData = getCurrentPlayingData();
	$combined = array_merge(json_decode($deviceData,true),json_decode($playingData,true));
	
	echo "Randolph Park Lights Data Sync<br /><br />";
	
	echo "Temp: " . round(((($combined['tempC']/1000)*(9/5))+32),2) . "<br />";
	echo "Serial: " . $combined['serial'] . "<br />";
	echo "Eth0 Address: " . $combined['eth0Addr'] . "<br />";
	echo "Wlan0 Address: " . $combined['wlan0Addr'] . "<br /><br />";
	echo "Playlist: " . $combined['playlistName'] . "<br />";
	echo "Sequence: " . $combined['sequenceName'] . "<br />";
	echo "Seconds Elapsed: " . $combined['secondsElapsed'] . "<br />";
	echo "Seconds Remaining: " . $combined['secondsRemaining'] . "<br />";
	echo "Sequence Started: " . date('Y-m-d h:i:sa',$combined['sequenceStarted']) . " (" . $combined['sequenceStarted'] . ")<br /><br />";
	echo "Device Time: " . date('Y-m-d h:i:sa',$combined['time']) . "<br />";
	echo "Device Time Epoch: " . $combined['time'] . "<br /><br />";
	echo "Latest Remote Sync Result: " . $pluginSettings['latestRemoteSyncResult'];

?>