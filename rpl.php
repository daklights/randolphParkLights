<?php

	include_once "rpl_common.php";
	
	$r = getDeviceData();
	echo "<pre>";
	print_r($r);
	echo "</pre><br /><hr /><br />";
	
	$r = getDeviceStatus();
	echo "<pre>";
	print_r($r);
	echo "</pre><br /><hr /><br />";
	
	//echo "<pre>";
	//print_r($settings);
	//echo "</pre><br /><hr /><br />";
	
	$d = getCurrentPlayingData();
	echo "<pre>";
	print_r($d);
	echo "</pre><br /><hr /><br />";

?>