<div class="container grey-text">
	<?php if(isset($_GET['d'])) : ?>
		<div class="row">
			<div class="download-row">
				<a href="/download/<?= $_GET['d'] ?>.scs" class="btn-large blue-grey darken-3 large card-title waves-effect waves-light left"><?= t('download_mod') ?></a>
				<h6><?= $_GET['d'] ?>.scs</h6>
			</div>
		</div>
	<?php endif ?>
	<?php if(isset($_GET['e'])) :
		GLOBAL $error_codes;
		if(key_exists($_GET['e'], $error_codes)): ?>
			<div class="row">
				<div class="card-panel grey darken-3">
					<h5 class="card-title light"><i class="material-icons left notranslate">warning</i><?= t('error').$_GET['e'] ?>)</h5>
				</div>
			</div>
		<?php endif;
	endif ?>
	<!--[if gt IE 6]>
	<div class="card-panel grey-text text-darken-3 yellow lighten-3">
		<h4 class="card-title"><i class="material-icons left notranslate">warning</i><?= t('ie_notification') ?></h4>
	</div>
	<![endif]-->
	<div class="card grey darken-3">
		<form action="renamer.php" method="post" enctype="multipart/form-data">
			<div class="card-content">
				<div class="row">
					<div class="input-field col s12">
						<input type="text" name="title">
						<label><?= t('mod_title') ?></label>
					</div>
				</div>
				<div class="row" id="chassis">
					<div class="col s12">
						<label><?= t('pick_chassis') ?></label>
						<select class="browser-default grey darken-3" name="chassis" required>
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
						<label><?= t('pick_accessory') ?></label>
						<select class="browser-default grey darken-3" name="accessory">
							<option value="" selected><?= t('choose_accessory') ?></option>
						</select>
					</div>
					<h5 class="col s12">
						<label>
							<input type="checkbox" id="all_accessories" data-target="accessory">
							<span><?= t('show_all_accessories') ?></span>
						</label>
					</h5>
				</div>
				<div class="row" id="paint" style="display: none">
					<div class="col s12">
						<label><?= t('pick_paint') ?></label>
						<select class="browser-default grey darken-3" name="paint">
							<option selected value="all"><?= t('all_companies') ?></option>
						</select>
					</div>
					<div class="colors" style="display: none;">
						<div class="col s12 palette">
							<div class="input-field inline" style="height: 26px; min-width: 170px;">
								<input type="color" name="color" value="#ffffff" style="cursor: pointer; width: 170px;" id="color_palette">
							</div>
							<span class="offset-m3"><?= t('pick_color') ?></span>
						</div>
						<div class="col s12 hex">
							<div class="input-field inline" style="max-width: 170px;">
								<input id="color_hex" type="text" name="color[hex]" value="#ffffff" maxlength="7" style="text-transform: uppercase;">
								<label for="color[color_hex]">HEX</label>
							</div>
							<span><?= t('type_color_hex') ?></span>
						</div>
						<div class="col s12 rgb">
							<div class="input-field inline">
								<input id="color_rgb_r" type="text" name="color[rgb][r]" min="0" max="255" value="255" maxlength="3">
								<label for="color_rgb_r">R</label>
							</div>
							<div class="input-field inline">
								<input id="color_rgb_g" type="text" name="color[rgb][g]" min="0" max="255" value="255" maxlength="3">
								<label for="color_rgb_g">G</label>
							</div>
							<div class="input-field inline">
								<input id="color_rgb_b" type="text" name="color[rgb][b]" min="0" max="255" value="255" maxlength="3">
								<label for="color_rgb_b">B</label>
							</div>
							<span class="offset-m3"><?= t('type_color_rgb') ?></span>
						</div>
						<div class="col s12 scs">
							<div class="input-field inline">
								<input id="color_scs_r" type="text" name="color[scs][r]" min="0" max="1" value="1">
								<label for="color_scs_r">R</label>
							</div>
							<div class="input-field inline">
								<input id="color_scs_g" type="text" name="color[scs][g]" min="0" max="1" value="1">
								<label for="color_scs_g">G</label>
							</div>
							<div class="input-field inline">
								<input id="color_scs_b" type="text" name="color[scs][b]" min="0" max="1" value="1">
								<label for="color_scs_b">B</label>
							</div>
							<span class="offset-m3"><?= t('type_color_scs') ?></span>
						</div>
					</div>
					<h5 class="col s12">
						<label>
							<input type="checkbox" id="all_paints" data-target="paint">
							<span><?= t('show_all_paints') ?></span>
						</label>
					</h5>
				</div>
				<div class="advanced row" style="margin-bottom: 0;">
					<ul class="collapsible grey darken-3 z-depth-0 col s12">
						<li>
							<div class="collapsible-header grey darken-3"><i class="material-icons">arrow_drop_down</i><?= t('advanced') ?></div>
							<div class="collapsible-body">
								<label><?= t('image_upload') ?></label>
								<div class="file-field input-field">
									<div class="btn blue-grey darken-2">
										<span><i class="material-icons notranslate" style="font-size: 2em;">file_upload</i></span>
										<input type="file" name="img" id="image" accept="image/jpeg, image/png" data-size="<?= t('max_file_size_5') ?>" data-dimensions="<?= t('max_dimensions_3000') ?>">
									</div>
									<div class="file-path-wrapper">
										<input class="file-path" type="text" id="image-path" readonly>
									</div>
								</div>
								<div class="input-field weight">
									<input id="weight" type="text" name="weight" >
									<label for="weight"><?= t('trailer_weight') ?></label>
								</div>
							</div>
						</li>
					</ul>
				</div>
			</div>
			<div class="card-action row center-on-small-only">
				<button class="btn blue-grey waves-effect left-med-and-up" type="submit" onclick="return confirm('<?= t('are_you_sure') ?>')">
					<i class="material-icons right notranslate">send</i><?= t('proceed') ?>
				</button>
				<a href="/gallery.php" class="gallery-btn right-med-and-up btn-flat waves-effect grey-text">
					<i class="material-icons left notranslate">photo_library</i><?= t('trailers_gallery') ?>
				</a>
			</div>
			<input type="hidden" name="target" value="<?= $game ?>">
		</form>
	</div>
	<div class="card-panel grey darken-3">
		<div class="card-title"><i class="material-icons left notranslate">info</i><?= t('beta_notification') ?></div>
	</div>
</div>
<div class="fixed-action-btn">
	<a class="btn-floating btn-large waves-effect waves-light red modal-trigger tooltipped" data-tooltip="<?= t('how_to') ?>" href="#how">?</a>
</div>
<div id="how" class="modal grey darken-2 white-text">
	<div class="modal-content">
		<h4 class="light"><?= t('how_to_modal') ?></h4>
		<ol class="light">
			<li><?= t('instruction_modal_1') ?></li>
			<li><?= t('instruction_modal_2') ?></li>
			<li><?= t('instruction_modal_3') ?></li>
			<li><?= t('instruction_modal_4') ?></li>
			<li><?= t('instruction_modal_5') ?></li>
			<li><?= t('instruction_modal_6') ?></li>
		</ol>
	</div>
	<div class="modal-footer grey darken-2">
		<a href="#!" class="modal-action modal-close waves-effect btn-flat white-text"><?= t('close_modal') ?></a>
	</div>
</div>