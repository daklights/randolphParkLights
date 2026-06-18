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
    <input type="checkbox" id="autoRefresh">&nbsp;&nbsp;Automatically Refresh
</label>

<select id="refreshSeconds">
    <option value="10">10 seconds</option>
    <option value="20">20 seconds</option>
    <option value="30">30 seconds</option>
    <option value="60">60 seconds</option>
</select>

<div id="refreshProgressContainer" style="width: 300px; height: 18px; border: 1px solid #999; margin-top: 8px; display: none;">
    <div id="refreshProgressBar" style="height: 100%; width: 100%; background: #4caf50;"></div>
</div>

<div id="refreshCountdownText" style="margin-top: 4px; display: none;"></div>

<script>
    const checkbox = document.getElementById("autoRefresh");
    const dropdown = document.getElementById("refreshSeconds");
    const progressContainer = document.getElementById("refreshProgressContainer");
    const progressBar = document.getElementById("refreshProgressBar");
    const countdownText = document.getElementById("refreshCountdownText");

    let refreshTimer = null;
    let countdownTimer = null;
    let secondsRemaining = 0;

    // Load saved settings, defaulting to checked and 10 seconds
    checkbox.checked = localStorage.getItem("autoRefreshEnabled") !== "false";
    dropdown.value = localStorage.getItem("autoRefreshSeconds") || "10";

    function startRefresh() {
        stopRefresh();

        const totalSeconds = parseInt(dropdown.value, 10);
        secondsRemaining = totalSeconds;

        dropdown.disabled = false;
        progressContainer.style.display = "block";
        countdownText.style.display = "block";

        updateProgress(totalSeconds);

        countdownTimer = setInterval(function () {
            secondsRemaining--;

            if (secondsRemaining <= 0) {
                window.location.reload();
                return;
            }

            updateProgress(totalSeconds);
        }, 1000);
    }

    function stopRefresh() {
        if (refreshTimer !== null) {
            clearInterval(refreshTimer);
            refreshTimer = null;
        }

        if (countdownTimer !== null) {
            clearInterval(countdownTimer);
            countdownTimer = null;
        }

        progressContainer.style.display = "none";
        countdownText.style.display = "none";
    }

    function updateProgress(totalSeconds) {
        const percent = (secondsRemaining / totalSeconds) * 100;
        progressBar.style.width = percent + "%";
        countdownText.textContent = "Refreshing in " + secondsRemaining + " seconds";
    }

    function applySettings() {
        localStorage.setItem("autoRefreshEnabled", checkbox.checked ? "true" : "false");
        localStorage.setItem("autoRefreshSeconds", dropdown.value);

        if (checkbox.checked) {
            dropdown.disabled = false;
            startRefresh();
        } else {
            dropdown.disabled = true;
            stopRefresh();
        }
    }

    checkbox.addEventListener("change", applySettings);

    dropdown.addEventListener("change", function () {
        localStorage.setItem("autoRefreshSeconds", dropdown.value);

        if (checkbox.checked) {
            startRefresh();
        }
    });

    applySettings();
</script>