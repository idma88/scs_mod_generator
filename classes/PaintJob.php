<?php

class PaintJob{

	public $paint_def;
	public $look = 'default';
	public $dlc = array();
	public $allCompanies = false;
	public $color = '1, 1, 1';
	private $game;

	public function __construct($chassis, $paint_data){
		$this->paint_def = $this->getPaintDef($chassis, $paint_data['paint']);
		$this->look = $this->getTrailerLook($paint_data['paint']);
		$this->dlc = $this->getPaintDLC();
		$this->color = $this->getPaintColor($paint_data['color']);
		$this->game = $paint_data['target'];
	}

	private function getPaintDef($chassis, $paint){
		if($chassis->chassis_name == 'aero_dynamic'){
			$paint_def = '/def/vehicle/trailer/aero_dynamic/company_paint_job/default.sii';
		}else if($paint == 'all'){
			$paint_def = $chassis->default_paint_job;
			$this->allCompanies = true;
		}else if($chassis->chassis_name == 'paintable' && stripos($paint, '/def/vehicle/trailer/') === false){
			$paint_def = false;
		}else{
			$paint_def = $paint;
		}
		return $paint_def;
	}

	private function getTrailerLook($paint){
	    if(!$paint) return $this->look;
		if(!$this->paint_def) return $paint;
		$array = explode('/', $this->paint_def);
		return str_replace(['.sii', '_2017'], '', $array[count($array) - 1]);
	}

	public static function getTrailerLookByDef($def){
		$array = explode('/', $def);
		return str_replace('.sii', '', $array[count($array) - 1]);
	}

	private function getPaintDLC(){
		GLOBAL $dlc_paints, $companies_dlc;
		$dlc = $this->dlc;
		if(!$this->paint_def){
			if(key_exists($this->look, $companies_dlc)){
				$dlc = [$companies_dlc[$this->look]];
			}
		}else{
			if(key_exists($this->paint_def, $dlc_paints)){
				$dlc = explode(',', $dlc_paints[$this->paint_def]);
			}
		}
		return $dlc;
	}

	private function getPaintColor($color){
		if($this->look == 'default'){
			if(is_array($color)){
				$colors['r'] = $color['scs']['r'] ?? '1';
				$colors['g'] = $color['scs']['g'] ?? '1';
				$colors['b'] = $color['scs']['b'] ?? '1';
				return $colors['r'].', '.$colors['g'].', '.$colors['b'];
			}else{
				return $color;
			}
		}else{
			return '1, 1, 1';
		}
	}

	public static function getAllPaintsDefs($game){
		GLOBAL $paints;
		foreach($paints[$game] as $chassis => $item){
			foreach($item as $key => $def){
				$list[] = [
					'name' => $def,
					'value' => $def
				];
			}
		}
		$list[0]['selected'] = true;
		return $list;
	}

}