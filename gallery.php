<?php
require_once 'arrays.php';
require_once 'functions.php';

// ajax handler
if(isset($_POST['ajax']) && $chassis = $_POST['chassis']){
	GLOBAL $with_accessory, $with_paint_job;
	$lang = $_POST['lang'] ?? null;
	$result = null;
	if(key_exists($_POST['chassis'], $with_paint_job)){
		$data = getPaintByChassis($lang, $_POST['game'], $chassis);
	}elseif(in_array($_POST['chassis'], $with_accessory)){
		$data = getAccessoriesByChassis($lang, $_POST['game'], $chassis);
	}
	$chassis = str_replace(['_default', '_black', '_yellow', '_red', '_blue', '_grey'], '', $chassis);
	foreach($data as $def => $name){
		$trailer_look = getTrailerLook($def);
		if(file_exists('assets/img/trailers/'.$chassis.'/'.$trailer_look.'.jpg')) $trailer_look .= '.jpg';
		$result[$trailer_look] = $name;
	}
	echo json_encode(['result' => $result, 'status' => 'OK', 'chassis' => $chassis]);
	die();
}

include_once 'modules/header.php';

include_once 'modules/gallery_view.php';

include_once 'modules/footer.php';