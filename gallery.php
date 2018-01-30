<?php
require_once 'arrays.php';
require_once 'functions.php';
require_once 'classes/Chassis.php';
require 'classes/PaintJob.php';

// ajax handler
if(isset($_POST['ajax']) && $_POST['chassis']){
	GLOBAL $with_accessory, $with_paint_job;
	$lang = $_POST['lang'] ?? null;
	$result = null;
	$chassis = new Chassis();
	if($chassis->isWithPaintJob()){
		$data = $chassis->getAvailablePaints($lang);
	}elseif(in_array($_POST['chassis'], $with_accessory)){
		$data = $chassis->getAvailableAccessories($lang);
	}
	$chassis_name = str_replace(['_default', '_black', '_yellow', '_red', '_blue', '_grey'], '', $chassis->chassis_name);
	foreach($data as $def => $name){
		$trailer_look = PaintJob::getTrailerLookByDef($def);
		if(file_exists('assets/img/trailers/'.$chassis_name.'/'.$trailer_look.'.jpg')) $trailer_look .= '.jpg';
		$result[$trailer_look] = $name;
	}
	echo json_encode(['result' => $result, 'status' => 'OK', 'chassis' => $chassis_name]);
	die();
}

include_once 'modules/header.php';

include_once 'modules/gallery_view.php';

include_once 'modules/footer.php';