<?php
require_once 'modules/header.php';
require_once 'arrays.php';
?>

	<div class="container">

		<div class="card">
			<form action="renamer.php" method="post">
				<div class="card-content">
					<div class="row">
						<div class="col m6 s12">
							<label>Pick chassis from list below</label>
							<select class="browser-default" name="chassis" required>
								<option selected value="">Choose chassis</option>
								<?php foreach($chassis_list as $name => $chassis): ?>
										<option value="<?= $name ?>"><?= $name ?></option>
									<?php endforeach; ?>
							</select>
						</div>
						<div class="input-field col m6 s12">
							<input type="text" placeholder="/def/vehicle/trailer/..../chassis.sii">
							<label>Or type chassis def here (overwrites select)</label>
						</div>
					</div>
					<div class="row">
						<div class="col s12">
							<label>Pick accessory from list below</label>
							<select class="browser-default" name="accessory">
								<option value="" selected>Choose accessory</option>
								<?php $accessories = file('def/accessories.txt', FILE_IGNORE_NEW_LINES);
									foreach($accessories as $accessory): ?>
										<option value="<?= $accessory ?>"><?= $accessory ?></option>
									<?php endforeach; ?>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col s12">
							<label>Pick paint job from list below</label>
							<select class="browser-default" name="paint_job">
								<option selected value="all">All companies</option>
								<?php $paints = file('def/paints.txt', FILE_IGNORE_NEW_LINES);
									foreach($paints as $paint): ?>
										<option value="<?= $paint ?>"><?= $paint ?></option>
									<?php endforeach; ?>
							</select>
						</div>
					</div>
				</div>
				<div class="card-action">
					<button class="btn-flat teal-text" type="submit" onclick="return confirm('Are you sure?')">proceed</button>
				</div>
				<input type="hidden" name="target" value="ets2">
			</form>
		</div>

	</div>

<?php require_once 'modules/footer.php' ?>