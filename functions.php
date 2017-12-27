<?php
require_once 'arrays.php';
require_once 'strings.php';

function t($name){
	GLOBAL $strings_en, $strings_ru;
	$lang = getUserLanguage();
	return ${'strings_'.$lang}[$name];
}

function getUserLanguage(){
	if(!isset($_COOKIE['lang'])){
		$lang = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];
		$lang = explode('-', $lang)[0];
		$ru = ['ru', 'uk', 'be', 'kk', 'mo'];
		$lang = in_array($lang, $ru) ? 'ru' : 'en';
	}else{
		$lang = $_COOKIE['lang'];
	}
	return $lang;
}

function getChassis($chassis){
	GLOBAL $chassis_list;
	return $chassis_list[$chassis];
}

function getWheels($chassis){
	GLOBAL $wheels;
	return $wheels[$chassis];
}

function getAxles($chassis){
	GLOBAL $axles;
	return $axles[$chassis];
}

function getDLCArray($chassis, $accessory, $paint_job){
	GLOBAL $dlc_accessories, $dlc_chassis_list, $dlc_paints;
	$dlc = ['base'];
	if(key_exists($_POST['chassis'], $dlc_chassis_list)){
		$dlc = array_merge($dlc, explode(',', $dlc_chassis_list[$chassis]));
	}
	if($accessory){
		if(key_exists($accessory, $dlc_accessories)){
			$dlc = array_merge($dlc, explode(',', $dlc_accessories[$accessory]));
		}
	}
	if($paint_job){
		if(key_exists($paint_job, $dlc_paints)){
			$dlc = array_merge($dlc, explode(',', $dlc_paints[$paint_job]));
		}
	}
	return array_unique($dlc);
}

function rrmdir($src) {
	if(is_dir($src)){
		$dir = opendir($src);
		while(false !== ( $file = readdir($dir)) ) {
			if (( $file != '.' ) && ( $file != '..' )) {
				$full = $src . '/' . $file;
				if ( is_dir($full) ) {
					rrmdir($full);
				}
				else {
					unlink($full);
				}
			}
		}
		closedir($dir);
		rmdir($src);
	}
}

function copyTrailerFiles($dlc_list){
	mkdir('out/vehicle/');
	mkdir('out/vehicle/trailer');
	foreach($dlc_list as $dlc){
		$dir = 'files/'.$_POST['target'].'/'.$dlc.'/trailers';
		if($dir_files = scandir($dir)){
			foreach($dir_files as $file){
				if(is_file($dir .'/'. $file)){
					copy($dir .'/'. $file, 'out/vehicle/trailer/'. $file);
				}elseif($file != '.' && $file != '..' && in_array($file, $dlc_list)){
					$new_dir = 'files/'.$_POST['target'].'/'.$dlc.'/trailers/'.$file;
					if($new_dir_files = scandir($new_dir)){
						foreach($new_dir_files as $new_file){
							if(is_file($new_dir .'/'. $new_file)){
								copy($new_dir .'/'. $new_file, 'out/vehicle/trailer/'. $new_file);
							}
						}
					}
				}
			}
		}
	}
}

function copyCompanyFiles($dlc_list){
	mkdir('out/company');
	foreach($dlc_list as $dlc){
		$dir = 'files/'.$_POST['target'].'/'.$dlc.'/companies';
		if(is_dir($dir) && $dir_files = scandir($dir)){
			foreach($dir_files as $file){
				if(is_file($dir . '/' . $file)){
					copy($dir . '/' . $file, 'out/company/' . $file);
				}
			}
		}
	}
}

function getTrailerLook($paint_job){
	$array = explode('/', $paint_job);
	return str_replace('.sii', '', $array[count($array) - 1]);
}

function replaceTrailerFiles($dirname, $data){
	if(!is_dir($dirname)) mkdir($dirname);
	$dir = opendir($dirname);
	while (($file = readdir($dir)) !== false){
		if($file != "." && $file != ".."){
			if(is_file($dirname."/".$file)){
				$rows = file($dirname."/".$file, FILE_IGNORE_NEW_LINES);
				$trailer_name = trim(preg_split('/trailer\./', $rows[0])[1]);
				$content = generateTrailerContent($trailer_name, $data);
				file_put_contents($dirname."/".$file, $content);
			}
		}
	}
	closedir($dir);
}

