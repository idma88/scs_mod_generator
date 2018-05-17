<!doctype html>
<html lang="en">
<head>
	<?php include_once 'header.php'; ?>
</head>
<body<?php if(isset($_COOKIE['dark_theme']) && $_COOKIE['dark_theme'] == 'true'): ?> class="mdc-theme--dark"<?php endif ?>>
	<?php include_once 'navbar.php'; ?>
	<main>
		<?php if(stripos($_SERVER['SCRIPT_NAME'], 'gallery')){
			include_once 'gallery.php';
		}else{
			include_once 'index.php';
		} ?>
	</main>
	<?php include_once 'footer.php'; ?>
</body>
</html>