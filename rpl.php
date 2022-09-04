<?php

	include_once "rpl_common.php";
	
	$deviceData = getDeviceData();
	$playingData = getCurrentPlayingData();
	$combined = json_encode(array_merge(json_decode($deviceData,true),json_decode($playingData,true)));
	
	echo "<pre>";
	print_r($combined);
	echo "</pre><br /><hr /><br />";

?>