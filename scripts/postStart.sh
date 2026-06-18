#!/bin/bash

PLUGINDIR=/home/fpp/media/plugins/randolphParkLights
PIDFILE=${PLUGINDIR}/randolphParkLights.pid
LOGFILE=/home/fpp/media/logs/randolphParkLights.log

echo "$(date '+%Y-%m-%d %H:%M:%S'): POST START BEGIN"
echo "$(date '+%Y-%m-%d %H:%M:%S'): POST START BEGIN" >> "$LOGFILE"

nohup /usr/bin/php ${PLUGINDIR}/rpl_run.php >> "$LOGFILE" 2&>1 < /dev/null &
echo $! > "$PIDFILE"

echo "$(date '+%Y-%m-%d %H:%M:%S'): POST START COMPLETE"
echo "$(date '+%Y-%m-%d %H:%M:%S'): POST START COMPLETE" >> "$LOGFILE"

#postStart


