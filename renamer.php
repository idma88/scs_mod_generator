<?php

	include 'classes/kint/Kint.class.php';
	include 'classes/Image.php';
	include 'functions.php';
	include 'arrays.php';

	GLOBAL $with_accessory, $with_paint_job, $dlc_accessories, $dlc_chassis_list, $dlc_paints;

	$chassis = getChassis($_POST['chassis'], $_POST['target']);
	$wheels  = getWheels($_POST['chassis']);
	$axles  = getAxles($_POST['chassis']);
	$weight  = $_POST['weight'] ?? null;
	$accessory = null;
	$paint_job = null;
	$color = null;
	if(in_array($_POST['chassis'], $with_accessory)){
		$accessory = $_POST['accessory'] ?? null;
	}
	if(key_exists($_POST['chassis'], $with_paint_job)){
		$paint_job = $_POST['paint'] ?? $with_paint_job[$_POST['chassis']];
		if($_POST['paint'] == 'all'){
			$paint_job = $with_paint_job[$_POST['chassis']];
		}
		if(stripos($_POST['paint'], 'default.sii')){
			$colors['r'] = $_POST['color']['scs']['r'] ?? '1';
			$colors['g'] = $_POST['color']['scs']['g'] ?? '1';
			$colors['b'] = $_POST['color']['scs']['b'] ?? '1';
			$color = $colors['r'].', '.$colors['g'].', '.$colors['b'];
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
    !is_dir('out/cargo') ? : rrmdir('out/cargo');
    !file_exists('out/mod_icon.jpg') ? : unlink('out/mod_icon.jpg');

	copyTrailerFiles($dlc_list);
	replaceTrailerFiles('out/vehicle/trailer', $trailer_data);

	if(isset($_POST['paint']) && $_POST['paint'] != 'all' && $_POST['chassis'] != 'aero_dynamic'){
		$trailer_look = getTrailerLook($paint_job);
		copyCompanyFiles($dlc_list);
		replaceCompanyFiles('out/company', $trailer_look);
	}

	if($weight && is_numeric($weight)){
		copyCargoFiles($dlc_list);
		replaceCargoFiles('out/cargo', $weight);
	}

	if(is_uploaded_file($_FILES['img']['tmp_name'])){
		$file = $_FILES[0]['img'];
		if($file->size <= 5500000){
			$img = new Image();
			$img->load($_FILES['img']['tmp_name']);
			$img->resize(276, 162);
			$img->save('out/mod_icon.jpg');
		}
	}else{
		if(file_exists('files/mod/mod_icon.jpg')){
			copy('files/mod/mod_icon.jpg', 'out/mod_icon.jpg');
		}
	}

	$title = trim($_POST['title']);
	$title = strlen($title) == 0 ? 'Mod' : $title;
	$filename = zip_files($title);

	$add = $_POST['target'] == 'ats' ? 'game=ats&' : '';

	header('Location: http://'.$_SERVER['HTTP_HOST'].'/?'.$add.'d='.$filename);