#!/bin/bash

# Common variables
PLUGINDIR=/home/fpp/media/plugins/randolphParkLights
LOGFILE=/home/fpp/media/logs/randolphParkLights.log

# Source FPP common functions if available
if [ -f "${FPPDIR}/scripts/common" ]; then
    . ${FPPDIR}/scripts/common
elif [ -f "/opt/fpp/scripts/common" ]; then
    . /opt/fpp/scripts/common
fi

# Set reboot flag if setSetting function is available
if command -v setSetting &> /dev/null; then
	echo "$(date '+%Y-%m-%d %H:%M:%S'): Reboot required to complete plugin uninstallation" | tee -a "$LOGFILE"
    setSetting rebootFlag 1
fi