#!/bin/bash

echo "$(date '+%Y-%m-%d %H:%M:%S'): postStart BEGIN" | tee -a "$LOGFILE"

# Wait 15 seconds for FPP to fully boot and start
echo "$(date '+%Y-%m-%d %H:%M:%S'): Waiting 15 seconds to ensure FPP is booted and started..." | tee -a "$LOGFILE"
sleep(15);

# Start the background PHP process to monitor
PLUGINDIR=/home/fpp/media/plugins/randolphParkLights
/usr/bin/php ${PLUGINDIR}/rpl_run.php &

echo "$(date '+%Y-%m-%d %H:%M:%S'): postStart COMPLETE" | tee -a "$LOGFILE"