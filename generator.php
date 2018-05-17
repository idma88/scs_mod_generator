<?php

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
	header('Location: http://'.$_SERVER['HTTP_HOST']);
	exit;
}

include 'functions.php';
include 'arrays.php';
include 'classes/kint/Kint.class.php';
include 'classes/App.php';
include 'classes/Chassis.php';
include 'classes/Accessory.php';
include 'classes/PaintJob.php';
include 'classes/Image.php';
include 'classes/Logger.php';

// POST validation
if(!isset($_POST['chassis']) || $_POST['chassis'] == ''){
	header('Location: http://'.$_SERVER['HTTP_HOST'].'/?e=2301');
}
if(in_array($_POST['chassis'], $with_accessory) && !isset($_POST['accessory'])){
	header('Location: http://'.$_SERVER['HTTP_HOST'].'/?e=9502');
}
if(key_exists($_POST['chassis'], $with_paint_job) && (!isset($_POST['paint']) || $_POST['paint'] == '')){
	header('Location: http://'.$_SERVER['HTTP_HOST'].'/?e=8113');
}
if(!isset($_POST['target']) || $_POST['target'] == ''){
	header('Location: http://'.$_SERVER['HTTP_HOST'].'/?e=6764');
}

$chassis = new Chassis([
	'target' => $_POST['target'],
	'chassis' => $_POST['chassis'],
	'weight' => $_POST['weight'] ?? null,
	'wheels' => $_POST['wheels'] ?? null,
]);
$accessory = null;
$paint_job = null;

if($chassis->isWithAccessory()) $accessory = new Accessory();
if($chassis->isWithPaintJob() || $chassis->chassis_name == 'aero_dynamic'){
	$paint_job = new PaintJob($chassis, [
		'target' => $_POST['target'],
		'paint' => $_POST['paint'],
		'color' => $_POST['color'],
	]);
}

//!d($chassis);
//!d($paint_job); exit;

$generator = new App();
$generator->load($chassis, $accessory, $paint_job);
$generator->run();

$logger = new Logger();
$logger->writeLog($generator->fileName);

$add = $_POST['target'] == 'ats' ? 'game=ats&' : '';

header('Location: http://'.$_SERVER['HTTP_HOST'].'/?'.$add.'d='.$generator->fileName);