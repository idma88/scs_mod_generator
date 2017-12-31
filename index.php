<?php
require_once 'arrays.php';
require_once 'functions.php';

if(isset($_POST['ajax']) && $chassis = $_POST['chassis']){
	$lang = $_POST['lang'] ?? null;
	if(isset($_POST['all']) && $_POST['all'] == 'true'){
		if($_POST['target'] == 'accessory'){
			$chassis = null;
			echo json_encode(['result' => getAccessoriesByChassis($lang), 'first' => t('choose_accessory',
				$lang), 'status' => 'OK']);
			die();
		}
		if($_POST['target'] == 'paint'){
			$chassis = null;
			echo json_encode(['result' => getPaintByChassis($lang), 'first' => t('all_companies',
				$lang), 'status' => 'OK']);
			die();
		}
	}
	GLOBAL $with_accessory, $with_paint_job;
	$echo = false;
	$target = null;
	if(in_array($_POST['chassis'], $with_accessory)){
		$echo = getAccessoriesByChassis($lang, $chassis);
		$first = t('choose_accessory', $lang);
		$target = 'accessory';
	}
	if(key_exists($_POST['chassis'], $with_paint_job)){
		$echo = getPaintByChassis($lang, $chassis);
		$first = t('all_companies', $lang);
		$target = 'paint';
	}
	echo json_encode(['target' => $target, 'result' => $echo, 'first' => $first, 'status' => 'OK']);
	die();
}

require_once 'modules/header.php'; ?>

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
					<div class="row" id="chassis">
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
				<input type="hidden" name="target" value="ets2">
			</form>
		</div>

	</div>

<?php require_once 'modules/footer.php' ?>