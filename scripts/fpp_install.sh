#!/bin/bash

${FPPDIR}/scripts/ManageApacheContentPolicy.sh add connect-src https://dakjr.com 2>/dev/null || true

setSetting restartFlag 1

#fpp_install