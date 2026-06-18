#!/bin/bash

echo "$(date '+%Y-%m-%d %H:%M:%S'): FPP_INSTALL BEGIN" >> "$LOGFILE"

# Source FPP common functions if available
if [ -f "${FPPDIR}/scripts/common" ]; then
    . ${FPPDIR}/scripts/common
elif [ -f "/opt/fpp/scripts/common" ]; then
    . /opt/fpp/scripts/common
fi

# Common variables
PLUGINDIR=/home/fpp/media/plugins/randolphParkLights
LOGFILE=/home/fpp/media/logs/randolphParkLights.log

# Ensure log file exists
if [ ! -f "$LOGFILE" ]; then
    touch "$LOGFILE"
    chown fpp:fpp "$LOGFILE"
    chmod 664 "$LOGFILE"
    echo "Created log file: $LOGFILE"
fi

# Set reboot flag if setSetting function is available
if command -v setSetting &> /dev/null; then
    setSetting rebootFlag 1
fi

echo "$(date '+%Y-%m-%d %H:%M:%S'): FPP_INSTALL COMPLETE" >> "$LOGFILE"