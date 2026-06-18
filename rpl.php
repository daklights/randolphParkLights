<?php

	include_once "rpl_common.php";
	
	$pluginName = basename(dirname(__FILE__));
	$pluginConfigFile = "/home/fpp/media/config/plugin." .$pluginName;
	$pluginSettings = parse_ini_file($pluginConfigFile);
	
	$deviceData = getDeviceData();
	$playingData = getCurrentPlayingData();
	$combined = array_merge(json_decode($deviceData,true),json_decode($playingData,true));
	
	echo "Randolph Park Lights Data Sync<br /><br />";
	
	echo "Temp: " . round(((($combined['tempC']/1000)*(9/5))+32),2) . "<br />";
	echo "Serial: " . $combined['serial'] . "<br />";
	echo "Eth0 Address: " . $combined['eth0Addr'] . "<br />";
	echo "Wlan0 Address: " . $combined['wlan0Addr'] . "<br /><br />";
	
	if ($combined['mode'] == "player") {
		echo "Playlist: " . $combined['playlistName'] . "<br />";
		echo "Sequence: " . $combined['sequenceName'] . "<br />";
		echo "Seconds Elapsed: " . $combined['secondsElapsed'] . "<br />";
		echo "Seconds Remaining: " . $combined['secondsRemaining'] . "<br />";
		echo "Sequence Started: " . date('Y-m-d h:i:sa',$combined['sequenceStarted']) . " (" . $combined['sequenceStarted'] . ")<br /><br />";
	}
	
	echo "Device Time: " . date('Y-m-d h:i:sa',$combined['time']) . "<br />";
	echo "Device Time Epoch: " . $combined['time'] . "<br /><br />";
	echo "Latest Remote Sync Result: " . $pluginSettings['latestRemoteSyncResult'] . "<br /><br />";
	
?>	
<label>
    <input type="checkbox" id="autoRefresh" checked> Automatically Refresh
</label>

<script>
    let refreshTimer = null;

    function startRefresh() {
        stopRefresh(); // Prevent multiple timers
        refreshTimer = setInterval(function () {
            window.location.reload();
        }, 10000); // 10 seconds
    }

    function stopRefresh() {
        if (refreshTimer !== null) {
            clearInterval(refreshTimer);
            refreshTimer = null;
        }
    }

    const checkbox = document.getElementById("autoRefresh");

    checkbox.addEventListener("change", function () {
        if (this.checked) {
            startRefresh();
        } else {
            stopRefresh();
        }
    });

    // Start automatically since the checkbox is checked by default
    startRefresh();
</script>