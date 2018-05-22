<aside id="mdc-dialog-lang" class="mdc-dialog" role="alertdialog" aria-labelledby="my-mdc-dialog-label" aria-describedby="my-mdc-dialog-description">
	<div class="mdc-dialog__surface">
		<header class="mdc-dialog__header">
			<h2 id="my-mdc-dialog-label" class="mdc-dialog__header__title center"><?= I18n::t('choose_language') ?></h2>
		</header>
		<section id="my-mdc-dialog-description" class="mdc-dialog__body row">
			<?php foreach (array_chunk($languages, ceil(count($languages)/2), true) as $array): ?>
				<div class="col m6 s12 lang-col">
					<?php foreach($array as $locale => $lang) : ?>
						<a href="<?= !$_SERVER['QUERY_STRING'] ? '' : '?'.$_SERVER['QUERY_STRING'] ?>"
						   class="valign-wrapper lang-btn<?php if(I18n::getUserLanguage() === $locale) echo ' active' ?>"
						   data-lang="<?= $locale ?>">
							<img src="./assets/img/langs/<?= $locale ?>.png" alt="<?= $lang ?>">
							<?= $lang ?>
						</a>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
			<div class="clearfix"></div>
			<h6 class="center"><?= I18n::t('help_translate') ?><br>
				<a href="http://mods-generator.oneskyapp.com"
				   target="_blank"
				   class="grey-text text-darken-1">http://mods-generator.oneskyapp.com</a>
			</h6>
		</section>
		<footer class="mdc-dialog__footer">
			<button type="button" class="mdc-button mdc-ripple mdc-dialog__footer__button mdc-dialog__footer__button--accept"><?= I18n::t('close') ?></button>
		</footer>
	</div>
	<div class="mdc-dialog__backdrop"></div>
</aside>