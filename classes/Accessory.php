<?php

class Accessory{

	public $accessory_def;
	public $dlc = array();
	private $game;

	public function __construct(){
		$this->accessory_def = $_POST['accessory'];
		$this->dlc = $this->getAccessoryDLC();
		$this->game = $_POST['target'];
	}

	private function getAccessoryDLC(){
		GLOBAL $dlc_accessories;
		$dlc = $this->dlc;
		if(key_exists($this->accessory_def, $dlc_accessories)){
			$dlc = explode(',', $dlc_accessories[$this->accessory_def]);
		}
		return $dlc;
	}

	public static function getAllAccessoriesDefs($game){
		GLOBAL $accessories;
		foreach($accessories[$game] as $chassis => $item){
			foreach($item as $def => $name){
				$list[$def] = $def;
			}
		}
		return $list;
	}

}