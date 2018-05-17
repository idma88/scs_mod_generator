<?php
require_once 'classes/Chassis.php';
require_once 'classes/Accessory.php';
require_once 'classes/PaintJob.php';
require_once 'classes/I18n.php';
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
				'first' => I18n::t('choose_accessory')
			];
		}
		if($_POST['select'] == 'paint'){
			$chassis = null;
			$data['paint'] = [
				'echo' => PaintJob::getAllPaintsDefs($game),
				'first' => I18n::t('all_companies')
			];
		}
		echo json_encode(['result' => $data, 'status' => 'OK']);
		die();
	}
    if(isset($_POST['accessory'])){
        $chassis = new Chassis([
            'target' => $_POST['target'],
            'chassis' => $_POST['chassis'],
            'wheels' => '/def/vehicle/t_wheel/single.sii'
        ]);
        if($chassis->isWithAccessory()){
            $accessory = new Accessory();
            $dlc = $accessory->dlc;
        }
        if($chassis->isWithPaintJob()){
            $paint = new PaintJob($chassis, [
                'target' => $_POST['target'],
                'paint' => $_POST['accessory'],
                'color' => null,
            ]);
            $dlc = $paint->dlc;
        }
        echo json_encode(['status' => 'OK', 'dlc' => array_unique(array_merge($dlc, $chassis->dlc))]);
        die();
    }

	$chassis = new Chassis([
		'target' => $_POST['target'],
		'chassis' => $_POST['chassis'],
        'wheels' => '/def/vehicle/t_wheel/single.sii'
	]);
	$chassis->game = $game;
	$echo = false;
	$target = null;
	$data = null;
	if($chassis->isWithAccessory()){
		$data['accessory'] = [
			'echo' => $chassis->getAvailableAccessories($lang),
			'first' => I18n::t('choose_accessory', $lang)
		];
	}
	if($chassis->isWithPaintJob()){
		$data['paint'] = [
			'echo' => $chassis->chassis_name == 'paintable' ? $chassis->getAllCompanies($lang) : $chassis->getAvailablePaints($lang)
		];
	}
	echo json_encode(['result' => $data, 'status' => 'OK', 'dlc' => $chassis->dlc]);
	die();
}

require_once 'views/layout.php';