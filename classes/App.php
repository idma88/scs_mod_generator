<?php

class App{

	public $title;
	public $chassis;
	public $accessory;
	public $paintJob;
	public $outDir = 'out_';
	public $downloadDir = 'download';
	public $dlc = ['base'];
	public $game;
	public $fileName;

	public function load($chassis, $accessory, $paintJob){
		$this->title = strlen(trim($_POST['title'])) == 0 ? 'Mod' : trim($_POST['title']);
		$this->chassis = $chassis;
		$this->accessory = $accessory;
		$this->paintJob = $paintJob;
		$this->dlc = $this->getDLCArray([$this->chassis->dlc, $this->accessory->dlc, $this->paintJob->dlc]);
		$this->game = $_POST['target'];
		$this->outDir .= time();
	}

	public function run(){
		$this->makeOutDirectory();
		$this->copyTrailerFiles();
		$this->replaceTrailerFiles();
		if($this->paintJob && !$this->paintJob->allCompanies && $this->chassis->chassis_name !== 'aero_dynamic'){
			$this->copyCompanyFiles();
			$this->replaceCompanyFiles();
		}
		if($this->chassis->weight && is_numeric($this->chassis->weight)){
			$this->copyCargoFiles();
			$this->replaceCargoFiles();
		}
		$this->copyImage();
		$this->fileName = $this->zipFiles();
		$this->removeOutDirectory();
	}

	private function getDLCArray($dlc){
		$array = $this->dlc;
		foreach($dlc as $item){
			if($item) $array = array_merge($array, $item);
		}
		return array_unique($array);
	}

	private function makeOutDirectory(){
		mkdir($this->outDir);
	}

	private function removeOutDirectory(){
		!is_dir($this->outDir) ? : rrmdir($this->outDir);
	}

	private function copyTrailerFiles(){
		mkdir($this->outDir.'/vehicle/');
		mkdir($this->outDir.'/vehicle/trailer');
		foreach($this->dlc as $dlc){
			$dir = 'files/'.$this->game.'/'.$dlc.'/trailers';
			if($dir_files = scandir($dir)){
				foreach($dir_files as $file){
					if(is_file($dir .'/'. $file)){
						copy($dir .'/'. $file, $this->outDir.'/vehicle/trailer/'. $file);
					}elseif($file != '.' && $file != '..' && in_array($file, $this->dlc)){
						$new_dir = 'files/'.$_POST['target'].'/'.$dlc.'/trailers/'.$file;
						if($new_dir_files = scandir($new_dir)){
							foreach($new_dir_files as $new_file){
								if(is_file($new_dir .'/'. $new_file)){
									copy($new_dir .'/'. $new_file, $this->outDir.'/vehicle/trailer/'. $new_file);
								}
							}
						}
					}
				}
			}
		}
	}

	private function copyCompanyFiles(){
		mkdir($this->outDir.'/company');
		foreach($this->dlc as $dlc){
			$dir = 'files/'.$this->game.'/'.$dlc.'/companies';
			if(is_dir($dir) && $dir_files = scandir($dir)){
				foreach($dir_files as $file){
					if(is_file($dir . '/' . $file)){
						copy($dir . '/' . $file, $this->outDir.'/company/' . $file);
					}
				}
			}
		}
	}

	private function copyCargoFiles(){
		mkdir($this->outDir.'/cargo');
		foreach($this->dlc as $dlc){
			$dir = 'files/'.$this->game.'/'.$dlc.'/cargos';
			if($inner_dirs = scandir($dir)){
				foreach($inner_dirs as $inner_dir){
					if($inner_dir !== '.' && $inner_dir !== '..'){
						$out_dir = $this->outDir.'/cargo/'.$inner_dir;
						is_dir($out_dir) ? : mkdir($out_dir);
						foreach(scandir($dir.'/'.$inner_dir) as $file){
							if($file !== '.' && $file !== '..'){
								copy($dir . '/' . $inner_dir .'/'. $file, $out_dir .'/'. $file);
							}
						}
					}

				}
			}
		}
	}

	private function copyImage(){
		if(is_uploaded_file($_FILES['img']['tmp_name'])){
			$file = $_FILES['img'];
			if($file->size <= 5500000){
				$img = new Image();
				$img->load($_FILES['img']['tmp_name']);
				$img->resize(276, 162);
				$img->save($this->outDir.'/mod_icon.jpg');
			}
		}else{
			if(file_exists('files/mod/mod_icon.jpg')){
				copy('files/mod/mod_icon.jpg', $this->outDir.'/mod_icon.jpg');
			}
		}
	}

