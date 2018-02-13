<div class="container">
	<?php if(isset($_GET['d'])) : ?>
		<div class="row">
			<div class="download-row">
				<a href="/download/<?= $_GET['d'] ?>.scs" class="mdc-button mdc-button--raised mdc-ripple large-btn left">
					<i class="material-icons mdc-button__icon">file_download</i>
					<?= t('download_mod') ?>
				</a>
				<h6><?= $_GET['d'] ?>.scs</h6>
			</div>
		</div>
	<?php endif ?>
	<?php if(isset($_GET['e'])) :
		GLOBAL $error_codes;
		if(key_exists($_GET['e'], $error_codes)): ?>
			<div class="row">
				<div class="card-panel">
					<h5 class="card-title light"><i class="material-icons left notranslate">warning</i><?= t('error').$_GET['e'] ?>)</h5>
				</div>
			</div>
		<?php endif;
	endif ?>
	<!--[if gt IE 6]>
	<div class="card-panel yellow lighten-3">
		<h5 class="card-title"><i class="material-icons left notranslate">warning</i><?= t('ie_notification') ?></h5>
	</div>
	<![endif]-->
	<div class="card">
		<form action="generator.php" method="post" enctype="multipart/form-data">
			<div class="card-content">
				<div class="row">
					<div class="col s12">
						<div class="mdc-text-field">
							<input type="text" id="title" class="browser-default mdc-text-field__input" name="title">
							<label for="title" class="mdc-text-field__label"><?= t('mod_title') ?></label>
							<div class="mdc-text-field__bottom-line"></div>
						</div>
					</div>
				</div>
				<div class="row" id="chassis">
					<div class="col s12">
						<label for="select-chassis"><?= t('pick_chassis') ?></label>
						<select class="browser-default ui search dropdown chassis" id="select-chassis" name="chassis" required>
							<option selected value=""><?= t('choose_chassis') ?></option>
							<?php
							foreach($chassis_list[$game] as $name => $chassis): ?>
								<option value="<?= $name ?>"><?= t($name) ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="row" id="accessory" style="display: none">
					<div class="col s12">
						<label class="for-select"><?= t('pick_accessory') ?></label>
					</div>
					<div class="col s12">
						<div class="mdc-switch">
							<input type="checkbox" id="all_accessories" data-target="accessory" class="mdc-switch__native-control" />
							<div class="mdc-switch__background">
								<div class="mdc-switch__knob"></div>
							</div>
						</div>
						<label for="all_accessories" class="mdc-switch-label"><?= t('show_all_accessories') ?></label>
					</div>
				</div>
				<div class="row" id="paint" style="display: none">
					<div class="col s12">
						<label class="for-select"><?= t('pick_paint') ?></label>
					</div>
					<div class="colors" style="display: none;">
						<div class="col s12 palette">
							<div class="input-field inline" style="height: 26px; min-width: 170px;">
								<input type="color" name="color" value="#ffffff" style="cursor: pointer; width: 170px;" id="color_palette">
							</div>
							<span class="offset-m3"><?= t('pick_color') ?></span>
						</div>
						<div class="col s12 hex">
							<div class="mdc-text-field">
								<input type="text" id="color_hex" class="browser-default mdc-text-field__input" name="color[hex]" value="#ffffff" maxlength="7" style="text-transform: uppercase;">
								<label for="color_hex" class="mdc-text-field__label">HEX</label>
								<div class="mdc-text-field__bottom-line"></div>
							</div>
							<span><?= t('type_color_hex') ?></span>
						</div>
						<div class="col s12 rgb">
							<div class="mdc-text-field inline">
								<input type="text" id="color_rgb_r" class="browser-default mdc-text-field__input" name="color[rgb][r]" min="0" max="255" value="255" maxlength="3">
								<label for="color_rgb_r" class="mdc-text-field__label">R</label>
								<div class="mdc-text-field__bottom-line"></div>
							</div>
							<div class="mdc-text-field inline">
								<input type="text" id="color_rgb_g" class="browser-default mdc-text-field__input" name="color[rgb][g]" min="0" max="255" value="255" maxlength="3">
								<label for="color_rgb_g" class="mdc-text-field__label">G</label>
								<div class="mdc-text-field__bottom-line"></div>
							</div>
							<div class="mdc-text-field inline">
								<input type="text" id="color_rgb_b" class="browser-default mdc-text-field__input" name="color[rgb][b]" min="0" max="255" value="255" maxlength="3">
								<label for="color_rgb_b" class="mdc-text-field__label">B</label>
								<div class="mdc-text-field__bottom-line"></div>
							</div>
							<span class="offset-m3"><?= t('type_color_rgb') ?></span>
						</div>
						<div class="col s12 scs">
							<div class="mdc-text-field inline">
								<input type="text" id="color_scs_r" class="browser-default mdc-text-field__input" name="color[scs][r]" value="1">
								<label for="color_scs_r" class="mdc-text-field__label">R</label>
								<div class="mdc-text-field__bottom-line"></div>
							</div>
							<div class="mdc-text-field inline">
								<input type="text" id="color_scs_g" class="browser-default mdc-text-field__input" name="color[scs][g]" value="1">
								<label for="color_scs_g" class="mdc-text-field__label">G</label>
								<div class="mdc-text-field__bottom-line"></div>
							</div>
							<div class="mdc-text-field inline">
								<input type="text" id="color_scs_b" class="browser-default mdc-text-field__input" name="color[scs][b]" value="1">
								<label for="color_scs_b" class="mdc-text-field__label">B</label>
								<div class="mdc-text-field__bottom-line"></div>
							</div>
							<span class="offset-m3"><?= t('type_color_scs') ?></span>
						</div>
					</div>
					<div class="col s12">
						<div class="mdc-switch">
							<input type="checkbox" id="all_paints" data-target="paint" class="mdc-switch__native-control" />
							<div class="mdc-switch__background">
								<div class="mdc-switch__knob"></div>
							</div>
						</div>
						<label for="all_paints" class="mdc-switch-label"><?= t('show_all_paints') ?></label>
					</div>
				</div>
				<div class="advanced row" style="margin-bottom: 0;">
					<ul class="collapsible z-depth-0 col s12">
						<li>
							<div class="collapsible-header grey-text"><i class="material-icons">arrow_drop_down</i><?= t('advanced') ?></div>
							<div class="collapsible-body">
								<label><?= t('image_upload') ?></label>
								<div class="file-field input-field">
									<div class="btn blue-grey waves-effect waves-light">
										<span><i class="material-icons notranslate" style="font-size: 2em;">file_upload</i></span>
										<input type="file" name="img" id="image" accept="image/jpeg, image/png"
											   data-size="<?= t('max_file_size_5') ?>"
											   data-dimensions="<?= t('max_dimensions_3000') ?>">
									</div>
									<div class="file-path-wrapper">
										<input class="file-path" type="text" id="image-path" readonly>
									</div>
								</div>
								<div class="mdc-text-field weight">
									<input type="text" id="weight" class="browser-default mdc-text-field__input" name="weight">
									<label for="weight" class="mdc-text-field__label"><?= t('trailer_weight') ?></label>
									<div class="mdc-text-field__bottom-line"></div>
								</div>
								<div class="wheels input-field" style="display: none;">
									<select class="icons" name="wheels">
										<option value="" selected><?= t('w_default') ?></option>
										<?php foreach($available_wheels[$game] as $def => $wheel) : ?>
											<option value="<?= $def ?>" data-icon="assets/img/wheels/<?= $game ?>/<?= $wheel ?>.jpg"><?= t($wheel) ?></option>
										<?php endforeach ?>
									</select>
									<label><?= t('select_wheels') ?></label>
								</div>
							</div>
						</li>
					</ul>
				</div>
			</div>
			<div class="card-action row center-on-small-only">
				<input type="hidden" name="target" value="<?= $game ?>">
				<button class="mdc-button mdc-button--raised mdc-ripple left-med-and-up" type="submit" onclick="return confirm('<?= t('are_you_sure') ?>')">
					<i class="material-icons mdc-button__icon notranslate">send</i><?= t('proceed') ?>
				</button>
				<a href="/gallery.php<?php if($game == 'ats'): ?>#ats<?php endif ?>" class="gallery-btn right-med-and-up mdc-button mdc-ripple">
					<i class="material-icons mdc-button__icon notranslate">photo_library</i><?= t('trailers_gallery') ?>
				</a>
			</div>
		</form>
	</div>
	<div class="card-panel grey-text">
		<div class="card-title"><i class="material-icons left notranslate">info</i><?= t('beta_notification') ?></div>
	</div>
