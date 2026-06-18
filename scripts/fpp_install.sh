#!/bin/bash

PLUGINDIR=/home/fpp/media/plugins/randolphParkLights
LOGFILE=/home/fpp/media/logs/randolphParkLights.log

echo "$(date '+%Y-%m-%d %H:%M:%S'): FPP_INSTALL BEGIN"
echo "$(date '+%Y-%m-%d %H:%M:%S'): FPP_INSTALL BEGIN" >> "$LOGFILE"

#${FPPDIR}/scripts/ManageApacheContentPolicy.sh add connect-src https://dakjr.com 2>/dev/null || true

#setSetting restartFlag 1

echo "$(date '+%Y-%m-%d %H:%M:%S'): FPP_INSTALL COMPLETE"
echo "$(date '+%Y-%m-%d %H:%M:%S'): FPP_INSTALL COMPLETE" >> "$LOGFILE"

#fpp_install