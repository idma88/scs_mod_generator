<?php require_once 'functions.php';

$game = $_GET['game'] ?? 'ets2';
$_SERVER['SCRIPT_NAME'] !== '/gallery.php' ? : $game = null; ?>

<div class="navbar-fixed">
	<nav class="blue-grey darken-3">
		<div class="nav-wrapper">
			<a href="/" class="brand-logo left" style="font-weight: 500; text-transform: uppercase; margin-left: 15px;"><?= I18n::t('head_title') ?></a>
			<ul id="nav-mobile" class="right">
				<li<?php if($game == 'ets2'): ?> class="active"<?php endif ?>><a href="/"><?= I18n::t('ets2') ?></a></li>
				<li<?php if($game == 'ats'): ?> class="active"<?php endif ?>><a href="/?game=ats"><?= I18n::t('ats') ?></a></li>
				<li>
					<a href="<?= !$_SERVER['QUERY_STRING'] ? '' : '?'.$_SERVER['QUERY_STRING'] ?>" id="lang-btn" data-lang="<?= I18n::getUserLanguage() == 'en' ? 'ru' : 'en' ?>">
						<?= I18n::getUserLanguage() == 'en' ? 'РУС' : 'EN' ?><i class="material-icons notranslate left">language</i>
					</a>
				</li>
			</ul>
		</div>
	</nav>
</div>