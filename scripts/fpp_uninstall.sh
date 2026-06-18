#!/bin/bash

echo "$(date '+%Y-%m-%d %H:%M:%S'): FPP_UNINSTALL BEGIN" >> "$LOGFILE"

# Source FPP common functions if available
if [ -f "${FPPDIR}/scripts/common" ]; then
    . ${FPPDIR}/scripts/common
elif [ -f "/opt/fpp/scripts/common" ]; then
    . /opt/fpp/scripts/common
fi

# Set reboot flag if setSetting function is available
if command -v setSetting &> /dev/null; then
    setSetting rebootFlag 1
fi

echo "$(date '+%Y-%m-%d %H:%M:%S'): FPP_UNINSTALL COMPLETE" >> "$LOGFILE"