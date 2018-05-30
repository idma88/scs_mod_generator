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
			$user_langs = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
			$user_langs = array_filter($user_langs, function($v){
				return stripos($v, ';') || strlen($v) === 2;
			});
			foreach($user_langs as $key => $value){
				$user_langs[$key] = substr($value, 0, 2);
			}
			$locales_per_lang = [
				'ru' => ['ru', 'uk', 'be', 'kk', 'mo', 'ky', 'lv', 'lt', 'et', 'ka', 'az', 'hy', 'uz', 'tk'],
				'de' => ['de', 'da', 'nl'],
				'fr' => ['fr'],
				'es' => ['es', 'pt'],
				'pt' => ['pt', 'es'],
			];
			foreach(array_unique($user_langs) as $lang){
				if(key_exists($lang, $languages)) return $lang;
			}
			$lang = 'en';
			foreach($user_langs as $user_lang){
				foreach($locales_per_lang as $language => $locales){
					if(in_array($user_lang, $locales)) $lang = $language;
				}
			}
			return $lang;
		}else{
			$lang = $_COOKIE['lang'];
		}
		return $lang;
	}

}