<?php include 'modules/kint/Kint.class.php';

	$zip = new ZipArchive();
	$filename = 'zip.scs';
	if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
		exit("Невозможно открыть <$filename>\n");
	}

	$zip->addFromString("testfilephp.txt", "#1 Это тестовая строка, добавленная как testfilephp.txt.\n");
	$zip->close();