function generateTrailerContent($name, $data){

	$chassis = $data['chassis'];
	$accessory = $data['accessory'];
	$paint_job = $data['paint_job'];
	$axles = $data['axles'];
	$wheels = $data['wheels'];

	$output_string = "trailer : trailer.".$name."\n{\n\taccessories[]: .".$name.".tchassis";
	for($i = 0; $i < $axles; $i++){
		$output_string .= "\n\taccessories[]: .".$name.".trwheel".$i;
	}
	if($accessory || $paint_job){
		if($accessory){
			$output_string .= "\n\taccessories[]: .".$name.".cargo";
		}
		if($paint_job){
			$output_string .= "\n\taccessories[]: .".$name.".paint_job";
		}
	}
	$output_string .= "\n}\n\nvehicle_accessory: .".$name.".tchassis\n{\n
		data_path: \"".$chassis."\"\n}\n";
	for($i = 0; $i < $axles; $i++){
		$output_string .= "\nvehicle_wheel_accessory: .".$name.".trwheel".$i."\n{";
		if($_POST['chassis'] == 'Schw Overweight' && $i == 2){
			$output_string .= "\n\toffset: 0\n\t\tdata_path: \"/def/vehicle/t_wheel/overweight_f.sii\"";
		}else{
			$output_string .= "\n\toffset: ".($i*2)."\n\t\tdata_path: \"".$wheels."\"";
		}

		$output_string .= "\n}\n";
	}
	if($accessory || $paint_job){
		if($accessory){
			$output_string .= "\nvehicle_accessory: .".$name.".cargo\n{\n\t\tdata_path: \"".$accessory."\"\n}";
		}
		if($paint_job){
			$output_string .= "\nvehicle_paint_job_accessory: .".$name.".paint_job\n{\n\t\tdata_path: \"".$paint_job."\"\n}";
		}
	}
	return $output_string;
}

function replaceCompanyFiles($dirname, $look){
	$pattern = '/trailer_look: [a-z.-_0-9]*/'; // паттерн на компанию
	$retext = 'trailer_look: '.$look; // Строка замены
	$dir = opendir($dirname);
	while (($file = readdir($dir)) !== false){
		if($file != "." && $file != ".."){
			if(is_file($dirname."/".$file)){
				$content = file_get_contents($dirname."/".$file);
				$content = preg_replace($pattern, $retext, $content);
				file_put_contents($dirname."/".$file, $content);
			}
		}
	}
	closedir($dir);
}

function zip_files($modname = 'mod'){
	$zip = new ZipArchive();
	$filename = transliterate($modname);

	if ($zip->open('download/'.$filename.'.scs', ZipArchive::CREATE) !== true){
		return false;
	}

	$content = file_get_contents('files/mod/manifest_template.sii');
	$content = str_replace('%%', $modname, $content);
	file_put_contents('files/mod/manifest.sii', $content);

	$zip->addFile('files/mod/manifest.sii', 'manifest.sii');
	$zip->addFile('files/mod/mod_icon.jpg', 'mod_icon.jpg');
	$zip->addEmptyDir('def/vehicle/trailer');

	$dir = scandir('out/vehicle/trailer');
	foreach($dir as $item){
		if($item != '.' && $item != '..'){
			$zip->addFile('out/vehicle/trailer/'.$item, 'def/vehicle/trailer/'.$item);
		}
	}

	if(is_dir('out/company')){
		$zip->addEmptyDir('def/company');
		$dir = scandir('out/company');
		foreach($dir as $item){
			if($item != '.' && $item != '..'){
				$zip->addFile('out/company/'.$item, 'def/company/'.$item);
			}
		}
	};
	$zip->close();

	return $filename;
}

function transliterate($str){
	$ru = ['а' => 'a',   'б' => 'b',   'в' => 'v',
		'г' => 'g',   'д' => 'd',   'е' => 'e',
		'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
		'и' => 'i',   'й' => 'y',   'к' => 'k',
		'л' => 'l',   'м' => 'm',   'н' => 'n',
		'о' => 'o',   'п' => 'p',   'р' => 'r',
		'с' => 's',   'т' => 't',   'у' => 'u',
		'ф' => 'f',   'х' => 'h',   'ц' => 'c',
		'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
		'ь' => '',  'ы' => 'y',   'ъ' => '',
		'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

		'А' => 'A',   'Б' => 'B',   'В' => 'V',
		'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
		'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
		'И' => 'I',   'Й' => 'Y',   'К' => 'K',
		'Л' => 'L',   'М' => 'M',   'Н' => 'N',
		'О' => 'O',   'П' => 'P',   'Р' => 'R',
		'С' => 'S',   'Т' => 'T',   'У' => 'U',
		'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
		'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
		'Ь' => '',  'Ы' => 'Y',   'Ъ' => '',
		'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',

		' ' => '_'];

	return strtr($str, $ru);

}