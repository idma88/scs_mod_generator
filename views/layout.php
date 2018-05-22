<!doctype html>
<html lang="en">
<head>
	<?php include_once __DIR__.'/header.php'; ?>
</head>
<body<?php if(isset($_COOKIE['dark_theme']) && $_COOKIE['dark_theme'] == 'true'): ?> class="mdc-theme--dark"<?php endif ?>>
<?php include_once __DIR__.'/navbar.php'; ?>
<main>
	<?php if(stripos($_SERVER['SCRIPT_NAME'], 'gallery.php')){
		include_once __DIR__.'/gallery.php';
	}else{
		include_once __DIR__.'/index.php';
	} ?>
	<?php include_once __DIR__.'/lang_modal.php' ?>
</main>
<?php include_once __DIR__.'/footer.php'; ?>
</body>
</html>