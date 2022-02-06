#!/bin/bash

# Tell FPP that a reboot is needed to kill the postStart.sh runners
sed -i -e "s/^rebootFlag .*/rebootFlag = 1/" /home/fpp/media/settings

#fpp_uninstall