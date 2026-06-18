#!/bin/bash

echo "$(date '+%Y-%m-%d %H:%M:%S'): postStart BEGIN" >> "$LOGFILE"

# Wait 15 seconds for FPP to fully boot and start
echo "$(date '+%Y-%m-%d %H:%M:%S'): Pending 15 second delay..." >> "$LOGFILE"
sleep(15);

# Start the background PHP process to monitor
PLUGINDIR=/home/fpp/media/plugins/randolphParkLights
/usr/bin/php ${PLUGINDIR}/rpl_run.php &

echo "$(date '+%Y-%m-%d %H:%M:%S'): postStart COMPLETE" >> "$LOGFILE"