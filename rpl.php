Sample Landing Page<br /><br />

<?php

$output = shell_exec('cat /proc/cpuinfo');

echo "<pre>";
print_r($output);
echo "</pre>";

echo "<br /><br />";

echo "<pre>";
print_r($_SERVER);
echo "</pre>";

echo "<br /><br />";

echo "<pre>";
print_r($settings);
echo "</pre>";

?>