</div>
<div class="fixed-action-btn">
	<a class="mdc-fab mdc-ripple orange darken-3 modal-trigger tooltipped" data-tooltip="<?= t('how_to') ?>" href="#how">
		<span class="mdc-fab__icon">?</span>
	</a>
</div>

<aside id="mdc-dialog-default" class="mdc-dialog" role="alertdialog" aria-labelledby="my-mdc-dialog-label" aria-describedby="my-mdc-dialog-description">
	<div class="mdc-dialog__surface">
		<header class="mdc-dialog__header">
			<h2 id="my-mdc-dialog-label" class="mdc-dialog__header__title"><?= t('how_to_modal') ?></h2>
		</header>
		<section id="my-mdc-dialog-description" class="mdc-dialog__body">
			<ol>
				<li><?= t('instruction_modal_1') ?></li>
				<li><?= t('instruction_modal_2') ?></li>
				<li><?= t('instruction_modal_3') ?></li>
				<li><?= t('instruction_modal_4') ?></li>
				<li><?= t('instruction_modal_5') ?></li>
				<li><?= t('instruction_modal_6') ?></li>
			</ol>
		</section>
		<footer class="mdc-dialog__footer">
			<button type="button" class="mdc-button mdc-ripple mdc-dialog__footer__button mdc-dialog__footer__button--accept"><?= t('close_modal') ?></button>
		</footer>
	</div>
	<div class="mdc-dialog__backdrop"></div>
</aside>