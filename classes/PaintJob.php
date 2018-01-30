<?php

class PaintJob{

	public $paint_def;
	public $look;
	public $dlc = array();
	public $allCompanies = false;
	public $color = '1, 1, 1';
	private $game;

	public function __construct($chassis){
		$this->paint_def = $this->getPaintDef($chassis);
		$this->look = $this->getTrailerLook();
		$this->dlc = $this->getPaintDLC();
		$this->color = $this->getPaintColor();
		$this->game = $_POST['target'];
	}

	private function getPaintDef($chassis){
		if($chassis->chassis_name == 'aero_dynamic'){
			$paint_def = '/def/vehicle/trailer/aero_dynamic/company_paint_job/default.sii';
		}else if($_POST['paint'] == 'all'){
			$paint_def = $chassis->default_paint_job;
			$this->allCompanies = true;
		}else{
			$paint_def = $_POST['paint'];
		}
		return $paint_def;
	}

	private function getTrailerLook(){
		$array = explode('/', $this->paint_def);
		return str_replace('.sii', '', $array[count($array) - 1]);
	}

	public static function getTrailerLookByDef($def){
		$array = explode('/', $def);
		return str_replace('.sii', '', $array[count($array) - 1]);
	}

	private function getPaintDLC(){
		GLOBAL $dlc_paints;
		$dlc = $this->dlc;
		if(key_exists($this->paint_def, $dlc_paints)){
			$dlc = explode(',', $dlc_paints[$this->paint_def]);
		}
		return $dlc;
	}

	private function getPaintColor(){
		if($this->look == 'default'){
			$colors['r'] = $_POST['color']['scs']['r'] ?? '1';
			$colors['g'] = $_POST['color']['scs']['g'] ?? '1';
			$colors['b'] = $_POST['color']['scs']['b'] ?? '1';
			return $colors['r'].', '.$colors['g'].', '.$colors['b'];
		}else{
			return '1, 1, 1';
		}
	}

	public static function getAllPaintsDefs($game){
		GLOBAL $paints;
		$list = array();
		foreach($paints[$game] as $chassis => $item){
			foreach($item as $def){
				$list[$def] = $def;
			}
		}
		return $list;
	}

}