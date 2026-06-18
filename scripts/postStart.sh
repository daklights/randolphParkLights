#!/bin/bash

PLUGINDIR=/home/fpp/media/plugins/randolphParkLights
LOGFILE=/home/fpp/media/logs/randolphParkLights.log

echo "$(date '+%Y-%m-%d %H:%M:%S'): POST START BEGIN"
echo "$(date '+%Y-%m-%d %H:%M:%S'): POST START BEGIN" >> "$LOGFILE"

/usr/bin/php ${PLUGINDIR}/rpl_run.php &
echo $! > "$LOGFILE"

echo "$(date '+%Y-%m-%d %H:%M:%S'): POST START COMPLETE"
echo "$(date '+%Y-%m-%d %H:%M:%S'): POST START COMPLETE" >> "$LOGFILE"

#postStart


