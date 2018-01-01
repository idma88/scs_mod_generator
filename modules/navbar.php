<?php require_once 'functions.php'; ?>

<div class="navbar-fixed">
	<nav class="blue-grey darken-3">
		<div class="nav-wrapper">
			<a href="/" class="brand-logo left light" style="text-transform: uppercase; margin-left: 15px;"><?= t('head_title') ?></a>
			<ul id="nav-mobile" class="right">
				<li class="active"><a href="/"><?= t('ets2mp') ?></a></li>
				<li class="disabled"><a class="grey-text"><?= t('atsmp') ?></a></li>
                <li><a href="#faq" class="modal-trigger" style="font-size: 20px; font-weight: bold;">?</a></li>
				<li>
					<a href="/" id="lang-btn" data-lang="<?= getUserLanguage() == 'en' ? 'ru' : 'en' ?>">
						<?= getUserLanguage() == 'en' ? 'RU' : 'EN' ?><i class="material-icons notranslate left">language</i>
					</a>
				</li>
			</ul>
		</div>
	</nav>
</div>