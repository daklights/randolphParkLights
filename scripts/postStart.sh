#!/bin/bash

PLUGINDIR=/home/fpp/media/plugins/randolphParkLights
PIDFILE=${PLUGINDIR}/randolphParkLights.pid
LOGFILE=/home/fpp/media/logs/randolphParkLights.log

nohup /usr/bin/php /home/fpp/media/plugins/randolphParkLights/rpl_run.php >> "$LOGFILE" 2&>1 < /dev/null &
echo $! > "$PIDFILE"

#postStart


