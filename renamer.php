<?php

	include 'modules/kint/Kint.class.php';
	include 'functions.php';
	include 'arrays.php';

	GLOBAL $with_accessory, $with_paint_job, $dlc_accessories, $dlc_chassis_list, $dlc_paints;

	$chassis = getChassis($_POST['chassis']);
	$wheels  = getWheels($_POST['chassis']);
	$axles  = getAxles($_POST['chassis']);
	$accessory = null;
	$paint_job = null;
	if(in_array($_POST['chassis'], $with_accessory)){
		$accessory = $_POST['accessory'] ?? null;
	}
	if(key_exists($_POST['chassis'], $with_paint_job)){
		$paint_job = $_POST['paint_job'] ?? null;
		if($_POST['paint_job'] == 'all'){
			$paint_job = $with_paint_job[$_POST['chassis']];
		}
	}
	$dlc_list = getDLCArray($_POST['chassis'], $accessory, $paint_job);
	$trailer_data = [
		'chassis' => $chassis,
		'accessory' => $accessory,
		'paint_job' => $paint_job,
		'wheels' => $wheels,
		'axles' => $axles
	];

	rrmdir('out/company');
	rrmdir('out/vehicle');

	copyTrailerFiles($dlc_list);
	replaceTrailerFiles('out/vehicle/trailer', $trailer_data);

	if($paint_job && $_POST['paint_job'] != 'all'){
		$trailer_look = getTrailerLook($paint_job);
		copyCompanyFiles($dlc_list);
		replaceCompanyFiles('out/company', $trailer_look);
	}

	header('Location: http://renamer.local/');