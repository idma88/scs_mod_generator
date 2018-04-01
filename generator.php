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
	'weight' => isset($_POST['weight']) ?? null,
	'wheels' => isset($_POST['wheels']) ?? null,
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

$log = file_get_contents('log.txt');
$user_data = getBrowser();
$append = date('d-m-Y H:i')." $generator->fileName\nTitle:\t\t$_POST[title]\nChassis:\t$_POST[chassis]\n".
	"Accessory:\t$_POST[accessory]\nPaint:\t\t$_POST[paint]\nWeight:\t\t$_POST[weight]".
	"\nColor:\t\t".$_POST['color']['scs']['r'].", ".$_POST['color']['scs']['g'].", ".$_POST['color']['scs']['b']."\n".
	"Wheels: \t$_POST[wheels]\n".
	"Target:\t\t$_POST[target]\nUser:\t\t".$user_data['platform']." ".$user_data['name']." ".$user_data['version']."\n\n";
file_put_contents('log.txt', $append.$log);

$add = $_POST['target'] == 'ats' ? 'game=ats&' : '';

header('Location: http://'.$_SERVER['HTTP_HOST'].'/?'.$add.'d='.$generator->fileName);