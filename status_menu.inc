<?php

$pages = Array(
	'name' => "Randolph Park Lights",
	'type' => "status",
	'page' => "rpl.php",
	);

?>

<li><a href="<?php echo preg_replace('/.*\/plugins\/(.*)/', 'plugin.php?plugin=$1&page=rpl.php', dirname(__FILE__)); ?>">Randolph Park Lights</a></li>
