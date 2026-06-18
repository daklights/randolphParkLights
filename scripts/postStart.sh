#!/bin/bash

# Common variables
PLUGINDIR=/home/fpp/media/plugins/randolphParkLights
LOGFILE=/home/fpp/media/logs/randolphParkLights.log

# Wait for FPP web and API to fully start
echo "$(date '+%Y-%m-%d %H:%M:%S'): Checking to ensure FPP web and API are running..." | tee -a "$LOGFILE"
until curl -sf http://127.0.0.1/api/fppd/status >/dev/null; do
    echo "$(date '+%Y-%m-%d %H:%M:%S'): Waiting for FPP web and API to start..." | tee -a "$LOGFILE"
    sleep 2
done
echo "$(date '+%Y-%m-%d %H:%M:%S'): FPP web and API are running." | tee -a "$LOGFILE"

# Start the background PHP process to monitor
/usr/bin/php ${PLUGINDIR}/rpl_run.php &