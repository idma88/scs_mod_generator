<?php	
	
	//$text = 'mass: 80000'; // Искомая строка
	//$pattern = '/trailer: trailer.[a-z.-_0-9]*/'; // паттерн на трейлер
	$pattern = '/trailer_look: [a-z.-_0-9]*/'; // паттерн на компанию
	//$pattern = '/mass: [0-9]*/'; // паттерн на массу

  $retext = 'trailer_look: nord_crown'; // Строка замены

  $dirname = "company"; 
  //$dirname = "cargo"; 

  scan_dir($dirname);  // Вызов рекурсивной функции
  
  echo "Done!";

  //////////////////////////////////////////////////////////
  // Рекурсивная функция - спускаемся вниз по каталогу
  //////////////////////////////////////////////////////////
  function scan_dir($dirname){

    // Объявляем переменные замены глобальными
    GLOBAL $text, $retext, $pattern; 

    // Открываем текущую директорию
    $dir = opendir($dirname); 

    // Читаем в цикле директорию
    while (($file = readdir($dir)) !== false){

      // Если файл обрабатываем его содержимое
      if($file != "." && $file != ".."){

        // Если имеем дело с файлом - производим в нём замену
        if(is_file($dirname."/".$file)){

          // Читаем содержимое файла
          $content = file_get_contents($dirname."/".$file); 

          // Осуществляем замену

          //$content = str_replace($text, $retext, $content); 
          $content = preg_replace($pattern, $retext, $content); 

          // Перезаписываем файл
          file_put_contents($dirname."/".$file, $content); 

        }

        // Если перед нами директория, вызываем рекурсивно
        // функцию scan_dir
        if(is_dir($dirname."/".$file)){
          echo $dirname."/".$file."<br>";
          scan_dir($dirname."/".$file);
        }

      }

    }

    // Закрываем директорию
    closedir($dir); 

  }