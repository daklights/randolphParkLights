<?php

	include_once('rpl_common.php');
	
	$r = getDeviceData();	
	echo "<pre>";
	print_r($r);
	echo "</pre>";
	
	echo "<br /><br />";
	
	echo "<pre>";
	print_r($settings);
	echo "</pre>";

?>