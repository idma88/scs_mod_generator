<?php

class Chassis{

	public $chassis_name;
	public $chassis_def;
	public $default_paint_job;
	public $wheels;
	public $customWheels = false;
	public $axles;
	public $weight = false;
	public $dlc = array();
	public $game;

	public function __construct($chassis_data){
		GLOBAL $chassis_list, $axles, $with_paint_job;
		$this->chassis_name = $chassis_data['chassis'];
		$this->game = $chassis_data['target'];
        $this->wheels = $this->getWheels($chassis_data['wheels']);
		if($this->chassis_name != 'paintable'){
			$this->chassis_def = $chassis_list[$this->game][$this->chassis_name];
			$this->axles = $axles[$this->chassis_name];
			$this->default_paint_job = $this->isWithPaintJob() ? $with_paint_job[$this->chassis_name] : null;
			$this->dlc = $this->getChassisDlc();
		}
		$this->weight = isset($chassis_data['weight']) && is_numeric($chassis_data['weight']) ? $chassis_data['weight'] : false;
	}

	private function getWheels($wheels_data){
		GLOBAL $chassis_one_wheel_support, $wheels;
		if(in_array($this->chassis_name, $chassis_one_wheel_support) || !$wheels_data || $wheels_data == ''){
			$t_wheels = $wheels[$this->chassis_name];
		}else{
			$this->customWheels = true;
            $t_wheels = $wheels_data;
		}
		return $t_wheels;
	}

	private function getChassisDlc(){
		GLOBAL $dlc_chassis_list;
		$dlc = $this->dlc;
		if(key_exists($this->chassis_name, $dlc_chassis_list)){
			$dlc = explode(',', $dlc_chassis_list[$this->chassis_name]);
		}
		return $dlc;
	}

	public function isWithPaintJob(){
		GLOBAL $with_paint_job;
		return key_exists($this->chassis_name, $with_paint_job) || $this->chassis_name == 'paintable';
	}

	public function isWithAccessory(){
		GLOBAL $with_accessory;
		return in_array($this->chassis_name, $with_accessory);
	}

	public function getAvailableAccessories($lang = null){
		GLOBAL $accessories;
		$list[] = [
			'name' => t('choose_accessory'),
			'value' => '',
			'selected' => true
		];
		if($this->isWithAccessory()){
			$chassis = str_replace(['_default', '_black', '_yellow', '_red', '_blue', '_grey'], '', $this->chassis_name);
			foreach($accessories[$this->game][$chassis] as $def => $name){
				$list[] = [
					'name' => t($name, $lang),
					'value' => $def
				];
			}
			return $list;
		}else{
			return false;
		}
	}

	public function getAvailablePaints($lang = null){
		GLOBAL $paints;
		if($this->isWithPaintJob()){
			$chassis = str_replace(['_1', '_1_4', '_4', '_4_3', 'rm_double', 'rm53_double', 'pup_double', 'pup_triple'], '', $this->chassis_name);
			$list[] = [
				'name' => t('all_companies'),
				'value' => 'all',
				'selected' => true
			];
			foreach($paints[$this->game][$chassis] as $def){
				$list[] = [
					'name' => t(PaintJob::getTrailerLookByDef($def), $lang),
					'value' => $def
				];
			}
			return $list;
		}else{
			return false;
		}
	}

	public function getAllCompanies($lang){
		GLOBAL $companies;
		$list[] = [
			'name' => t('choose_paint'),
			'value' => '',
			'selected' => true
		];
		$list[] = [
			'name' => t('default', $lang),
			'value' => 'default'];
		foreach($companies[$this->game] as $company){
			$list[] = [
				'name' => t($company, $lang),
				'value' => $company
			];
		}
		return $list;
	}

}