<?php

$f = fopen("/sys/class/thermal/thermal_zone0/temp","r");
$temp = fgets($f);
fclose($f);

$output = shell_exec('cat /proc/cpuinfo');
$serial = substr($output, (strpos($output, 'Serial'))+9, 17);
echo "Serial: " . $serial . "<br />";
echo "IP Address: " . $_SERVER['SERVER_ADDR'] . "<br />";
echo "FPP Mode: " . $settings['fppMode'] . "<br />";
echo "Variant: " . $settings['Variant'] . "<br />";
echo "Temp (C): " . ($temp/1000) . "<br />";
echo "Time: " . $_SERVER['REQUEST_TIME'] . "<br />";

?>