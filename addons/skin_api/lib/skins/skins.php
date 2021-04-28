<?php

	require 'MinecraftSkins.php';

	use \MinecraftSkins\MinecraftSkins;

	if (isset($_GET['user'])) {
		$user = $_GET['user'];
	} else {
		$user = "steve";
	}

	$skinImage = imagecreatefrompng("https://api.evoniamc.eu/api/skins/". $user .".png");

	if (isset($_GET['mode'])) {
		if (strpos($_GET['mode'], "head") !== false) {
			$result = MinecraftSkins::head($skinImage, 10);
		} else {
			$result = MinecraftSkins::skin($skinImage, 10);
		}
	} else {
		$result = MinecraftSkins::head($skinImage, 10);
	}

	header('Content-type: image/png');
	imagepng($result);

?>