	private function replaceTrailerFiles(){
		GLOBAL $coupled_trailers;
		$dirname = $this->outDir.'/vehicle/trailer';
		if(!is_dir($dirname)) mkdir($dirname);
		$dir = opendir($dirname);
		while (($file = readdir($dir)) !== false){
			if($file != "." && $file != ".."){
				if(is_file($dirname."/".$file)){
					$rows = file($dirname."/".$file, FILE_IGNORE_NEW_LINES);
					$trailer_name = trim(preg_split('/trailer\./', $rows[0])[1]);
					$accessory_name = trim(preg_replace('/\.[a-z0-9]+$/', '', explode(':', $rows[2])[1]));
					in_array($_POST['chassis'], $coupled_trailers) ?
						$content = $this->generateCoupledTrailerContent($trailer_name) :
						$content = $this->generateTrailerContent($trailer_name, $accessory_name);
					file_put_contents($dirname."/".$file, $content);
				}
			}
		}
		closedir($dir);
	}

	private function replaceCompanyFiles(){
		$dirname = $this->outDir.'/company';
		$pattern = '/trailer_look: [a-z.-_0-9]*/'; // паттерн на компанию
		$retext = 'trailer_look: '.$this->paintJob->look; // Строка замены
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

	private function replaceCargoFiles(){
		$dirname = $this->outDir.'/cargo';
		foreach(scandir($dirname) as $inner_dir){
			if($inner_dir !== '.' && $inner_dir !== '..'){
				foreach(scandir($dirname.'/'.$inner_dir) as $file){
					if($file !== '.' && $file !== '..'){
						$content = file_get_contents($dirname.'/'.$inner_dir.'/'.$file);
						$content = preg_replace('/mass: [0-9]*/', 'mass: '.$this->chassis->weight.'000', $content);
						file_put_contents($dirname.'/'.$inner_dir.'/'.$file, $content);
					}
				}
			}
		}
	}

	private function generateTrailerContent($trailer_name, $accessory_name){
		$output_string = "trailer : trailer.".$trailer_name."\n{\n\taccessories[]: ".$accessory_name.".tchassis";
		for($i = 0; $i < $this->chassis->axles; $i++){
			$output_string .= "\n\taccessories[]: ".$accessory_name.".trwheel".$i;
		}
		if($this->accessory || $this->paintJob){
			if(isset($this->accessory->accessory_def) && $this->accessory->accessory_def !== ''){
				$output_string .= "\n\taccessories[]: ".$accessory_name.".cargo";
			}
			if(isset($this->paintJob->paint_def) && $this->paintJob->paint_def !== ''){
				$output_string .= "\n\taccessories[]: ".$accessory_name.".paint_job";
			}
		}
		$output_string .= "\n}\n\nvehicle_accessory: ".$accessory_name.".tchassis\n{
		data_path: \"".$this->chassis->chassis_def."\"\n}\n";
		for($i = 0; $i < $this->chassis->axles; $i++){
			$output_string .= "\nvehicle_wheel_accessory: " . $accessory_name . ".trwheel" . $i . "\n{";
			if($this->chassis->chassis_name == 'schw_overweight' && $i == 2){
				$output_string .= "\n\toffset: 0\n\t\tdata_path: \"/def/vehicle/t_wheel/overweight_f.sii\"";
			}elseif((!$this->chassis->customWheels) &&
				($this->chassis->chassis_name == 'chemical_long' ||
				$this->chassis->chassis_name == 'acid_long') && $i == 0){
				$output_string .= "\n\toffset: 0\n\t\tdata_path: \"/def/vehicle/t_wheel/front.sii\"";
			}else{
				$output_string .= "\n\toffset: ".($i*2)."\n\t\tdata_path: \"".$this->chassis->wheels."\"";
			}

			$output_string .= "\n}\n";
		}
		if($this->accessory || $this->paintJob){
			if(isset($this->accessory->accessory_def) && $this->accessory->accessory_def !== ''){
				$output_string .= "\nvehicle_accessory: ".$accessory_name.".cargo\n{\n\t\tdata_path: \"".$this->accessory->accessory_def."\"\n}\n";
			}
			if(isset($this->paintJob->paint_def) && $this->paintJob->paint_def !== ''){
				$output_string .= "\nvehicle_paint_job_accessory: ".$accessory_name.".paint_job\n{\n";
				if(stripos($this->paintJob->paint_def ,'default.sii') && $this->chassis->chassis_name != 'aero_dynamic'){
					$output_string .= "\tbase_color: (".$this->paintJob->color.")\n";
				}
				$output_string .= "\t\tdata_path: \"".$this->paintJob->paint_def."\"\n}\n";
			}
		}
		return $output_string;
	}

	private function generateCoupledTrailerContent($trailer_name){
		GLOBAL $heavy_ats_accessory_with_spreader;
		$content = null;
		if($this->accessory && $this->accessory->accessory_def === '' && $this->chassis->chassis_name == 'magnitude_55l'){
			$content = file_get_contents('files/ats/coupled_templates/magnitude_55l_empty.sii');
		}
		if($this->accessory && $this->accessory->accessory_def !== ''){
			$temp = 'magnitude_55l';
			if(in_array($this->accessory->accessory_def, $heavy_ats_accessory_with_spreader)) $temp .= '_spreader';
			$content = file_get_contents('files/'.$this->game.'/coupled_templates/'.$temp.'.sii');
			$content = str_replace(['%cargo%'], $this->accessory->accessory_def, $content);
		}
		if($this->paintJob){
			$content = file_get_contents('files/'.$this->game.'/coupled_templates/'.$this->chassis->chassis_name.'.sii');
			$content = str_replace(['%color%'], $this->paintJob->color ? "base_color: (".$this->paintJob->color.")" : '', $content);
			$content = str_replace(['%paint_job%'], $this->paintJob->paint_def, $content);
			$content = str_replace(['%paint_job_s%'], str_replace('profiliner', 'proficarrier', $this->paintJob->paint_def), $content);
		}
		$to_replace = ['box_pup_2', 'box_pup_3', 'box_rm_2', 'reefer_pup_2', 'reefer_pup_3', 'reefer_rm_2', '%trailer%'];
		$content = str_replace($to_replace, $trailer_name, $content);
		$content = str_replace(['%wheel%'], $this->chassis->wheels, $content);

		return $content;
	}

	private function zipFiles(){
		$zip = new ZipArchive();
		$filename = time().'_'.$this->transliterate($this->title);

		if($zip->open($this->downloadDir.'/'.$filename.'.scs', ZipArchive::CREATE) !== true){
			return false;
		}

		$content = file_get_contents('files/mod/manifest_template.sii');
		$content = str_replace('%%', $this->title, $content);
		file_put_contents('files/mod/manifest.sii', $content);

		$zip->addFile('files/mod/manifest.sii', 'manifest.sii');
		$zip->addFile($this->outDir.'/mod_icon.jpg', 'mod_icon.jpg');
		$zip->addEmptyDir('def/vehicle/trailer');

		$dir = scandir($this->outDir.'/vehicle/trailer');
		foreach($dir as $item){
			if($item != '.' && $item != '..'){
				$zip->addFile($this->outDir.'/vehicle/trailer/'.$item, 'def/vehicle/trailer/'.$item);
			}
		}

		if(is_dir($this->outDir.'/company')){
			$zip->addEmptyDir('def/company');
			$dir = scandir($this->outDir.'/company');
			foreach($dir as $item){
				if($item != '.' && $item != '..'){
					$zip->addFile($this->outDir.'/company/'.$item, 'def/company/'.$item);
				}
			}
		};

		if(is_dir($this->outDir.'/cargo')){
			$zip->addEmptyDir('def/cargo');
			$dir = scandir($this->outDir.'/cargo');
			foreach($dir as $inner_dir){
				if($inner_dir != '.' && $inner_dir != '..'){
					$zip->addEmptyDir('def/cargo/'.$inner_dir);
					foreach(scandir($this->outDir.'/cargo/'.$inner_dir) as $file){
						if($file != '.' && $file != '..'){
							$zip->addFile($this->outDir.'/cargo/'.$inner_dir.'/'.$file, 'def/cargo/'.$inner_dir.'/'.$file);
						}
					}

				}
			}
		};

		$zip->close();

		return $filename;
	}

	private function transliterate($str){
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
			'ü' => 'u',

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
			'Ü' => 'u',

			' ' => '_', '&' => ''];

		return strtr($str, $ru);
	}

}