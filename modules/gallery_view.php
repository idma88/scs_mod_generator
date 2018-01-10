<?php GLOBAL $chassis_list, $with_paint_job, $with_accessory; ?>
<div class="container grey-text">
	<div class="row">
		<ul class="tabs grey darken-4">
			<li class="tab col s6"><a class="active grey-text" href="#ets2">Euro Truck Simulator 2</a></li>
			<li class="tab col s6"><a href="#ats" class="grey-text">American Truck Simulator</a></li>
		</ul>
		<div id="ets2" class="game">
			<h4 class="light"><?= t('ets_trailer_guide') ?></h4>
			<?php $chassis = array();
			foreach($chassis_list['ets2'] as $key => $value){
				$chassis[] = str_replace(['_default', '_black', '_yellow', '_red', '_blue', '_grey', '_wall', '_floor', '_glass'], '', $key);
			}
			$diff = [
				'car_transporter',
				'overweight',
				'goldhofer_mpa',
				'goldhofer_stz',
			];
			foreach(array_values(array_unique($chassis)) as $key => $name) :?>
				<div class="col m6 s12">
					<div class="card trailer grey darken-3 <?= $key ?>">
						<div class="card-image">
							<img src="/assets/img/trailers/<?= $name ?>/<?= $name ?>.jpg">
							<h5 class="card-title trailer-name light text-shadow"><?= t($name) ?></h5>
						</div>
						<?php $name_alt = in_array($name, $diff) ? $name . '_default' : $name;
						if(key_exists($name_alt, $with_paint_job) || in_array($name_alt, $with_accessory)) : ?>
							<div class="card-content">
								<ul class="collapsible grey darken-3 z-depth-0 grey-text text-lighten-2" data-trailer="<?= $name_alt ?>" data-game="ets2">
									<li>
										<div class="collapsible-header grey darken-3">
											<i class="material-icons notranslate">arrow_downward</i>
											<?php if(key_exists($name_alt, $with_paint_job)) : ?>
												<span style="flex: 1;"><?= t('see_paints') ?></span>
											<?php elseif(in_array($name_alt, $with_accessory)): ?>
												<span style="flex: 1;"><?= t('see_cargo') ?></span>
											<?php endif ?>
										</div>
										<div class="collapsible-body"></div>
									</li>
								</ul>
							</div>
						<?php endif ?>
					</div>
				</div>
				<?php if($key % 2 != 0) : ?>
				    <div class="clearfix"></div>
				<?php endif ?>
			<?php endforeach ?>
		</div>
		<div id="ats" class="game">
			<h4 class="light"><?= t('ats_trailer_guide') ?></h4>
			<?php $chassis = array();
			foreach($chassis_list['ats'] as $key => $value){
				$chassis[] = str_replace(['_default', '_black', '_yellow', '_red', '_blue', '_grey', '_1', '_4', '_3'], '', $key);
			}
			foreach(array_values(array_unique($chassis)) as $key => $name) :?>
				<div class="col m6 s12">
					<div class="card trailer grey darken-3 <?= $key ?>">
						<div class="card-image">
							<img src="/assets/img/trailers/<?= $name ?>/<?= $name ?>.jpg">
							<h5 class="card-title trailer-name light text-shadow"><?= t($name) ?></h5>
						</div>
						<?php $name_alt = in_array($name, $diff) ? $name . '_default' : $name;
							if(key_exists($name_alt, $with_paint_job) || in_array($name_alt, $with_accessory)) : ?>
								<div class="card-content">
									<ul class="collapsible grey darken-3 z-depth-0 grey-text text-lighten-2" data-trailer="<?= $name_alt ?>" data-game="ats">
										<li>
											<div class="collapsible-header grey darken-3">
												<i class="material-icons notranslate">arrow_downward</i>
												<?php if(key_exists($name_alt, $with_paint_job)) : ?>
													<span style="flex: 1;"><?= t('see_paints') ?></span>
												<?php elseif(in_array($name_alt, $with_accessory)): ?>
													<span style="flex: 1;"><?= t('see_cargo') ?></span>
												<?php endif ?>
											</div>
											<div class="collapsible-body"></div>
										</li>
									</ul>
								</div>
							<?php endif ?>
					</div>
				</div>
				<?php if($key % 2 != 0) : ?>
					<div class="clearfix"></div>
				<?php endif ?>
			<?php endforeach ?>
		</div>
	</div>
</div>