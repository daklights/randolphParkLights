#!/bin/bash

# Tell FPP that a reboot is needed to pickup postStart.sh runners
sed -i -e "s/^restartFlag .*/restartFlag = 1/" /home/fpp/media/settings

#fpp_install