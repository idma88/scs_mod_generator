<?php

class I18n{

	public static function t($name, $lang = null){
		$lang ? : $lang = self::getUserLanguage();
		$strings = json_decode(file_get_contents('lang/'.$lang.'.json'), true);
		foreach($strings as $group){
			if(array_key_exists($name, $group)){
				return $group[$name];
			}
		}
		return $name;
	}

	public static function getUserLanguage(){
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

}