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
		GLOBAL $languages;
		if(!isset($_COOKIE['lang'])){
			$user_lang = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];
			$user_lang = explode('-', $user_lang)[0];
			$locales_per_lang = [
				'ru' => ['ru', 'uk', 'be', 'kk', 'mo', 'ky', 'lv', 'lt', 'et', 'ka', 'az', 'hy', 'uz', 'tk'],
				'de' => ['de', 'da', 'nl'],
				'fr' => ['fr', ''],
				'es' => ['es', 'pt'],
				'pt' => ['pt', 'es'],
			];
			if(!key_exists($user_lang, $languages)){
				$lang = 'en';
				foreach($locales_per_lang as $language => $locales){
					if(in_array($user_lang, $locales)) $lang = $language;
				}
			}else $lang = $user_lang;
		}else{
			$lang = $_COOKIE['lang'];
		}
		return $lang;
	}

}