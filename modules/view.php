<div class="container grey-text">
	<?php if(isset($_GET['download'])) : ?>
		<div class="row">
			<h5 class="center">
				<a href="/download/<?= $_GET['download'] ?>.scs" class="btn blue-grey darken-3 large card-title waves-effect waves-light"><?= t('download_mod') ?></a>
			</h5>
		</div>
	<?php endif ?>
	<div class="card grey darken-3">
		<form action="renamer.php" method="post">
			<div class="card-content">
				<div class="row">
					<div class="input-field col s12">
						<input type="text" name="title" placeholder="<chassis> - <accessory/paint>">
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
			</div>
			<div class="card-action row">
				<div class="col s12">
					<button class="btn blue-grey waves-effect" type="submit" onclick="return confirm('<?= t('are_you_sure') ?>')"><?= t('proceed') ?></button>
				</div>
			</div>
			<input type="hidden" name="target" value="<?= $game ?>">
		</form>
	</div>

</div>