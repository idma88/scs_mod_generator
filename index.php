<?php
require_once 'classes/Chassis.php';
require_once 'classes/Accessory.php';
require_once 'classes/PaintJob.php';
require_once 'arrays.php';
require_once 'functions.php';

$game = $_GET['game'] ?? 'ets2';

if(isset($_POST['ajax']) && $chassis = $_POST['chassis']){

	$lang = $_POST['lang'] ?? null;

	if(isset($_POST['all']) && $_POST['all'] == 'true'){
		if($_POST['select'] == 'accessory'){
			$chassis = null;
			$data['accessory'] = [
				'echo' => Accessory::getAllAccessoriesDefs($game),
				'first' => t('choose_accessory')
			];
		}
		if($_POST['select'] == 'paint'){
			$chassis = null;
			$data['paint'] = [
				'echo' => PaintJob::getAllPaintsDefs($game),
				'first' => t('all_companies')
			];
		}
		echo json_encode(['result' => $data, 'status' => 'OK']);
		die();
	}
	$chassis = new Chassis([
		'target' => $_POST['target'],
		'chassis' => $_POST['chassis']
	]);
	$chassis->game = $game;
	$echo = false;
	$target = null;
	$data = null;
	if($chassis->isWithAccessory()){
		$data['accessory'] = [
			'echo' => $chassis->getAvailableAccessories($lang),
			'first' => t('choose_accessory', $lang)
		];
	}
	if($chassis->isWithPaintJob()){
		$data['paint'] = [
			'echo' => $chassis->chassis_name == 'paintable' ? $chassis->getAllCompanies($lang) : $chassis->getAvailablePaints($lang)
		];
	}
	echo json_encode(['result' => $data, 'status' => 'OK']);
	die();
}

require_once 'modules/header.php';

require_once 'modules/view.php';

require_once 'modules/footer.php';