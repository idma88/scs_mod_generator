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
	$color = null;
	if(in_array($_POST['chassis'], $with_accessory)){
		$accessory = $_POST['accessory'] ?? null;
	}
	if(key_exists($_POST['chassis'], $with_paint_job)){
		$paint_job = $_POST['paint'] ?? null;
		if($_POST['paint'] == 'all'){
			$paint_job = $with_paint_job[$_POST['chassis']];
		}
		if(stripos($_POST['paint'], 'default.sii')){
			$color = $_POST['color']['scs']['r'].', '.$_POST['color']['scs']['g'].', '.$_POST['color']['scs']['b'];
		}
	}
	if($_POST['chassis'] == 'aero_dynamic') $paint_job = '/def/vehicle/trailer/aero_dynamic/company_paint_job/default.sii';
	$dlc_list = getDLCArray($_POST['chassis'], $accessory, $paint_job);
	$trailer_data = [
		'chassis' => $chassis,
		'accessory' => $accessory,
		'paint_job' => $paint_job,
		'wheels' => $wheels,
		'axles' => $axles,
		'color' => $color
	];

	!is_dir('out/company') ? : rrmdir('out/company');
    !is_dir('out/vehicle') ? : rrmdir('out/vehicle');

	copyTrailerFiles($dlc_list);
	replaceTrailerFiles('out/vehicle/trailer', $trailer_data);

	if($paint_job && $_POST['paint'] != 'all' && $_POST['chassis'] != 'aero_dynamic'){
		$trailer_look = getTrailerLook($paint_job);
		copyCompanyFiles($dlc_list);
		replaceCompanyFiles('out/company', $trailer_look);
	}

	$filename = zip_files($_POST['title']);

	header('Location: http://'.$_SERVER['HTTP_HOST'].'/?download='.$filename);