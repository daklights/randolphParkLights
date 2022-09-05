<?php

	include_once "rpl_common.php";
	
	$deviceData = getDeviceData();
	$playingData = getCurrentPlayingData();
	$combined = array_merge(json_decode($deviceData,true),json_decode($playingData,true));
	
	echo "Randolph Park Lights Data Sync<br /><br />";
	
	echo "Temp: " . round(((($combined['tempC']/1000)*(9/5))+32),2) . "<br />";
	echo "Serial: " . $combined['serial'] . "<br />";
	echo "Eth0 Address: " . $combined['eth0Addr'] . "<br />";
	echo "Wlan0 Address: " . $combined['wlan0Addr'] . "<br /><br />";
	echo "Playlist: " . $combined['playlistName'] . "<br />";
	echo "Sequence: " . $combined['sequence'] . "<br />";
	echo "Seconds Elapsed: " . $combined['secondsElapsed'] . "<br />";
	echo "Seconds Remaining: " . $combined['secondsRemaining'] . "<br /><br />";
	echo "Device Time: " . date('Y-m-d h:i:sa',$combined['time']) . " (" . $combined['time'] . ")";	

?>