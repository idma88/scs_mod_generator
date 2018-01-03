<?php
require_once 'arrays.php';
require_once 'functions.php';

$game = $_GET['game'] ?? 'ets2';

if(isset($_POST['ajax']) && $chassis = $_POST['chassis']){
	$lang = $_POST['lang'] ?? null;
	if(isset($_POST['all']) && $_POST['all'] == 'true'){
		if($_POST['target'] == 'accessory'){
			$chassis = null;
			$data['accessory'] = [
				'echo' => getAccessoriesByChassis($lang, $game),
				'first' => t('choose_accessory')
			];
		}
		if($_POST['target'] == 'paint'){
			$chassis = null;
			$data['paint'] = [
				'echo' => getPaintByChassis($lang, $game),
				'first' => t('all_companies')
			];
		}
		echo json_encode(['result' => $data, 'status' => 'OK']);
		die();
	}
	GLOBAL $with_accessory, $with_paint_job;
	$echo = false;
	$target = null;
	$data = null;
	if(in_array($_POST['chassis'], $with_accessory)){
		$data['accessory'] = [
			'echo' => getAccessoriesByChassis($lang, $game, $chassis),
			'first' => t('choose_accessory', $lang)
		];
	}
	if(key_exists($_POST['chassis'], $with_paint_job)){
		$data['paint'] = [
			'echo' => getPaintByChassis($lang, $game, $chassis),
			'first' => t('all_companies', $lang)
		];
	}
	echo json_encode(['result' => $data, 'status' => 'OK']);
	die();
}

require_once 'modules/header.php';

require_once 'modules/view.php';

require_once 'modules/footer.php';