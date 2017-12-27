<?php
require_once 'modules/header.php';
require_once 'arrays.php';
require_once 'functions.php';
?>

	<div class="container">
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
					<div class="row">
						<div class="col s12">
							<label><?= t('pick_chassis') ?></label>
							<select class="browser-default grey darken-3" name="chassis" required>
								<option selected value=""><?= t('choose_chassis') ?></option>
								<?php foreach($chassis_list as $name => $chassis): ?>
										<option value="<?= $name ?>"><?= t($name) ?></option>
									<?php endforeach; ?>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col s12">
							<label><?= t('pick_accessory') ?></label>
							<select class="browser-default grey darken-3" name="accessory">
								<option value="" selected><?= t('choose_accessory') ?></option>
								<?php $accessories = file('def/accessories.txt', FILE_IGNORE_NEW_LINES);
									foreach($accessories as $accessory): ?>
										<option value="<?= $accessory ?>"><?= $accessory ?></option>
									<?php endforeach; ?>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col s12">
							<label><?= t('pick_paint') ?></label>
							<select class="browser-default grey darken-3" name="paint_job">
								<option selected value="all"><?= t('all_companies') ?></option>
								<?php $paints = file('def/paints.txt', FILE_IGNORE_NEW_LINES);
									foreach($paints as $paint): ?>
										<option value="<?= $paint ?>"><?= $paint ?></option>
									<?php endforeach; ?>
							</select>
						</div>
					</div>
				</div>
				<div class="card-action">
					<button class="btn blue-grey waves-effect" type="submit" onclick="return confirm('<?= t('are_you_sure') ?>')"><?= t('proceed') ?></button>
				</div>
				<input type="hidden" name="target" value="ets2">
			</form>
		</div>

	</div>

<?php require_once 'modules/footer.php' ?>