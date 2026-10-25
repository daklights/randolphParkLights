<?php

	include_once __DIR__ . "/rpl_common.php";

	header("Content-Type: application/json; charset=utf-8");

	if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
		http_response_code(405);
		echo json_encode(array(
			'success' => false,
			'error' => 'POST required'
		));
		exit;
	}

	if (!isset($_POST['enabled']) ||
		!in_array($_POST['enabled'], array('0', '1'), true)) {

		http_response_code(400);
		echo json_encode(array(
			'success' => false,
			'error' => 'Invalid enabled value'
		));
		exit;
	}

	$pluginName = basename(__DIR__);
	$enabled = $_POST['enabled'];

	WriteSettingToFile("syncEnabled", $enabled, $pluginName);

	echo json_encode(array(
		'success' => true,
		'syncEnabled' => (int)$enabled
	));
	exit;
	
?>