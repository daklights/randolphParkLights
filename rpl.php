<?php

	include_once "rpl_common.php";
	
	// Handle AJAX request to enable/disable remote syncing
	if ($_SERVER['REQUEST_METHOD'] === 'POST'
		&& isset($_POST['action'])
		&& $_POST['action'] === 'setSyncEnabled') {

		header('Content-Type: application/json');

		$pluginName = basename(dirname(__FILE__));

		if (!isset($_POST['enabled'])
			|| !in_array($_POST['enabled'], array('0', '1'), true)) {
			http_response_code(400);
			echo json_encode(array('success' => false));
			exit;
		}

		$enabled = $_POST['enabled'];

		WriteSettingToFile("syncEnabled", $enabled, $pluginName);

		echo json_encode(array(
			'success' => true,
			'syncEnabled' => (int)$enabled
		));
		exit;
	}
	
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
	echo "Time: " . date('Y-m-d h:i:sa',$combined['time']) . " (" . $combined['time'] . ")<br />";
	
	if ($combined['mode'] == "player") {
		echo "Playlist: " . $combined['playlistName'] . "<br />";
		echo "Sequence: " . $combined['sequenceName'] . "<br />";
		echo "Seconds Elapsed: " . $combined['secondsElapsed'] . "<br />";
		echo "Seconds Remaining: " . $combined['secondsRemaining'] . "<br />";
		echo "Sequence Started: " . date('Y-m-d h:i:sa',$combined['sequenceStarted']) . " (" . $combined['sequenceStarted'] . ")<br /><br />";
	}
	
	$lastSync = $pluginSettings['latestRemoteSyncResult'];
	$lastSyncArray = explode(' ',$lastSync);
	echo "Remote Sync: " . $lastSyncArray[0] . " " . date('Y-m-d h:i:sa',$lastSyncArray[1]) . " (" . $lastSyncArray[1] . ")<br /><br />";
	
	$syncEnabled = !isset($pluginSettings['syncEnabled'])
		|| (int)$pluginSettings['syncEnabled'] === 1;
?>

<div style="margin-bottom: 20px;">
	<label>
		<input type="checkbox" id="syncEnabled" <?php echo $syncEnabled ? 'checked' : ''; ?>>
		<strong>Enable Remote Sync</strong>
	</label>
	<span id="syncStatus" style="margin-left: 10px; font-weight: bold;">
		<?php echo $syncEnabled ? 'ENABLED' : 'DISABLED'; ?>
	</span>
</div>
	
<label>
    <input type="checkbox" id="autoRefresh">&nbsp;&nbsp;Automatically refresh every
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
	const syncToggle = document.getElementById("syncEnabled");
	const syncStatus = document.getElementById("syncStatus");

	function updateSyncStatus(enabled) {
		syncStatus.textContent = enabled ? "ENABLED" : "DISABLED";
		syncStatus.style.color = enabled ? "#4caf50" : "#f44336";
	}

	updateSyncStatus(syncToggle.checked);

	syncToggle.addEventListener("change", async function () {
		const enabled = syncToggle.checked;
		syncToggle.disabled = true;
		syncStatus.textContent = "Saving...";

		try {
			const response = await fetch(window.location.href, {
				method: "POST",
				headers: {
					"Content-Type": "application/x-www-form-urlencoded"
				},
				body: new URLSearchParams({
					action: "setSyncEnabled",
					enabled: enabled ? "1" : "0"
				})
			});

			if (!response.ok) {
				throw new Error("HTTP " + response.status);
			}

			const result = await response.json();

			if (!result.success) {
				throw new Error("Unable to save setting");
			}

			updateSyncStatus(result.syncEnabled === 1);

		} catch (error) {
			syncToggle.checked = !enabled;
			updateSyncStatus(syncToggle.checked);
			alert("Failed to update sync setting: " + error.message);
		} finally {
			syncToggle.disabled = false;
		}
	});

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